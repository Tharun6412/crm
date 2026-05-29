<?php
namespace App\Actions\Prepaid;

use App\Enums\Constants;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\MroStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\TaxType;
use App\Helpers\ApiLogger;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillInvoice;
use App\Models\Invoice\BillInvoiceConsumption;
use App\Models\Invoice\BillInvoiceConsumptionDetails;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use App\Models\Master\PriceGroupHistory;
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
                if (empty($data['current_readings']) || !is_array($data['current_readings'])) {
                    throw new \InvalidArgumentException('current_readings is empty or not an array.');
                }
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
                    $cf = (float) ($consumer->activeMeter->vcf ?? 1);
                    $cf = ($cf > 0) ? $cf : 1;
                    // 4. calculation of overall readings and overall dates for billing cycle.
                    $readings_cnt = count($data['current_readings']);
                    $start_reading = (floor($data['current_readings'][0]['start_reading_1'] * 100)/100);
                    $end_reading = $data['current_readings'][$readings_cnt-1]['end_reading_'.$readings_cnt];
                    $start_date = $data['current_readings'][0]['start_reading_date_time_1'];
                    $end_date = $data['current_readings'][$readings_cnt-1]['end_reading_date_time_'.$readings_cnt];
                    $start_meter_balance = $data['current_readings'][0]['start_meter_balance_1'];
                    $end_meter_balance = $data['current_readings'][$readings_cnt-1]['end_meter_balance_'.$readings_cnt];
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
                        throw new \RuntimeException("End reading must be greater than start reading.");
                    }
                    else {
                        // 6. Looping of meter readings array.
                        $total_consumption = 0;
                        $total_basic_amount = 0;
                        // 7. Fetching of price group history id, vat for given selected period.
                        $price = PriceGroupHistory::where('effective_from','<=', $end_date)->where('segment_id', $consumer->segment_id)->where('ga_id', $consumer->ga_id)->orderBy('effective_from', 'desc')->first();
                        if (!$price) {
                            throw new \RuntimeException("No price history found");
                        }
                        $tax_value = (float) $price->vat;

                        foreach ($data['current_readings'] as $key => $reading) {
                            $consmp_breakup = 0;
                            $start = (floor((float)$reading['start_reading_'.($key+1)] * 100)/100);
                            $end   = (float)$reading['end_reading_'.($key+1)];
                            $s_date = Carbon::parse($reading['start_reading_date_time_'.($key+1)])->toDateString();
                            $e_date = Carbon::parse($reading['end_reading_date_time_'.($key+1)])->toDateString();
                            $no_days = Carbon::parse($s_date)->diffInDays($e_date);

                            if($end < $start){
                                $ackFailPayload[] = [
                                    'mro_order_id' => $record->mro_number,
                                    'meter_serial_no' => $data['meter_serial_no'],
                                    'crn' => $data['crn'],
                                    'reason' => "Invalid readings.."
                                ];
                                throw new \RuntimeException("End reading must be greater than start reading.");
                            }
                            // 8. calculation of meter readings and given gas price. (for consumption details).
                            $net_price = $reading['gas_price_'.($key+1)]; 
                            $basic_price = round(($net_price*(100/(100+$tax_value))),2);
                            $consmp_breakup = ($end - $start);
                            $netReading = ($consmp_breakup * $cf);
                            $baseAmt = $netReading * $basic_price;

                            $net_basic_price += $basic_price;
                            $total_consumption += $consmp_breakup;
                            $net_consumption += $netReading;
                            $total_basic_amount += $baseAmt;
                            $avgPrice += $net_price;
                            // 9. Invoice Consumption Details Array. ()
                            // $invoice['consumption_details'][] = [
                            //     'price_history_id' => $price?->id,
                            //     'days' => $no_days,
                            //     'consumption' => $consmp_breakup,
                            //     'cf' => $cf,
                            //     'unit_price' => $basic_price,
                            //     'total_price' => $baseAmt,
                            // ];
                        }

                        // 10. Amount Calculation part.
                        // $avgPrice = $avgPrice / $readings_cnt;
                        $avgPrice = ($net_consumption > 0) ? round(($total_basic_amount / $net_consumption),4) : 0 ;
                        $basicAmount = round(($net_consumption * $avgPrice),2);
                        $vatAmount = round((($basicAmount * $tax_value) / 100),2);
                        $totalAmount = round(($basicAmount + $vatAmount),2);   

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
                            'paid_amount' => $totalAmount,
                            'balance_amount' => 0,
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
                                'amount'          => $totalAmount,
                                'balance'         => 0,
                                'status_id'       => PaymentStatus::COMPLETED->value,
                                'notes'           => NULL,
                            ]);   
                        }
                        // 14. Update of status and invoice id in the MRO Staging table.
                        $record->update([
                            'status_id' => MroStatus::BILL_SENT->value,
                            'invoice_id' => $inv_resp['invoice_id'],
                            'start_meter_balance' => $start_meter_balance,
                            'end_meter_balance' => $end_meter_balance,
                        ]);
                        // 15. Insertion of Status history records.
                        BillMroDataHistory::insert([
                            'mro_data_id' => $record->id,
                            'status_id' => MroStatus::BILL_SENT->value,
                            'created_at' => now()
                        ]);
                        
                        // 16. MRO Acknowledgment payload array.
                        $encoded_id = md5($inv_resp['invoice_number']);
                        // $url = "https://consumer.meghagas.com/Invoice/invoice_pdf/".$encoded_id; // regular gas bill
                        $url = "https://consumer.meghagas.com/Invoice/prepaid_invoice_pdf/".$encoded_id;
                        $ackPayload[] = [
                            'crn' => $data['crn'],
                            'mro_order_id' => $record->mro_number,
                            'meter_serial_no' => $data['meter_serial_no'],
                            'invoice_no' => $inv_resp['invoice_number'],
                            'invoice_date' => now()->format('Y-m-d'),
                            'month_consumption' => round($netReading, 3),
                            'month_amount' => round($totalAmount,2),
                            'remarks' => "Successfully generated.",
                            'bill_url' => $url,
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
                    'status_id' => MroStatus::PROCESS_FAIL->value
                ]);
                BillMroDataHistory::insert([
                    'mro_data_id' => $record->id,
                    'status_id' => MroStatus::PROCESS_FAIL->value,
                    'notes' => $e->getMessage(),
                    'created_at' => now()
                ]);
                ApiLogger::error('mro_api','mro_process_api','MRO processing failed', [
                    'mro_id'     => $record->id,
                    'mro_number' => $record->mro_number,
                    'error'      => $e->getMessage(),
                    'trace'      => $e->getTraceAsString(),
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
                    ]);    
                    BillMroDataHistory::insert([
                        'mro_data_id' => $mroData->id,
                        'status_id'   => $status,
                        'notes'=> $resp['error_message'] ?? null,
                        'created_at' => now()
                    ]);
                }
            }
            // 5. update the logger file with updated count.
            print "Total MRO Bills Generated : ".count($responses);
            ApiLogger::info('mro_api','mro_process_api','MRO API response received', [
                'response_count' => count($responses)
            ]);
        }
        else {
            ApiLogger::info('mro_api','mro_process_api','No MRO data to send');
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

    /**
     * Calculate interest, GST on interest
     * 
     * @param double $sdBalance Balance security deposit
     * @param double $dayInterestRate Daily interest rate
     * @param double $amount Deducted amount
     * @param int $days Deducted number of days 
     * 
     * @return array
     */
    public static function calculateInterest($sdBalance, $dayInterestRate, $amount, $days)
    {
        $interest = $days * ($sdBalance * ($dayInterestRate / 100));
        $interestGst = $interest * (18/100);
        
        // Return parameters
        return [
            'interest' => $interest,
            'interestGst' => $interestGst,
            'principal' => $amount - ($interest + $interestGst),
        ];
    }
}