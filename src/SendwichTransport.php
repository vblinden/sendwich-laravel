<?php

namespace Vblinden\Sendwich;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\MessageConverter;

class SendwichTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $envelope = $message->getEnvelope();

        $apiKey = config('sendwich.api.key');
        $apiUrl = config('sendwich.api.url', 'https://sendwich.dev');

        $headers = [];
        $headersToSkip = ['from', 'to', 'cc', 'bcc', 'subject', 'content-type', 'sender', 'reply-to'];
        foreach ($email->getHeaders() as $name => $header) {
            if (in_array($name, $headersToSkip, true)) {
                continue;
            }

            $headers[$header->getName()] = $header->getBodyAsString();
        }

        if (! $apiKey) {
            throw new TransportException('Sendwich API key is not configured. Please define SENDWICH_API_KEY in your environment.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Accept' => 'application/json',
            ])
                ->withoutRedirecting()
                ->timeout(30)
                ->post(sprintf('%s/api/v1/message', $apiUrl), [
                    'bcc' => $this->stringifyAddresses($email->getBcc()),
                    'cc' => $this->stringifyAddresses($email->getCc()),
                    'from' => $envelope->getSender()->toString(),
                    'headers' => $headers,
                    'html' => $email->getHtmlBody(),
                    'reply_to' => $this->stringifyAddresses($email->getReplyTo()),
                    'subject' => $email->getSubject(),
                    'text' => $email->getTextBody(),
                    'to' => $this->stringifyAddresses($this->getRecipients($email, $envelope)),
                    'attachments' => $this->getAttachments($email),
                ]);

            if ($response->failed()) {
                throw new TransportException(sprintf('Request to Sendwich API failed. Reason: %s', $response->body()), $response->status());
            }
        } catch (\Throwable $exception) {
            throw new TransportException(sprintf('Request to Sendwich API failed. Reason: %s', $exception->getMessage()), is_int($exception->getCode()) ? $exception->getCode() : 0, $exception);
        }
    }

    protected function getRecipients(Email $email, Envelope $envelope): array
    {
        return array_filter($envelope->getRecipients(), function (Address $address) use ($email) {
            return in_array($address, array_merge($email->getCc(), $email->getBcc()), true) === false;
        });
    }

    protected function getAttachments(Email $email): array
    {
        $attachments = [];
        if ($email->getAttachments()) {
            foreach ($email->getAttachments() as $attachment) {
                $attachmentHeaders = $attachment->getPreparedHeaders();

                $contentType = $attachmentHeaders->get('Content-Type')->getBody();
                $disposition = $attachmentHeaders->getHeaderBody('Content-Disposition');
                $filename = $attachmentHeaders->getHeaderParameter('Content-Disposition', 'filename');

                if ($contentType == 'text/calendar') {
                    $content = $attachment->getBody();
                } else {
                    $content = str_replace("\r\n", '', $attachment->bodyToString());
                }

                $item = [
                    'content_type' => $contentType,
                    'content' => $content,
                    'filename' => $filename,
                ];

                if ($disposition === 'inline') {
                    $item['content_id'] = $attachment->hasContentId() ? $attachment->getContentId() : $filename;
                }

                $attachments[] = $item;
            }
        }

        return $attachments;
    }

    public function __toString(): string
    {
        return 'sendwich';
    }
}
