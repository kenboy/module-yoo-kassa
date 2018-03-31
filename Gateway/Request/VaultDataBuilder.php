<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Vault Data Builder
 */
class VaultDataBuilder implements BuilderInterface
{
    /**
     * The option that determines whether the payment method associated with
     * the successful transaction should be stored in the Vault.
     */
    const SAVE_PAYMENT_METHOD = 'save_payment_method';

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        return [
            self::SAVE_PAYMENT_METHOD => true
        ];
    }
}
