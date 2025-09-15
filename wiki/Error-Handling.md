# Hata Yönetimi ve Debugging

ZAPI PHP SDK'da hata yönetimi, debugging ve troubleshooting için kapsamlı rehber.

## 📋 İçindekiler

- [🛡️ Hata Türleri](#hata-türleri)
- [🔍 Hata Yakalama](#hata-yakalama)
- [🐛 Debugging](#debugging)
- [📊 Logging](#logging)
- [🚨 Hata Kodları](#hata-kodları)
- [🔧 Troubleshooting](#troubleshooting)

## 🛡️ Hata Türleri

### ZAPIException (Ana Hata Sınıfı)

```php
<?php
use ZAPI\Exceptions\ZAPIException;

try {
    $result = $zapi->auth->login(['email' => 'test@example.com', 'password' => 'wrong']);
} catch (ZAPIException $e) {
    echo "ZAPI Hatası: " . $e->getMessage();
    echo "HTTP Kodu: " . $e->getStatusCode();
    echo "Hata Detayları: " . json_encode($e->getErrorDetails());
}
```

### AuthenticationException (Kimlik Doğrulama Hatası)

```php
<?php
use ZAPI\Exceptions\AuthenticationException;

try {
    $result = $zapi->auth->login(['email' => 'test@example.com', 'password' => 'wrong']);
} catch (AuthenticationException $e) {
    echo "Kimlik doğrulama hatası: " . $e->getMessage();
    echo "HTTP Kodu: " . $e->getStatusCode();
}
```

### ValidationException (Doğrulama Hatası)

```php
<?php
use ZAPI\Exceptions\ValidationException;

try {
    $result = $zapi->auth->register(['email' => 'invalid-email', 'password' => '123']);
} catch (ValidationException $e) {
    echo "Doğrulama hatası: " . $e->getMessage();
    echo "Hata detayları: " . json_encode($e->getErrorDetails());
}
```

### RateLimitException (Rate Limit Hatası)

```php
<?php
use ZAPI\Exceptions\RateLimitException;

try {
    // Çok fazla istek gönder
    for ($i = 0; $i < 1000; $i++) {
        $result = $zapi->responses->create([...]);
    }
} catch (RateLimitException $e) {
    echo "Rate limit aşıldı: " . $e->getMessage();
    echo "Yeniden deneme süresi: " . $e->getRetryAfter() . " saniye";
}
```

### ServerException (Sunucu Hatası)

```php
<?php
use ZAPI\Exceptions\ServerException;

try {
    $result = $zapi->system->getHealth();
} catch (ServerException $e) {
    echo "Sunucu hatası: " . $e->getMessage();
    echo "HTTP Kodu: " . $e->getStatusCode();
}
```

## 🔍 Hata Yakalama

### Temel Hata Yakalama

```php
<?php
use ZAPI\ZAPI;
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;
use ZAPI\Exceptions\RateLimitException;
use ZAPI\Exceptions\ServerException;

class ErrorHandler {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function safeApiCall(callable $apiCall): array {
        try {
            $result = $apiCall();
            return ['success' => true, 'data' => $result];
            
        } catch (AuthenticationException $e) {
            return [
                'success' => false,
                'error' => 'Kimlik doğrulama hatası',
                'message' => $e->getMessage(),
                'code' => $e->getStatusCode()
            ];
            
        } catch (ValidationException $e) {
            return [
                'success' => false,
                'error' => 'Doğrulama hatası',
                'message' => $e->getMessage(),
                'details' => $e->getErrorDetails()
            ];
            
        } catch (RateLimitException $e) {
            return [
                'success' => false,
                'error' => 'Rate limit aşıldı',
                'message' => $e->getMessage(),
                'retry_after' => $e->getRetryAfter()
            ];
            
        } catch (ServerException $e) {
            return [
                'success' => false,
                'error' => 'Sunucu hatası',
                'message' => $e->getMessage(),
                'code' => $e->getStatusCode()
            ];
            
        } catch (ZAPIException $e) {
            return [
                'success' => false,
                'error' => 'ZAPI hatası',
                'message' => $e->getMessage(),
                'code' => $e->getStatusCode()
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Genel hata',
                'message' => $e->getMessage()
            ];
        }
    }
}

// Kullanım
$errorHandler = new ErrorHandler('your_api_key', 'your_app_id');

$result = $errorHandler->safeApiCall(function() use ($errorHandler) {
    return $errorHandler->zapi->auth->login([
        'email' => 'test@example.com',
        'password' => 'wrong'
    ]);
});

if (!$result['success']) {
    echo "❌ " . $result['error'] . ": " . $result['message'];
}
```

### Gelişmiş Hata Yakalama

```php
<?php
use ZAPI\ZAPI;

class AdvancedErrorHandler {
    private ZAPI $zapi;
    private array $errorLog = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function handleApiRequest(string $endpoint, array $data = []): array {
        $startTime = microtime(true);
        
        try {
            $result = $this->callApiEndpoint($endpoint, $data);
            
            $this->logSuccess($endpoint, microtime(true) - $startTime);
            
            return [
                'success' => true,
                'data' => $result,
                'execution_time' => microtime(true) - $startTime
            ];
            
        } catch (Exception $e) {
            $this->logError($endpoint, $e, microtime(true) - $startTime);
            
            return $this->formatErrorResponse($e);
        }
    }
    
    private function callApiEndpoint(string $endpoint, array $data) {
        switch ($endpoint) {
            case 'auth/login':
                return $this->zapi->auth->login($data);
            case 'auth/register':
                return $this->zapi->auth->register($data);
            case 'responses/create':
                return $this->zapi->responses->create($data);
            default:
                throw new Exception("Geçersiz endpoint: $endpoint");
        }
    }
    
    private function formatErrorResponse(Exception $e): array {
        $response = [
            'success' => false,
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        if ($e instanceof ZAPIException) {
            $response['http_code'] = $e->getStatusCode();
            $response['error_details'] = $e->getErrorDetails();
        }
        
        if ($e instanceof RateLimitException) {
            $response['retry_after'] = $e->getRetryAfter();
        }
        
        return $response;
    }
    
    private function logSuccess(string $endpoint, float $executionTime): void {
        $this->errorLog[] = [
            'type' => 'success',
            'endpoint' => $endpoint,
            'execution_time' => $executionTime,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    private function logError(string $endpoint, Exception $e, float $executionTime): void {
        $this->errorLog[] = [
            'type' => 'error',
            'endpoint' => $endpoint,
            'error' => get_class($e),
            'message' => $e->getMessage(),
            'execution_time' => $executionTime,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    public function getErrorLog(): array {
        return $this->errorLog;
    }
    
    public function getErrorStats(): array {
        $stats = [
            'total_requests' => count($this->errorLog),
            'successful_requests' => 0,
            'failed_requests' => 0,
            'average_execution_time' => 0
        ];
        
        $totalTime = 0;
        
        foreach ($this->errorLog as $log) {
            if ($log['type'] === 'success') {
                $stats['successful_requests']++;
            } else {
                $stats['failed_requests']++;
            }
            
            $totalTime += $log['execution_time'];
        }
        
        if ($stats['total_requests'] > 0) {
            $stats['average_execution_time'] = $totalTime / $stats['total_requests'];
        }
        
        return $stats;
    }
}

// Kullanım
$errorHandler = new AdvancedErrorHandler('your_api_key', 'your_app_id');

// API istekleri
$loginResult = $errorHandler->handleApiRequest('auth/login', [
    'email' => 'test@example.com',
    'password' => 'wrong'
]);

$registerResult = $errorHandler->handleApiRequest('auth/register', [
    'email' => 'new@example.com',
    'password' => 'password123',
    'name' => 'New User'
]);

// Hata istatistikleri
$stats = $errorHandler->getErrorStats();
echo "Toplam istek: " . $stats['total_requests'] . "\n";
echo "Başarılı: " . $stats['successful_requests'] . "\n";
echo "Başarısız: " . $stats['failed_requests'] . "\n";
echo "Ortalama süre: " . round($stats['average_execution_time'], 3) . " saniye\n";
```

## 🐛 Debugging

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
    echo "🔍 Debug modu aktif\n";
    
    // Debug bilgilerini al
    $debugInfo = $zapi->getDebugInfo();
    echo "Debug bilgileri: " . json_encode($debugInfo, JSON_PRETTY_PRINT);
}
```

### Detaylı Debugging

```php
<?php
use ZAPI\ZAPI;

class Debugger {
    private ZAPI $zapi;
    private array $debugLog = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId, null, ['debug' => true]);
    }
    
    public function debugApiCall(string $method, array $data = []): array {
        $debugInfo = [
            'method' => $method,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s'),
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ];
        
        $startTime = microtime(true);
        
        try {
            $result = $this->callMethod($method, $data);
            
            $debugInfo['success'] = true;
            $debugInfo['execution_time'] = microtime(true) - $startTime;
            $debugInfo['result_size'] = strlen(json_encode($result));
            
            $this->debugLog[] = $debugInfo;
            
            return [
                'success' => true,
                'data' => $result,
                'debug' => $debugInfo
            ];
            
        } catch (Exception $e) {
            $debugInfo['success'] = false;
            $debugInfo['error'] = $e->getMessage();
            $debugInfo['execution_time'] = microtime(true) - $startTime;
            
            $this->debugLog[] = $debugInfo;
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'debug' => $debugInfo
            ];
        }
    }
    
    private function callMethod(string $method, array $data) {
        $parts = explode('.', $method);
        
        if (count($parts) !== 2) {
            throw new Exception("Geçersiz method formatı: $method");
        }
        
        [$class, $methodName] = $parts;
        
        if (!property_exists($this->zapi, $class)) {
            throw new Exception("Geçersiz sınıf: $class");
        }
        
        $classInstance = $this->zapi->$class;
        
        if (!method_exists($classInstance, $methodName)) {
            throw new Exception("Geçersiz method: $methodName");
        }
        
        return $classInstance->$methodName($data);
    }
    
    public function getDebugLog(): array {
        return $this->debugLog;
    }
    
    public function exportDebugLog(string $filename): bool {
        $logData = [
            'exported_at' => date('Y-m-d H:i:s'),
            'total_calls' => count($this->debugLog),
            'calls' => $this->debugLog
        ];
        
        return file_put_contents($filename, json_encode($logData, JSON_PRETTY_PRINT)) !== false;
    }
}

// Kullanım
$debugger = new Debugger('your_api_key', 'your_app_id');

// Debug ile API çağrısı
$result = $debugger->debugApiCall('auth.login', [
    'email' => 'test@example.com',
    'password' => 'password123'
]);

if ($result['success']) {
    echo "✅ API çağrısı başarılı\n";
    echo "⏱️ Süre: " . $result['debug']['execution_time'] . " saniye\n";
    echo "💾 Bellek: " . $result['debug']['memory_usage'] . " bytes\n";
} else {
    echo "❌ API çağrısı başarısız: " . $result['error'] . "\n";
}

// Debug log'u dışa aktar
$debugger->exportDebugLog('debug_log.json');
```

## 📊 Logging

### Basit Logging

```php
<?php
use ZAPI\ZAPI;

class SimpleLogger {
    private ZAPI $zapi;
    private string $logFile;
    
    public function __construct(string $apiKey, string $appId, string $logFile = 'zapi.log') {
        $this->zapi = new ZAPI($apiKey, $appId);
        $this->logFile = $logFile;
    }
    
    public function log(string $level, string $message, array $context = []): void {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context
        ];
        
        $logLine = json_encode($logEntry) . "\n";
        file_put_contents($this->logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    public function info(string $message, array $context = []): void {
        $this->log('INFO', $message, $context);
    }
    
    public function warning(string $message, array $context = []): void {
        $this->log('WARNING', $message, $context);
    }
    
    public function error(string $message, array $context = []): void {
        $this->log('ERROR', $message, $context);
    }
    
    public function debug(string $message, array $context = []): void {
        $this->log('DEBUG', $message, $context);
    }
}

// Kullanım
$logger = new SimpleLogger('your_api_key', 'your_app_id');

$logger->info('API çağrısı başlatıldı', ['endpoint' => 'auth.login']);

try {
    $result = $zapi->auth->login(['email' => 'test@example.com', 'password' => 'password123']);
    $logger->info('API çağrısı başarılı', ['endpoint' => 'auth.login']);
} catch (Exception $e) {
    $logger->error('API çağrısı başarısız', [
        'endpoint' => 'auth.login',
        'error' => $e->getMessage()
    ]);
}
```

### Gelişmiş Logging

```php
<?php
use ZAPI\ZAPI;

class AdvancedLogger {
    private ZAPI $zapi;
    private string $logDir;
    private array $logLevels = ['DEBUG', 'INFO', 'WARNING', 'ERROR'];
    private string $currentLevel = 'INFO';
    
    public function __construct(string $apiKey, string $appId, string $logDir = 'logs') {
        $this->zapi = new ZAPI($apiKey, $appId);
        $this->logDir = $logDir;
        
        if (!is_dir($this->logDir)) {
            mkdir($this->logDir, 0755, true);
        }
    }
    
    public function setLogLevel(string $level): void {
        if (in_array($level, $this->logLevels)) {
            $this->currentLevel = $level;
        }
    }
    
    public function log(string $level, string $message, array $context = []): void {
        if (!$this->shouldLog($level)) {
            return;
        }
        
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true)
        ];
        
        $logFile = $this->logDir . '/' . date('Y-m-d') . '.log';
        $logLine = json_encode($logEntry) . "\n";
        
        file_put_contents($logFile, $logLine, FILE_APPEND | LOCK_EX);
    }
    
    private function shouldLog(string $level): bool {
        $levelIndex = array_search($level, $this->logLevels);
        $currentLevelIndex = array_search($this->currentLevel, $this->logLevels);
        
        return $levelIndex >= $currentLevelIndex;
    }
    
    public function info(string $message, array $context = []): void {
        $this->log('INFO', $message, $context);
    }
    
    public function warning(string $message, array $context = []): void {
        $this->log('WARNING', $message, $context);
    }
    
    public function error(string $message, array $context = []): void {
        $this->log('ERROR', $message, $context);
    }
    
    public function debug(string $message, array $context = []): void {
        $this->log('DEBUG', $message, $context);
    }
    
    public function getLogFiles(): array {
        $files = glob($this->logDir . '/*.log');
        return array_map('basename', $files);
    }
    
    public function getLogContent(string $date = null): array {
        $date = $date ?? date('Y-m-d');
        $logFile = $this->logDir . '/' . $date . '.log';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $content = file_get_contents($logFile);
        $lines = explode("\n", trim($content));
        
        $logs = [];
        foreach ($lines as $line) {
            if (!empty($line)) {
                $logs[] = json_decode($line, true);
            }
        }
        
        return $logs;
    }
}

// Kullanım
$logger = new AdvancedLogger('your_api_key', 'your_app_id');
$logger->setLogLevel('DEBUG');

$logger->debug('Debug mesajı', ['test' => 'data']);
$logger->info('Bilgi mesajı', ['user' => 'john']);
$logger->warning('Uyarı mesajı', ['issue' => 'rate_limit']);
$logger->error('Hata mesajı', ['error' => 'connection_failed']);

// Log dosyalarını listele
$logFiles = $logger->getLogFiles();
echo "Log dosyaları: " . implode(', ', $logFiles) . "\n";

// Log içeriğini al
$logs = $logger->getLogContent();
echo "Toplam log kaydı: " . count($logs) . "\n";
```

## 🚨 Hata Kodları

### HTTP Hata Kodları

| Kod | Açıklama | Çözüm |
|-----|----------|-------|
| 400 | Geçersiz istek | İstek parametrelerini kontrol edin |
| 401 | Kimlik doğrulama gerekli | API anahtarı veya token kontrolü |
| 403 | Yetki yok | Kullanıcı yetkilerini kontrol edin |
| 404 | Kaynak bulunamadı | Endpoint URL'ini kontrol edin |
| 429 | Rate limit aşıldı | İstek sıklığını azaltın |
| 500 | Sunucu hatası | ZAPI ekibi ile iletişime geçin |
| 502 | Bad Gateway | Sunucu bağlantısını kontrol edin |
| 503 | Servis kullanılamıyor | Sunucu bakımda, tekrar deneyin |

### ZAPI Hata Kodları

```php
<?php
class ZAPIErrorCodes {
    const INVALID_API_KEY = 'INVALID_API_KEY';
    const INVALID_APP_ID = 'INVALID_APP_ID';
    const INVALID_TOKEN = 'INVALID_TOKEN';
    const RATE_LIMIT_EXCEEDED = 'RATE_LIMIT_EXCEEDED';
    const INSUFFICIENT_CREDITS = 'INSUFFICIENT_CREDITS';
    const MODEL_NOT_AVAILABLE = 'MODEL_NOT_AVAILABLE';
    const FILE_TOO_LARGE = 'FILE_TOO_LARGE';
    const INVALID_FILE_TYPE = 'INVALID_FILE_TYPE';
    const USER_NOT_FOUND = 'USER_NOT_FOUND';
    const EMAIL_ALREADY_EXISTS = 'EMAIL_ALREADY_EXISTS';
    
    public static function getErrorMessage(string $code): string {
        $messages = [
            self::INVALID_API_KEY => 'Geçersiz API anahtarı',
            self::INVALID_APP_ID => 'Geçersiz uygulama ID',
            self::INVALID_TOKEN => 'Geçersiz token',
            self::RATE_LIMIT_EXCEEDED => 'Rate limit aşıldı',
            self::INSUFFICIENT_CREDITS => 'Yetersiz kredi',
            self::MODEL_NOT_AVAILABLE => 'Model kullanılamıyor',
            self::FILE_TOO_LARGE => 'Dosya çok büyük',
            self::INVALID_FILE_TYPE => 'Geçersiz dosya tipi',
            self::USER_NOT_FOUND => 'Kullanıcı bulunamadı',
            self::EMAIL_ALREADY_EXISTS => 'E-posta zaten kayıtlı'
        ];
        
        return $messages[$code] ?? 'Bilinmeyen hata';
    }
}

// Kullanım
$errorCode = 'INVALID_API_KEY';
$errorMessage = ZAPIErrorCodes::getErrorMessage($errorCode);
echo "Hata: $errorMessage";
```

## 🔧 Troubleshooting

### Yaygın Sorunlar ve Çözümleri

#### 1. "API anahtarı boş olamaz" Hatası

```php
// ❌ Yanlış
$zapi = new ZAPI('', $appId);

// ✅ Doğru
$zapi = new ZAPI($apiKey, $appId);
```

#### 2. "cURL extension bulunamadı" Hatası

```bash
# Ubuntu/Debian
sudo apt-get install php-curl

# CentOS/RHEL
sudo yum install php-curl

# Windows (XAMPP)
# php.ini dosyasında extension=curl satırının başındaki ; kaldırın
```

#### 3. "SSL certificate verify failed" Hatası

```php
// Geçici çözüm (sadece geliştirme için)
$zapi = new ZAPI($apiKey, $appId, null, [
    'verify_ssl' => false // Üretimde kullanmayın!
]);
```

#### 4. Timeout Hatası

```php
// Timeout süresini artır
$zapi = new ZAPI($apiKey, $appId, null, [
    'timeout' => 120 // 2 dakika
]);
```

#### 5. Memory Limit Hatası

```php
// Memory limit'i artır
ini_set('memory_limit', '256M');

$zapi = new ZAPI($apiKey, $appId);
```

### Sistem Kontrolü

```php
<?php
class SystemChecker {
    public static function checkRequirements(): array {
        $checks = [];
        
        // PHP versiyonu
        $checks['php_version'] = [
            'required' => '8.2.0',
            'current' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '8.2.0', '>=')
        ];
        
        // cURL extension
        $checks['curl'] = [
            'required' => true,
            'current' => extension_loaded('curl'),
            'status' => extension_loaded('curl')
        ];
        
        // JSON extension
        $checks['json'] = [
            'required' => true,
            'current' => extension_loaded('json'),
            'status' => extension_loaded('json')
        ];
        
        // OpenSSL extension
        $checks['openssl'] = [
            'required' => true,
            'current' => extension_loaded('openssl'),
            'status' => extension_loaded('openssl')
        ];
        
        // Memory limit
        $memoryLimit = ini_get('memory_limit');
        $checks['memory_limit'] = [
            'required' => '128M',
            'current' => $memoryLimit,
            'status' => self::parseMemoryLimit($memoryLimit) >= 128 * 1024 * 1024
        ];
        
        return $checks;
    }
    
    private static function parseMemoryLimit(string $limit): int {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $limit = (int) $limit;
        
        switch ($last) {
            case 'g':
                $limit *= 1024;
            case 'm':
                $limit *= 1024;
            case 'k':
                $limit *= 1024;
        }
        
        return $limit;
    }
    
    public static function printReport(): void {
        $checks = self::checkRequirements();
        
        echo "🔍 Sistem Kontrol Raporu\n";
        echo "========================\n\n";
        
        foreach ($checks as $name => $check) {
            $status = $check['status'] ? '✅' : '❌';
            echo "$status $name: {$check['current']} (Gerekli: {$check['required']})\n";
        }
        
        $allPassed = array_reduce($checks, function($carry, $check) {
            return $carry && $check['status'];
        }, true);
        
        echo "\n" . ($allPassed ? '✅ Tüm gereksinimler karşılanıyor' : '❌ Bazı gereksinimler karşılanmıyor') . "\n";
    }
}

// Kullanım
SystemChecker::printReport();
```

---

**Hata yönetimi ve debugging rehberi tamamlandı!** Artık güçlü hata yönetimi ve debugging teknikleri kullanabilirsiniz. 🚀
