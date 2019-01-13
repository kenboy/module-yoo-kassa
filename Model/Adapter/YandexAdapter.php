<?php
/**
 * Copyright (c) 2018. All rights reserved.
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
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function payment(array $attributes)
    {
        return $this->client->createPayment($attributes);
    }

    /**
     * @param string $paymentId
     * @param array $attributes
     * @return \YandexCheckout\Request\Payments\Payment\CreateCaptureResponse
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function capture($paymentId, array $attributes)
    {
        return $this->client->capturePayment($attributes, $paymentId);
    }

    /**
     * @param string $paymentId
     * @return \YandexCheckout\Request\Payments\Payment\CancelResponse
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function cancel($paymentId)
    {
        return $this->client->cancelPayment($paymentId);
    }

    /**
     * @param array $attributes
     * @return \YandexCheckout\Request\Refunds\CreateRefundResponse
     * @throws \YandexCheckout\Common\Exceptions\ApiException
     * @throws \YandexCheckout\Common\Exceptions\BadApiRequestException
     * @throws \YandexCheckout\Common\Exceptions\ForbiddenException
     * @throws \YandexCheckout\Common\Exceptions\InternalServerError
     * @throws \YandexCheckout\Common\Exceptions\NotFoundException
     * @throws \YandexCheckout\Common\Exceptions\ResponseProcessingException
     * @throws \YandexCheckout\Common\Exceptions\TooManyRequestsException
     * @throws \YandexCheckout\Common\Exceptions\UnauthorizedException
     */
    public function refund(array $attributes)
    {
        return $this->client->createRefund($attributes);
    }
}
