<?php

namespace App\Contracts\Prepaid;

use App\Enums\PrepaidApi;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Return_;

class Acquisition
{
    /**
     * Acquisition API
     */
    public function push($consumerData)
    {
        switch ($consumerData->segment_id) {
            case 2:
                $con_type = "COM";
                break;
            case 3:
                $con_type = "IND";
                break;
            default:
                $con_type = "DOM";
                break;
        }
        $maxLen = 48;
        $consumer_name = $consumerData->name;
        $cnsr_name = str_split($consumer_name, 40);
        $part1 = $cnsr_name[0] ?? "";
        $part2 = $cnsr_name[1] ?? ".";
        $consumer_details = array(
            'MT_Integ_Request' => [
                'Integ_Request' => [[
                    'COMPANY' => '',
                    'GA' => $consumerData->ga->hes_code,
                    'PNGRB_DISTRICT' => $consumerData->district->name,
                    'ADDRESS_DISTRICT' => $consumerData->district->name,
                    'CHARGE_AREA_CODE' => $consumerData->ca->name, //Location Id taken for Charge Area Code 
                    'CRN' => $consumerData->crn,
                    'FIRST_NAME' => $part1,
                    'LAST_NAME' => $part2,
                    'middle_name' => NULL,
                    'name_of_the_org' => '',
                    'full_address_of_customer' => $consumerData->hno .", ". $consumerData->street .", ". $consumerData->colony,
                    'POSTAL_CODE' => $consumerData->pincode,
                    'CITY' => $consumerData->city,
                    'DISTRICT' => $consumerData->district->name,
                    'CONTACT_NO' => $consumerData->phone,
                    'EMAIL_ADDRESS' => (!empty($consumerData->email)) ? $consumerData->email : 'noreplay@meghagas.com',
                    'DMA_SCHEME' => $consumerData->scheme->scheme->code,
                    'SCHEME_DESCRIPTION' => $consumerData->scheme->scheme->code,
                    'MECH_METER_SERIAL_NO' => $consumerData->activeMeter->meter_no,
                    'PREPAID_MODULENO' => $consumerData->activeMeter->meter_serial_no,
                    'METER_INSTALLATION_DATE' => date('Ymd', strtotime($consumerData->activeMeter->install_date)),
                    'REGISTRATION_AMOUNT_RECEIVED' => '',
                    'CONNECTION_SECURITY_DEP_AMOUNT_RECEIVED' => $consumerData->scheme->security_deposit,
                    'CONSUMPTION_SECURITY_DEP_AMOUNT_RECEIVED' => $consumerData->scheme->consumption_deposit,
                    'customer_mode' => $con_type,
                    'bp_grouping' => $consumerData->priceGroup?->code,
                ]]
            ]
        );
        // Call API
        $response = Http::withHeaders([
            'X-API-KEY' => 'YfRPGJH1S98n2l7tbC7k7gD9RmQdJ2j8TxLr9JKL4A3gF1pL5m'
                ])->acceptJson()->post(PrepaidApi::acquisition()->value, $consumer_details);
        if ($response->failed()) {
            logger()->error('HES API FAILED', [
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $consumer_details,
            ]);
        }
        return $response;

        // return $consumer_details;
    }
}