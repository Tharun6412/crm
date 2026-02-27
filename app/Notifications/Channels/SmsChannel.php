<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class SmsChannel
{
    /**
     * Send
     */
    public function send($notifiable, Notification $notification)
    {
        // Get connected phone number
        $phone =  $notifiable->phone;
        // Get SMS message, template Id etc.
        $sms = $notification->toSms($notifiable);
        // dd($sms['message']);

        // Try to call SMS gateway
        try {
            // GET SMS gateway details
            $gateway = config("sms.gateways.w2p");

            // Call SMS API From Way2Plus
            $response = Http::asForm()
                ->timeout(10)
                ->retry(3, 200)
                ->post($gateway['api_url'], [
                    'username' => $gateway['username'],
                    'password' => $gateway['password'],
                    'sendername' => $gateway['sender'],
                    'mobile' => $phone,
                    'message'=> $sms['message'],
                    'routetype' => 1,
                ]);

            // Check reponse 
            if($response->failed()) {
                // Store log
                \Log::error('SMS Failed', [
                    'mobile' => $phone,
                    'response' => $response->body()
                ]);

                // Response
                return $response->body();
            }
            
            // Response
            return $response->successful();
        }
        catch(\Exception $e) {
            // Error log
            \Log::critical('SMS API exception', [
                'mobile' => $phone,
                'error'  => $e->getMessage(),
            ]);

            // Response
            return false;
        }
    }
}
