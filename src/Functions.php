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
        // Orijinal API'ye uygun header ekle
        $headers = [];
        if (isset($options['appId'])) {
            $headers['x-app-id'] = $options['appId'];
            unset($options['appId']); // Header'a ekledikten sonra data'dan çıkar
        }
        
        return $this->zapi->getHttpClient()->get('/functions', $options, $headers);
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
    
    /**
     * Fonksiyon bilgilerini günceller
     */
    
    /**
     * Fonksiyonu siler
     */
    
    /**
     * Fonksiyonu çalıştırır
     */
    
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
