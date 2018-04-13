<?php
/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Command;

use Kenboy\YandexCheckout\Gateway\SubjectReader;
use Kenboy\YandexCheckout\Model\Adapter\YandexAdapterFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\CommandInterface;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\TransactionRepositoryInterface;

/**
 * Class CaptureStrategyCommand
 * @SuppressWarnings(PHPMD)
 */
class CaptureStrategyCommand implements CommandInterface
{
    /**
     * Yandex authorize and capture command
     */
    const SALE = 'sale';

    /**
     * Yandex capture command
     */
    const CAPTURE = 'settlement';

    /**
     * Yandex vault capture command
     */
    const VAULT_CAPTURE = 'vault_capture';

    /**
     * @var CommandPoolInterface
     */
    private $commandPool;

    /**
     * @var TransactionRepositoryInterface
     */
    private $transactionRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @var YandexAdapterFactory
     */
    private $yandexAdapterFactory;

    /**
     * Constructor
     *
     * @param CommandPoolInterface $commandPool
     * @param TransactionRepositoryInterface $repository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SubjectReader $subjectReader
     * @param YandexAdapterFactory $yandexAdapterFactory
     */
    public function __construct(
        CommandPoolInterface $commandPool,
        TransactionRepositoryInterface $repository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SubjectReader $subjectReader,
        YandexAdapterFactory $yandexAdapterFactory
    ) {
        $this->commandPool = $commandPool;
        $this->transactionRepository = $repository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->subjectReader = $subjectReader;
        $this->yandexAdapterFactory = $yandexAdapterFactory;
    }

    /**
     * @inheritdoc
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute(array $commandSubject)
    {
        /** @var \Magento\Payment\Gateway\Data\PaymentDataObjectInterface $paymentDO */
        $paymentDO = $this->subjectReader->readPayment($commandSubject);
        /** @var OrderPaymentInterface $payment */
        $payment = $paymentDO->getPayment();
        ContextHelper::assertOrderPayment($payment);

        $command = $this->getCommand($payment);
        $this->commandPool->get($command)->execute($commandSubject);
    }

    /**
     * Gets command name.
     *
     * @param OrderPaymentInterface $payment
     * @return string
     */
    private function getCommand(OrderPaymentInterface $payment)
    {
        // if auth transaction does not exist then execute authorize&capture command
        $existsCapture = $this->isExistsCaptureTransaction($payment);
        if (!$payment->getAuthorizationTransaction() && !$existsCapture) {
            return self::SALE;
        }

        // do capture for authorization transaction
        if (!$existsCapture) {
            return self::CAPTURE;
        }

        // process capture for payment via Vault
        return self::VAULT_CAPTURE;
    }

    /**
     * Check if capture transaction already exists
     *
     * @param OrderPaymentInterface $payment
     * @return bool
     */
    private function isExistsCaptureTransaction(OrderPaymentInterface $payment)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('payment_id', $payment->getId())
            ->addFilter('txn_type', TransactionInterface::TYPE_CAPTURE)
            ->create();

        return (boolean) $this->transactionRepository
            ->getList($searchCriteria)
            ->getTotalCount();
    }
}
