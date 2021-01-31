<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Gateway\Request;

use Magento\Framework\Exception\LocalizedException;
use Kenboy\YooKassa\Gateway\SubjectReader;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Helper\Formatter;

/**
 * Capture Data Builder
 */
class CaptureDataBuilder implements BuilderInterface
{
    use Formatter;

    const PAYMENT_ID = 'payment_id';

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
     * @throws LocalizedException
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);

        $payment = $paymentDO->getPayment();
        $order = $paymentDO->getOrder();

        $transactionId = $payment->getCcTransId();
        if (!$transactionId) {
            throw new LocalizedException(__('No authorization transaction to proceed capture.'));
        }

        return [
            self::PAYMENT_ID => $transactionId,
            PaymentDataBuilder::AMOUNT => [
                'value' => $this->formatPrice($this->subjectReader->readAmount($buildSubject)),
                'currency' => $order->getCurrencyCode()
            ]
        ];
    }
}
