<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Auth - Kimlik doğrulama endpoint'leri
 * 
 * Bu sınıf kullanıcı kaydı, giriş, şifre sıfırlama, email/telefon doğrulama,
 * OTP işlemleri ve profil yönetimi için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $auth = $zapi->auth;
 * $login = $auth->login('user@example.com', 'password');
 * $zapi->setApiKey($login['token']);
 */
class Auth
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * Auth constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Kullanıcı kaydı yapar
     * 
     * Bu metod yeni bir kullanıcı hesabı oluşturur.
     * Email ve telefon doğrulaması gerekebilir.
     * 
     * @param array $data Kayıt verileri
     * @return array Kayıt sonucu
     * @throws ValidationException Geçersiz veri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $register = $zapi->auth->register([
     *     'email' => 'user@example.com',
     *     'password' => 'secure_password',
     *     'firstName' => 'John',
     *     'lastName' => 'Doe'
     * ]);
     * echo "Kayıt başarılı: " . $register['message'];
     */
    public function register(array $data): array
    {
        // Orijinal API'ye uygun header ekle
        $headers = [];
        if (isset($data['appId'])) {
            $headers['x-app-id'] = $data['appId'];
            unset($data['appId']); // Header'a ekledikten sonra data'dan çıkar
        }
        
        return $this->zapi->getHttpClient()->post('/auth/register', $data, $headers);
    }
    
    /**
     * Kullanıcı girişi yapar
     * 
     * Bu metod kullanıcının email/telefon ve şifre ile giriş yapmasını sağlar.
     * Başarılı girişte JWT token döner.
     * 
     * @param string $emailOrPhone Email veya telefon
     * @param string $password Şifre
     * @param array $options Ek seçenekler
     * @return array Giriş sonucu
     * @throws AuthenticationException Geçersiz kimlik bilgileri
     * @throws ValidationException Geçersiz veri
     * 
     * @example
     * $login = $zapi->auth->login('user@example.com', 'password');
     * $zapi->setApiKey($login['token']);
     * echo "Hoş geldin " . $login['user']['firstName'];
     */
    public function login(?string $email = null, ?string $phone = null, string $password, array $options = []): array
    {
        $data = ['password' => $password];
        
        if ($email) {
            $data['email'] = $email;
        }
        
        if ($phone) {
            $data['phone'] = $phone;
        }
        
        $data = array_merge($data, $options);
        
        // Orijinal API'ye uygun header ekle
        $headers = [];
        if (isset($options['appId'])) {
            $headers['x-app-id'] = $options['appId'];
            unset($data['appId']); // Header'a ekledikten sonra data'dan çıkar
        }
        
        return $this->zapi->getHttpClient()->post('/auth/login', $data, $headers);
    }
    
    /**
     * Email veya telefon doğrulama kodu gönderir
     * 
     * Bu metod belirtilen email veya telefon numarasına
     * doğrulama kodu gönderir.
     * 
     * @param string $emailOrPhone Email veya telefon
     * @param string $type Doğrulama türü (email, phone)
     * @return array Gönderim sonucu
     * @throws ValidationException Geçersiz email/telefon
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->sendVerification('user@example.com', 'email');
     * echo "Doğrulama kodu gönderildi: " . $result['message'];
     */
    public function sendVerification(?string $email, ?string $phone, string $type): array
    {
        return $this->zapi->getHttpClient()->post('/auth/send-verification', [
            'email' => $email,
            'phone' => $phone,
            'type' => $type
        ]);
    }
    
    /**
     * Email doğrulama kodu ile doğrulama yapar
     * 
     * Bu metod email doğrulama kodunu kontrol eder ve
     * email'i doğrular.
     * 
     * @param string $email Email adresi
     * @param string $code Doğrulama kodu
     * @return array Doğrulama sonucu
     * @throws ValidationException Geçersiz kod
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->verifyEmail('user@example.com', '123456');
     * echo "Email doğrulandı: " . $result['message'];
     */
    public function verifyEmail(string $token): array
    {
        return $this->zapi->getHttpClient()->post('/auth/verify-email', [
            'token' => $token
        ]);
    }
    
    /**
     * Genel doğrulama işlemi yapar
     * 
     * Bu metod email, telefon veya diğer doğrulama
     * işlemlerini gerçekleştirir.
     * 
     * @param string|null $email Email adresi
     * @param string|null $phone Telefon numarası
     * @param string $code Doğrulama kodu
     * @param string $type Doğrulama türü
     * @return array Doğrulama sonucu
     * @throws ValidationException Geçersiz veri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->verify('user@example.com', null, '123456', 'email');
     */
    public function verify(?string $email, ?string $phone, string $code, string $type): array
    {
        return $this->zapi->getHttpClient()->post('/auth/verify', [
            'email' => $email,
            'phone' => $phone,
            'code' => $code,
            'type' => $type
        ]);
    }
    
    /**
     * Şifre sıfırlama isteği gönderir
     * 
     * Bu metod kullanıcının email'ine şifre sıfırlama
     * linki gönderir.
     * 
     * @param string $email Email adresi
     * @return array Gönderim sonucu
     * @throws ValidationException Geçersiz email
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->requestPasswordReset('user@example.com');
     * echo "Şifre sıfırlama linki gönderildi: " . $result['message'];
     */
    
    /**
     * Şifre sıfırlama işlemini tamamlar
     * 
     * Bu metod şifre sıfırlama token'ı ile yeni şifre belirler.
     * 
     * @param string $token Şifre sıfırlama token'ı
     * @param string $newPassword Yeni şifre
     * @return array Sıfırlama sonucu
     * @throws ValidationException Geçersiz token veya şifre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->resetPassword('reset_token_123', 'new_password');
     * echo "Şifre sıfırlandı: " . $result['message'];
     */
    public function forgotPassword(?string $email = null, ?string $phone = null): array
    {
        $data = [];
        
        if ($email) {
            $data['email'] = $email;
        }
        
        if ($phone) {
            $data['phone'] = $phone;
        }
        
        return $this->zapi->getHttpClient()->post('/auth/forgot-password', $data);
    }
    
    public function resetPassword(string $code, string $newPassword): array
    {
        return $this->zapi->getHttpClient()->post('/auth/reset-password', [
            'code' => $code,
            'newPassword' => $newPassword
        ]);
    }
    
    /**
     * Şifre değiştirme işlemi yapar
     * 
     * Bu metod mevcut şifre ile yeni şifre belirler.
     * 
     * @param string $currentPassword Mevcut şifre
     * @param string $newPassword Yeni şifre
     * @return array Değiştirme sonucu
     * @throws ValidationException Geçersiz şifre
     * @throws AuthenticationException Mevcut şifre yanlış
     * 
     * @example
     * $result = $zapi->auth->changePassword('old_password', 'new_password');
     * echo "Şifre değiştirildi: " . $result['message'];
     */
    
    /**
     * OTP (One-Time Password) gönderir
     * 
     * Bu metod belirtilen telefon numarasına OTP gönderir.
     * 
     * @param string $phone Telefon numarası
     * @param string $purpose OTP amacı (login, verification, etc.)
     * @return array Gönderim sonucu
     * @throws ValidationException Geçersiz telefon
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->sendOTP('+905551234567', 'login');
     * echo "OTP gönderildi: " . $result['message'];
     */
    public function sendOTP(?string $mail = null, ?string $phone = null, string $phonePrefix = '90', string $firstName = '', string $lastName = '', string $name = '', string $surname = '', ?string $appId = null): array
    {
        $data = [];
        
        if ($mail) {
            $data['mail'] = $mail;
        }
        
        if ($phone) {
            $data['phone'] = $phone;
            $data['phonePrefix'] = $phonePrefix;
        }
        
        if ($firstName) {
            $data['firstName'] = $firstName;
        }
        
        if ($lastName) {
            $data['lastName'] = $lastName;
        }
        
        if ($name) {
            $data['name'] = $name;
        }
        
        if ($surname) {
            $data['surname'] = $surname;
        }
        
        // Orijinal API'ye uygun header ekle
        $headers = [];
        if ($appId) {
            $headers['x-app-id'] = $appId;
        }
        
        return $this->zapi->getHttpClient()->post('/auth/otp', $data, $headers);
    }
    
    /**
     * OTP doğrulama işlemi yapar
     * 
     * Bu metod gönderilen OTP kodunu kontrol eder.
     * 
     * @param string $phone Telefon numarası
     * @param string $code OTP kodu
     * @param string $purpose OTP amacı
     * @return array Doğrulama sonucu
     * @throws ValidationException Geçersiz kod
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->verifyOTP('+905551234567', '123456', 'login');
     * echo "OTP doğrulandı: " . $result['message'];
     */
    public function verifyOTP(?string $phone = null, ?string $phonePrefix = null, ?string $email = null, string $otpCode): array
    {
        $data = ['otpCode' => $otpCode];
        
        if ($phone) {
            $data['phone'] = $phone;
        }
        
        if ($phonePrefix !== null) {
            $data['phonePrefix'] = $phonePrefix;
        }
        
        if ($email) {
            $data['email'] = $email;
        }
        
        return $this->zapi->getHttpClient()->post('/auth/otp-verify', $data);
    }
    
    /**
     * Token yenileme işlemi yapar
     * 
     * Bu metod mevcut JWT token'ı yeniler.
     * 
     * @param string $refreshToken Refresh token
     * @return array Yenileme sonucu
     * @throws AuthenticationException Geçersiz refresh token
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->auth->refreshToken('refresh_token_123');
     * $zapi->setApiKey($result['token']);
     * echo "Token yenilendi";
     */
    public function getProfile(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/profile');
    }
    
    public function updateProfile(array $data): array
    {
        return $this->zapi->getHttpClient()->put('/auth/profile', $data);
    }
    
    
    /**
     * Kullanıcı çıkışı yapar
     * 
     * Bu metod kullanıcının oturumunu sonlandırır.
     * 
     * @return array Çıkış sonucu
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->auth->logout();
     * echo "Çıkış yapıldı: " . $result['message'];
     */
    public function logout(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/logout');
    }
    
    /**
     * JWT token doğrulama işlemi yapar
     * 
     * Bu metod JWT token'ı doğrular ve detaylarını döndürür.
     * 
     * @param string $token Doğrulanacak JWT token
     * @return array API yanıtı
     * 
     * @example
     * $result = $zapi->auth->verifyToken('jwt_token_here');
     * echo "Token geçerli mi: " . ($result['data']['validation']['isValid'] ? 'Evet' : 'Hayır');
     */
    public function verifyToken(string $token): array
    {
        return $this->zapi->getHttpClient()->post('/auth/verify-token', [
            'token' => $token
        ]);
    }
    
    /**
     * Şifre değiştirme işlemi yapar
     */
    public function changePassword(string $currentPassword, string $newPassword): array
    {
        return $this->zapi->getHttpClient()->post('/auth/change-password', [
            'currentPassword' => $currentPassword,
            'newPassword' => $newPassword
        ]);
    }
    
    /**
     * Token yenileme işlemi yapar
     */
    public function refresh(string $refreshToken): array
    {
        return $this->zapi->getHttpClient()->post('/auth/refresh', [
            'refreshToken' => $refreshToken
        ]);
    }
    
    /**
     * Auth servisi sağlık kontrolü
     */
    public function health(): array
    {
        return $this->zapi->getHttpClient()->get('/auth/health');
    }
}
