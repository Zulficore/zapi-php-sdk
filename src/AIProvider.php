<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * AIProvider - AI sağlayıcı yönetimi endpoint'leri
 * 
 * Bu sınıf AI sağlayıcıları ve modellerini yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $aiProvider = $zapi->aiProvider;
 * $providers = $aiProvider->list();
 * $models = $aiProvider->getModels();
 */
class AIProvider
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * AI sağlayıcılarını listeler
     * 
     * @param array $options Listeleme seçenekleri
     * @return array Sağlayıcılar listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $providers = $zapi->aiProvider->list();
     * foreach ($providers['data'] as $provider) {
     *     echo "Sağlayıcı: " . $provider['name'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/ai-providers', $options);
    }
    
    /**
     * Yeni AI sağlayıcı oluşturur
     * 
     * @param array $data Sağlayıcı verileri
     * @return array Oluşturulan sağlayıcı bilgileri
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $provider = $zapi->aiProvider->create([
     *     'name' => 'OpenAI',
     *     'type' => 'openai',
     *     'apiKey' => 'sk-...',
     *     'baseUrl' => 'https://api.openai.com'
     * ]);
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/ai-providers', $data);
    }
    
    /**
     * AI sağlayıcı detaylarını getirir
     * 
     * @param string $providerId Sağlayıcı ID'si
     * @return array Sağlayıcı detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $provider = $zapi->aiProvider->get('507f1f77bcf86cd799439011');
     * echo "Sağlayıcı: " . $provider['provider']['name'];
     */
    public function get(string $providerId): array
    {
        if (empty($providerId)) {
            throw new ValidationException('Sağlayıcı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/ai-providers/{$providerId}");
    }
    
    /**
     * AI sağlayıcı bilgilerini günceller
     * 
     * @param string $providerId Sağlayıcı ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş sağlayıcı bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->aiProvider->update('507f1f77bcf86cd799439011', [
     *     'name' => 'Güncellenmiş OpenAI',
     *     'status' => 'active'
     * ]);
     */
    public function update(string $providerId, array $data): array
    {
        if (empty($providerId)) {
            throw new ValidationException('Sağlayıcı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/ai-providers/{$providerId}", $data);
    }
    
    /**
     * AI sağlayıcıyı siler
     * 
     * @param string $providerId Sağlayıcı ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->aiProvider->delete('507f1f77bcf86cd799439011');
     * echo "Sağlayıcı silindi: " . $result['message'];
     */
    public function delete(string $providerId): array
    {
        if (empty($providerId)) {
            throw new ValidationException('Sağlayıcı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/ai-providers/{$providerId}");
    }
    
    /**
     * AI sağlayıcıyı test eder
     * 
     * @param string $providerId Sağlayıcı ID'si
     * @return array Test sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $test = $zapi->aiProvider->test('507f1f77bcf86cd799439011');
     * echo "Test sonucu: " . $test['status'];
     */
    public function test(string $providerId): array
    {
        if (empty($providerId)) {
            throw new ValidationException('Sağlayıcı ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/ai-providers/{$providerId}/test");
    }
    
    /**
     * AI modellerini listeler
     * 
     * @param array $options Listeleme seçenekleri
     * @return array Modeller listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $models = $zapi->aiProvider->getModels();
     * foreach ($models['data'] as $model) {
     *     echo "Model: " . $model['name'];
     * }
     */
    public function getModels(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/ai-providers/models', $options);
    }
    
    /**
     * AI model detaylarını getirir
     * 
     * @param string $modelId Model ID'si
     * @return array Model detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $model = $zapi->aiProvider->getModel('507f1f77bcf86cd799439011');
     * echo "Model: " . $model['model']['name'];
     */
    public function getModel(string $modelId): array
    {
        if (empty($modelId)) {
            throw new ValidationException('Model ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/ai-providers/models/{$modelId}");
    }
    
    /**
     * AI model bilgilerini günceller
     * 
     * @param string $modelId Model ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş model bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->aiProvider->updateModel('507f1f77bcf86cd799439011', [
     *     'name' => 'gpt-4-turbo',
     *     'status' => 'active'
     * ]);
     */
    public function updateModel(string $modelId, array $data): array
    {
        if (empty($modelId)) {
            throw new ValidationException('Model ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/ai-providers/models/{$modelId}", $data);
    }
    
    /**
     * AI modeli siler
     * 
     * @param string $modelId Model ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->aiProvider->deleteModel('507f1f77bcf86cd799439011');
     * echo "Model silindi: " . $result['message'];
     */
    public function deleteModel(string $modelId): array
    {
        if (empty($modelId)) {
            throw new ValidationException('Model ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/ai-providers/models/{$modelId}");
    }
    
    /**
     * Varsayılan modelleri getirir
     * 
     * @return array Varsayılan modeller
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $defaults = $zapi->aiProvider->getDefaultModels();
     * echo "Varsayılan chat modeli: " . $defaults['chat'];
     * echo "Varsayılan embedding modeli: " . $defaults['embedding'];
     */
    public function getDefaultModels(): array
    {
        return $this->zapi->getHttpClient()->get('/ai-providers/models/defaults');
    }
}
