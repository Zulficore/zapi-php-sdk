<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Plans - Abonelik planı yönetimi endpoint'leri
 * 
 * Bu sınıf abonelik planlarını yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Plans
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Planları listeler (public)
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/plans', $options);
    }
    
    /**
     * Plan karşılaştırması getirir (public)
     */
    public function compare(array $planIds = []): array
    {
        return $this->zapi->getHttpClient()->get('/plans/compare', ['plans' => $planIds]);
    }
    
    /**
     * Yeni plan oluşturur (admin)
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/plans', $data);
    }
    
    /**
     * Plan detaylarını getirir
     */
    public function get(string $planId): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/plans/{$planId}");
    }
    
    /**
     * Plan bilgilerini günceller (admin)
     */
    public function update(string $planId, array $data): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/plans/{$planId}", $data);
    }
    
    /**
     * Planı siler (admin)
     */
    public function delete(string $planId): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/plans/{$planId}");
    }
    
    /**
     * Plan durumunu değiştirir (admin)
     */
    public function toggleStatus(string $planId): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/plans/{$planId}/toggle-status");
    }
    
    /**
     * Plan abonelerini listeler (admin)
     */
    public function getSubscribers(string $planId, array $options = []): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/plans/subscribers/{$planId}", $options);
    }
    
    /**
     * Plan analitiğini getirir (admin)
     */
    public function getAnalytics(string $planId, array $options = []): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/plans/analytics/{$planId}", $options);
    }
    
    /**
     * Plan metadata bilgilerini getirir
     */
    public function getMetadata(string $planId, string $path = ''): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/plans/{$planId}/metadata/{$path}" : "/plans/{$planId}/metadata";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * Plan metadata bilgilerini günceller
     */
    public function updateMetadata(string $planId, string $path, array $value): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/plans/{$planId}/metadata/{$path}", ['value' => $value]);
    }
    
    /**
     * Plan metadata bilgilerini kısmi olarak günceller
     */
    public function patchMetadata(string $planId, string $path, array $value): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/plans/{$planId}/metadata/{$path}", ['value' => $value]);
    }
    
    /**
     * Plan metadata bilgilerini siler
     */
    public function deleteMetadata(string $planId, string $path): array
    {
        if (empty($planId)) {
            throw new ValidationException('Plan ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/plans/{$planId}/metadata/{$path}");
    }
}
