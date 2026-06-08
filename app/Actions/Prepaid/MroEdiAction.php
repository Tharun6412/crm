<?php

namespace App\Actions\Prepaid;

use App\Enums\Constants;
use App\Enums\EdiInterest;
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
        // 1. Fetch the MRO Rental data which is not yet billed.
        $billing_data = BillMroData::where('status_id', MroStatus::BILL_SENT->value)
            ->where('rental_bill', 0)
            ->get();

        if ($billing_data->isNotEmpty()) {
            foreach ($billing_data as $record) {
                try {
                    // 2. Null-safety guard on consumer and scheme.
                    $consumer = Consumer::find($record->consumer_id);

                    if (!$consumer || !$consumer->scheme) {
                        throw new \RuntimeException(
                            "Consumer or scheme not found for consumer_id: {$record->consumer_id}"
                        );
                    }

                    $sd_balance = $consumer->scheme->balance;

                    // 3. JSON Decode of stored raw data.
                    $data = json_decode($record->mro_data, true);

                    $readings_cnt = count($data['current_readings']);
                    $start_date   = $data['current_readings'][0]['start_reading_date_time_1'];
                    $end_date     = $data['current_readings'][$readings_cnt - 1]['end_reading_date_time_' . $readings_cnt];
                    $total_no_days = Carbon::parse($start_date)->diffInDays($end_date);
                    $edi_amount   = $data['total_daily_rental_deducted'];

                    // 4. Fixed typo: was $record['inovice_id']
                    $parent_invoice = $record->invoice_id;

                    $sd_array = MroProcessAction::calculateInterest(
                        $sd_balance,
                        EdiInterest::DAY->value,
                        $edi_amount,
                        $total_no_days
                    );

                    if (!empty($sd_array) && $sd_array['principal'] > 0) {
                        // Invoice 1: SD Principal (SD)
                        $sd_invoice = [
                            'invoice' => [
                                'type_id'           => InvoiceType::SD_EMI->value,
                                'consumer_id'       => $consumer->id,
                                'invoice_date'      => Carbon::parse($end_date)->toDateString(),
                                'base_amount'       => $sd_array['principal'],
                                'taxable_amount'    => $sd_array['principal'],
                                'tax_id'            => TaxType::GST->value,
                                'tax_value'         => 0,
                                'tax_amount'        => 0,
                                'total_amount'      => $sd_array['principal'],
                                'payable_amount'    => $sd_array['principal'],
                                'paid_amount'       => $sd_array['principal'],
                                'balance_amount'    => 0,
                                'parent_invoice_id' => $parent_invoice,
                                'due_date'          => Carbon::parse($end_date)
                                                        ->addDays((int) Constants::DPNG_DUEDAYS->value)
                                                        ->format('Y-m-d'),
                                'status_id'         => InvoiceStatus::PAID->value,
                                'prepaid'           => 2,
                            ],
                            'items' => [
                                'item_id'     => InvoiceItem::SDEMI->value,
                                'description' => 'EDI SD Principal Amount',
                                'quantity'    => 1,
                                'unit_price'  => $sd_array['principal'],
                                'total_price' => $sd_array['principal'],
                            ],
                        ];

                        InvoiceService::create($sd_invoice);

                        // Invoice 2: SD Interest + GST on Interest
                        $interest_total = $sd_array['interest'] + $sd_array['interestGst'];

                        $interest_invoice = [
                            'invoice' => [
                                'type_id'           => InvoiceType::SD_INTEREST->value,
                                'consumer_id'       => $consumer->id,
                                'invoice_date'      => Carbon::parse($end_date)->toDateString(),
                                'base_amount'       => $sd_array['interest'],
                                'taxable_amount'    => $sd_array['interest'],
                                'tax_id'            => TaxType::GST->value,
                                'tax_value'         => 18,
                                'tax_amount'        => $sd_array['interestGst'],
                                'total_amount'      => $interest_total,
                                'payable_amount'    => $interest_total,
                                'paid_amount'       => $interest_total,
                                'balance_amount'    => 0,
                                'parent_invoice_id' => $parent_invoice,
                                'due_date'          => Carbon::parse($end_date)
                                                        ->addDays((int) Constants::DPNG_DUEDAYS->value)
                                                        ->format('Y-m-d'),
                                'status_id'         => InvoiceStatus::PAID->value,
                                'prepaid'           => 2,
                            ],
                            'items' => [
                                'item_id'     => InvoiceItem::SD_INTEREST->value,
                                'description' => 'EDI SD Interest Amount',
                                'quantity'    => 1,
                                'unit_price'  => $interest_total,
                                'total_price' => $interest_total,
                            ],
                        ];

                        InvoiceService::create($interest_invoice);

                        // Deduct EDI amount from SD balance
                        $consumer->scheme->update([
                            'balance' => $sd_balance - $edi_amount,
                        ]);

                        // Mark rental bill as generated
                        $record->update([
                            'rental_bill' => 1,
                        ]);
                    }
                } catch (\Throwable $e) {

                    ApiLogger::error('mro_api', 'mro_edi_action', 'MRO EDI processing failed', [
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