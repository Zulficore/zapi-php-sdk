<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * AuthOAuth - OAuth 2.0 kimlik doğrulama endpoint'leri
 * 
 * Bu sınıf OAuth 2.0 tabanlı kimlik doğrulama işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $authOAuth = $zapi->authOAuth;
 * $login = $authOAuth->initiateGoogleLogin('app_id');
 */
class AuthOAuth
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Google OAuth girişi başlatır
     * 
     * @param string $appId Uygulama ID'si
     * @param array $options Ek seçenekler
     * @return array Giriş başlatma sonucu
     * @throws ValidationException Geçersiz app ID
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $login = $zapi->authOAuth->initiateGoogleLogin('app_123');
     * echo "Giriş URL'si: " . $login['authUrl'];
     */
    public function initiateGoogleLogin(string $appId, array $options = []): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/oauth/google/initiate', array_merge([
            'appId' => $appId
        ], $options));
    }
    
    /**
     * Apple OAuth girişi başlatır
     * 
     * @param string $appId Uygulama ID'si
     * @param array $options Ek seçenekler
     * @return array Giriş başlatma sonucu
     * @throws ValidationException Geçersiz app ID
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $login = $zapi->authOAuth->initiateAppleLogin('app_123');
     * echo "Giriş URL'si: " . $login['authUrl'];
     */
    public function initiateAppleLogin(string $appId, array $options = []): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/oauth/apple/initiate', array_merge([
            'appId' => $appId
        ], $options));
    }
    
    /**
     * Google OAuth callback'ini işler
     * 
     * @param string $code Authorization code
     * @param string $state State parameter
     * @param array $options Ek seçenekler
     * @return array Callback işleme sonucu
     * @throws ValidationException Geçersiz code veya state
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $result = $zapi->authOAuth->handleGoogleCallback('auth_code_123', 'state_123');
     * $zapi->setApiKey($result['token']);
     */
    public function handleGoogleCallback(string $code, string $state, array $options = []): array
    {
        if (empty($code)) {
            throw new ValidationException('Authorization code boş olamaz');
        }
        
        if (empty($state)) {
            throw new ValidationException('State parameter boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/oauth/google/callback', array_merge([
            'code' => $code,
            'state' => $state
        ], $options));
    }
    
    /**
     * Apple OAuth callback'ini işler
     * 
     * @param string $code Authorization code
     * @param string $state State parameter
     * @param array $options Ek seçenekler
     * @return array Callback işleme sonucu
     * @throws ValidationException Geçersiz code veya state
     * @throws AuthenticationException Kimlik doğrulama hatası
     * 
     * @example
     * $result = $zapi->authOAuth->handleAppleCallback('auth_code_123', 'state_123');
     * $zapi->setApiKey($result['token']);
     */
    public function handleAppleCallback(string $code, string $state, array $options = []): array
    {
        if (empty($code)) {
            throw new ValidationException('Authorization code boş olamaz');
        }
        
        if (empty($state)) {
            throw new ValidationException('State parameter boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/auth/oauth/apple/callback', array_merge([
            'code' => $code,
            'state' => $state
        ], $options));
    }
    
    /**
     * OAuth hesabını bağlar
     * 
     * @param string $provider OAuth sağlayıcısı (google, apple)
     * @param string $accessToken Access token
     * @param array $options Ek seçenekler
     * @return array Bağlama sonucu
     * @throws ValidationException Geçersiz provider veya token
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->authOAuth->linkAccount('google', 'access_token_123');
     * echo "Hesap bağlandı: " . $result['message'];
     */
    public function linkAccount(string $provider, string $accessToken, array $options = []): array
    {
        if (empty($provider)) {
            throw new ValidationException('Provider boş olamaz');
        }
        
        if (empty($accessToken)) {
            throw new ValidationException('Access token boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/auth/oauth/{$provider}/link", array_merge([
            'accessToken' => $accessToken
        ], $options));
    }
    
    /**
     * OAuth hesabını bağlantısını kaldırır
     * 
     * @param string $provider OAuth sağlayıcısı (google, apple)
     * @return array Bağlantı kaldırma sonucu
     * @throws ValidationException Geçersiz provider
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->authOAuth->unlinkAccount('google');
     * echo "Hesap bağlantısı kaldırıldı: " . $result['message'];
     */
    public function unlinkAccount(string $provider): array
    {
        if (empty($provider)) {
            throw new ValidationException('Provider boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/auth/oauth/{$provider}/unlink");
    }
    
    /**
     * OAuth başarı sayfasını getirir
     * 
     * @param array $options Sayfa seçenekleri
     * @return array Başarı sayfası
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $success = $zapi->authOAuth->getSuccessPage(['message' => 'Giriş başarılı']);
     * echo "Başarı sayfası: " . $success['html'];
     */
    public function getSuccessPage(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/auth/oauth/success', $options);
    }
    
    /**
     * OAuth hata sayfasını getirir
     * 
     * @param array $options Sayfa seçenekleri
     * @return array Hata sayfası
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $error = $zapi->authOAuth->getErrorPage(['error' => 'Giriş başarısız']);
     * echo "Hata sayfası: " . $error['html'];
     */
    public function getErrorPage(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/auth/oauth/error', $options);
    }
    
    /**
     * OAuth sandbox test yapar
     * 
     * @param string $provider OAuth sağlayıcısı (google, apple)
     * @return array Test sonucu
     * @throws ValidationException Geçersiz provider
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $test = $zapi->authOAuth->sandboxTest('google');
     * echo "Test sonucu: " . $test['status'];
     */
    public function sandboxTest(string $provider): array
    {
        if (empty($provider)) {
            throw new ValidationException('Provider boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/auth/oauth/{$provider}/sandbox-test");
    }
    
    /**
     * OAuth debug bilgilerini getirir
     * 
     * @param string $provider OAuth sağlayıcısı (google, apple)
     * @return array Debug bilgileri
     * @throws ValidationException Geçersiz provider
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $debug = $zapi->authOAuth->getDebugInfo('google');
     * echo "Debug bilgileri: " . json_encode($debug);
     */
    public function getDebugInfo(string $provider): array
    {
        if (empty($provider)) {
            throw new ValidationException('Provider boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/auth/oauth/{$provider}/debug");
    }
    
    /**
     * OAuth metadata bilgilerini getirir
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path (opsiyonel)
     * @return array Metadata bilgileri
     * @throws ValidationException Geçersiz app ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $metadata = $zapi->authOAuth->getMetadata('app_123', 'google');
     * echo "Google metadata: " . json_encode($metadata['value']);
     */
    public function getMetadata(string $appId, string $path = ''): array
    {
        if (empty($appId)) {
            throw new ValidationException('Uygulama ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/auth/oauth/{$appId}/metadata/{$path}" : "/auth/oauth/{$appId}/metadata";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * OAuth metadata bilgilerini günceller
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @param array $value Metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz app ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->authOAuth->updateMetadata('app_123', 'google', [
     *     'clientId' => 'new_client_id',
     *     'clientSecret' => 'new_client_secret'
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
        
        return $this->zapi->getHttpClient()->put("/auth/oauth/{$appId}/metadata/{$path}", ['value' => $value]);
    }
    
    /**
     * OAuth metadata bilgilerini kısmi olarak günceller
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @param array $value Güncellenecek metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz app ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->authOAuth->patchMetadata('app_123', 'google', [
     *     'clientId' => 'updated_client_id'
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
        
        return $this->zapi->getHttpClient()->patch("/auth/oauth/{$appId}/metadata/{$path}", ['value' => $value]);
    }
    
    /**
     * OAuth metadata bilgilerini siler
     * 
     * @param string $appId Uygulama ID'si
     * @param string $path Metadata path
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz app ID veya path
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->authOAuth->deleteMetadata('app_123', 'google');
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
        
        return $this->zapi->getHttpClient()->delete("/auth/oauth/{$appId}/metadata/{$path}");
    }
}
