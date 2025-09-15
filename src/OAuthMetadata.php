<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * OAuthMetadata - OAuth metadata yönetimi endpoint'leri
 * 
 * Bu sınıf OAuth metadata'larını yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class OAuthMetadata
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * OAuth metadata bilgilerini getirir
     */
    public function get(string $appId, string $path = ''): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/oauth-metadata/{$appId}/{$path}" : "/oauth-metadata/{$appId}";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * OAuth metadata bilgilerini günceller
     */
    public function update(string $appId, string $path, array $value): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/oauth-metadata/{$appId}/{$path}", ['value' => $value]);
    }
    
    /**
     * OAuth metadata bilgilerini kısmi olarak günceller
     */
    public function patch(string $appId, string $path, array $value): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/oauth-metadata/{$appId}/{$path}", ['value' => $value]);
    }
    
    /**
     * OAuth metadata bilgilerini siler
     */
    public function delete(string $appId, string $path): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/oauth-metadata/{$appId}/{$path}");
    }
}
