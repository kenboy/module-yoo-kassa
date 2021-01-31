<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Gateway\Request;

use Kenboy\YooKassa\Gateway\SubjectReader;
use Kenboy\YooKassa\Observer\DataAssignObserver;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Helper\Formatter;

/**
 * Payment Data Builder
 */
class PaymentDataBuilder implements BuilderInterface
{
    use Formatter;

    /**
     * The billing amount of the request. This value must be greater than 0,
     * and must match the currency format of the merchant account.
     */
    const AMOUNT = 'amount';

    /**
     * One-time-use token that references a payment method provided by your customer.
     */
    const PAYMENT_TOKEN = 'payment_token';

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * Constructor
     *
     * @param SubjectReader $subjectReader
     */
    public function __construct(SubjectReader $subjectReader)
    {
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $order = $paymentDO->getOrder();
        $payment = $paymentDO->getPayment();

        return [
            self::AMOUNT => [
                'value' => $this->formatPrice($this->subjectReader->readAmount($buildSubject)),
                'currency' => $order->getCurrencyCode()
            ],
            self::PAYMENT_TOKEN => $payment->getAdditionalInformation(
                DataAssignObserver::PAYMENT_METHOD_TOKEN
            ),
        ];
    }
}
