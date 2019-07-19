<?php
/**
 * Copyright © Graycore, LLC. All rights reserved.
 * See LICENSE.md for details.
 */
namespace Graycore\Cors\Configuration;

use Graycore\Cors\Configuration\CorsConfigurationInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * CorsConfiguration is responsible for retrieving the Configuration
 * for the GraphQL Cors settings from the Magento Configuration.
 * @category  PHP
 * @package   Graycore_Cors
 * @author    Graycore <damien@graycore.io>
 * @copyright Graycore, LLC (https://www.graycore.io/)
 * @license   MIT https://github.com/graycoreio/magento2-cors/license
 * @link      https://github.com/graycoreio/magento2-cors
 */
class CorsConfiguration implements CorsConfigurationInterface
{
    const XML_PATH_GRAPHQL_CORS_ORIGINS = 'web/graphql/cors_allowed_origins';

    const XML_PATH_GRAPHQL_CORS_METHODS = 'web/graphql/cors_allowed_methods';

    const XML_PATH_GRAPHQL_CORS_HEADERS = 'web/graphql/cors_allowed_headers';

    const XML_PATH_GRAPHQL_CORS_MAX_AGE = 'web/graphql/cors_max_age';

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /**
     * CorsAllowHeadersHeaderProvider constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    private function _cleanConfig($configString)
    {
        $configuration = explode(',', $configString);
        $cleanedConfiguration = [];
        $cleanedConfiguration = array_map(
            function ($item) {
                return trim($item);
            },
            $configuration
        );

        $cleanedConfiguration = array_values(array_filter($cleanedConfiguration, function ($item) {
            return !empty($item) ? true : false;
        }));
        return $cleanedConfiguration;
    }

    /**
     * Takes the configuration for Cors Origins
     * and parses it into an array of allowed origins
     * @return array
     */
    public function getAllowedOrigins(): array
    {
        return $this->_cleanConfig($this->scopeConfig->getValue(self::XML_PATH_GRAPHQL_CORS_ORIGINS));
    }

    /**
     * Retrieves the allowed CORS headers from configuration
     * @return string;
     */
    public function getAllowedHeaders(): array
    {
        return $this->_cleanConfig($this->scopeConfig->getValue(self::XML_PATH_GRAPHQL_CORS_HEADERS));
    }

    /**
     * @return string[];
     */
    public function getAllowedMethods(): array
    {
        return $this->_cleanConfig($this->scopeConfig->getValue(self::XML_PATH_GRAPHQL_CORS_METHODS));
    }

    /**
     * @return string;
     */
    public function getMaxAge(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_GRAPHQL_CORS_MAX_AGE);
    }
}
