<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Apps - Uygulama yönetimi endpoint'leri
 * 
 * Bu sınıf uygulama oluşturma, güncelleme, silme, listeleme,
 * istatistikler ve metadata yönetimi için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $apps = $zapi->apps;
 * $appList = $apps->list();
 * $newApp = $apps->create(['name' => 'Yeni Uygulama']);
 */
class Apps
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * Apps constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Uygulamaları listeler
     * 
     * Bu metod kullanıcının sahip olduğu uygulamaları
     * sayfalama ile listeler.
     * 
     * @param array $options Listeleme seçenekleri
     * @return array Uygulamalar listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $apps = $zapi->apps->list(['page' => 1, 'limit' => 10]);
     * foreach ($apps['data'] as $app) {
     *     echo "Uygulama: " . $app['name'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/apps', $options);
    }
    
    /**
     * Yeni uygulama oluşturur
     * 
     * Bu metod yeni bir uygulama oluşturur.
     * 
     * @param array $data Uygulama verileri
     * @return array Oluşturulan uygulama bilgileri
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $app = $zapi->apps->create([
     *     'name' => 'Yeni Uygulama',
     *     'description' => 'Uygulama açıklaması',
     *     'type' => 'web'
     * ]);
     * echo "Uygulama oluşturuldu: " . $app['app']['name'];
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apps', $data);
    }
    
    /**
     * Uygulama detaylarını getirir
     * 
     * Bu metod belirtilen ID'ye sahip uygulamanın
     * detaylı bilgilerini getirir.
     * 
     * @param string $appId Uygulama ID'si
     * @return array Uygulama detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $app = $zapi->apps->get('507f1f77bcf86cd799439011');
     * echo "Uygulama: " . $app['app']['name'];
     * echo "Durum: " . $app['app']['status'];
     */
    public function get(string $appId): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/apps/{$appId}");
    }
    
    /**
     * Uygulama bilgilerini günceller
     * 
     * Bu metod belirtilen uygulamanın bilgilerini günceller.
     * 
     * @param string $appId Uygulama ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş uygulama bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->apps->update('507f1f77bcf86cd799439011', [
     *     'name' => 'Güncellenmiş Uygulama',
     *     'description' => 'Yeni açıklama'
     * ]);
     */
    public function update(string $appId, array $data): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/apps/{$appId}", $data);
    }
    
    /**
     * Uygulamayı siler
     * 
     * Bu metod belirtilen uygulamayı siler.
     * 
     * @param string $appId Uygulama ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apps->delete('507f1f77bcf86cd799439011');
     * echo "Uygulama silindi: " . $result['message'];
     */
    public function delete(string $appId): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/apps/{$appId}");
    }
    
    /**
     * Uygulama istatistiklerini getirir
     * 
     * Bu metod uygulamanın kullanım istatistiklerini getirir.
     * 
     * @param array $options İstatistik seçenekleri
     * @return array İstatistik verileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $stats = $zapi->apps->getStats(['period' => 'monthly']);
     * echo "Toplam istek: " . $stats['totalRequests'];
     * echo "Aktif kullanıcı: " . $stats['activeUsers'];
     */
    public function getStats(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/apps/stats', $options);
    }
    
    /**
     * Belirli uygulamanın istatistiklerini getirir
     */
    public function getAppStats(string $appId, array $options = []): array
    {
        return $this->zapi->getHttpClient()->get("/apps/stats/{$appId}", $options);
    }
    
    /**
     * Uygulama kullanım sayaçlarını sıfırlar
     */
    public function resetUsage(string $appId): array
    {
        return $this->zapi->getHttpClient()->post("/apps/reset-usage/{$appId}");
    }
    
    /**
     * Belirli uygulamanın istatistiklerini getirir
     * 
     * Bu metod belirtilen uygulamanın detaylı istatistiklerini getirir.
     * 
     * @param string $appId Uygulama ID'si
     * @param array $options İstatistik seçenekleri
     * @return array Uygulama istatistikleri
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $appStats = $zapi->apps->getAppStats('507f1f77bcf86cd799439011', [
     *     'period' => 'daily',
     *     'startDate' => '2024-01-01',
     *     'endDate' => '2024-01-31'
     * ]);
     */
    
    /**
     * Uygulama kullanım sayaçlarını sıfırlar
     * 
     * Bu metod belirtilen uygulamanın kullanım sayaçlarını sıfırlar.
     * 
     * @param string $appId Uygulama ID'si
     * @return array Sıfırlama sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apps->resetUsage('507f1f77bcf86cd799439011');
     * echo "Kullanım sayaçları sıfırlandı: " . $result['message'];
     */
    
    /**
     * Uygulama durumunu değiştirir
     * 
     * Bu metod uygulamanın aktif/pasif durumunu değiştirir.
     * 
     * @param string $appId Uygulama ID'si
     * @return array Durum değiştirme sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apps->toggleStatus('507f1f77bcf86cd799439011');
     * echo "Uygulama durumu değiştirildi: " . $result['message'];
     */
    
    /**
     * Uygulamanın metadata bilgilerini getirir
     * 
     * Bu metod uygulamanın metadata bilgilerini getirir.
     * Nested path desteği vardır.
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path (opsiyonel)
     * @return array Metadata bilgileri
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $metadata = $zapi->apps->getMetadata('507f1f77bcf86cd799439011', 'settings');
     * echo "Tema: " . $metadata['value']['theme'];
     */
    public function getMetadata(string $appId, string $path = ''): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/apps/{$appId}/metadata/{$path}" : "/apps/{$appId}/metadata";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * Uygulamanın metadata bilgilerini günceller
     * 
     * Bu metod uygulamanın metadata bilgilerini tamamen günceller.
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @param array $value Metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->apps->updateMetadata('507f1f77bcf86cd799439011', 'settings', [
     *     'theme' => 'dark',
     *     'language' => 'tr',
     *     'features' => ['chat', 'upload']
     * ]);
     */
    public function updateMetadata(string $appId, string $path, array $value): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/apps/{$appId}/metadata/{$path}", $value);
    }
    
    /**
     * Uygulamanın metadata bilgilerini kısmi olarak günceller
     * 
     * Bu metod uygulamanın metadata bilgilerini kısmi olarak günceller.
     * Sadece belirtilen alanları günceller.
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @param array $value Güncellenecek metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->apps->patchMetadata('507f1f77bcf86cd799439011', 'settings', [
     *     'theme' => 'light'
     * ]);
     */
    public function patchMetadata(string $appId, string $path, array $value): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/apps/{$appId}/metadata/{$path}", $value);
    }
    
    /**
     * Uygulamanın metadata bilgilerini siler
     * 
     * Bu metod belirtilen path'teki metadata'yı siler.
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID veya path
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->apps->deleteMetadata('507f1f77bcf86cd799439011', 'settings');
     * echo "Metadata silindi: " . $result['message'];
     */
    public function deleteMetadata(string $appId, string $path): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/apps/{$appId}/metadata/{$path}");
    }
}
