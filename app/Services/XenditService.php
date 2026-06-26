<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Integrasi Xendit Invoice API via HTTP langsung (REST murni, tanpa SDK)
 * supaya tidak menambah dependency composer baru.
 */
class XenditService
{
    private string $apiKey;

    private string $webhookToken;

    public function __construct()
    {
        $this->apiKey = (string) config('services.xendit.api_key');
        $this->webhookToken = (string) config('services.xendit.webhook_token');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * Buat invoice pembayaran dan kembalikan URL checkout hosted Xendit.
     */
    public function createInvoice(string $externalId, int $amount, string $description, array $payer = []): array
    {
        $response = Http::withBasicAuth($this->apiKey, '')
            ->withHeaders(['Accept' => 'application/json'])
            ->post('https://api.xendit.co/v2/invoices', [
                'external_id' => $externalId,
                'amount' => $amount,
                'description' => $description,
                'payer_email' => $payer['email'] ?? null,
                'success_redirect_url' => route('billing.index'),
                'failure_redirect_url' => route('billing.index'),
            ]);

        if ($response->failed()) {
            Log::error('Xendit createInvoice failed', ['body' => $response->body()]);
            throw new \RuntimeException('Gagal membuat invoice pembayaran: '.$response->json('message', $response->body()));
        }

        return [
            'invoice_id' => $response->json('id'),
            'invoice_url' => $response->json('invoice_url'),
        ];
    }

    /**
     * Verifikasi header X-Callback-Token pada webhook Xendit.
     */
    public function isValidCallback(?string $tokenHeader): bool
    {
        return ! empty($this->webhookToken) && hash_equals($this->webhookToken, (string) $tokenHeader);
    }
}
