<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;

/**
 * Debug - Debug endpoint'leri
 * 
 * Bu sınıf geliştirme ortamı için debug bilgilerini getirmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $debug = $zapi->debug;
 * $models = $debug->getModels();
 * $providerManager = $debug->getProviderManager();
 */
class Debug
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * AI model cache durumunu getirir
     * 
     * @return array Model cache durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $models = $zapi->debug->getModels();
     * echo "Cache boyutu: " . $models['cacheSize'];
     * echo "Model sayısı: " . count($models['allModels']);
     */
    public function getModels(): array
    {
        return $this->zapi->getHttpClient()->get('/debug/models');
    }
    
    /**
     * ProviderManager durumunu getirir
     * 
     * @return array ProviderManager durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $providerManager = $zapi->debug->getProviderManager();
     * echo "ProviderManager durumu: " . $providerManager['status'];
     * echo "Aktif sağlayıcılar: " . count($providerManager['activeProviders']);
     */
    public function getProviderManager(): array
    {
        return $this->zapi->getHttpClient()->get('/debug/provider-manager');
    }
}
