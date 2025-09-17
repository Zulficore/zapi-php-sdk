<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * APIKeys - API anahtarı yönetimi endpoint'leri
 * 
 * Bu sınıf API anahtarlarını yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $apiKeys = $zapi->apiKeys;
 * $keys = $apiKeys->list();
 * $newKey = $apiKeys->create(['name' => 'Yeni Anahtar']);
 */
class APIKeys
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * API anahtarlarını listeler
     * 
     * @param array $options Listeleme seçenekleri
     * @return array API anahtarları listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $keys = $zapi->apiKeys->list();
     * foreach ($keys['data'] as $key) {
     *     echo "Anahtar: " . $key['name'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/api-keys', $options);
    }
    
    /**
     * Yeni API anahtarı oluşturur
     * 
     * @param array $data API anahtarı verileri
     * @return array Oluşturulan API anahtarı bilgileri
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $key = $zapi->apiKeys->create([
     *     'name' => 'Yeni Anahtar',
     *     'description' => 'Test için oluşturuldu',
     *     'permissions' => ['read', 'write']
     * ]);
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/api-keys', $data);
    }
    
    /**
     * API anahtarı detaylarını getirir
     * 
     * @param string $keyId API anahtarı ID'si
     * @return array API anahtarı detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $key = $zapi->apiKeys->get('507f1f77bcf86cd799439011');
     * echo "Anahtar: " . $key['key']['name'];
     */
    public function get(string $keyId): array
    {
        if (empty($keyId)) {
            throw new ValidationException('API anahtarı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/api-keys/{$keyId}");
    }
    
    /**
     * API anahtarı bilgilerini günceller
     * 
     * @param string $keyId API anahtarı ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş API anahtarı bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->apiKeys->update('507f1f77bcf86cd799439011', [
     *     'name' => 'Güncellenmiş Anahtar',
     *     'status' => 'active'
     * ]);
     */
    public function update(string $keyId, array $data): array
    {
        if (empty($keyId)) {
            throw new ValidationException('API anahtarı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/api-keys/{$keyId}", $data);
    }
    
    /**
     * API anahtarını siler
     * 
     * @param string $keyId API anahtarı ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apiKeys->delete('507f1f77bcf86cd799439011');
     * echo "API anahtarı silindi: " . $result['message'];
     */
    public function delete(string $keyId): array
    {
        if (empty($keyId)) {
            throw new ValidationException('API anahtarı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/api-keys/{$keyId}");
    }
    
    /**
     * API anahtarı kullanım istatistiklerini getirir
     * 
     * @param string $keyId API anahtarı ID'si
     * @return array Kullanım istatistikleri
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $usage = $zapi->apiKeys->getUsage('507f1f77bcf86cd799439011');
     * echo "Toplam istek: " . $usage['totalRequests'];
     * echo "Son kullanım: " . $usage['lastUsed'];
     */
    public function getUsage(string $keyId): array
    {
        if (empty($keyId)) {
            throw new ValidationException('API anahtarı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/api-keys/{$keyId}/usage");
    }
    
    /**
     * Mevcut roller listesini getirir
     * 
     * @return array Roller listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $roles = $zapi->apiKeys->getAvailableRoles();
     * foreach ($roles['roles'] as $role) {
     *     echo "Rol: " . $role['name'];
     * }
     */
    public function getAvailableRoles(): array
    {
        return $this->zapi->getHttpClient()->get('/api-keys/roles/available');
    }
    
    /**
     * API anahtarını yeniler
     * 
     * @param string $keyId API anahtarı ID'si
     * @return array Yenileme sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apiKeys->rotate('507f1f77bcf86cd799439011');
     * echo "API anahtarı yenilendi: " . $result['newKey'];
     */
    public function rotate(string $keyId): array
    {
        if (empty($keyId)) {
            throw new ValidationException('API anahtarı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/api-keys/{$keyId}/rotate");
    }
    
    /**
     * API anahtarı ile arama yapar
     * 
     * @param string $apiKey API anahtarı
     * @return array API anahtarı bilgileri
     * @throws ValidationException Geçersiz API anahtarı
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $keyInfo = $zapi->apiKeys->lookup('sk-1234567890');
     * echo "Anahtar sahibi: " . $keyInfo['owner']['name'];
     */
    public function lookup(string $apiKey): array
    {
        if (empty($apiKey)) {
            throw new ValidationException('API anahtarı boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/api-keys/key/{$apiKey}");
    }
    
    /**
     * API anahtarı durumunu değiştirir
     * 
     * @param string $keyId API anahtarı ID'si
     * @return array Durum değiştirme sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apiKeys->toggleStatus('507f1f77bcf86cd799439011');
     * echo "API anahtarı durumu değiştirildi: " . $result['message'];
     */
}
