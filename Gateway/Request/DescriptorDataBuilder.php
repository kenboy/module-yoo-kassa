<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Request;

use Kenboy\YandexCheckout\Gateway\SubjectReader;
use Kenboy\YandexCheckout\Gateway\Config\Config;
use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Descriptor Data Builder
 */
class DescriptorDataBuilder implements BuilderInterface
{
    /**
     * @var string
     */
    const DESCRIPTION = 'description';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var SubjectReader
     */
    private $subjectReader;

    /**
     * @param Config $config
     * @param SubjectReader $subjectReader
     */
    public function __construct(Config $config, SubjectReader $subjectReader)
    {
        $this->config = $config;
        $this->subjectReader = $subjectReader;
    }

    /**
     * @inheritdoc
     */
    public function build(array $buildSubject)
    {
        $paymentDO = $this->subjectReader->readPayment($buildSubject);
        $order = $paymentDO->getOrder();

        $values = $this->config->getDynamicDescriptors($order->getStoreId());
        return !empty($values) ? [self::DESCRIPTION => $values] : [];
    }
}
