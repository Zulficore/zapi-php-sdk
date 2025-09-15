<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Roles - Rol yönetimi endpoint'leri
 * 
 * Bu sınıf kullanıcı rollerini yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Roles
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Rolleri listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/roles', $options);
    }
    
    /**
     * Yeni rol oluşturur
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/roles', $data);
    }
    
    /**
     * Rol detaylarını getirir
     */
    public function get(string $roleId): array
    {
        if (empty($roleId)) {
            throw new ValidationException('Rol ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/roles/{$roleId}");
    }
    
    /**
     * Rol bilgilerini günceller
     */
    public function update(string $roleId, array $data): array
    {
        if (empty($roleId)) {
            throw new ValidationException('Rol ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/roles/{$roleId}", $data);
    }
    
    /**
     * Rolü siler
     */
    public function delete(string $roleId): array
    {
        if (empty($roleId)) {
            throw new ValidationException('Rol ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/roles/{$roleId}");
    }
    
    /**
     * Rol kullanıcılarını listeler
     */
    public function getUsers(string $roleId, array $options = []): array
    {
        if (empty($roleId)) {
            throw new ValidationException('Rol ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/roles/{$roleId}/users", $options);
    }
    
    /**
     * Mevcut yetkileri listeler
     */
    public function getAvailablePermissions(): array
    {
        return $this->zapi->getHttpClient()->get('/roles/permissions/available');
    }
    
    /**
     * Rol analitiğini getirir
     */
    public function getAnalytics(): array
    {
        return $this->zapi->getHttpClient()->get('/roles/analytics');
    }
}
