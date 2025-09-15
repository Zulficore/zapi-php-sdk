# Hızlı Başlangıç Rehberi

Bu rehber, ZAPI PHP SDK'yı kullanmaya başlamak için gereken temel adımları 5 dakikada öğretir.

## 🚀 5 Dakikada Başlayın

### Adım 1: Kurulum

```bash
# Proje dizininizde
composer require zulficore/zapi-php-sdk
```

### Adım 2: Temel Yapılandırma

```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

// SDK'yı başlat
$zapi = new ZAPI(
    apiKey: 'your_api_key_here',
    appId: 'your_app_id_here'
);
```

### Adım 3: İlk API Çağrısı

```php
// Sistem durumunu kontrol et
$health = $zapi->system->getHealth();
echo "API Durumu: " . $health['data']['status'];
```

## 🎯 Temel Kullanım Senaryoları

### 1. Kullanıcı Kaydı ve Girişi

```php
<?php
use ZAPI\ZAPI;

$zapi = new ZAPI('your_api_key', 'your_app_id');

try {
    // Kullanıcı kaydı
    $registerResult = $zapi->auth->register([
        'email' => 'user@example.com',
        'password' => 'password123',
        'name' => 'John Doe'
    ]);
    
    echo "✅ Kayıt başarılı: " . $registerResult['data']['message'];
    
    // Kullanıcı girişi
    $loginResult = $zapi->auth->login([
        'email' => 'user@example.com',
        'password' => 'password123'
    ]);
    
    // Bearer token'ı ayarla
    $zapi->setBearerToken($loginResult['data']['token']);
    
    echo "✅ Giriş başarılı!";
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage();
}
```

### 2. AI Sohbet

```php
<?php
use ZAPI\ZAPI;

$zapi = new ZAPI('your_api_key', 'your_app_id');

try {
    // AI sohbet
    $chatResponse = $zapi->responses->create([
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'user', 'content' => 'Merhaba! PHP hakkında bilgi ver.']
        ]
    ]);
    
    echo "AI Yanıtı: " . $chatResponse['data']['content'];
    
} catch (Exception $e) {
    echo "❌ Sohbet hatası: " . $e->getMessage();
}
```

### 3. Dosya Yükleme

```php
<?php
use ZAPI\ZAPI;

$zapi = new ZAPI('your_api_key', 'your_app_id');

try {
    // Dosya yükleme
    $uploadResult = $zapi->upload->uploadFile([
        'file' => '/path/to/your/file.pdf',
        'type' => 'document'
    ]);
    
    echo "✅ Dosya yüklendi: " . $uploadResult['data']['file_id'];
    
} catch (Exception $e) {
    echo "❌ Yükleme hatası: " . $e->getMessage();
}
```

### 4. Kullanıcı Profili

```php
<?php
use ZAPI\ZAPI;

$zapi = new ZAPI('your_api_key', 'your_app_id');
$zapi->setBearerToken('your_bearer_token');

try {
    // Profil bilgilerini al
    $profile = $zapi->user->getProfile();
    
    echo "Kullanıcı: " . $profile['data']['name'];
    echo "E-posta: " . $profile['data']['email'];
    
    // Profil güncelle
    $updateResult = $zapi->user->updateProfile([
        'name' => 'John Updated',
        'bio' => 'Yeni biyografi'
    ]);
    
    echo "✅ Profil güncellendi!";
    
} catch (Exception $e) {
    echo "❌ Profil hatası: " . $e->getMessage();
}
```

## 🔧 Temel Yapılandırma

### Environment Variables

```bash
# .env dosyası
ZAPI_API_KEY=your_api_key_here
ZAPI_APP_ID=your_app_id_here
ZAPI_BASE_URL=https://dev.zulficoresystem.net
```

### PHP Kodu

```php
<?php
use ZAPI\ZAPI;

// Environment variables'dan yapılandırma
$zapi = new ZAPI(
    apiKey: $_ENV['ZAPI_API_KEY'],
    appId: $_ENV['ZAPI_APP_ID'],
    baseUrl: $_ENV['ZAPI_BASE_URL'] ?? null
);
```

## 🎨 Pratik Örnekler

### Basit Chat Bot

```php
<?php
use ZAPI\ZAPI;

class SimpleChatBot {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function chat(string $message): string {
        try {
            $response = $this->zapi->responses->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'user', 'content' => $message]
                ]
            ]);
            
            return $response['data']['content'];
        } catch (Exception $e) {
            return "Üzgünüm, bir hata oluştu: " . $e->getMessage();
        }
    }
}

// Kullanım
$bot = new SimpleChatBot('your_api_key', 'your_app_id');
echo $bot->chat("Merhaba! Nasılsın?");
```

### Kullanıcı Yönetim Sistemi

```php
<?php
use ZAPI\ZAPI;

class UserManager {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function registerUser(string $email, string $password, string $name): array {
        try {
            $result = $this->zapi->auth->register([
                'email' => $email,
                'password' => $password,
                'name' => $name
            ]);
            
            return [
                'success' => true,
                'data' => $result['data']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function loginUser(string $email, string $password): array {
        try {
            $result = $this->zapi->auth->login([
                'email' => $email,
                'password' => $password
            ]);
            
            // Bearer token'ı ayarla
            $this->zapi->setBearerToken($result['data']['token']);
            
            return [
                'success' => true,
                'token' => $result['data']['token'],
                'user' => $result['data']['user']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

// Kullanım
$userManager = new UserManager('your_api_key', 'your_app_id');

// Kullanıcı kaydı
$registerResult = $userManager->registerUser(
    'user@example.com',
    'password123',
    'John Doe'
);

if ($registerResult['success']) {
    echo "✅ Kullanıcı kaydedildi!";
} else {
    echo "❌ Kayıt hatası: " . $registerResult['error'];
}
```

### Dosya Yönetim Sistemi

```php
<?php
use ZAPI\ZAPI;

class FileManager {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function uploadFile(string $filePath, string $type = 'document'): array {
        try {
            $result = $this->zapi->upload->uploadFile([
                'file' => $filePath,
                'type' => $type
            ]);
            
            return [
                'success' => true,
                'file_id' => $result['data']['file_id'],
                'url' => $result['data']['url']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getFileInfo(string $fileId): array {
        try {
            $result = $this->zapi->upload->getFileInfo($fileId);
            
            return [
                'success' => true,
                'file' => $result['data']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}

// Kullanım
$fileManager = new FileManager('your_api_key', 'your_app_id');

// Dosya yükleme
$uploadResult = $fileManager->uploadFile('/path/to/file.pdf', 'document');

if ($uploadResult['success']) {
    echo "✅ Dosya yüklendi: " . $uploadResult['file_id'];
    
    // Dosya bilgilerini al
    $fileInfo = $fileManager->getFileInfo($uploadResult['file_id']);
    if ($fileInfo['success']) {
        echo "Dosya boyutu: " . $fileInfo['file']['size'] . " bytes";
    }
} else {
    echo "❌ Yükleme hatası: " . $uploadResult['error'];
}
```

## 🔍 Hata Ayıklama

### Debug Modu

```php
<?php
use ZAPI\ZAPI;

// Debug modu ile başlat
$zapi = new ZAPI(
    apiKey: 'your_api_key',
    appId: 'your_app_id',
    baseUrl: null,
    options: ['debug' => true]
);

// Debug bilgilerini kontrol et
if ($zapi->isDebugMode()) {
    echo "Debug modu aktif";
}
```

### Hata Yakalama

```php
<?php
use ZAPI\ZAPI;
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

try {
    $zapi = new ZAPI('your_api_key', 'your_app_id');
    $result = $zapi->auth->login(['email' => 'test@example.com', 'password' => 'wrong']);
    
} catch (AuthenticationException $e) {
    echo "Kimlik doğrulama hatası: " . $e->getMessage();
    
} catch (ValidationException $e) {
    echo "Doğrulama hatası: " . $e->getMessage();
    echo "Hata detayları: " . json_encode($e->getErrorDetails());
    
} catch (ZAPIException $e) {
    echo "ZAPI hatası: " . $e->getMessage();
    echo "HTTP kodu: " . $e->getStatusCode();
    
} catch (Exception $e) {
    echo "Genel hata: " . $e->getMessage();
}
```

## 📚 Sonraki Adımlar

Temel kullanımı öğrendikten sonra:

1. **[API Referansı](API-Reference)** - Tüm endpoint'leri detaylı inceleyin
2. **[Örnekler](Examples)** - Daha karmaşık kullanım senaryolarını öğrenin
3. **[Hata Yönetimi](Error-Handling)** - Güçlü hata yönetimi teknikleri
4. **[Gelişmiş Kullanım](Advanced-Usage)** - İleri seviye özellikler

## 🆘 Yardım

Sorun yaşıyorsanız:

- **[FAQ](FAQ-and-Troubleshooting)** - Sık sorulan sorular
- **[GitHub Issues](https://github.com/Zulficore/zapi-php-sdk/issues)** - Hata bildirimi
- **E-posta**: dev@zapi.com

---

**Tebrikler!** ZAPI PHP SDK'nın temel kullanımını öğrendiniz. Artık güçlü API entegrasyonları oluşturabilirsiniz! 🚀
