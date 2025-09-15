<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Logs - Log yönetimi endpoint'leri
 * 
 * Bu sınıf sistem loglarını listeleme, görüntüleme ve yönetme işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Logs
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Logları listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/logs', $options);
    }
    
    /**
     * Log detaylarını getirir
     */
    public function get(string $logId): array
    {
        if (empty($logId)) {
            throw new ValidationException('Log ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/logs/{$logId}");
    }
    
    /**
     * Log istatistiklerini getirir
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/logs/stats');
    }
    
    /**
     * Log temizleme işlemi yapar
     */
    public function cleanup(array $options = []): array
    {
        return $this->zapi->getHttpClient()->delete('/logs/cleanup', $options);
    }
    
    /**
     * Tüm logları temizler
     */
    public function clear(): array
    {
        return $this->zapi->getHttpClient()->delete('/logs/clear');
    }
}
