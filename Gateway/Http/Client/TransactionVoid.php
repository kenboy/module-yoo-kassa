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
     * @inheritdoc
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
