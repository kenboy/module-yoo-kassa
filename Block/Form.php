<?php
/**
 * Copyright (c) 2021. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YooKassa\Block;

use Magento\Backend\Model\Session\Quote;
use Kenboy\YooKassa\Gateway\Config\Config as GatewayConfig;
use Kenboy\YooKassa\Model\Adminhtml\Source\CcType;
use Kenboy\YooKassa\Model\Ui\ConfigProvider;
use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Block\Form\Cc;
use Magento\Payment\Helper\Data;
use Magento\Payment\Model\Config;
use Magento\Vault\Model\VaultPaymentInterface;

/**
 * Class Form
 */
class Form extends Cc
{
    /**
     * @var Quote
     */
    protected $sessionQuote;

    /**
     * @var Config
     */
    protected $gatewayConfig;

    /**
     * @var CcType
     */
    protected $ccType;

    /**
     * @var Data
     */
    private $paymentDataHelper;

    /**
     * @param Context $context
     * @param Config $paymentConfig
     * @param Quote $sessionQuote
     * @param GatewayConfig $gatewayConfig
     * @param CcType $ccType
     * @param Data $paymentDataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $paymentConfig,
        Quote $sessionQuote,
        GatewayConfig $gatewayConfig,
        CcType $ccType,
        Data $paymentDataHelper,
        array $data = []
    ) {
        parent::__construct($context, $paymentConfig, $data);
        $this->sessionQuote = $sessionQuote;
        $this->gatewayConfig = $gatewayConfig;
        $this->ccType = $ccType;
        $this->paymentDataHelper = $paymentDataHelper;
    }

    /**
     * Get list of available card types of order billing address country
     * @return array
     */
    public function getCcAvailableTypes()
    {
        $configuredCardTypes = $this->getConfiguredCardTypes();
        $countryId = $this->sessionQuote->getQuote()->getBillingAddress()->getCountryId();
        return $this->filterCardTypesForCountry($configuredCardTypes, $countryId);
    }

    /**
     * Check if cvv validation is available
     * @return boolean
     */
    public function useCvv()
    {
        return $this->gatewayConfig->isCvvEnabled($this->sessionQuote->getStoreId());
    }

    /**
     * Check if vault enabled
     * @return bool
     */
    public function isVaultEnabled()
    {
        $vaultPayment = $this->getVaultPayment();
        return $vaultPayment->isActive($this->sessionQuote->getStoreId());
    }

    /**
     * Get card types available for Yoo
     * @return array
     */
    private function getConfiguredCardTypes()
    {
        $types = $this->ccType->getCcTypeLabelMap();
        $configCardTypes = array_fill_keys(
            $this->gatewayConfig->getAvailableCardTypes($this->sessionQuote->getStoreId()),
            ''
        );

        return array_intersect_key($types, $configCardTypes);
    }

    /**
     * Filter card types for specific country
     * @param array $configCardTypes
     * @param string $countryId
     * @return array
     */
    private function filterCardTypesForCountry(array $configCardTypes, $countryId)
    {
        $filtered = $configCardTypes;
        $countryCardTypes = $this->gatewayConfig->getCountryAvailableCardTypes(
            $countryId,
            $this->sessionQuote->getStoreId()
        );

        // filter card types only if specific card types are set for country
        if (!empty($countryCardTypes)) {
            $availableTypes = array_fill_keys($countryCardTypes, '');
            $filtered = array_intersect_key($filtered, $availableTypes);
        }
        return $filtered;
    }

    /**
     * Get configured vault payment for Yoo
     * @return VaultPaymentInterface
     */
    private function getVaultPayment()
    {
        return $this->paymentDataHelper->getMethodInstance(ConfigProvider::CC_VAULT_CODE);
    }
}
