# FAQ ve Troubleshooting

ZAPI PHP SDK ile ilgili sık sorulan sorular ve sorun giderme rehberi.

## 📋 İçindekiler

- [❓ Sık Sorulan Sorular](#sık-sorulan-sorular)
- [🔧 Kurulum Sorunları](#kurulum-sorunları)
- [🚨 API Hataları](#api-hataları)
- [⚡ Performans Sorunları](#performans-sorunları)
- [🛡️ Güvenlik Sorunları](#güvenlik-sorunları)
- [📱 Platform Sorunları](#platform-sorunları)

## ❓ Sık Sorulan Sorular

### Genel Sorular

**S: ZAPI PHP SDK nedir?**
C: ZAPI PHP SDK, ZAPI servislerinin tüm özelliklerini kullanmanızı sağlayan kapsamlı bir PHP kütüphanesidir. Kimlik doğrulama, AI sohbet, gerçek zamanlı iletişim, dosya yükleme ve daha fazlası için hazır çözümler sunar.

**S: Hangi PHP versiyonları destekleniyor?**
C: ZAPI PHP SDK, PHP 8.2 ve üzeri versiyonları destekler. PHP 8.3 ve 8.4 ile tam uyumludur.

**S: Composer olmadan kullanabilir miyim?**
C: Evet, manuel kurulum yapabilirsiniz. Ancak Composer kullanmanızı şiddetle tavsiye ederiz çünkü bağımlılık yönetimi ve otomatik güncellemeler için gereklidir.

**S: Ücretsiz mi?**
C: ZAPI PHP SDK açık kaynaklıdır ve MIT lisansı altında ücretsizdir. Ancak ZAPI API servislerini kullanmak için ücretli plan gerekebilir.

### API ve Kimlik Doğrulama

**S: API anahtarı ve Bearer token arasındaki fark nedir?**
C: 
- **API Anahtarı**: SDK'yı başlatmak için kullanılır (`x-api-key` header)
- **Bearer Token**: Kullanıcı kimlik doğrulaması için kullanılır (`Authorization: Bearer` header)

```php
// API anahtarı ile SDK başlat
$zapi = new ZAPI('your_api_key', 'your_app_id');

// Bearer token ile kimlik doğrula
$zapi->setBearerToken('your_bearer_token');
```

**S: API anahtarımı nasıl alırım?**
C: ZAPI dashboard'a giriş yapın ve "API Keys" bölümünden yeni bir API anahtarı oluşturun.

**S: Bearer token nasıl alırım?**
C: Kullanıcı girişi yaptıktan sonra API'den dönen token'ı kullanın:

```php
$loginResult = $zapi->auth->login([
    'email' => 'user@example.com',
    'password' => 'password123'
]);

$bearerToken = $loginResult['data']['token'];
$zapi->setBearerToken($bearerToken);
```

### AI ve Sohbet

**S: Hangi AI modelleri destekleniyor?**
C: GPT-4, GPT-3.5, Claude, Gemini ve daha fazlası desteklenir. Mevcut modelleri kontrol etmek için:

```php
$models = $zapi->info->getAIModels();
print_r($models['data']['models']);
```

**S: AI yanıtları nasıl özelleştirilir?**
C: Temperature, max_tokens ve diğer parametrelerle özelleştirebilirsiniz:

```php
$response = $zapi->responses->create([
    'model' => 'gpt-4',
    'messages' => [['role' => 'user', 'content' => 'Merhaba!']],
    'temperature' => 0.7,
    'max_tokens' => 1000
]);
```

**S: Stream yanıtlar nasıl kullanılır?**
C: Uzun yanıtlar için stream modunu kullanın:

```php
$streamResponse = $zapi->responses->createStream([
    'model' => 'gpt-4',
    'messages' => [['role' => 'user', 'content' => 'Uzun bir hikaye yaz']]
]);
```

### Dosya Yükleme

**S: Hangi dosya tipleri destekleniyor?**
C: 
- **Resimler**: JPG, PNG, GIF, WebP
- **Belgeler**: PDF, DOC, DOCX, TXT
- **Ses**: MP3, WAV, OGG
- **Video**: MP4, AVI, MOV

**S: Maksimum dosya boyutu nedir?**
C: Varsayılan olarak 10MB'dir. Daha büyük dosyalar için özel yapılandırma gerekebilir.

**S: Dosya yükleme ilerlemesi nasıl takip edilir?**
C: Upload progress endpoint'ini kullanın:

```php
$progress = $zapi->upload->getProgress('file_id');
echo "İlerleme: " . $progress['data']['percentage'] . "%";
```

## 🔧 Kurulum Sorunları

### Composer Kurulum Hataları

**Hata: "Could not find package zulficore/zapi-php-sdk"**

```bash
# Çözüm 1: Composer cache'i temizle
composer clear-cache

# Çözüm 2: Packagist'i güncelle
composer update

# Çözüm 3: Manuel kurulum
git clone https://github.com/Zulficore/zapi-php-sdk.git
cd zapi-php-sdk
composer install
```

**Hata: "Your requirements could not be resolved"**

```bash
# PHP versiyonunu kontrol et
php -v

# Minimum gereksinimler
# PHP 8.2+, cURL, JSON, OpenSSL extensions
```

### Extension Hataları

**Hata: "cURL extension not found"**

```bash
# Ubuntu/Debian
sudo apt-get install php-curl

# CentOS/RHEL
sudo yum install php-curl

# Windows (XAMPP)
# php.ini dosyasında extension=curl satırının başındaki ; kaldırın
```

**Hata: "OpenSSL extension not found"**

```bash
# Ubuntu/Debian
sudo apt-get install php-openssl

# CentOS/RHEL
sudo yum install php-openssl

# Windows (XAMPP)
# php.ini dosyasında extension=openssl satırının başındaki ; kaldırın
```

### Autoload Sorunları

**Hata: "Class 'ZAPI\ZAPI' not found"**

```php
// Çözüm: Autoload'u kontrol et
require_once 'vendor/autoload.php';

// Veya manuel include
require_once 'src/ZAPI.php';
```

## 🚨 API Hataları

### Kimlik Doğrulama Hataları

**Hata: "API anahtarı boş olamaz"**

```php
// ❌ Yanlış
$zapi = new ZAPI('', $appId);

// ✅ Doğru
$zapi = new ZAPI($apiKey, $appId);
```

**Hata: "Invalid API key"**

```php
// API anahtarını kontrol et
if (empty($apiKey) || strlen($apiKey) < 20) {
    throw new Exception('Geçersiz API anahtarı');
}

// API anahtarını doğrula
$health = $zapi->system->getHealth();
if (!$health['success']) {
    throw new Exception('API anahtarı geçersiz');
}
```

**Hata: "Invalid Bearer token"**

```php
// Token'ı kontrol et
if (empty($bearerToken)) {
    throw new Exception('Bearer token gerekli');
}

// Token'ı yenile
$loginResult = $zapi->auth->login([
    'email' => $email,
    'password' => $password
]);
$zapi->setBearerToken($loginResult['data']['token']);
```

### Rate Limit Hataları

**Hata: "Rate limit exceeded"**

```php
use ZAPI\Exceptions\RateLimitException;

try {
    $result = $zapi->responses->create($data);
} catch (RateLimitException $e) {
    $retryAfter = $e->getRetryAfter();
    echo "Rate limit aşıldı. $retryAfter saniye sonra tekrar deneyin.";
    
    // Bekleme
    sleep($retryAfter);
    
    // Tekrar dene
    $result = $zapi->responses->create($data);
}
```

### Validation Hataları

**Hata: "Validation failed"**

```php
use ZAPI\Exceptions\ValidationException;

try {
    $result = $zapi->auth->register($data);
} catch (ValidationException $e) {
    $errors = $e->getErrorDetails();
    
    foreach ($errors as $field => $message) {
        echo "$field: $message\n";
    }
}
```

## ⚡ Performans Sorunları

### Yavaş API Yanıtları

**Sorun: API çağrıları çok yavaş**

```php
// Çözüm 1: Timeout'u artır
$zapi = new ZAPI($apiKey, $appId, null, [
    'timeout' => 120 // 2 dakika
]);

// Çözüm 2: Connection pooling kullan
class ZAPIConnectionPool {
    private array $connections = [];
    
    public function getConnection(string $apiKey, string $appId): ZAPI {
        $key = md5($apiKey . $appId);
        
        if (!isset($this->connections[$key])) {
            $this->connections[$key] = new ZAPI($apiKey, $appId);
        }
        
        return $this->connections[$key];
    }
}

// Çözüm 3: Caching kullan
$cache = new ZAPICache($zapi);
$result = $cache->get('cache_key', function() use ($zapi) {
    return $zapi->responses->create($data);
}, 3600); // 1 saat cache
```

### Memory Limit Hataları

**Hata: "Fatal error: Allowed memory size exhausted"**

```php
// Çözüm 1: Memory limit'i artır
ini_set('memory_limit', '256M');

// Çözüm 2: Büyük verileri parçalara böl
function processLargeData(array $data, int $chunkSize = 100): void {
    $chunks = array_chunk($data, $chunkSize);
    
    foreach ($chunks as $chunk) {
        // Her chunk'ı işle
        processChunk($chunk);
        
        // Memory'yi temizle
        unset($chunk);
        gc_collect_cycles();
    }
}

// Çözüm 3: Stream processing kullan
$streamResponse = $zapi->responses->createStream($data);
```

### Timeout Hataları

**Hata: "Request timeout"**

```php
// Çözüm 1: Timeout'u artır
$zapi = new ZAPI($apiKey, $appId, null, [
    'timeout' => 300 // 5 dakika
]);

// Çözüm 2: Retry mekanizması
function retryRequest(callable $request, int $maxRetries = 3): mixed {
    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return $request();
        } catch (Exception $e) {
            if ($i === $maxRetries - 1) {
                throw $e;
            }
            
            sleep(pow(2, $i)); // Exponential backoff
        }
    }
}

// Kullanım
$result = retryRequest(function() use ($zapi, $data) {
    return $zapi->responses->create($data);
});
```

## 🛡️ Güvenlik Sorunları

### API Anahtarı Güvenliği

**Sorun: API anahtarı kodda hardcode edilmiş**

```php
// ❌ Yanlış
$zapi = new ZAPI('hardcoded_api_key', $appId);

// ✅ Doğru
$apiKey = $_ENV['ZAPI_API_KEY'] ?? getenv('ZAPI_API_KEY');
$zapi = new ZAPI($apiKey, $appId);

// ✅ Daha güvenli
class SecureConfig {
    private static $apiKey;
    
    public static function getApiKey(): string {
        if (self::$apiKey === null) {
            self::$apiKey = self::loadFromSecureStorage();
        }
        return self::$apiKey;
    }
    
    private static function loadFromSecureStorage(): string {
        // Güvenli depolama yöntemi
        return 'your_secure_api_key';
    }
}

$zapi = new ZAPI(SecureConfig::getApiKey(), $appId);
```

### Token Güvenliği

**Sorun: Bearer token güvenli olmayan şekilde saklanıyor**

```php
// ❌ Yanlış - Session'da saklama
$_SESSION['bearer_token'] = $token;

// ✅ Doğru - Şifreli saklama
class TokenStorage {
    private string $encryptionKey;
    
    public function __construct(string $encryptionKey) {
        $this->encryptionKey = $encryptionKey;
    }
    
    public function storeToken(string $userId, string $token): void {
        $encrypted = $this->encrypt($token);
        // Güvenli veritabanında sakla
        $this->saveToDatabase($userId, $encrypted);
    }
    
    public function getToken(string $userId): ?string {
        $encrypted = $this->loadFromDatabase($userId);
        if (!$encrypted) {
            return null;
        }
        
        return $this->decrypt($encrypted);
    }
    
    private function encrypt(string $data): string {
        return openssl_encrypt($data, 'AES-256-CBC', $this->encryptionKey);
    }
    
    private function decrypt(string $data): string {
        return openssl_decrypt($data, 'AES-256-CBC', $this->encryptionKey);
    }
}
```

### Input Validation

**Sorun: Kullanıcı girdileri doğrulanmıyor**

```php
// ❌ Yanlış
$result = $zapi->auth->login($_POST);

// ✅ Doğru
class InputValidator {
    public static function validateLoginData(array $data): array {
        $errors = [];
        
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Geçersiz e-posta adresi';
        }
        
        if (empty($data['password']) || strlen($data['password']) < 8) {
            $errors['password'] = 'Şifre en az 8 karakter olmalı';
        }
        
        return $errors;
    }
}

$errors = InputValidator::validateLoginData($_POST);
if (!empty($errors)) {
    throw new ValidationException('Geçersiz girdi', $errors);
}

$result = $zapi->auth->login($_POST);
```

## 📱 Platform Sorunları

### Windows Sorunları

**Sorun: PowerShell'de && operatörü çalışmıyor**

```bash
# ❌ Yanlış
cd zapi-php-sdk && php test.php

# ✅ Doğru
cd zapi-php-sdk; php test.php

# Veya
cd zapi-php-sdk
php test.php
```

**Sorun: Path sorunları**

```php
// Windows path'leri için
$filePath = str_replace('/', DIRECTORY_SEPARATOR, $filePath);

// Veya
$filePath = realpath($filePath);
```

### Linux/Mac Sorunları

**Sorun: Permission denied**

```bash
# Çözüm
chmod +x script.php
sudo chown -R www-data:www-data /path/to/project
```

**Sorun: Composer global kurulum**

```bash
# Composer'ı global olarak kur
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Docker Sorunları

**Sorun: Container içinde API erişimi**

```dockerfile
# Dockerfile
FROM php:8.3-cli

# Gerekli extension'ları kur
RUN docker-php-ext-install curl json openssl

# Composer'ı kur
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Projeyi kopyala
COPY . /app
WORKDIR /app

# Bağımlılıkları kur
RUN composer install
```

```yaml
# docker-compose.yml
version: '3.8'
services:
  app:
    build: .
    environment:
      - ZAPI_API_KEY=${ZAPI_API_KEY}
      - ZAPI_APP_ID=${ZAPI_APP_ID}
    volumes:
      - .:/app
```

## 🔍 Debugging Araçları

### Debug Modu

```php
// Debug modu ile başlat
$zapi = new ZAPI($apiKey, $appId, null, ['debug' => true]);

// Debug bilgilerini al
if ($zapi->isDebugMode()) {
    $debugInfo = $zapi->getDebugInfo();
    echo "Debug bilgileri: " . json_encode($debugInfo, JSON_PRETTY_PRINT);
}
```

### Logging

```php
// Detaylı logging
class ZAPILogger {
    public function logRequest(string $method, array $data): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'data' => $data,
            'memory_usage' => memory_get_usage(true)
        ];
        
        error_log('ZAPI Request: ' . json_encode($logData));
    }
    
    public function logResponse(string $method, array $response): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'success' => true,
            'response_size' => strlen(json_encode($response))
        ];
        
        error_log('ZAPI Response: ' . json_encode($logData));
    }
    
    public function logError(string $method, Exception $e): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
        
        error_log('ZAPI Error: ' . json_encode($logData));
    }
}
```

### Performance Profiling

```php
class ZAPIProfiler {
    private array $profiles = [];
    
    public function startProfile(string $name): void {
        $this->profiles[$name] = [
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true)
        ];
    }
    
    public function endProfile(string $name): array {
        if (!isset($this->profiles[$name])) {
            throw new Exception("Profile '$name' bulunamadı");
        }
        
        $profile = $this->profiles[$name];
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        
        $result = [
            'name' => $name,
            'execution_time' => $endTime - $profile['start_time'],
            'memory_usage' => $endMemory - $profile['start_memory'],
            'peak_memory' => memory_get_peak_usage(true)
        ];
        
        unset($this->profiles[$name]);
        return $result;
    }
    
    public function getProfiles(): array {
        return $this->profiles;
    }
}

// Kullanım
$profiler = new ZAPIProfiler();

$profiler->startProfile('api_call');
$result = $zapi->responses->create($data);
$profile = $profiler->endProfile('api_call');

echo "Süre: " . round($profile['execution_time'], 3) . " saniye\n";
echo "Bellek: " . round($profile['memory_usage'] / 1024 / 1024, 2) . " MB\n";
```

## 📞 Destek

### Yardım Alma

1. **GitHub Issues**: [Issues](https://github.com/Zulficore/zapi-php-sdk/issues)
2. **E-posta**: dev@zapi.com
3. **Dokümantasyon**: [Wiki](https://github.com/Zulficore/zapi-php-sdk/wiki)

### Hata Bildirimi

Hata bildirirken şu bilgileri ekleyin:

```php
// Sistem bilgileri
echo "PHP Versiyonu: " . PHP_VERSION . "\n";
echo "OS: " . PHP_OS . "\n";
echo "cURL: " . (extension_loaded('curl') ? 'Var' : 'Yok') . "\n";
echo "OpenSSL: " . (extension_loaded('openssl') ? 'Var' : 'Yok') . "\n";

// SDK bilgileri
echo "SDK Versiyonu: " . \ZAPI\ZAPI::getVersion() . "\n";

// Hata detayları
echo "Hata: " . $e->getMessage() . "\n";
echo "Dosya: " . $e->getFile() . ":" . $e->getLine() . "\n";
echo "Stack Trace: " . $e->getTraceAsString() . "\n";
```

---

**FAQ ve troubleshooting rehberi tamamlandı!** Artık karşılaştığınız sorunları kolayca çözebilirsiniz. 🚀
