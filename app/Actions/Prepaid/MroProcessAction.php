<?php
namespace App\Actions\Prepaid;

use App\Enums\Constants;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\MroStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\TaxType;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use App\Models\Master\PriceHistory;
use App\Services\InvoiceService;
use App\Services\LedgerService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MroProcessAction
{
    public static function getBillingData()
    {   
        // 1. Fetch the MRO data which is not processed.
        $billing_data = BillMroData::where('status_id',MroStatus::RECEIVED->value)->get();

        $ackPayload = [];
        $ackFailPayload = [];

        foreach ($billing_data as $record) {
            try {
                // 2. JSON Decode of stored raw data.
                $data = json_decode($record->mro_data, true);
                // 3. Checking the given crn and meter_sr_no is valid or not.
                $consumer = Consumer::where('crn',$data['crn'])
                    ->whereHas('activeMeter',function($q) use($data){
                        $q->where('meter_serial_no',$data['meter_serial_no']);
                    })
                    ->first();
                if($consumer) {
                    $netReading = 0;
                    $avgPrice = 0;
                    $net_basic_price = 0;
                    $net_consumption = 0;
                    $cf = 1;
                    // 4. calculation of overall readings and overall dates for billing cycle.
                    $readings_cnt = count($data['current_readings']);
                    $start_reading = $data['current_readings'][0]['start_reading'];
                    $end_reading = $data['current_readings'][$readings_cnt-1]['end_reading'];
                    $start_date = $data['current_readings'][0]['start_reading_date_time'];
                    $end_date = $data['current_readings'][$readings_cnt-1]['end_reading_date_time'];
                    $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
                    // $total_consumption = ($end_reading - $start_reading);

                    // 5. End reading must be greater than equal to start_reading.
                    if($end_reading < $start_reading){
                        $ackFailPayload[] = [
                            'mro_order_id' => $record->mro_number,
                            'meter_serial_no' => $data['meter_serial_no'],
                            'crn' => $data['crn'],
                            'reason' => "Invalid readings.."
                        ];
                    }
                    else {
                        // 6. Looping of meter readings array.
                        foreach ($data['current_readings'] as $reading) {
                            $consmp_breakup = 0;
                            $start = (float)$reading['start_reading'];
                            $end   = (float)$reading['end_reading'];

                            $s_date = Carbon::parse($reading['start_reading_date_time']);
                            $e_date = Carbon::parse($reading['end_reading_date_time']);
                            $no_days = Carbon::parse($s_date)->diffInDays($e_date);
                            
                            // 7. Fetching of price history id, vat for given selected period.
                            $price = PriceHistory::where('effective_from','<=', $e_date)->where('segment_id', $consumer->segment_id)->where('district_id', $consumer->district_id)->first();

                            // 8. calculation of meter readings and given gas price. (for consumption details).
                            $consmp_breakup = ($end - $start);
                            $net_price = $reading['gas_price']; 
                            $basic_price = round(($reading['gas_price']*(100/(100+$price->vat))),2);
                            $netReading = ($consmp_breakup * $cf);
                            $baseAmt = $netReading * $basic_price;

                            $net_basic_price += $basic_price;
                            $total_consumption += $consmp_breakup;
                            $net_consumption += $netReading;
                            $avgPrice += $net_price;
                            $tax_value = $price['vat'];
                            // 9. Invoice Consumption Details Array.
                            $invoice['consumption_details'][] = [
                                'price_history_id' => $price?->id,
                                'days' => $no_days,
                                'consumption' => $consmp_breakup,
                                'cf' => $cf,
                                'unit_price' => $reading['gas_price'],
                                'total_price' => $baseAmt,
                            ];
                        }

                        // 10. Amount Calculation part.
                        $avgPrice = $avgPrice / count($data['current_readings']);
                        $basicAmount = $net_consumption * $avgPrice;
                        $vatAmount = ($basicAmount * $tax_value) / 100;
                        $totalAmount = $basicAmount + $vatAmount;   

                        // 11. bill invoice array
                        $invoice['invoice'] = [
                            'type_id' => InvoiceType::GAS_BILL->value, //1 => Gas Invoice
                            'consumer_id' => $consumer->id,
                            'invoice_date' => Carbon::now()->format('Y-m-d'),
                            'base_amount' => $basicAmount,
                            'taxable_amount' => $basicAmount,
                            'tax_id'=> TaxType::VAT->value,
                            'tax_value' => $tax_value,
                            'tax_amount' => $vatAmount,
                            'total_amount' => $totalAmount,
                            'payable_amount' => $totalAmount,
                            'paid_amount' => NULL,
                            'balance_amount' => $totalAmount,
                            'due_date' => Carbon::now()->addDays((int)Constants::DPNG_DUEDAYS->value)->format('Y-m-d'),
                            'status_id' => InvoiceStatus::PAID->value, // paid
                        ];
                        // 12.Invoice Consumption Array.
                        $invoice['consumption'] = [
                            'meter_id' => $consumer->activemeter->id,
                            'date_from' => $start_date,
                            'date_to' => $end_date,
                            'days' => $total_no_days,
                            'prev_reading' => $start_reading,
                            'curr_reading' => $end_reading,
                            'consumption' => $total_consumption,
                            'old_consumption' => NULL,
                            'net_consumption' => $net_consumption,
                            'unit_price' => $avgPrice, // average price.
                            'meter_change_id' => NULL,
                            'file_id' => NULL,
                        ];

                        // 13. Calling of insertion method from the same controller.
                        $inv_resp = self::invoiceInsert($consumer, $invoice);
                        if($inv_resp)
                        {
                            // Create payment record for THIS invoice
                            PaymentService::create([
                                'invoice_id'      => $inv_resp['invoice_id'],
                                'payment_date'    => date('Y-m-d'),
                                'payment_type_id' => PaymentType::CASH_PAYMENT->value,
                                'transaction_id'  => "CASH",
                                'amount'          => 0,
                                'balance'         => 0,
                                'status_id'       => PaymentStatus::COMPLETED->value,
                                'notes'           => NULL,
                            ]);   
                        }
                        // 14. Update of status and invoice id in the MRO Staging table.
                        $record->update([
                            'status_id' => MroStatus::BILL_SENT->value,
                            'invoice_id' => $inv_resp['invoice_id'],
                        ]);
                        // 15. Insertion of Status history records.
                        BillMroDataHistory::create([
                            'mro_data_id' => $record->id,
                            'status_id' => MroStatus::BILL_SENT->value,
                        ]);
                        
                        // 16. MRO Acknowledgment payload array.
                        $ackPayload[] = [
                            'crn' => $data['crn'],
                            'mro_order_id' => $record->mro_number,
                            'meter_serial_no' => $data['meter_serial_no'],
                            'invoice_no' => $inv_resp['invoice_number'],
                            'invoice_date' => now()->format('Y-m-d'),
                            'month_consumption' => $netReading,
                            'month_amount' => $totalAmount,
                            'remarks' => "Successfully generated."
                        ];
                    }
                }
                else {
                    $ackFailPayload[] = [
                        'mro_order_id' => $record->mro_number,
                        'meter_serial_no' => $data['meter_serial_no'],
                        'crn' => $data['crn'],
                        'reason' => "Consumer Not Found"
                    ];
                }
            } catch (\Throwable $e) {
                $record->update([
                    'status_id' => MroStatus::PROCESS_FAIL->value,
                    'message' => $e->getMessage()
                ]);
            }
        }

        // return the payload to command.
        return [
            'ack_payload' => $ackPayload,
            'ack_fail_payload' => $ackFailPayload,
        ];
    }
    /**
     * Update the response of acknowledgment API, back in the staging table.
     * 
     */
    public static function updateMroRequest($responses)
    {
        // 1. checking the responses
        if($responses) {
            foreach ($responses as $resp) {
                // 2. fecth the mro details to be updated.
                $mroData = BillMroData::where('mro_number', $resp['mro_order_id'])
                    ->first();

                // 3. if MRO found update the status id.
                if ($mroData) {
                    // 4. based the api response, prearing the status ( 5 = bill sent, 4 = ack fail.)
                    $status = $resp['status'] === 'success' ? MroStatus::BILL_SENT->value : MroStatus::PROCESS_FAIL->value;
                    $mroData->update([
                        'status_id' => $status,
                        'error_code'=> $resp['error_code'] ?? null,
                        'error_message'=> $resp['error_message'] ?? null,
                    ]);    
                    BillMroDataHistory::insert([
                        'mro_data_id' => $mroData->id,
                        'status_id'   => $status,
                        'created_at' => now()
                    ]);
                }
            }
            // 5. update the logger file with updated count.
            Log::info('MRO API response received', [
                'response_count' => count($responses)
            ]);
        }
        else {
            Log::info('No MRO data to send');
            return;
        }
    }
    
    /** 
     * Common function for the invoice generation.
     */ 
    public static function invoiceInsert($consumer, $invoice_data)
    {
        // 1. Invoice number generation via service.
        $inv_number = InvoiceService::generateNumber($consumer->ga->state_id, TaxType::VAT->value);
        // 2. Invoice insertion from the data received (excl. invoice number).
        $invoice_data['invoice']['invoice_number'] = $inv_number;
        $inv_insert = BillInvoice::create($invoice_data['invoice']);
        
        if($inv_insert) {
            // 3. save the invoice id column with new consumption object.
            $invoice_data['consumption']['invoice_id'] = $inv_insert->id;
            // 4. Invoice Consumption array insertion (excl. invoice id).
            $inv_cons = BillInvoiceConsumption::create($invoice_data['consumption']);
            // 6.. Attach consumption_id to consumption details.
            if($inv_cons and !empty($invoice_data['consumption_details'])) {
                foreach ($invoice_data['consumption_details'] as &$detail) {
                    $detail['invoice_consumption_id'] = $inv_cons->id;
                }
                BillInvoiceConsumptionDetails::insert($invoice_data['consumption_details']);
            }
            // Ledger Service.
            $ledger_record = LedgerService::create([
                'model' => $inv_insert,
                'consumer_id' => $inv_insert->consumer_id,
                'amount' => ($invoice_data['invoice']['total_amount']),
            ], 'dr');
        }
        return [
            'invoice_id' => $inv_insert->id, 
            'invoice_number' => $inv_number, 
            'net_consumption' => $invoice_data['consumption']['net_consumption'],
            'total_price' => $invoice_data['invoice']['payable_amount'],
            'due_date' => $invoice_data['invoice']['due_date'],
        ];
    }
}