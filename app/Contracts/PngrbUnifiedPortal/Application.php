<?php

namespace App\Contracts\PngrbUnifiedPortal;

class Application
{
    /**
     * Send Application
     */
    public static function send()
    {
        $apiR = new OAuthApiService();
        // Send data
        $response = $apiR->post('http://15.207.185.179/api/v1/admin/dma/users', 
            [
                'applicantInfo' =>  [
                    'name' => "Ramakrishna Uppala",
                    'mobileNumber' => "9703722588",
                    'father_spouse' => "",
                    'dob' => "07-04-1985",
                    'email' => "",
                    'whatsapp' => ""
                ],
                'applicationInfo' => [
                    'dmaApplicationRefNumber' => "string",
                    'cgdId' => "19",
                    'gaId' => "9.72",
                    'ekycStatus' => "AUTO_APPROVED",
                    'serviceabilityStatus' => "SERVICEABLE",
                    'pngAddress' => [
                        'occupancyType' => "TENANT",
                        'houseNo' => "111A",
                        'floor' => "First",
                        'society' => "Subishi Fortuna Towers",
                        'area' => "Mokila",
                        'city' => "Shankarapalle",
                        'district' => "RangaReddy",
                        'state' => "Telangana",
                        'pincode' => "501203",
                        'premiseType' => "SOCIETY",
                        'latitude' => 17.429873,
                        'longitude' => 78.1959254
                    ]
                ],
                'consentAndDeclaration' => [
                    'isDataSharingConsentTaken' => true,
                    'dataSharingConsentText' => "string",
                    'isCommunicationConsentTaken' => true,
                    'communicationConsentText' => "string",
                    'isPremiseOwnerShipDeclarationTaken' => true,
                    'premiseOwnerShipDeclarationText' => "string"
                ],
                'dmaInfo' => [
                    'cgdId' => "string",
                    'dmaId' => "string",
                    'dmaName' => "string",
                    'dmaMobileNo' => "string",
                    'dmaEmail' => "string"
                ]
            ]
        ); 

        return $response;
    }

    /**
     * Send status updates
     */
    public static function updateStatus()
    {
        //
        $apiHandler = new OAuthApiService();
        $response = $apiHandler->post('http://15.207.185.179/api/v1/admin/cgd/applications/status-update', 
            [
                [
                    'applicationNumber' => "PNG202600010",
                    'status' => "APPROVED",
                    'remarks' => "string",
                    'cgdId' => "CGD-192"
                ]
            ]
        );

        return $response;
    }
}