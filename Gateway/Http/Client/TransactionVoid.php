<?php
/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Http\Client;

/**
 * Transaction Void
 */
class TransactionVoid extends AbstractTransaction
{
    /**
     * @param array $data
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
    protected function process(array $data)
    {
        $storeId = $data['store_id'] ?? null;
        // sending store id and other additional keys are restricted by Yandex API
        unset($data['store_id']);

        return $this->adapterFactory->create($storeId)
            ->cancel($data['payment_id']);
    }
}
