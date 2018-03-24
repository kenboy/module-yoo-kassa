<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Model\Adapter;

use YandexCheckout\Client;

/**
 * Yandex Adapter
 * Use \Kenboy\YandexCheckout\Model\Adapter\YandexAdapterFactory to create new instance of adapter.
 * @codeCoverageIgnore
 */
class YandexAdapter
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param $shopId
     * @param $secretKey
     */
    public function __construct($shopId, $secretKey)
    {
        $this->client = (new Client())->setAuth($shopId, $secretKey);
    }

    /**
     * @param array $attributes
     * @return \YandexCheckout\Request\Payments\CreatePaymentResponse
     */
    public function payment(array $attributes)
    {
        return $this->client->createPayment($attributes);
    }

    /**
     * @param string $paymentId
     * @param array $attributes
     * @return \YandexCheckout\Request\Payments\Payment\CreateCaptureResponse
     */
    public function capture($paymentId, array $attributes)
    {
        return $this->client->capturePayment($attributes, $paymentId);
    }

    /**
     * @param string $paymentId
     * @return \YandexCheckout\Request\Payments\Payment\CancelResponse
     */
    public function cancel($paymentId)
    {
        return $this->client->cancelPayment($paymentId);
    }

    /**
     * @param array $attributes
     * @return \YandexCheckout\Request\Refunds\CreateRefundResponse
     */
    public function refund(array $attributes)
    {
        return $this->client->createRefund($attributes);
    }
}
