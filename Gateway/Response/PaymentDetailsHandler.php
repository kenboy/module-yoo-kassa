<?php
/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Response;

use Kenboy\YandexCheckout\Gateway\SubjectReader;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

/**
 * Payment Details Handler
 */
class PaymentDetailsHandler implements HandlerInterface
{
    const TEST = 'test';
    const STATUS = 'status';
    const PAID = 'paid';
    const RECEIPT_REGISTRATION = 'receipt_registration';

    /**
     * List of additional details
     * @var array
     */
    protected $additionalInformationMapping = [
        self::TEST,
        self::STATUS,
        self::PAID,
        self::RECEIPT_REGISTRATION,
    ];

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
    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        $transaction = $this->subjectReader->readTransaction($response);
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDO->getPayment();

        $payment->setCcTransId($transaction['id']);
        $payment->setLastTransId($transaction['id']);

        //remove previously set payment nonce
        $payment->unsAdditionalInformation('payment_token');
        foreach ($this->additionalInformationMapping as $item) {
            if (!isset($transaction[$item])) {
                continue;
            }

            $payment->setAdditionalInformation($item, $transaction[$item]);
        }
    }
}
