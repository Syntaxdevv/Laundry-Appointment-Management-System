<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PayPalService
{
    private function baseUrl(): string
    {
        $url = rtrim(
            (string) config('services.paypal.base_url'),
            '/'
        );

        if (!$url) {
            throw new RuntimeException('PayPal API URL is missing.');
        }

        return $url;
    }

    private function accessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new RuntimeException(
                'PayPal credentials are missing.'
            );
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->timeout(30)
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$response->successful()) {
            Log::error('PayPal token request failed.', [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                'Unable to connect to PayPal.'
            );
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new RuntimeException(
                'PayPal did not return an access token.'
            );
        }

        return $token;
    }

    public function createOrder(Booking $booking): string
    {
        $amount = number_format(
            (float) $booking->total_price,
            2,
            '.',
            ''
        );

        if ((float) $amount <= 0) {
            throw new RuntimeException(
                'Invalid booking amount.'
            );
        }

        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->timeout(30)
            ->post($this->baseUrl() . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',

                'purchase_units' => [
                    [
                        'reference_id' => 'BOOKING-' . $booking->id,
                        'description' => 'LinisLaba Booking #' . $booking->id,

                        'amount' => [
                            'currency_code' => 'PHP',
                            'value' => $amount,
                        ],
                    ],
                ],

                'payment_source' => [
                    'paypal' => [
                        'experience_context' => [
                            'return_url' => route(
                                'customer.paypal.success',
                                $booking->id
                            ),

                            'cancel_url' => route(
                                'customer.paypal.cancel',
                                $booking->id
                            ),
                        ],
                    ],
                ],
            ]);

        if (!$response->successful()) {
            Log::error('PayPal order creation failed.', [
                'booking_id' => $booking->id,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                'Unable to create PayPal checkout.'
            );
        }

        $orderId = $response->json('id');
        $links = $response->json('links', []);

        $approvalLink = collect($links)->first(function ($link) {
            return in_array(
                $link['rel'] ?? '',
                ['payer-action', 'approve'],
                true
            );
        });

        $approvalUrl = $approvalLink['href'] ?? null;

        if (!$orderId || !$approvalUrl) {
            throw new RuntimeException(
                'Incomplete PayPal order response.'
            );
        }

        // Save the PayPal order ID without mass-assignment issues.
        $booking->forceFill([
            'payment_provider' => 'paypal',
            'payment_method' => 'online',
            'payment_status' => 'pending',
            'paypal_order_id' => $orderId,
        ])->save();

        return $approvalUrl;
    }

    public function captureOrder(string $orderId): array
    {
        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->timeout(30)
            ->post(
                $this->baseUrl() . '/v2/checkout/orders/' .
                urlencode($orderId) . '/capture',
                []
            );

        if (!$response->successful()) {
            Log::error('PayPal capture failed.', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);

            throw new RuntimeException(
                'Unable to verify PayPal payment.'
            );
        }

        $data = $response->json();

        if (($data['status'] ?? '') !== 'COMPLETED') {
            throw new RuntimeException(
                'PayPal payment is not completed.'
            );
        }

        return $data;
    }
}
