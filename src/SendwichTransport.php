<?php

namespace Vblinden\Sendwich;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;

class SendwichTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $apiKey = config('sendwich.api.key');
        $apiUrl = config('sendwich.api.url');

        if (empty($apiKey)) {
            throw new TransportException('Sendwich API key is not configured. Please set SENDWICH_API_KEY in your environment.');
        }

        $raw = $message->getMessage();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
            ])->timeout(30)->post(sprintf('%s/api/v1/message', $apiUrl), [
                'message' => base64_encode($raw->toString()),
            ]);

            if ($response->failed()) {
                $statusCode = $response->status();
                $errorBody = $response->body();

                $errorMessage = match ($statusCode) {
                    401 => 'Authentication failed. Please check your Sendwich API key.',
                    403 => 'Access denied. Your API key may not have permission to send messages.',
                    404 => 'Sendwich API endpoint not found. Please verify the API URL configuration.',
                    422 => sprintf('Invalid message format: %s', $errorBody),
                    429 => 'Rate limit exceeded. Please try again later.',
                    500, 502, 503, 504 => 'Sendwich service is temporarily unavailable. Please try again later.',
                    default => sprintf('Failed to send message via Sendwich API. Status: %d, Response: %s', $statusCode, $errorBody),
                };

                throw new TransportException($errorMessage, $statusCode);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new TransportException(sprintf('Failed to connect to Sendwich API: %s', $e->getMessage()), 0, $e);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new TransportException(sprintf('Request to Sendwich API failed: %s', $e->getMessage()), 0, $e);
        } catch (\Exception $e) {
            throw new TransportException(sprintf('Unexpected error while sending message via Sendwich: %s', $e->getMessage()), 0, $e);
        }
    }

    public function __toString(): string
    {
        return 'sendwich';
    }
}
