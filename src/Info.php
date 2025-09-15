<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;

/**
 * Info - Sistem bilgi endpoint'leri
 * 
 * Bu sınıf sistem sağlık durumu, metrikler ve AI model bilgilerini getirmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $info = $zapi->info;
 * $health = $info->getHealth();
 * $metrics = $info->getMetrics();
 */
class Info
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Sistem sağlık durumunu getirir
     * 
     * @return array Sağlık durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $health = $zapi->info->getHealth();
     * echo "API durumu: " . $health['status'];
     * echo "Veritabanı: " . $health['database']['status'];
     * echo "Redis: " . $health['redis']['status'];
     */
    public function getHealth(): array
    {
        return $this->zapi->getHttpClient()->get('/info/health');
    }
    
    /**
     * Sistem metriklerini getirir
     * 
     * @return array Sistem metrikleri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $metrics = $zapi->info->getMetrics();
     * echo "Bellek kullanımı: " . $metrics['memory']['used'] . " MB";
     * echo "CPU kullanımı: " . $metrics['cpu']['usage'] . "%";
     * echo "Uptime: " . $metrics['uptime'] . " saniye";
     */
    public function getMetrics(): array
    {
        return $this->zapi->getHttpClient()->get('/info/metrics');
    }
    
    /**
     * Sistem durumunu getirir
     * 
     * @return array Sistem durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $status = $zapi->info->getStatus();
     * echo "Sistem durumu: " . $status['status'];
     * echo "Veritabanı bağlantısı: " . $status['database']['connected'];
     * echo "Redis bağlantısı: " . $status['redis']['connected'];
     */
    public function getStatus(): array
    {
        return $this->zapi->getHttpClient()->get('/info/status');
    }
    
    /**
     * AI modelleri ve sağlayıcılarını listeler
     * 
     * @return array AI modelleri ve sağlayıcıları
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $aiModels = $zapi->info->getAIModels();
     * echo "Toplam sağlayıcı: " . count($aiModels['providers']);
     * echo "Toplam model: " . count($aiModels['models']);
     * foreach ($aiModels['providers'] as $provider) {
     *     echo "Sağlayıcı: " . $provider['name'] . " - " . $provider['status'];
     * }
     */
    public function getAIModels(): array
    {
        return $this->zapi->getHttpClient()->get('/info/aimodels');
    }
}
