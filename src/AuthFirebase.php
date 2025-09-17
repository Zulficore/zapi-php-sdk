<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * AuthFirebase - Firebase kimlik doğrulama endpoint'leri
 * 
 * Bu sınıf Firebase tabanlı kimlik doğrulama işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $authFirebase = $zapi->authFirebase;
 * $login = $authFirebase->loginWithGoogle('firebase_token');
 */
class AuthFirebase
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Google ile Firebase girişi yapar
     * 
     * @param string $firebaseToken Firebase ID token
     * @param array $options Ek seçenekler
     * @return array Giriş sonucu
     * @throws ValidationException Geçersiz token
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $login = $zapi->authFirebase->loginWithGoogle('firebase_token_123');
     * $zapi->setApiKey($login['token']);
     */
    public function loginWithGoogle(string $firebaseToken, array $options = []): array
    {
        if (empty($firebaseToken)) {
            throw new ValidationException('Firebase token boş olamaz');
        }
        
        $data = array_merge([
            'idToken' => $firebaseToken
        ], $options);
        
        // Orijinal API'ye uygun header ekle
        $headers = [];
        if (isset($options['appId'])) {
            $headers['x-app-id'] = $options['appId'];
            unset($data['appId']); // Header'a ekledikten sonra data'dan çıkar
        }
        
        return $this->zapi->getHttpClient()->post('/auth/firebase/google', $data, $headers);
    }
    
    /**
     * Apple ile Firebase girişi yapar
     * 
     * @param string $firebaseToken Firebase ID token
     * @param array $options Ek seçenekler
     * @return array Giriş sonucu
     * @throws ValidationException Geçersiz token
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $login = $zapi->authFirebase->loginWithApple('firebase_token_123');
     * $zapi->setApiKey($login['token']);
     */
    public function loginWithApple(string $firebaseToken, array $options = []): array
    {
        if (empty($firebaseToken)) {
            throw new ValidationException('Firebase token boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/firebase/apple', array_merge([
            'idToken' => $firebaseToken
        ], $options));
    }
    
    /**
     * Firebase token'ı yeniler
     * 
     * @param string $refreshToken Refresh token
     * @return array Yenileme sonucu
     * @throws ValidationException Geçersiz refresh token
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $result = $zapi->authFirebase->refreshToken('refresh_token_123');
     * $zapi->setApiKey($result['token']);
     */
    public function refreshToken(string $refreshToken): array
    {
        if (empty($refreshToken)) {
            throw new ValidationException('Refresh token boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/firebase/refresh', [
            'refreshToken' => $refreshToken
        ]);
    }
    
    /**
     * Firebase kullanıcı profilini günceller
     * 
     * @param array $data Profil verileri
     * @return array Güncelleme sonucu
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->authFirebase->updateProfile([
     *     'displayName' => 'Yeni Ad',
     *     'photoURL' => 'https://example.com/photo.jpg'
     * ]);
     */
    public function updateProfile(array $data): array
    {
        return $this->zapi->getHttpClient()->put('/auth/firebase/profile', $data);
    }
    
    /**
     * Firebase çıkışı yapar
     * 
     * @return array Çıkış sonucu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->authFirebase->logout();
     * echo "Çıkış yapıldı: " . $result['message'];
     */
    public function logout(): array
    {
        return $this->zapi->getHttpClient()->post('/auth/firebase/logout');
    }
    
    /**
     * Firebase Admin SDK durumunu kontrol eder
     * 
     * @return array SDK durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $status = $zapi->authFirebase->getSDKStatus();
     * echo "SDK durumu: " . $status['status'];
     */
    public function getSDKStatus(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/firebase/sdk-status');
    }
    
    /**
     * Firebase debug bilgilerini getirir
     * 
     * @return array Debug bilgileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $debug = $zapi->authFirebase->getDebugInfo();
     * echo "Debug bilgileri: " . json_encode($debug);
     */
    public function getDebugInfo(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/firebase/debug');
    }
    
    /**
     * Firebase sağlık kontrolü yapar
     * 
     * @return array Sağlık durumu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $health = $zapi->authFirebase->healthCheck();
     * echo "Firebase durumu: " . $health['status'];
     */
    public function healthCheck(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/firebase/health');
    }
    
    /**
     * Kullanıcı durumunu getirir
     */
    public function getUserStatus(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/firebase/user/status');
    }
}
