<?php

/**
 * ZAPI PHP SDK - Temel Kullanım Örneği
 * 
 * Bu dosya ZAPI PHP SDK'sının temel kullanımını gösterir.
 * 
 * @author ZAPI Team
 * @version 1.0.0
 */

require_once '../vendor/autoload.php';

use ZAPI\ZAPI;
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

// ZAPI instance oluştur
$zapi = new ZAPI('your_api_key', 'your_app_id');

// Alternatif olarak custom base URL ile
// $zapi = new ZAPI('your_api_key', 'your_app_id', 'https://custom-api.example.com');

try {
    // 1. Sistem durumunu kontrol et
    echo "=== Sistem Durumu ===" . PHP_EOL;
    $config = $zapi->config->get();
    echo "API Versiyonu: " . $config['version'] . PHP_EOL;
    echo "Environment: " . $config['environment'] . PHP_EOL;
    echo "WebSocket URL: " . $config['wsUrl'] . PHP_EOL;
    
    // 2. Kullanıcı girişi yap
    echo PHP_EOL . "=== Kullanıcı Girişi ===" . PHP_EOL;
    $login = $zapi->auth->login('user@example.com', 'password');
    
    if ($login['success']) {
        echo "Giriş başarılı!" . PHP_EOL;
        echo "Token: " . substr($login['token'], 0, 20) . "..." . PHP_EOL;
        
        // Bearer token'ı SDK'ya set et
        $zapi->setBearerToken($login['token']);
        
        // 3. Kullanıcı profili getir
        echo PHP_EOL . "=== Kullanıcı Profili ===" . PHP_EOL;
        $profile = $zapi->user->getProfile();
        
        echo "Kullanıcı: " . $profile['user']['firstName'] . " " . $profile['user']['lastName'] . PHP_EOL;
        echo "Email: " . $profile['user']['email'] . PHP_EOL;
        echo "Plan: " . $profile['user']['subscription']['plan']['name'] . PHP_EOL;
        echo "Günlük mesaj kullanımı: " . $profile['user']['subscription']['usage']['dailyMessages'] . 
             "/" . $profile['user']['subscription']['plan']['limits']['dailyMessageLimit'] . PHP_EOL;
        
        // 4. AI yanıtı oluştur
        echo PHP_EOL . "=== AI Yanıtı Oluştur ===" . PHP_EOL;
        $response = $zapi->responses->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => 'Merhaba, nasılsın?']
            ],
            'temperature' => 0.7,
            'max_tokens' => 100
        ]);
        
        echo "AI Yanıtı: " . $response['choices'][0]['message']['content'] . PHP_EOL;
        echo "Token kullanımı: " . $response['usage']['totalTokens'] . PHP_EOL;
        
        // 5. Uygulamaları listele
        echo PHP_EOL . "=== Uygulamalar ===" . PHP_EOL;
        $apps = $zapi->apps->list(['limit' => 5]);
        
        echo "Toplam uygulama: " . $apps['pagination']['totalItems'] . PHP_EOL;
        foreach ($apps['data'] as $app) {
            echo "- " . $app['name'] . " (" . $app['status'] . ")" . PHP_EOL;
        }
        
        // 6. Dosya yükleme örneği (varsa)
        $testFile = __DIR__ . '/test-image.jpg';
        if (file_exists($testFile)) {
            echo PHP_EOL . "=== Dosya Yükleme ===" . PHP_EOL;
            $upload = $zapi->upload->upload($testFile);
            
            echo "Dosya yüklendi: " . $upload['file']['filename'] . PHP_EOL;
            echo "URL: " . $upload['file']['url'] . PHP_EOL;
            echo "Boyut: " . number_format($upload['file']['size'] / 1024, 2) . " KB" . PHP_EOL;
        }
        
        // 7. Bildirim gönderme örneği
        echo PHP_EOL . "=== E-posta Bildirimi ===" . PHP_EOL;
        $notification = $zapi->notifications->sendEmail([
            'to' => 'test@example.com',
            'subject' => 'Test E-postası',
            'content' => 'Bu bir test e-postasıdır.',
            'template' => 'default'
        ]);
        
        echo "E-posta gönderildi: " . $notification['message'] . PHP_EOL;
        echo "Bildirim ID: " . $notification['notificationId'] . PHP_EOL;
        
    } else {
        echo "Giriş başarısız: " . $login['message'] . PHP_EOL;
    }
    
} catch (AuthenticationException $e) {
    echo "Kimlik doğrulama hatası: " . $e->getMessage() . PHP_EOL;
    echo "HTTP Status: " . $e->getHttpStatusCode() . PHP_EOL;
    
} catch (ValidationException $e) {
    echo "Doğrulama hatası: " . $e->getMessage() . PHP_EOL;
    echo "Hatalı alanlar: " . implode(', ', $e->getInvalidFields()) . PHP_EOL;
    
} catch (ZAPIException $e) {
    echo "ZAPI Hatası: " . $e->getMessage() . PHP_EOL;
    echo "Hata kodu: " . $e->getCode() . PHP_EOL;
    echo "HTTP Status: " . $e->getHttpStatusCode() . PHP_EOL;
    
} catch (Exception $e) {
    echo "Genel hata: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== SDK Bilgileri ===" . PHP_EOL;
echo "SDK Versiyonu: " . ZAPI\ZAPI::getVersion() . PHP_EOL;
echo "Base URL: " . $zapi->getBaseUrl() . PHP_EOL;
echo "Debug Modu: " . ($zapi->isDebugMode() ? 'Aktif' : 'Pasif') . PHP_EOL;
echo "Timeout: " . $zapi->getTimeout() . " saniye" . PHP_EOL;
