<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Webhook - Webhook yönetimi endpoint'leri
 * 
 * Bu sınıf webhook'ları yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Webhook
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Webhook'ları listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/webhook', $options);
    }
    
    /**
     * Yeni webhook oluşturur
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/webhook', $data);
    }
    
    /**
     * Webhook detaylarını getirir
     */
    public function get(string $webhookId): array
    {
        if (empty($webhookId)) {
            throw new ValidationException('Webhook ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/webhook/{$webhookId}");
    }
    
    /**
     * Webhook bilgilerini günceller
     */
    public function update(string $webhookId, array $data): array
    {
        if (empty($webhookId)) {
            throw new ValidationException('Webhook ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/webhook/{$webhookId}", $data);
    }
    
    /**
     * Webhook'u siler
     */
    public function delete(string $webhookId): array
    {
        if (empty($webhookId)) {
            throw new ValidationException('Webhook ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/webhook/{$webhookId}");
    }
    
    /**
     * Webhook'u test eder
     */
    public function test(string $webhookId): array
    {
        if (empty($webhookId)) {
            throw new ValidationException('Webhook ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/webhook/{$webhookId}/test");
    }
}
