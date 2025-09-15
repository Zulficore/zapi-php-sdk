<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;

/**
 * Config - Konfigürasyon endpoint'leri
 * 
 * Bu sınıf frontend konfigürasyon bilgilerini getirmek için
 * endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $config = $zapi->config;
 * $settings = $config->get();
 * echo "WebSocket URL: " . $settings['wsUrl'];
 */
class Config
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * Config constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Frontend konfigürasyon bilgilerini getirir
     * 
     * Bu metod frontend uygulaması için gerekli konfigürasyon
     * bilgilerini getirir. Kimlik doğrulama gerektirmez.
     * 
     * @return array Konfigürasyon bilgileri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $config = $zapi->config->get();
     * echo "WebSocket URL: " . $config['wsUrl'];
     * echo "Environment: " . $config['environment'];
     * echo "Base URL: " . $config['baseUrl'];
     * echo "Version: " . $config['version'];
     * 
     * // Özellikler
     * if ($config['features']['realtime']) {
     *     echo "Real-time özelliği aktif";
     * }
     * if ($config['features']['fileUpload']) {
     *     echo "Dosya yükleme özelliği aktif";
     * }
     * if ($config['features']['aiChat']) {
     *     echo "AI Chat özelliği aktif";
     * }
     */
    public function get(): array
    {
        return $this->zapi->getHttpClient()->get('/config');
    }
}
