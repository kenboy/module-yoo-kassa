<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Model\Adapter;

use YooKassa\Client;

/**
 * YooKassa Adapter
 * Use \Kenboy\YooKassa\Model\Adapter\YooAdapterFactory to create new instance of adapter.
 * @codeCoverageIgnore
 */
class YooAdapter
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
     * @return \YooKassa\Request\Payments\CreatePaymentResponse
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function payment(array $attributes)
    {
        return $this->client->createPayment($attributes);
    }

    /**
     * @param string $paymentId
     * @param array $attributes
     * @return \YooKassa\Request\Payments\Payment\CreateCaptureResponse
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function capture($paymentId, array $attributes)
    {
        return $this->client->capturePayment($attributes, $paymentId);
    }

    /**
     * @param string $paymentId
     * @return \YooKassa\Request\Payments\Payment\CancelResponse
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function cancel($paymentId)
    {
        return $this->client->cancelPayment($paymentId);
    }

    /**
     * @param array $attributes
     * @return \YooKassa\Request\Refunds\CreateRefundResponse
     * @throws \YooKassa\Common\Exceptions\ApiException
     * @throws \YooKassa\Common\Exceptions\BadApiRequestException
     * @throws \YooKassa\Common\Exceptions\ForbiddenException
     * @throws \YooKassa\Common\Exceptions\InternalServerError
     * @throws \YooKassa\Common\Exceptions\NotFoundException
     * @throws \YooKassa\Common\Exceptions\ResponseProcessingException
     * @throws \YooKassa\Common\Exceptions\TooManyRequestsException
     * @throws \YooKassa\Common\Exceptions\UnauthorizedException
     */
    public function refund(array $attributes)
    {
        return $this->client->createRefund($attributes);
    }
}
