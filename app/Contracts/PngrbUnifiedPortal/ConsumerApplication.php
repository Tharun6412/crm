<?php

namespace App\Contracts\PngrbUnifiedPortal;

class ConsumerApplication
{
    /**
     * Send Application
     */
    public static function send()
    {
        $apiR = new OAuthApiService();
        // Send data
        $response = $apiR->post('http://15.207.185.179:8070/api/v1/admin/dma/users', 
            [
                'applicantInfo' =>  [
                    'name' => "Ramakrishna Uppala 2",
                    'mobileNumber' => "9703722588",
                    'father_spouse' => "",
                    'dob' => "07-04-1985",
                    'email' => "ramakrishna@testmail.com",
                    'whatsapp' => ""
                ],
                'applicationInfo' => [
                    'dmaApplicationRefNumber' => "CGD192-REF-20260622-003",
                    'cgdId' => "192",
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
                    'dataSharingConsentText' => "Tken",
                    'isCommunicationConsentTaken' => true,
                    'communicationConsentText' => "Yes",
                    'isPremiseOwnerShipDeclarationTaken' => true,
                    'premiseOwnerShipDeclarationText' => ""
                ],
                'dmaInfo' => [
                    'cgdId' => "192",
                    'dmaId' => "ramakrishna",
                    'dmaName' => "Ramakrishna Uppala",
                    'dmaMobileNo' => "9703722588",
                    'dmaEmail' => "ramakrishna.uppala@meghagas.com"
                ]
            ]
        ); 

        return $response;
    }

    /**
     * DMA Documents upload
     * 
     */
    public static function sendDocument()
    {
        $apiHandler = new OAuthApiService();
        $response = $apiHandler->postAttachment('http://15.207.185.179:8070/api/v1/document/dma/users/document',
            [
                'name' => 'sample-document.pdf',
                'path' => 'sample-document.pdf'
            ],
            [
                'metadata' => json_encode([
                    'dmaApplicationRefNumber' => 'CGD192-REF-20260622-003',
                    'cgdId' => '192',
                    'gaId' => '9.72',
                    'documentType' => 'AADHAAR',
                    'documentCategory' => 'IDENTITY_PROOF',
                ]),
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