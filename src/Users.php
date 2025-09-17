<?php

namespace ZAPI;

use ZAPI\Exceptions\ValidationException;
use ZAPI\Exceptions\ZAPIException;

/**
 * Users Endpoint
 * 
 * Bu sınıf kullanıcı yönetimi işlemlerini yönetir.
 * 
 * @package ZAPI
 * @author ZAPI Team
 * @version 1.0.0
 */
class Users extends BaseEndpoint
{
    /**
     * Kullanıcıları listeler
     * 
     * Bu metod tüm kullanıcıları sayfalama ile listeler.
     * 
     * @param array $options Filtreleme ve sayfalama seçenekleri
     * @return array Kullanıcı listesi
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $users = $zapi->users->list([
     *     'page' => 1,
     *     'limit' => 20,
     *     'search' => 'john',
     *     'role' => 'user'
     * ]);
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/users', $options);
    }

    /**
     * Kullanıcı istatistiklerini getirir
     * 
     * Bu metod kullanıcı istatistiklerini getirir.
     * 
     * @return array İstatistik verileri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $stats = $zapi->users->getStats();
     * echo "Toplam kullanıcı: " . $stats['data']['total'];
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/users/stats');
    }

    /**
     * Belirli bir kullanıcıyı getirir
     * 
     * Bu metod belirtilen ID'ye sahip kullanıcının detaylarını getirir.
     * 
     * @param string $userId Kullanıcı ID'si
     * @return array Kullanıcı detayları
     * @throws ValidationException Geçersiz kullanıcı ID
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $user = $zapi->users->get('64f1a2b3c4d5e6f7g8h9i0j1');
     * echo "Kullanıcı adı: " . $user['data']['firstName'];
     */
    public function get(string $userId): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/users/{$userId}");
    }

    /**
     * Kullanıcı günceller
     * 
     * Bu metod belirtilen kullanıcının bilgilerini günceller.
     * 
     * @param string $userId Kullanıcı ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncelleme sonucu
     * @throws ValidationException Geçersiz veri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->users->update('64f1a2b3c4d5e6f7g8h9i0j1', [
     *     'firstName' => 'John',
     *     'lastName' => 'Doe'
     * ]);
     * echo "Kullanıcı güncellendi: " . $result['message'];
     */
    public function update(string $userId, array $data): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/users/{$userId}", $data);
    }

    /**
     * Kullanıcı siler
     * 
     * Bu metod belirtilen kullanıcıyı siler.
     * 
     * @param string $userId Kullanıcı ID'si
     * @return array Silme sonucu
     * @throws ValidationException Geçersiz kullanıcı ID
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->users->delete('64f1a2b3c4d5e6f7g8h9i0j1');
     * echo "Kullanıcı silindi: " . $result['message'];
     */
    public function delete(string $userId): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/users/{$userId}");
    }

    /**
     * Kullanıcı metadata'sını getirir
     * 
     * Bu metod belirtilen kullanıcının metadata bilgilerini getirir.
     * 
     * @param string $userId Kullanıcı ID'si
     * @param string $path Metadata path'i
     * @return array Metadata verileri
     * @throws ValidationException Geçersiz kullanıcı ID
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $metadata = $zapi->users->getMetadata('64f1a2b3c4d5e6f7g8h9i0j1', 'settings');
     * echo "Kullanıcı ayarları: " . json_encode($metadata['data']);
     */
    public function getMetadata(string $userId, string $path = ''): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        $endpoint = "/users/{$userId}/metadata";
        if (!empty($path)) {
            $endpoint .= "/{$path}";
        }
        
        return $this->zapi->getHttpClient()->get($endpoint);
    }

    /**
     * Kullanıcı metadata'sını günceller
     * 
     * Bu metod belirtilen kullanıcının metadata bilgilerini günceller.
     * 
     * @param string $userId Kullanıcı ID'si
     * @param string $path Metadata path'i
     * @param array $value Güncellenecek değer
     * @return array Güncelleme sonucu
     * @throws ValidationException Geçersiz veri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->users->updateMetadata('64f1a2b3c4d5e6f7g8h9i0j1', 'settings', [
     *     'theme' => 'dark',
     *     'language' => 'tr'
     * ]);
     * echo "Metadata güncellendi: " . $result['message'];
     */
    public function updateMetadata(string $userId, string $path, array $value): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/users/{$userId}/metadata/{$path}", ['value' => $value]);
    }

    /**
     * Kullanıcı metadata'sını siler
     * 
     * Bu metod belirtilen kullanıcının metadata bilgilerini siler.
     * 
     * @param string $userId Kullanıcı ID'si
     * @param string $path Metadata path'i
     * @return array Silme sonucu
     * @throws ValidationException Geçersiz veri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->users->deleteMetadata('64f1a2b3c4d5e6f7g8h9i0j1', 'settings');
     * echo "Metadata silindi: " . $result['message'];
     */
    public function deleteMetadata(string $userId, string $path): array
    {
        if (empty($userId)) {
            throw new ValidationException('Kullanıcı ID boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/users/{$userId}/metadata/{$path}");
    }
}
