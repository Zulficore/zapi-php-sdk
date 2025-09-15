# Gelişmiş Kullanım ve Best Practices

ZAPI PHP SDK'nın gelişmiş özelliklerini ve en iyi uygulamalarını öğrenin.

## 📋 İçindekiler

- [🏗️ Mimari Desenler](#mimari-desenler)
- [⚡ Performans Optimizasyonu](#performans-optimizasyonu)
- [🔄 Caching Stratejileri](#caching-stratejileri)
- [🛡️ Güvenlik Best Practices](#güvenlik-best-practices)
- [📊 Monitoring ve Analytics](#monitoring-ve-analytics)
- [🔧 Custom Middleware](#custom-middleware)
- [🌐 Multi-tenant Uygulamalar](#multi-tenant-uygulamalar)

## 🏗️ Mimari Desenler

### Repository Pattern

```php
<?php
use ZAPI\ZAPI;

interface UserRepositoryInterface {
    public function findById(string $id): ?array;
    public function findByEmail(string $email): ?array;
    public function create(array $data): array;
    public function update(string $id, array $data): array;
    public function delete(string $id): bool;
}

class ZAPIUserRepository implements UserRepositoryInterface {
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi) {
        $this->zapi = $zapi;
    }
    
    public function findById(string $id): ?array {
        try {
            $result = $this->zapi->user->getProfile();
            return $result['data'] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function findByEmail(string $email): ?array {
        try {
            // E-posta ile kullanıcı arama (API'de mevcut değilse)
            $result = $this->zapi->user->search(['email' => $email]);
            return $result['data'][0] ?? null;
        } catch (Exception $e) {
            return null;
        }
    }
    
    public function create(array $data): array {
        try {
            $result = $this->zapi->auth->register($data);
            return $result['data']['user'];
        } catch (Exception $e) {
            throw new Exception("Kullanıcı oluşturulamadı: " . $e->getMessage());
        }
    }
    
    public function update(string $id, array $data): array {
        try {
            $result = $this->zapi->user->updateProfile($data);
            return $result['data'];
        } catch (Exception $e) {
            throw new Exception("Kullanıcı güncellenemedi: " . $e->getMessage());
        }
    }
    
    public function delete(string $id): bool {
        try {
            $this->zapi->user->deleteProfile();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Kullanım
$zapi = new ZAPI('your_api_key', 'your_app_id');
$userRepository = new ZAPIUserRepository($zapi);

$user = $userRepository->findByEmail('user@example.com');
if ($user) {
    echo "Kullanıcı bulundu: " . $user['name'];
}
```

### Service Layer Pattern

```php
<?php
use ZAPI\ZAPI;

class UserService {
    private ZAPIUserRepository $userRepository;
    private ZAPI $zapi;
    
    public function __construct(ZAPIUserRepository $userRepository, ZAPI $zapi) {
        $this->userRepository = $userRepository;
        $this->zapi = $zapi;
    }
    
    public function registerUser(array $userData): array {
        // Validasyon
        $this->validateUserData($userData);
        
        // E-posta kontrolü
        if ($this->userRepository->findByEmail($userData['email'])) {
            throw new Exception('E-posta zaten kayıtlı');
        }
        
        // Kullanıcı oluştur
        $user = $this->userRepository->create($userData);
        
        // Hoş geldin e-postası gönder
        $this->sendWelcomeEmail($user);
        
        // Log
        $this->logUserRegistration($user);
        
        return $user;
    }
    
    public function authenticateUser(string $email, string $password): array {
        try {
            $result = $this->zapi->auth->login([
                'email' => $email,
                'password' => $password
            ]);
            
            $user = $result['data']['user'];
            $token = $result['data']['token'];
            
            // Token'ı güvenli bir şekilde sakla
            $this->storeUserToken($user['id'], $token);
            
            // Son giriş zamanını güncelle
            $this->updateLastLogin($user['id']);
            
            return [
                'user' => $user,
                'token' => $token
            ];
            
        } catch (Exception $e) {
            // Giriş denemesini logla
            $this->logFailedLogin($email);
            throw new Exception('Giriş başarısız: ' . $e->getMessage());
        }
    }
    
    private function validateUserData(array $data): void {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Geçersiz e-posta adresi');
        }
        
        if (empty($data['password']) || strlen($data['password']) < 8) {
            throw new Exception('Şifre en az 8 karakter olmalı');
        }
        
        if (empty($data['name'])) {
            throw new Exception('İsim gerekli');
        }
    }
    
    private function sendWelcomeEmail(array $user): void {
        try {
            $this->zapi->notifications->sendEmail([
                'to' => $user['email'],
                'subject' => 'Hoş Geldiniz!',
                'body' => "Merhaba {$user['name']}, ZAPI'ye hoş geldiniz!"
            ]);
        } catch (Exception $e) {
            // E-posta gönderilemezse logla ama hata fırlatma
            error_log("Hoş geldin e-postası gönderilemedi: " . $e->getMessage());
        }
    }
    
    private function logUserRegistration(array $user): void {
        error_log("Yeni kullanıcı kaydı: {$user['email']} - " . date('Y-m-d H:i:s'));
    }
    
    private function storeUserToken(string $userId, string $token): void {
        // Token'ı güvenli bir şekilde sakla (veritabanı, Redis, vb.)
        // Bu örnekte basit dosya saklama
        $tokenData = [
            'user_id' => $userId,
            'token' => $token,
            'created_at' => time()
        ];
        
        file_put_contents("tokens/{$userId}.json", json_encode($tokenData));
    }
    
    private function updateLastLogin(string $userId): void {
        // Son giriş zamanını güncelle
        $this->userRepository->update($userId, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
    
    private function logFailedLogin(string $email): void {
        error_log("Başarısız giriş denemesi: $email - " . date('Y-m-d H:i:s'));
    }
}

// Kullanım
$zapi = new ZAPI('your_api_key', 'your_app_id');
$userRepository = new ZAPIUserRepository($zapi);
$userService = new UserService($userRepository, $zapi);

try {
    $user = $userService->registerUser([
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'name' => 'New User'
    ]);
    
    echo "✅ Kullanıcı kaydedildi: " . $user['name'];
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage();
}
```

## ⚡ Performans Optimizasyonu

### Connection Pooling

```php
<?php
use ZAPI\ZAPI;

class ZAPIConnectionPool {
    private array $connections = [];
    private int $maxConnections = 10;
    private int $currentConnections = 0;
    
    public function getConnection(string $apiKey, string $appId): ZAPI {
        $key = md5($apiKey . $appId);
        
        if (!isset($this->connections[$key])) {
            if ($this->currentConnections >= $this->maxConnections) {
                $this->cleanupConnections();
            }
            
            $this->connections[$key] = new ZAPI($apiKey, $appId);
            $this->currentConnections++;
        }
        
        return $this->connections[$key];
    }
    
    private function cleanupConnections(): void {
        // Eski bağlantıları temizle
        $this->connections = array_slice($this->connections, -5, null, true);
        $this->currentConnections = count($this->connections);
    }
    
    public function closeAll(): void {
        $this->connections = [];
        $this->currentConnections = 0;
    }
}

// Kullanım
$pool = new ZAPIConnectionPool();
$zapi = $pool->getConnection('your_api_key', 'your_app_id');
```

### Async Operations

```php
<?php
use ZAPI\ZAPI;

class AsyncZAPIClient {
    private ZAPI $zapi;
    private array $pendingRequests = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function asyncRequest(string $method, array $data = []): string {
        $requestId = uniqid('req_');
        
        $this->pendingRequests[$requestId] = [
            'method' => $method,
            'data' => $data,
            'start_time' => microtime(true)
        ];
        
        return $requestId;
    }
    
    public function executePendingRequests(): array {
        $results = [];
        
        foreach ($this->pendingRequests as $requestId => $request) {
            try {
                $result = $this->executeRequest($request['method'], $request['data']);
                $results[$requestId] = [
                    'success' => true,
                    'data' => $result,
                    'execution_time' => microtime(true) - $request['start_time']
                ];
            } catch (Exception $e) {
                $results[$requestId] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'execution_time' => microtime(true) - $request['start_time']
                ];
            }
        }
        
        $this->pendingRequests = [];
        return $results;
    }
    
    private function executeRequest(string $method, array $data) {
        $parts = explode('.', $method);
        [$class, $methodName] = $parts;
        
        $classInstance = $this->zapi->$class;
        return $classInstance->$methodName($data);
    }
}

// Kullanım
$asyncClient = new AsyncZAPIClient('your_api_key', 'your_app_id');

// Birden fazla isteği asenkron olarak hazırla
$request1 = $asyncClient->asyncRequest('auth.login', [
    'email' => 'user1@example.com',
    'password' => 'password123'
]);

$request2 = $asyncClient->asyncRequest('responses.create', [
    'model' => 'gpt-4',
    'messages' => [['role' => 'user', 'content' => 'Hello']]
]);

$request3 = $asyncClient->asyncRequest('system.getHealth');

// Tüm istekleri paralel olarak çalıştır
$results = $asyncClient->executePendingRequests();

foreach ($results as $requestId => $result) {
    if ($result['success']) {
        echo "✅ $requestId başarılı (" . round($result['execution_time'], 3) . "s)\n";
    } else {
        echo "❌ $requestId başarısız: " . $result['error'] . "\n";
    }
}
```

### Request Batching

```php
<?php
use ZAPI\ZAPI;

class ZAPIBatchProcessor {
    private ZAPI $zapi;
    private array $batch = [];
    private int $batchSize = 10;
    private float $batchTimeout = 1.0; // saniye
    private float $lastBatchTime = 0;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
        $this->lastBatchTime = microtime(true);
    }
    
    public function addRequest(string $method, array $data, callable $callback = null): void {
        $this->batch[] = [
            'method' => $method,
            'data' => $data,
            'callback' => $callback,
            'timestamp' => microtime(true)
        ];
        
        // Batch boyutu veya timeout kontrolü
        if (count($this->batch) >= $this->batchSize || 
            (microtime(true) - $this->lastBatchTime) >= $this->batchTimeout) {
            $this->processBatch();
        }
    }
    
    public function processBatch(): void {
        if (empty($this->batch)) {
            return;
        }
        
        $batch = $this->batch;
        $this->batch = [];
        $this->lastBatchTime = microtime(true);
        
        // Batch'i paralel olarak işle
        $results = $this->executeBatch($batch);
        
        // Callback'leri çağır
        foreach ($results as $index => $result) {
            if (isset($batch[$index]['callback']) && is_callable($batch[$index]['callback'])) {
                $batch[$index]['callback']($result);
            }
        }
    }
    
    private function executeBatch(array $batch): array {
        $results = [];
        
        foreach ($batch as $request) {
            try {
                $result = $this->executeRequest($request['method'], $request['data']);
                $results[] = ['success' => true, 'data' => $result];
            } catch (Exception $e) {
                $results[] = ['success' => false, 'error' => $e->getMessage()];
            }
        }
        
        return $results;
    }
    
    private function executeRequest(string $method, array $data) {
        $parts = explode('.', $method);
        [$class, $methodName] = $parts;
        
        $classInstance = $this->zapi->$class;
        return $classInstance->$methodName($data);
    }
    
    public function __destruct() {
        $this->processBatch();
    }
}

// Kullanım
$batchProcessor = new ZAPIBatchProcessor('your_api_key', 'your_app_id');

// Batch'e istekler ekle
$batchProcessor->addRequest('auth.login', [
    'email' => 'user1@example.com',
    'password' => 'password123'
], function($result) {
    if ($result['success']) {
        echo "✅ Giriş başarılı\n";
    } else {
        echo "❌ Giriş başarısız: " . $result['error'] . "\n";
    }
});

$batchProcessor->addRequest('responses.create', [
    'model' => 'gpt-4',
    'messages' => [['role' => 'user', 'content' => 'Hello']]
], function($result) {
    if ($result['success']) {
        echo "✅ AI yanıtı alındı\n";
    } else {
        echo "❌ AI yanıtı alınamadı: " . $result['error'] . "\n";
    }
});

// Batch'i işle
$batchProcessor->processBatch();
```

## 🔄 Caching Stratejileri

### Redis Cache

```php
<?php
use ZAPI\ZAPI;
use Redis;

class ZAPICache {
    private ZAPI $zapi;
    private Redis $redis;
    private int $defaultTtl = 3600; // 1 saat
    
    public function __construct(string $apiKey, string $appId, Redis $redis) {
        $this->zapi = new ZAPI($apiKey, $appId);
        $this->redis = $redis;
    }
    
    public function get(string $key, callable $callback, int $ttl = null): mixed {
        $cached = $this->redis->get($key);
        
        if ($cached !== false) {
            return json_decode($cached, true);
        }
        
        $result = $callback();
        $this->redis->setex($key, $ttl ?? $this->defaultTtl, json_encode($result));
        
        return $result;
    }
    
    public function getUserProfile(string $userId): array {
        return $this->get("user_profile:$userId", function() use ($userId) {
            $this->zapi->setBearerToken($this->getUserToken($userId));
            $result = $this->zapi->user->getProfile();
            return $result['data'];
        }, 1800); // 30 dakika
    }
    
    public function getAIResponse(string $model, array $messages): array {
        $cacheKey = "ai_response:" . md5($model . json_encode($messages));
        
        return $this->get($cacheKey, function() use ($model, $messages) {
            $result = $this->zapi->responses->create([
                'model' => $model,
                'messages' => $messages
            ]);
            return $result['data'];
        }, 3600); // 1 saat
    }
    
    public function invalidateUserCache(string $userId): void {
        $this->redis->del("user_profile:$userId");
    }
    
    private function getUserToken(string $userId): string {
        // Kullanıcı token'ını al (veritabanı, session, vb.)
        return "user_token_$userId";
    }
}

// Kullanım
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$cache = new ZAPICache('your_api_key', 'your_app_id', $redis);

// Cache'li kullanıcı profili
$profile = $cache->getUserProfile('user_123');
echo "Kullanıcı: " . $profile['name'];

// Cache'li AI yanıtı
$response = $cache->getAIResponse('gpt-4', [
    ['role' => 'user', 'content' => 'Merhaba!']
]);
echo "AI Yanıtı: " . $response['content'];
```

### Memory Cache

```php
<?php
use ZAPI\ZAPI;

class ZAPIMemoryCache {
    private ZAPI $zapi;
    private array $cache = [];
    private array $cacheTimestamps = [];
    private int $defaultTtl = 3600;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function get(string $key, callable $callback, int $ttl = null): mixed {
        $ttl = $ttl ?? $this->defaultTtl;
        
        if (isset($this->cache[$key]) && 
            (time() - $this->cacheTimestamps[$key]) < $ttl) {
            return $this->cache[$key];
        }
        
        $result = $callback();
        $this->cache[$key] = $result;
        $this->cacheTimestamps[$key] = time();
        
        return $result;
    }
    
    public function set(string $key, mixed $value, int $ttl = null): void {
        $this->cache[$key] = $value;
        $this->cacheTimestamps[$key] = time();
    }
    
    public function delete(string $key): void {
        unset($this->cache[$key]);
        unset($this->cacheTimestamps[$key]);
    }
    
    public function clear(): void {
        $this->cache = [];
        $this->cacheTimestamps = [];
    }
    
    public function getStats(): array {
        return [
            'total_items' => count($this->cache),
            'memory_usage' => memory_get_usage(true),
            'cache_size' => strlen(serialize($this->cache))
        ];
    }
}

// Kullanım
$cache = new ZAPIMemoryCache('your_api_key', 'your_app_id');

// Cache'li API çağrısı
$health = $cache->get('system_health', function() use ($cache) {
    $result = $cache->zapi->system->getHealth();
    return $result['data'];
}, 300); // 5 dakika

echo "Sistem durumu: " . $health['status'];
```

## 🛡️ Güvenlik Best Practices

### API Key Güvenliği

```php
<?php
use ZAPI\ZAPI;

class SecureZAPIClient {
    private ZAPI $zapi;
    private string $encryptedApiKey;
    private string $encryptionKey;
    
    public function __construct(string $encryptedApiKey, string $appId, string $encryptionKey) {
        $this->encryptedApiKey = $encryptedApiKey;
        $this->encryptionKey = $encryptionKey;
        $this->appId = $appId;
        
        $apiKey = $this->decryptApiKey($encryptedApiKey);
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    private function decryptApiKey(string $encryptedApiKey): string {
        $decrypted = openssl_decrypt(
            base64_decode($encryptedApiKey),
            'AES-256-CBC',
            $this->encryptionKey,
            0,
            substr($this->encryptionKey, 0, 16)
        );
        
        if ($decrypted === false) {
            throw new Exception('API anahtarı çözülemedi');
        }
        
        return $decrypted;
    }
    
    public static function encryptApiKey(string $apiKey, string $encryptionKey): string {
        $encrypted = openssl_encrypt(
            $apiKey,
            'AES-256-CBC',
            $encryptionKey,
            0,
            substr($encryptionKey, 0, 16)
        );
        
        return base64_encode($encrypted);
    }
    
    public function makeSecureRequest(string $method, array $data = []): array {
        // Rate limiting kontrolü
        $this->checkRateLimit();
        
        // Input validation
        $this->validateInput($data);
        
        // Request logging
        $this->logRequest($method, $data);
        
        try {
            $result = $this->executeRequest($method, $data);
            
            // Response logging
            $this->logResponse($method, $result);
            
            return $result;
            
        } catch (Exception $e) {
            // Error logging
            $this->logError($method, $e);
            throw $e;
        }
    }
    
    private function checkRateLimit(): void {
        $key = 'rate_limit_' . $_SERVER['REMOTE_ADDR'];
        $current = apcu_fetch($key) ?: 0;
        
        if ($current >= 100) { // 100 istek/dakika
            throw new Exception('Rate limit aşıldı');
        }
        
        apcu_store($key, $current + 1, 60);
    }
    
    private function validateInput(array $data): void {
        // XSS koruması
        array_walk_recursive($data, function(&$value) {
            if (is_string($value)) {
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        });
    }
    
    private function logRequest(string $method, array $data): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'data' => $this->sanitizeLogData($data)
        ];
        
        error_log('ZAPI Request: ' . json_encode($logData));
    }
    
    private function logResponse(string $method, array $response): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'success' => true,
            'response_size' => strlen(json_encode($response))
        ];
        
        error_log('ZAPI Response: ' . json_encode($logData));
    }
    
    private function logError(string $method, Exception $e): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $method,
            'success' => false,
            'error' => $e->getMessage(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        error_log('ZAPI Error: ' . json_encode($logData));
    }
    
    private function sanitizeLogData(array $data): array {
        // Hassas verileri maskele
        $sensitiveKeys = ['password', 'token', 'api_key', 'secret'];
        
        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***MASKED***';
            }
        }
        
        return $data;
    }
    
    private function executeRequest(string $method, array $data) {
        $parts = explode('.', $method);
        [$class, $methodName] = $parts;
        
        $classInstance = $this->zapi->$class;
        return $classInstance->$methodName($data);
    }
}

// Kullanım
$encryptionKey = 'your_32_character_encryption_key_here';
$apiKey = 'your_api_key_here';
$encryptedApiKey = SecureZAPIClient::encryptApiKey($apiKey, $encryptionKey);

$secureClient = new SecureZAPIClient($encryptedApiKey, 'your_app_id', $encryptionKey);

$result = $secureClient->makeSecureRequest('auth.login', [
    'email' => 'user@example.com',
    'password' => 'password123'
]);
```

### Token Yönetimi

```php
<?php
use ZAPI\ZAPI;

class TokenManager {
    private ZAPI $zapi;
    private array $tokens = [];
    private int $tokenExpiry = 3600; // 1 saat
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function generateToken(array $userData): string {
        $token = $this->createJWT($userData);
        
        // Token'ı güvenli bir şekilde sakla
        $this->storeToken($token, $userData);
        
        return $token;
    }
    
    public function validateToken(string $token): ?array {
        if (!$this->isTokenValid($token)) {
            return null;
        }
        
        $userData = $this->getTokenData($token);
        if (!$userData) {
            return null;
        }
        
        // Token'ı yenile
        $this->refreshToken($token);
        
        return $userData;
    }
    
    public function revokeToken(string $token): bool {
        return $this->deleteToken($token);
    }
    
    private function createJWT(array $userData): string {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            'user_id' => $userData['id'],
            'email' => $userData['email'],
            'exp' => time() + $this->tokenExpiry,
            'iat' => time()
        ]);
        
        $headerEncoded = base64url_encode($header);
        $payloadEncoded = base64url_encode($payload);
        
        $signature = hash_hmac('sha256', 
            $headerEncoded . '.' . $payloadEncoded, 
            'your_secret_key', 
            true
        );
        $signatureEncoded = base64url_encode($signature);
        
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }
    
    private function isTokenValid(string $token): bool {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;
        
        $signature = base64url_decode($signatureEncoded);
        $expectedSignature = hash_hmac('sha256', 
            $headerEncoded . '.' . $payloadEncoded, 
            'your_secret_key', 
            true
        );
        
        return hash_equals($signature, $expectedSignature);
    }
    
    private function getTokenData(string $token): ?array {
        $parts = explode('.', $token);
        $payload = json_decode(base64url_decode($parts[1]), true);
        
        if ($payload['exp'] < time()) {
            return null; // Token süresi dolmuş
        }
        
        return $payload;
    }
    
    private function storeToken(string $token, array $userData): void {
        $this->tokens[$token] = [
            'user_data' => $userData,
            'created_at' => time(),
            'expires_at' => time() + $this->tokenExpiry
        ];
    }
    
    private function refreshToken(string $token): void {
        if (isset($this->tokens[$token])) {
            $this->tokens[$token]['expires_at'] = time() + $this->tokenExpiry;
        }
    }
    
    private function deleteToken(string $token): bool {
        if (isset($this->tokens[$token])) {
            unset($this->tokens[$token]);
            return true;
        }
        return false;
    }
}

function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64url_decode($data) {
    return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
}

// Kullanım
$tokenManager = new TokenManager('your_api_key', 'your_app_id');

// Token oluştur
$token = $tokenManager->generateToken([
    'id' => 'user_123',
    'email' => 'user@example.com',
    'name' => 'John Doe'
]);

// Token doğrula
$userData = $tokenManager->validateToken($token);
if ($userData) {
    echo "✅ Token geçerli: " . $userData['email'];
} else {
    echo "❌ Token geçersiz";
}
```

## 📊 Monitoring ve Analytics

### Performance Monitor

```php
<?php
use ZAPI\ZAPI;

class ZAPIPerformanceMonitor {
    private ZAPI $zapi;
    private array $metrics = [];
    private array $alerts = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function monitorRequest(string $method, array $data = []): array {
        $startTime = microtime(true);
        $startMemory = memory_get_usage(true);
        
        try {
            $result = $this->executeRequest($method, $data);
            
            $this->recordMetric($method, [
                'success' => true,
                'execution_time' => microtime(true) - $startTime,
                'memory_usage' => memory_get_usage(true) - $startMemory,
                'response_size' => strlen(json_encode($result))
            ]);
            
            return $result;
            
        } catch (Exception $e) {
            $this->recordMetric($method, [
                'success' => false,
                'execution_time' => microtime(true) - $startTime,
                'memory_usage' => memory_get_usage(true) - $startMemory,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
    
    private function recordMetric(string $method, array $metric): void {
        $this->metrics[] = array_merge($metric, [
            'method' => $method,
            'timestamp' => time()
        ]);
        
        // Alert kontrolü
        $this->checkAlerts($method, $metric);
    }
    
    private function checkAlerts(string $method, array $metric): void {
        // Yavaş istekler için alert
        if ($metric['execution_time'] > 5.0) {
            $this->addAlert('slow_request', [
                'method' => $method,
                'execution_time' => $metric['execution_time']
            ]);
        }
        
        // Yüksek bellek kullanımı için alert
        if ($metric['memory_usage'] > 50 * 1024 * 1024) { // 50MB
            $this->addAlert('high_memory', [
                'method' => $method,
                'memory_usage' => $metric['memory_usage']
            ]);
        }
        
        // Hata oranı için alert
        $errorRate = $this->calculateErrorRate($method);
        if ($errorRate > 0.1) { // %10
            $this->addAlert('high_error_rate', [
                'method' => $method,
                'error_rate' => $errorRate
            ]);
        }
    }
    
    private function addAlert(string $type, array $data): void {
        $this->alerts[] = [
            'type' => $type,
            'data' => $data,
            'timestamp' => time()
        ];
        
        // Alert'i logla veya bildirim gönder
        error_log("ZAPI Alert: $type - " . json_encode($data));
    }
    
    private function calculateErrorRate(string $method): float {
        $methodMetrics = array_filter($this->metrics, function($metric) use ($method) {
            return $metric['method'] === $method;
        });
        
        if (empty($methodMetrics)) {
            return 0;
        }
        
        $totalRequests = count($methodMetrics);
        $failedRequests = count(array_filter($methodMetrics, function($metric) {
            return !$metric['success'];
        }));
        
        return $failedRequests / $totalRequests;
    }
    
    public function getMetrics(string $method = null, int $limit = 100): array {
        $metrics = $this->metrics;
        
        if ($method) {
            $metrics = array_filter($metrics, function($metric) use ($method) {
                return $metric['method'] === $method;
            });
        }
        
        return array_slice($metrics, -$limit);
    }
    
    public function getAlerts(int $limit = 50): array {
        return array_slice($this->alerts, -$limit);
    }
    
    public function getStats(): array {
        $totalRequests = count($this->metrics);
        $successfulRequests = count(array_filter($this->metrics, function($metric) {
            return $metric['success'];
        }));
        
        $avgExecutionTime = 0;
        $avgMemoryUsage = 0;
        
        if ($totalRequests > 0) {
            $avgExecutionTime = array_sum(array_column($this->metrics, 'execution_time')) / $totalRequests;
            $avgMemoryUsage = array_sum(array_column($this->metrics, 'memory_usage')) / $totalRequests;
        }
        
        return [
            'total_requests' => $totalRequests,
            'successful_requests' => $successfulRequests,
            'failed_requests' => $totalRequests - $successfulRequests,
            'success_rate' => $totalRequests > 0 ? $successfulRequests / $totalRequests : 0,
            'average_execution_time' => $avgExecutionTime,
            'average_memory_usage' => $avgMemoryUsage,
            'total_alerts' => count($this->alerts)
        ];
    }
    
    private function executeRequest(string $method, array $data) {
        $parts = explode('.', $method);
        [$class, $methodName] = $parts;
        
        $classInstance = $this->zapi->$class;
        return $classInstance->$methodName($data);
    }
}

// Kullanım
$monitor = new ZAPIPerformanceMonitor('your_api_key', 'your_app_id');

// İstekleri izle
$result = $monitor->monitorRequest('auth.login', [
    'email' => 'user@example.com',
    'password' => 'password123'
]);

// İstatistikleri al
$stats = $monitor->getStats();
echo "Toplam istek: " . $stats['total_requests'] . "\n";
echo "Başarı oranı: " . round($stats['success_rate'] * 100, 2) . "%\n";
echo "Ortalama süre: " . round($stats['average_execution_time'], 3) . " saniye\n";

// Alert'leri kontrol et
$alerts = $monitor->getAlerts();
if (!empty($alerts)) {
    echo "⚠️ " . count($alerts) . " alert var\n";
}
```

---

**Gelişmiş kullanım ve best practices rehberi tamamlandı!** Artık profesyonel seviyede ZAPI entegrasyonları oluşturabilirsiniz. 🚀
