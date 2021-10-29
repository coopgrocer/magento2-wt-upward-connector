<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\UpwardConnector\Plugin\Magento\Framework\App;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AreaList
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Controller or frontname to load from default magento frontend
     */
    const UPWARD_CONFIG_PATH_FRONT_NAMES_TO_SKIP = 'web/upward/front_names_to_skip';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Add pwa area code by front name
     *
     * @param \Magento\Framework\App\AreaList $subject
     * @param string|null $result
     * @param string $frontName
     *
     * @return string|null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCodeByFrontName(
        \Magento\Framework\App\AreaList $subject,
        $result,
        $frontName
    ) {

        if ($result != 'frontend') {
            return $result;
        }

        $frontNamesToSkip = explode(
            "\r\n",
            $this->scopeConfig->getValue(
                self::UPWARD_CONFIG_PATH_FRONT_NAMES_TO_SKIP,
                ScopeInterface::SCOPE_STORE
            ) ?? ''
        );

        if ($frontName && in_array($frontName, $frontNamesToSkip)) {
            return $result;
        }
        
        
        

        
        $objectManager =  \Magento\Framework\App\ObjectManager::getInstance();
	    $request = $objectManager->get('\Magento\Framework\App\Request\Http');
        
        \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info('callback wt payload paymongo: '. $request->getOriginalPathInfo());
        
	    if (strpos($request->getOriginalPathInfo(), 'paymongo/webhooks') !== false)
            {
            
            \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->info('callback wt payload paymongo: in if clause');
            
                return $result;
            }
        

        return 'pwa';
    }
}
