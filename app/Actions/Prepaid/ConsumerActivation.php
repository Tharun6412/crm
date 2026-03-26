<?php
namespace App\Actions\Prepaid;

use App\Enums\ConsumerStatus as EnumsConsumerStatus;
use App\Models\Consumer\Consumer;
use App\Models\Consumer\ConsumerStatus;

class ConsumerActivation
{
    public static function process($payload)
    {
        $consumer = Consumer::where('crn', $payload['crn'])
        ->whereHas('activeMeter', function ($q) use ($payload) {
            $q->where('meter_no', $payload['meter_no']);
        })
        ->first();

        if (!$consumer) {
            return self::error('Invalid Consumer/MeterNumber/Data not sent to HES');
        }
        switch ($consumer->status_id) {
            case EnumsConsumerStatus::ACTIVATE->value:
                return self::error('This consumer is already in activated state');

            case EnumsConsumerStatus::REGISTER->value:
            case EnumsConsumerStatus::ACCEPT->value:
            case EnumsConsumerStatus::EXECUTE->value:
                return self::error('To activate, this consumer status should be in HSC.');

            case EnumsConsumerStatus::REJECT->value:
            case EnumsConsumerStatus::PD->value:
            case EnumsConsumerStatus::TD->value:
                return self::error('This consumer was Rejected or Disconnected');

            case EnumsConsumerStatus::HSC->value:
                return self::activate($consumer, $payload);
            default:
                return self::error('Consumer is Invalid');
        }
    }

    private static function activate($consumer, $payload)
    {
        // update the meter reading
        $meter = $consumer->activeMeter()
            ->where('meter_no', $payload['meter_no'])
            ->first();

        $meter->update([
            'initial_reading' => $payload['move_in_read'],
            'install_date' => $payload['move_in_date'],
        ]);

        // Update consumer
        $consumer->update([
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
        ]);

        // Insert status history
        ConsumerStatus::create([
            'consumer_id' => $consumer->id,
            'status_id' => EnumsConsumerStatus::ACTIVATE->value,
            'created_at' => now(),
        ]);

        return [
            'status' => true,
            'data' => 'Consumer Activated Successfully',
            'response' => null,
            'code' => 200
        ];
    }

    private static function error($message)
    {
        return [
            'status' => false,
            'data' => $message,
            'response' => null,
            'code' => 400
        ];
    }
}