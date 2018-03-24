<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Model\Adapter;

use Kenboy\YandexCheckout\Gateway\Config\Config;
use Magento\Framework\ObjectManagerInterface;

/**
 * This factory is preferable to use for Yandex Checkout adapter instance creation.
 */
class YandexAdapterFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param Config $config
     */
    public function __construct(ObjectManagerInterface $objectManager, Config $config)
    {
        $this->config = $config;
        $this->objectManager = $objectManager;
    }

    /**
     * Creates instance of Yandex Checkout Adapter.
     *
     * @param int $storeId if null is provided as an argument, then current scope will be resolved
     * by \Magento\Framework\App\Config\ScopeCodeResolver (useful for most cases) but for adminhtml area the store
     * should be provided as the argument for correct config settings loading.
     * @return YandexAdapter
     */
    public function create($storeId = null)
    {
        return $this->objectManager->create(
            YandexAdapter::class,
            [
                'shopId' => $this->config->getShopId($storeId),
                'secretKey' => $this->config->getValue(Config::KEY_SECRET_KEY, $storeId)
            ]
        );
    }
}
