<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Subscription - Abonelik yönetimi endpoint'leri
 * 
 * Bu sınıf kullanıcı aboneliklerini yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Subscription
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Abonelik oluşturur
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/subscription', $data);
    }
    
    /**
     * Aboneliği iptal eder
     */
    public function cancel(string $reason = ''): array
    {
        return $this->zapi->getHttpClient()->post('/subscription/cancel', ['reason' => $reason]);
    }
    
    /**
     * Aboneliği yeniler
     */
    public function renew(array $data = []): array
    {
        return $this->zapi->getHttpClient()->post('/subscription/renew', $data);
    }
    
    /**
     * Abonelik analitiğini getirir
     */
    public function getAnalytics(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/subscription/analytics', $options);
    }
    
    /**
     * Abonelik detaylarını getirir
     */
    public function getDetails(): array
    {
        return $this->zapi->getHttpClient()->get('/subscription/details');
    }
    
    /**
     * Upgrade kontrolü yapar
     */
    public function checkUpgrade(): array
    {
        return $this->zapi->getHttpClient()->get('/subscription/upgrade-check');
    }
}
