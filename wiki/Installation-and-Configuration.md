# Kurulum ve Yapılandırma

Bu rehber, ZAPI PHP SDK'yı projenize nasıl kuracağınızı ve yapılandıracağınızı adım adım açıklar.

## 📋 Sistem Gereksinimleri

### Minimum Gereksinimler
- **PHP**: 8.2 veya üzeri
- **Composer**: 2.0 veya üzeri
- **cURL**: PHP extension
- **JSON**: PHP extension
- **OpenSSL**: PHP extension (HTTPS için)

### Önerilen Gereksinimler
- **PHP**: 8.3 veya üzeri
- **Memory**: En az 128MB
- **Disk**: En az 50MB boş alan

## 🚀 Kurulum

### 1. Composer ile Kurulum

```bash
# Proje dizininizde
composer require zulficore/zapi-php-sdk
```

### 2. Manuel Kurulum

```bash
# GitHub'dan klonlama
git clone https://github.com/Zulficore/zapi-php-sdk.git
cd zapi-php-sdk
composer install
```

### 3. Geliştirme Kurulumu

```bash
# Geliştirme bağımlılıkları ile
composer require zulficore/zapi-php-sdk --dev
```

## ⚙️ Yapılandırma

### 1. Temel Yapılandırma

```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

// Temel yapılandırma
$zapi = new ZAPI(
    apiKey: 'your_api_key_here',
    appId: 'your_app_id_here'
);
```

### 2. Gelişmiş Yapılandırma

```php
<?php
use ZAPI\ZAPI;

// Gelişmiş yapılandırma seçenekleri
$zapi = new ZAPI(
    apiKey: 'your_api_key_here',
    appId: 'your_app_id_here',
    baseUrl: 'https://dev.zulficoresystem.net', // Özel API URL'i
    options: [
        'debug' => true,           // Debug modu
        'timeout' => 60,           // Timeout süresi (saniye)
        'bearerToken' => 'token'   // Bearer token (kimlik doğrulama için)
    ]
);
```

### 3. Environment Variables ile Yapılandırma

#### .env Dosyası Oluşturun

```bash
# .env
ZAPI_API_KEY=your_api_key_here
ZAPI_APP_ID=your_app_id_here
ZAPI_BASE_URL=https://dev.zulficoresystem.net
ZAPI_DEBUG=false
ZAPI_TIMEOUT=30
ZAPI_BEARER_TOKEN=your_bearer_token_here
```

#### Environment Variables Kullanımı

```php
<?php
use ZAPI\ZAPI;

// Environment variables'dan yapılandırma
$zapi = new ZAPI(
    apiKey: $_ENV['ZAPI_API_KEY'],
    appId: $_ENV['ZAPI_APP_ID'],
    baseUrl: $_ENV['ZAPI_BASE_URL'] ?? null,
    options: [
        'debug' => filter_var($_ENV['ZAPI_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'timeout' => (int) ($_ENV['ZAPI_TIMEOUT'] ?? 30),
        'bearerToken' => $_ENV['ZAPI_BEARER_TOKEN'] ?? null
    ]
);
```

### 4. Composer Autoload Yapılandırması

#### composer.json

```json
{
    "require": {
        "zulficore/zapi-php-sdk": "^1.0"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "config": {
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

## 🔧 Yapılandırma Seçenekleri

### API Anahtarı ve Uygulama ID

```php
// API anahtarı - ZAPI dashboard'dan alın
$apiKey = 'zapi_16e33437_909d7fedb93c071ebbee696c10da777adbfa3714fc9134e9bd7b5ba7';

// Uygulama ID - ZAPI dashboard'dan alın
$appId = '687e9401d524d30c80a998f9';
```

### Base URL Yapılandırması

```php
// Geliştirme ortamı
$baseUrl = 'https://dev.zulficoresystem.net';

// Üretim ortamı
$baseUrl = 'https://api.zulficoresystem.net';

// Özel API sunucusu
$baseUrl = 'https://your-custom-api.com';
```

### Debug Modu

```php
// Debug modu aktif
$zapi = new ZAPI($apiKey, $appId, null, ['debug' => true]);

// Debug bilgilerini al
if ($zapi->isDebugMode()) {
    echo "Debug modu aktif";
}
```

### Timeout Yapılandırması

```php
// 60 saniye timeout
$zapi = new ZAPI($apiKey, $appId, null, ['timeout' => 60]);

// Timeout süresini al
echo "Timeout: " . $zapi->getTimeout() . " saniye";
```

### Bearer Token Yapılandırması

```php
// Bearer token ile kimlik doğrulama
$zapi = new ZAPI($apiKey, $appId, null, [
    'bearerToken' => 'your_jwt_token_here'
]);

// Bearer token'ı güncelle
$zapi->setBearerToken('new_jwt_token_here');
```

## 🛡️ Güvenlik Yapılandırması

### 1. API Anahtarı Güvenliği

```php
// Güvenli API anahtarı saklama
class SecureConfig {
    private static $apiKey;
    
    public static function getApiKey(): string {
        if (self::$apiKey === null) {
            // Güvenli bir yerden API anahtarını al
            self::$apiKey = self::loadFromSecureStorage();
        }
        return self::$apiKey;
    }
    
    private static function loadFromSecureStorage(): string {
        // Güvenli depolama yöntemi (veritabanı, şifreli dosya, vb.)
        return 'your_secure_api_key';
    }
}

// Güvenli kullanım
$zapi = new ZAPI(
    apiKey: SecureConfig::getApiKey(),
    appId: 'your_app_id'
);
```

### 2. HTTPS Zorunluluğu

```php
// HTTPS kontrolü
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    throw new Exception('HTTPS gerekli!');
}

$zapi = new ZAPI($apiKey, $appId);
```

### 3. Rate Limiting

```php
// Rate limiting kontrolü
class RateLimiter {
    private static $requests = [];
    
    public static function checkLimit(string $endpoint): bool {
        $key = $endpoint . '_' . date('Y-m-d-H-i');
        $count = self::$requests[$key] ?? 0;
        
        if ($count >= 100) { // 100 istek/dakika limit
            return false;
        }
        
        self::$requests[$key] = $count + 1;
        return true;
    }
}

// Rate limiting ile kullanım
if (RateLimiter::checkLimit('api_call')) {
    $response = $zapi->responses->create($data);
} else {
    throw new Exception('Rate limit aşıldı!');
}
```

## 🔍 Yapılandırma Doğrulama

### 1. Bağlantı Testi

```php
<?php
use ZAPI\ZAPI;

try {
    $zapi = new ZAPI($apiKey, $appId);
    
    // Bağlantı testi
    $health = $zapi->system->getHealth();
    
    if ($health['data']['status'] === 'ok') {
        echo "✅ API bağlantısı başarılı!";
    } else {
        echo "❌ API bağlantısı başarısız!";
    }
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage();
}
```

### 2. Yapılandırma Kontrolü

```php
<?php
use ZAPI\ZAPI;

// Yapılandırma kontrolü
function validateZAPIConfig(): array {
    $errors = [];
    
    // API anahtarı kontrolü
    if (empty($_ENV['ZAPI_API_KEY'])) {
        $errors[] = 'ZAPI_API_KEY tanımlanmamış';
    }
    
    // Uygulama ID kontrolü
    if (empty($_ENV['ZAPI_APP_ID'])) {
        $errors[] = 'ZAPI_APP_ID tanımlanmamış';
    }
    
    // PHP versiyonu kontrolü
    if (version_compare(PHP_VERSION, '8.2.0', '<')) {
        $errors[] = 'PHP 8.2+ gerekli';
    }
    
    // cURL kontrolü
    if (!extension_loaded('curl')) {
        $errors[] = 'cURL extension gerekli';
    }
    
    return $errors;
}

// Kontrolü çalıştır
$errors = validateZAPIConfig();
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "❌ $error\n";
    }
} else {
    echo "✅ Tüm yapılandırmalar doğru!";
}
```

## 📝 Yapılandırma Örnekleri

### Laravel Entegrasyonu

```php
// config/zapi.php
return [
    'api_key' => env('ZAPI_API_KEY'),
    'app_id' => env('ZAPI_APP_ID'),
    'base_url' => env('ZAPI_BASE_URL', 'https://dev.zulficoresystem.net'),
    'debug' => env('ZAPI_DEBUG', false),
    'timeout' => env('ZAPI_TIMEOUT', 30),
];

// app/Services/ZAPIService.php
<?php
namespace App\Services;

use ZAPI\ZAPI;

class ZAPIService {
    private ZAPI $zapi;
    
    public function __construct() {
        $this->zapi = new ZAPI(
            apiKey: config('zapi.api_key'),
            appId: config('zapi.app_id'),
            baseUrl: config('zapi.base_url'),
            options: [
                'debug' => config('zapi.debug'),
                'timeout' => config('zapi.timeout')
            ]
        );
    }
    
    public function getZAPI(): ZAPI {
        return $this->zapi;
    }
}
```

### Symfony Entegrasyonu

```yaml
# config/packages/zapi.yaml
parameters:
    zapi.api_key: '%env(ZAPI_API_KEY)%'
    zapi.app_id: '%env(ZAPI_APP_ID)%'
    zapi.base_url: '%env(default::ZAPI_BASE_URL)%'
    zapi.debug: '%env(bool::ZAPI_DEBUG)%'
    zapi.timeout: '%env(int::ZAPI_TIMEOUT)%'

services:
    ZAPI\ZAPI:
        arguments:
            $apiKey: '%zapi.api_key%'
            $appId: '%zapi.app_id%'
            $baseUrl: '%zapi.base_url%'
            $options:
                debug: '%zapi.debug%'
                timeout: '%zapi.timeout%'
```

## 🚨 Yaygın Hatalar ve Çözümleri

### 1. "API anahtarı boş olamaz" Hatası

```php
// ❌ Yanlış
$zapi = new ZAPI('', $appId);

// ✅ Doğru
$zapi = new ZAPI($apiKey, $appId);
```

### 2. "cURL extension bulunamadı" Hatası

```bash
# Ubuntu/Debian
sudo apt-get install php-curl

# CentOS/RHEL
sudo yum install php-curl

# Windows (XAMPP)
# php.ini dosyasında extension=curl satırının başındaki ; kaldırın
```

### 3. "SSL certificate verify failed" Hatası

```php
// Geçici çözüm (sadece geliştirme için)
$zapi = new ZAPI($apiKey, $appId, null, [
    'verify_ssl' => false // Üretimde kullanmayın!
]);
```

### 4. Timeout Hatası

```php
// Timeout süresini artır
$zapi = new ZAPI($apiKey, $appId, null, [
    'timeout' => 120 // 2 dakika
]);
```

## 📚 Sonraki Adımlar

Kurulum tamamlandıktan sonra:

1. [Hızlı Başlangıç Rehberi](Quick-Start-Guide) ile temel kullanımı öğrenin
2. [API Referansı](API-Reference) ile tüm endpoint'leri keşfedin
3. [Örnekler](Examples) ile pratik kullanım senaryolarını inceleyin

---

**Kurulum tamamlandı!** Artık ZAPI PHP SDK'yı kullanmaya başlayabilirsiniz. 🚀
