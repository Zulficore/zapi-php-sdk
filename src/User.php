<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * User - Kullanıcı yönetimi endpoint'leri
 * 
 * Bu sınıf kullanıcı profil yönetimi, avatar işlemleri, kullanım istatistikleri,
 * AI yanıtları ve hesap yönetimi için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $user = $zapi->user;
 * $profile = $user->getProfile();
 * $user->updateProfile(['firstName' => 'Yeni Ad']);
 */
class User
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * User constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Kullanıcının profil bilgilerini getirir
     * 
     * Bu metod kullanıcının profil bilgilerini, abonelik durumunu,
     * kullanım istatistiklerini ve diğer kişisel bilgilerini getirir.
     * 
     * @return array Kullanıcı profil bilgileri
     * @throws AuthenticationException API anahtarı geçersizse
     * @throws ZAPIException Sunucu hatası durumunda
     * 
     * @example
     * $profile = $zapi->user->getProfile();
     * echo "Merhaba " . $profile['user']['firstName'];
     * echo "Plan: " . $profile['user']['subscription']['plan']['name'];
     */
    public function getProfile(): array
    {
        return $this->zapi->getHttpClient()->get('/user/profile');
    }
    
    /**
     * Kullanıcı profil bilgilerini günceller
     * 
     * Bu metod kullanıcının ad, soyad, telefon, cinsiyet gibi
     * temel profil bilgilerini günceller.
     * 
     * @param array $data Güncellenecek profil verileri
     * @return array Güncellenmiş kullanıcı bilgileri
     * @throws ValidationException Geçersiz veri gönderilirse
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->user->updateProfile([
     *     'firstName' => 'Yeni Ad',
     *     'lastName' => 'Yeni Soyad',
     *     'phone' => '+905551234567',
     *     'gender' => 'male'
     * ]);
     */
    public function updateProfile(array $data): array
    {
        return $this->zapi->getHttpClient()->put('/user/profile', $data);
    }
    
    /**
     * Kullanıcı avatar resmi yükler
     * 
     * Bu metod kullanıcının profil fotoğrafını yükler.
     * Desteklenen formatlar: JPEG, PNG, GIF, WebP
     * 
     * @param string $filePath Avatar dosya yolu
     * @return array Yüklenen avatar bilgileri
     * @throws ValidationException Geçersiz dosya formatı
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $avatar = $zapi->user->uploadAvatar('/path/to/avatar.jpg');
     * echo "Avatar URL: " . $avatar['avatar']['url'];
     */
    
    /**
     * Kullanıcının avatar resmini siler
     * 
     * Bu metod kullanıcının mevcut profil fotoğrafını siler.
     * 
     * @return array Silme işlemi sonucu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->user->deleteAvatar();
     * echo $result['message']; // "Avatar başarıyla silindi"
     */
    
    /**
     * Kullanıcının kullanım istatistiklerini getirir
     * 
     * Bu metod kullanıcının günlük/aylık kullanım bilgilerini,
     * limitlerini ve kalan kullanım miktarlarını getirir.
     * 
     * @return array Kullanım istatistikleri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $usage = $zapi->user->getUsage();
     * echo "Günlük mesaj: " . $usage['usage']['dailyMessages'];
     * echo "Kalan: " . $usage['remaining']['dailyMessages'];
     */
    public function getUsage(): array
    {
        return $this->zapi->getHttpClient()->get('/user/usage');
    }
    
    /**
     * Kullanıcının AI yanıtlarını listeler
     * 
     * Bu metod kullanıcının AI ile yaptığı konuşmaları ve
     * yanıtları sayfalama ile listeler.
     * 
     * @param array $options Listeleme seçenekleri
     * @return array AI yanıtları listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $responses = $zapi->user->getResponses([
     *     'page' => 1,
     *     'limit' => 10,
     *     'status' => 'completed'
     * ]);
     * foreach ($responses['responses'] as $response) {
     *     echo $response['model'] . ": " . $response['messages'][0]['content'];
     * }
     */
    public function getResponses(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/user/responses', $options);
    }
    
    /**
     * Belirli bir AI yanıtının detaylarını getirir
     * 
     * Bu metod belirtilen ID'ye sahip AI yanıtının
     * detaylı bilgilerini getirir.
     * 
     * @param string $responseId Yanıt ID'si
     * @return array Yanıt detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $response = $zapi->user->getResponse('507f1f77bcf86cd799439012');
     * echo "Model: " . $response['response']['model'];
     * echo "Status: " . $response['response']['status'];
     */
    public function getResponse(string $responseId): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/user/responses/{$responseId}");
    }
    
    /**
     * Belirli bir AI yanıtını siler
     * 
     * Bu metod belirtilen ID'ye sahip AI yanıtını siler.
     * 
     * @param string $responseId Yanıt ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->user->deleteResponse('507f1f77bcf86cd799439012');
     * echo $result['message']; // "Yanıt başarıyla silindi"
     */
    public function deleteResponse(string $responseId): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/user/responses/{$responseId}");
    }
    
    /**
     * Belirli bir AI yanıtını export eder
     * 
     * Bu metod belirtilen ID'ye sahip AI yanıtını
     * farklı formatlarda export eder.
     * 
     * @param string $responseId Yanıt ID'si
     * @param string $format Export formatı (json, txt, markdown)
     * @return array Export verisi
     * @throws ValidationException Geçersiz ID veya format
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $export = $zapi->user->exportResponse('507f1f77bcf86cd799439012', 'json');
     * file_put_contents('response.json', $export['export']['content']);
     */
    
    /**
     * Kullanıcının en son AI yanıtını getirir
     * 
     * Bu metod kullanıcının en son yaptığı AI konuşmasının
     * yanıtını getirir.
     * 
     * @return array Son yanıt bilgileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $lastResponse = $zapi->user->getLastResponse();
     * if ($lastResponse) {
     *     echo "Son yanıt: " . $lastResponse['response']['messages'][1]['content'];
     * }
     */
    public function getLastResponse(): array
    {
        return $this->zapi->getHttpClient()->get('/user/lastresponse');
    }
    
    /**
     * Kullanıcı hesabını deaktive eder
     * 
     * Bu metod kullanıcı hesabını deaktive eder.
     * Hesap silinmez, sadece devre dışı bırakılır.
     * 
     * @param string $reason Deaktive etme sebebi
     * @return array Deaktive etme sonucu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->user->deactivateAccount('Geçici olarak kullanmıyorum');
     * echo $result['message']; // "Hesap başarıyla deaktive edildi"
     */
    
    /**
     * Kullanıcı hesabını kalıcı olarak siler
     * 
     * Bu metod kullanıcı hesabını ve tüm verilerini
     * kalıcı olarak siler. Bu işlem geri alınamaz.
     * 
     * @param string $password Mevcut şifre
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz şifre
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->user->deleteAccount('current_password');
     * echo $result['message']; // "Hesap başarıyla silindi"
     */
    public function deleteAccount(): array
    {
        return $this->zapi->getHttpClient()->delete('/user/account');
    }
    
    /**
     * Kullanıcının metadata bilgilerini getirir
     * 
     * Bu metod kullanıcının metadata bilgilerini getirir.
     * Nested path desteği vardır.
     * 
     * @param string $path Metadata path (opsiyonel)
     * @return array Metadata bilgileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $metadata = $zapi->user->getMetadata('preferences');
     * echo "Tema: " . $metadata['value']['theme'];
     */
    public function getMetadata(string $path = ''): array
    {
        $endpoint = $path ? "/user/metadata/{$path}" : '/user/metadata';
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * Kullanıcının metadata bilgilerini günceller
     * 
     * Bu metod kullanıcının metadata bilgilerini tamamen günceller.
     * 
     * @param string $path Metadata path
     * @param array $value Metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->user->updateMetadata('preferences', [
     *     'language' => 'tr',
     *     'theme' => 'dark',
     *     'notifications' => true
     * ]);
     */
    public function updateMetadata(string $path, array $value): array
    {
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/user/metadata/{$path}", $value);
    }
    
    /**
     * Kullanıcının metadata bilgilerini kısmi olarak günceller
     * 
     * Bu metod kullanıcının metadata bilgilerini kısmi olarak günceller.
     * Sadece belirtilen alanları günceller.
     * 
     * @param string $path Metadata path
     * @param array $value Güncellenecek metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->user->patchMetadata('preferences', ['theme' => 'light']);
     */
    public function patchMetadata(string $path, array $value): array
    {
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/user/metadata/{$path}", $value);
    }
    
    /**
     * Kullanıcının metadata bilgilerini siler
     * 
     * Bu metod belirtilen path'teki metadata'yı siler.
     * 
     * @param string $path Metadata path
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz path
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->user->deleteMetadata('preferences');
     * echo $result['message']; // "Metadata başarıyla silindi"
     */
    public function deleteMetadata(string $path): array
    {
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/user/metadata/{$path}");
    }

    /**
     * Kullanıcının konuşmalarını listeler
     * 
     * Bu metod kullanıcının tüm konuşmalarını sayfalama ile listeler.
     * 
     * @param array $options Filtreleme ve sayfalama seçenekleri
     * @return array Konuşma listesi
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $conversations = $zapi->user->getConversations([
     *     'page' => 1,
     *     'limit' => 20,
     *     'search' => 'test',
     *     'status' => 'completed'
     * ]);
     */
    public function getConversations(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/user/conversations', $options);
    }

    /**
     * Belirli bir konuşmayı getirir
     * 
     * Bu metod belirtilen ID'ye sahip konuşmanın detaylarını getirir.
     * 
     * @param string $responseId Konuşma ID'si
     * @return array Konuşma detayları
     * @throws ValidationException Geçersiz ID
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $conversation = $zapi->user->getConversation('64f1a2b3c4d5e6f7g8h9i0j1');
     * echo "Konuşma başlığı: " . $conversation['data']['conversation']['title'];
     */
    public function getConversation(string $responseId): array
    {
        return $this->zapi->getHttpClient()->get("/user/conversations/{$responseId}");
    }
}
