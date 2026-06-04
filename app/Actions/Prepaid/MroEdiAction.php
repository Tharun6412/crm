<?php

namespace App\Actions\Prepaid;

use App\Enums\Constants;
use App\Enums\InvoiceItem;
use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Enums\MroStatus;
use App\Enums\TaxType;
use App\Helpers\ApiLogger;
use App\Models\Consumer\Consumer;
use App\Models\Invoice\BillMroData;
use App\Models\Invoice\BillMroDataHistory;
use App\Services\InvoiceService;
use Carbon\Carbon;

class MroEdiAction
{
    public static function getEdiBillingData()
    {
        // 1. Fetch the MRO Rental data which is not generated.
        $billing_data = BillMroData::where('status_id',MroStatus::BILL_SENT->value)->where('rental_bill',0)->get();

        if($billing_data)
        {
            foreach ($billing_data as $record) {
                try {
                    $consumer = Consumer::find($record->consumer_id);

                    $sd_balance = $consumer->scheme->balance;
                    // 2. JSON Decode of stored raw data.
                    $data = json_decode($record->mro_data, true);

                    $readings_cnt = count($data['current_readings']);
                    $start_date = $data['current_readings'][0]['start_reading_date_time_1'];
                    $end_date = $data['current_readings'][$readings_cnt-1]['end_reading_date_time_'.$readings_cnt];
                    $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
                    $edi_amount = $data['total_daily_rental_deducted'];
                    $parent_invoice = $record['inovice_id'];

                    $sd_array = MroProcessAction::calculateInterest($sd_balance, null, $edi_amount, $total_no_days );
                    
                    if(!empty($sd_array) and $sd_array['principal'] > 0) {
                        $invoice['invoice'] = [
                            'type_id' => InvoiceType::SD_EMI->value, //1 => Gas Invoice
                            'consumer_id' => $consumer->id,
                            'invoice_date' => Carbon::parse($end_date)->toDateString(),
                            'base_amount' => $sd_array['principal'],
                            'taxable_amount' => $sd_array['principal'],
                            'tax_id'=> TaxType::GST->value,
                            'tax_value' => 0,
                            'tax_amount' => 0,
                            'total_amount' => $sd_array['principal'],
                            'payable_amount' => $sd_array['principal'],
                            'paid_amount' => $sd_array['principal'],
                            'balance_amount' => 0,
                            'parent_invoice_id' => $parent_invoice,
                            'due_date' => Carbon::parse($end_date)->addDays((int)Constants::DPNG_DUEDAYS->value)->format('Y-m-d'),
                            'status_id' => InvoiceStatus::PAID->value, // paid
                            'prepaid' => 2,
                        ];
                        // 12.Invoice Consumption Array.
                        $invoice['items'] = [
                            'item_id' => InvoiceItem::SDEMI->value, // item id
                            'description' => 'EDI SD Amount',
                            'quantity' => 1,
                            'unit_price' => $sd_array['principal'],
                            'total_price' => $sd_array['principal'],
                        ];

                        // 13. Calling of insertion method from the same controller.
                        $inv_resp = InvoiceService::create($invoice);
                        // 14. Update of status and invoice id in the MRO Staging table.
                        $record->update([
                            'rental_bill' => 1, // Bill created.
                        ]);
                        // 15. Insertion of Status history records.
                        BillMroDataHistory::insert([
                            'mro_data_id' => $record->id,
                            'status_id' => MroStatus::BILL_SENT->value,
                            'created_at' => now()
                        ]);
                    }
                }
                catch (\Throwable $e) {
                    $record->update([
                        'status_id' => MroStatus::PROCESS_FAIL->value
                    ]);
                    BillMroDataHistory::insert([
                        'mro_data_id' => $record->id,
                        'status_id' => MroStatus::PROCESS_FAIL->value,
                        'notes' => $e->getMessage(),
                        'created_at' => now()
                    ]);
                    ApiLogger::error('mro_api','mro_edi_action','MRO EDI processing failed', [
                        'mro_id'     => $record->id,
                        'mro_number' => $record->mro_number,
                        'error'      => $e->getMessage(),
                        'trace'      => $e->getTraceAsString(),
                    ]);
                }
            }
        }
    }
}