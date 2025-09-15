<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Functions - Kullanıcı fonksiyonları endpoint'leri
 * 
 * Bu sınıf kullanıcı tanımlı fonksiyonları yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Functions
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Fonksiyonları listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/functions', $options);
    }
    
    /**
     * Yeni fonksiyon oluşturur
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/functions', $data);
    }
    
    /**
     * Fonksiyon detaylarını getirir
     */
    public function get(string $functionId): array
    {
        if (empty($functionId)) {
            throw new ValidationException('Fonksiyon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/functions/{$functionId}");
    }
    
    /**
     * Fonksiyon bilgilerini günceller
     */
    public function update(string $functionId, array $data): array
    {
        if (empty($functionId)) {
            throw new ValidationException('Fonksiyon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/functions/{$functionId}", $data);
    }
    
    /**
     * Fonksiyonu siler
     */
    public function delete(string $functionId): array
    {
        if (empty($functionId)) {
            throw new ValidationException('Fonksiyon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/functions/{$functionId}");
    }
    
    /**
     * Fonksiyonu çalıştırır
     */
    public function execute(string $functionId, array $parameters = []): array
    {
        if (empty($functionId)) {
            throw new ValidationException('Fonksiyon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/functions/{$functionId}/execute", [
            'parameters' => $parameters
        ]);
    }
    
    /**
     * Fonksiyonu test eder
     */
    public function test(string $functionId, array $testData = []): array
    {
        if (empty($functionId)) {
            throw new ValidationException('Fonksiyon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/functions/{$functionId}/test", $testData);
    }
}
