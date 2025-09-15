<?php

/**
 * ZAPI PHP SDK - Kimlik Doğrulama Örnekleri
 * 
 * Bu dosya kimlik doğrulama işlemlerinin örneklerini gösterir.
 * 
 * @author ZAPI Team
 * @version 1.0.0
 */

require_once '../vendor/autoload.php';

use ZAPI\ZAPI;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

$zapi = new ZAPI('your_api_key', 'your_app_id');

echo "=== ZAPI Kimlik Doğrulama Örnekleri ===" . PHP_EOL . PHP_EOL;

try {
    // 1. Kullanıcı Kaydı
    echo "1. Kullanıcı Kaydı" . PHP_EOL;
    echo "==================" . PHP_EOL;
    
    $registerData = [
        'email' => 'newuser@example.com',
        'password' => 'SecurePassword123!',
        'firstName' => 'Yeni',
        'lastName' => 'Kullanıcı',
        'phone' => '+905551234567',
        'gender' => 'male'
    ];
    
    $register = $zapi->auth->register($registerData);
    echo "Kayıt sonucu: " . $register['message'] . PHP_EOL;
    
    if (isset($register['user'])) {
        echo "Kullanıcı ID: " . $register['user']['_id'] . PHP_EOL;
        echo "Email doğrulama gerekli: " . ($register['user']['isEmailVerified'] ? 'Hayır' : 'Evet') . PHP_EOL;
    }
    
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "Kayıt doğrulama hatası: " . $e->getMessage() . PHP_EOL;
    foreach ($e->getValidationErrors() as $field => $error) {
        echo "- {$field}: {$error}" . PHP_EOL;
    }
    echo PHP_EOL;
}

try {
    // 2. Email Doğrulama Kodu Gönderme
    echo "2. Email Doğrulama Kodu Gönderme" . PHP_EOL;
    echo "===============================" . PHP_EOL;
    
    $verificationSent = $zapi->auth->sendVerification('newuser@example.com', 'email');
    echo "Doğrulama kodu gönderildi: " . $verificationSent['message'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "Email doğrulama gönderme hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 3. Email Doğrulama
    echo "3. Email Doğrulama" . PHP_EOL;
    echo "==================" . PHP_EOL;
    
    // Not: Gerçek kodla değiştirin
    $verifyResult = $zapi->auth->verifyEmail('newuser@example.com', '123456');
    echo "Email doğrulama sonucu: " . $verifyResult['message'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "Email doğrulama hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 4. Kullanıcı Girişi
    echo "4. Kullanıcı Girişi" . PHP_EOL;
    echo "==================" . PHP_EOL;
    
    $login = $zapi->auth->login('user@example.com', 'password');
    
    if ($login['success']) {
        echo "Giriş başarılı!" . PHP_EOL;
        echo "Token: " . substr($login['token'], 0, 20) . "..." . PHP_EOL;
        echo "Refresh Token: " . substr($login['refreshToken'], 0, 20) . "..." . PHP_EOL;
        echo "Kullanıcı: " . $login['user']['firstName'] . " " . $login['user']['lastName'] . PHP_EOL;
        
        // Bearer token'ı SDK'ya set et
        $zapi->setBearerToken($login['token']);
        
        // Bearer token'ı sakla (session, database, vs.)
        $accessToken = $login['token'];
        $refreshToken = $login['refreshToken'];
        
        echo PHP_EOL;
    }
    
} catch (AuthenticationException $e) {
    echo "Giriş hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 5. OTP Gönderme
    echo "5. OTP Gönderme" . PHP_EOL;
    echo "===============" . PHP_EOL;
    
    $otpSent = $zapi->auth->sendOTP('+905551234567', 'login');
    echo "OTP gönderildi: " . $otpSent['message'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "OTP gönderme hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 6. OTP Doğrulama
    echo "6. OTP Doğrulama" . PHP_EOL;
    echo "================" . PHP_EOL;
    
    // Not: Gerçek OTP koduyla değiştirin
    $otpVerify = $zapi->auth->verifyOTP('+905551234567', '123456', 'login');
    echo "OTP doğrulama sonucu: " . $otpVerify['message'] . PHP_EOL;
    
    if (isset($otpVerify['token'])) {
        echo "OTP ile giriş başarılı!" . PHP_EOL;
        echo "Token: " . substr($otpVerify['token'], 0, 20) . "..." . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "OTP doğrulama hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 7. Şifre Sıfırlama İsteği
    echo "7. Şifre Sıfırlama İsteği" . PHP_EOL;
    echo "========================" . PHP_EOL;
    
    $resetRequest = $zapi->auth->requestPasswordReset('user@example.com');
    echo "Şifre sıfırlama linki gönderildi: " . $resetRequest['message'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (ValidationException $e) {
    echo "Şifre sıfırlama isteği hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

// Token varsa, token gerektiren işlemler
if (isset($accessToken)) {
    $zapi->setApiKey($accessToken);
    
    try {
        // 8. Şifre Değiştirme
        echo "8. Şifre Değiştirme" . PHP_EOL;
        echo "==================" . PHP_EOL;
        
        $passwordChange = $zapi->auth->changePassword('oldPassword', 'newSecurePassword123!');
        echo "Şifre değiştirme sonucu: " . $passwordChange['message'] . PHP_EOL;
        echo PHP_EOL;
        
    } catch (AuthenticationException $e) {
        echo "Şifre değiştirme hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
    }
    
    try {
        // 9. Token Yenileme
        echo "9. Token Yenileme" . PHP_EOL;
        echo "=================" . PHP_EOL;
        
        if (isset($refreshToken)) {
            $tokenRefresh = $zapi->auth->refreshToken($refreshToken);
            echo "Token yenilendi!" . PHP_EOL;
            echo "Yeni Token: " . substr($tokenRefresh['token'], 0, 20) . "..." . PHP_EOL;
            echo "Yeni Refresh Token: " . substr($tokenRefresh['refreshToken'], 0, 20) . "..." . PHP_EOL;
            
            // Yeni token'ı kullan
            $zapi->setBearerToken($tokenRefresh['token']);
        }
        echo PHP_EOL;
        
    } catch (AuthenticationException $e) {
        echo "Token yenileme hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
    }
    
    try {
        // 10. Firebase Girişi
        echo "10. Firebase Girişi" . PHP_EOL;
        echo "==================" . PHP_EOL;
        
        // Firebase ID Token'ınızı buraya koyun
        $firebaseToken = 'firebase_id_token_here';
        
        $firebaseLogin = $zapi->authFirebase->loginWithGoogle($firebaseToken);
        echo "Firebase giriş başarılı!" . PHP_EOL;
        echo "Token: " . substr($firebaseLogin['token'], 0, 20) . "..." . PHP_EOL;
        echo PHP_EOL;
        
    } catch (ValidationException $e) {
        echo "Firebase giriş hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
    } catch (AuthenticationException $e) {
        echo "Firebase kimlik doğrulama hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
    }
    
    try {
        // 11. Çıkış Yapma
        echo "11. Çıkış Yapma" . PHP_EOL;
        echo "===============" . PHP_EOL;
        
        $logout = $zapi->auth->logout();
        echo "Çıkış yapıldı: " . $logout['message'] . PHP_EOL;
        echo PHP_EOL;
        
    } catch (AuthenticationException $e) {
        echo "Çıkış hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
    }
}

// 12. Auth Sağlık Kontrolü
try {
    echo "12. Auth Sağlık Kontrolü" . PHP_EOL;
    echo "========================" . PHP_EOL;
    
    $health = $zapi->auth->healthCheck();
    echo "Auth servisi durumu: " . $health['status'] . PHP_EOL;
    echo "Yanıt süresi: " . $health['responseTime'] . "ms" . PHP_EOL;
    
} catch (Exception $e) {
    echo "Sağlık kontrolü hatası: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Auth Örnekleri Tamamlandı ===" . PHP_EOL;
