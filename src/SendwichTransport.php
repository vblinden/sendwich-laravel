<?php

namespace Vblinden\Sendwich;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class SendwichTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $apiKey = config('sendwich.api.key');
        $apiUrl = config('sendwich.api.url');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$apiKey,
        ])->post(sprintf('%s/api/v1/message', $apiUrl), $message);

        if ($response->failed()) {
            throw new \Exception('Failed to send message');
        }
    }

    public function __toString(): string
    {
        return 'sendwich';
    }
}
