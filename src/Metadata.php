<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Metadata - Genel metadata yönetimi endpoint'leri
 * 
 * Bu sınıf genel metadata işlemlerini yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Metadata
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Metadata bilgilerini getirir
     */
    public function get(string $entityType, string $entityId, string $path = ''): array
    {
        if (empty($entityType)) {
            throw new ValidationException('Entity type boş olamaz');
        }
        
        if (empty($entityId)) {
            throw new ValidationException('Entity ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/metadata/{$entityType}/{$entityId}/{$path}" : "/metadata/{$entityType}/{$entityId}";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * Metadata bilgilerini günceller
     */
    public function update(string $entityType, string $entityId, string $path, array $value): array
    {
        if (empty($entityType)) {
            throw new ValidationException('Entity type boş olamaz');
        }
        
        if (empty($entityId)) {
            throw new ValidationException('Entity ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/metadata/{$entityType}/{$entityId}/{$path}", ['value' => $value]);
    }
    
    /**
     * Metadata bilgilerini kısmi olarak günceller
     */
    public function patch(string $entityType, string $entityId, string $path, array $value): array
    {
        if (empty($entityType)) {
            throw new ValidationException('Entity type boş olamaz');
        }
        
        if (empty($entityId)) {
            throw new ValidationException('Entity ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/metadata/{$entityType}/{$entityId}/{$path}", ['value' => $value]);
    }
    
    /**
     * Metadata bilgilerini siler
     */
    public function delete(string $entityType, string $entityId, string $path): array
    {
        if (empty($entityType)) {
            throw new ValidationException('Entity type boş olamaz');
        }
        
        if (empty($entityId)) {
            throw new ValidationException('Entity ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/metadata/{$entityType}/{$entityId}/{$path}");
    }
}
