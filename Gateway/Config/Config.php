<?php
/**
 * See LICENSE.txt for license details.
 */
namespace Kenboy\YandexCheckout\Gateway\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Config
 */
class Config extends \Magento\Payment\Gateway\Config\Config
{
    const KEY_ENVIRONMENT = 'environment';
    const KEY_ACTIVE = 'active';
    const KEY_SHOP_ID = 'shop_id';
    const KEY_SECRET_KEY = 'secret_key';
    const KEY_SDK_URL = 'sdk_url';
    const KEY_USE_CVV = 'useccv';
    const KEY_CC_TYPES = 'cctypes';
    const KEY_CC_TYPES_YANDEX_MAPPER = 'cctypes_yandex_mapper';
    const KEY_COUNTRY_CREDIT_CARD = 'countrycreditcard';

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * YandexCheckout config constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Json|null $serializer
     * @param null|string $methodCode
     * @param string $pathPattern
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $serializer,
        $methodCode = null,
        $pathPattern = self::DEFAULT_PATH_PATTERN
    ) {
        parent::__construct($scopeConfig, $methodCode, $pathPattern);
        $this->serializer = $serializer;
    }

    /**
     * Return the country specific card type config
     *
     * @param int|null $storeId
     * @return array
     */
    public function getCountrySpecificCardTypeConfig($storeId = null)
    {
        $countryCardTypes = $this->getValue(self::KEY_COUNTRY_CREDIT_CARD, $storeId);
        if (!$countryCardTypes) {
            return [];
        }

        $countryCardTypes = $this->serializer->unserialize($countryCardTypes);
        return is_array($countryCardTypes) ? $countryCardTypes : [];
    }

    /**
     * Gets list of card types available for country.
     *
     * @param string $country
     * @param int|null $storeId
     * @return array
     */
    public function getCountryAvailableCardTypes($country, $storeId = null)
    {
        $types = $this->getCountrySpecificCardTypeConfig($storeId);
        return (!empty($types[$country])) ? $types[$country] : [];
    }

    /**
     * Retrieve available credit card types
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAvailableCardTypes($storeId = null)
    {
        $ccTypes = $this->getValue(self::KEY_CC_TYPES, $storeId);
        return !empty($ccTypes) ? explode(',', $ccTypes) : [];
    }

    /**
     * Retrieve mapper between Magento and Yandex card types
     *
     * @return array
     */
    public function getCcTypesMapper()
    {
        $result = $this->serializer
            ->unserialize($this->getValue(self::KEY_CC_TYPES_YANDEX_MAPPER));

        return is_array($result) ? $result : [];
    }

    /**
     * Checks if cvv field is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isCvvEnabled($storeId = null)
    {
        return (bool) $this->getValue(self::KEY_USE_CVV, $storeId);
    }

    /**
     * Gets value of configured environment.
     * Possible values: production or sandbox.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getEnvironment($storeId = null)
    {
        return $this->getValue(Config::KEY_ENVIRONMENT, $storeId);
    }

    /**
     * Gets merchant ID.
     *
     * @param int|null $storeId
     * @return string
     */
    public function getShopId($storeId = null)
    {
        return $this->getValue(Config::KEY_SHOP_ID, $storeId);
    }

    /**
     * @return string
     */
    public function getSdkUrl()
    {
        return $this->getValue(Config::KEY_SDK_URL);
    }

    /**
     * Gets Payment configuration status.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isActive($storeId = null)
    {
        return (bool) $this->getValue(self::KEY_ACTIVE, $storeId);
    }
}
