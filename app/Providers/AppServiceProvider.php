<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Email;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Mail::extend('brevo', function (array $config) {
            return new class extends AbstractTransport {
                protected function doSend(SentMessage $message): void
                {
                    $email = $message->getOriginalMessage();
                    $envelope = $message->getEnvelope();

                    $from = [
                        'email' => $envelope->getSender()->getAddress(),
                        'name' => config('mail.from.name', 'PixelPlay')
                    ];

                    $to = collect($envelope->getRecipients())->map(fn($address) => [
                        'email' => $address->getAddress(),
                    ])->toArray();

                    $subject = $email->getHeaders()->get('Subject')?->getBodyAsString() ?? 'Aviso do Sistema';

                    $htmlContent = method_exists($email, 'getHtmlBody') ? $email->getHtmlBody() : '';
                    if (empty($htmlContent) && method_exists($email, 'getTextBody')) {
                        $htmlContent = $email->getTextBody();
                    }

                    Http::withHeaders([
                        'api-key' => config('mail.mailers.brevo.key'),
                        'Content-Type' => 'application/json',
                    ])->post('https://api.brevo.com/v3/smtp/email', [
                                'sender' => $from,
                                'to' => $to,
                                'subject' => $subject,
                                'htmlContent' => $htmlContent,
                            ]);
                }

                public function __toString(): string
                {
                    return 'brevo';
                }
            };
        });
    }
}