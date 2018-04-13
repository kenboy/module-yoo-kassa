<?php
/**
 * Copyright (c) 2018. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Model\Ui;

use Kenboy\YandexCheckout\Gateway\Config\Config;
use Kenboy\YandexCheckout\Model\Adapter\YandexAdapterFactory;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Asset\Source;
use Magento\Payment\Model\CcConfig;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'yandex_cc';

    const CC_VAULT_CODE = 'yandex_cc_vault';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var YandexAdapterFactory
     */
    private $adapterFactory;

    /**
     * @var SessionManagerInterface
     */
    private $session;

    /**
     * @var CcConfig
     */
    private $ccConfig;

    /**
     * @var Source
     */
    private $assetSource;

    /**
     * Constructor
     *
     * @param Config $config
     * @param YandexAdapterFactory $adapterFactory
     * @param SessionManagerInterface $session
     * @param CcConfig $ccConfig
     * @param Source $assetSource
     */
    public function __construct(
        Config $config,
        YandexAdapterFactory $adapterFactory,
        SessionManagerInterface $session,
        CcConfig $ccConfig,
        Source $assetSource
    ) {
        $this->config = $config;
        $this->adapterFactory = $adapterFactory;
        $this->session = $session;
        $this->ccConfig = $ccConfig;
        $this->assetSource = $assetSource;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        $storeId = $this->session->getStoreId();
        $config = [
            'payment' => [
                self::CODE => [
                    'isActive' => $this->config->isActive($storeId),
                    'sdkUrl' => $this->config->getSdkUrl(),
                    'useCvv' => $this->config->isCvvEnabled($storeId),
                    'environment' => $this->config->getEnvironment($storeId),
                    'shopId' => $this->config->getShopId($storeId),
                    'ccVaultCode' => self::CC_VAULT_CODE
                ],
            ],
        ];

        $asset = $this->ccConfig->createAsset('Kenboy_YandexCheckout::images/cc/mir.png');
        $placeholder = $this->assetSource->findSource($asset);
        if ($placeholder) {
            list($width, $height) = getimagesize($asset->getSourceFile());
            $config['payment']['ccform']['icons']['MIR'] = [
                'url' => $asset->getUrl(),
                'width' => $width,
                'height' => $height
            ];
        }

        return $config;
    }
}
