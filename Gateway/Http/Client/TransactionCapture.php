<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Http\Client;

/**
 * Transaction Capture
 */
class TransactionCapture extends AbstractTransaction
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
            ->capture($data['payment_id'], array_diff_key($data, array_flip(['payment_id'])));
    }
}
