# Örnekler ve Kullanım Senaryoları

ZAPI PHP SDK ile gerçek dünya uygulamaları oluşturmak için pratik örnekler ve kullanım senaryoları.

## 📋 İçindekiler

- [💬 Chat Bot Uygulaması](#chat-bot-uygulaması)
- [👥 Kullanıcı Yönetim Sistemi](#kullanıcı-yönetim-sistemi)
- [📁 Dosya Yönetim Sistemi](#dosya-yönetim-sistemi)
- [📊 Analitik Dashboard](#analitik-dashboard)
- [🔔 Bildirim Sistemi](#bildirim-sistemi)
- [🛒 E-ticaret Entegrasyonu](#e-ticaret-entegrasyonu)
- [📱 Mobil Uygulama Backend](#mobil-uygulama-backend)

## 💬 Chat Bot Uygulaması

### Basit Chat Bot

```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;
use ZAPI\Exceptions\ZAPIException;

class SimpleChatBot {
    private ZAPI $zapi;
    private array $conversationHistory = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function chat(string $userMessage): string {
        try {
            // Konuşma geçmişine ekle
            $this->conversationHistory[] = [
                'role' => 'user',
                'content' => $userMessage
            ];
            
            // AI'dan yanıt al
            $response = $this->zapi->responses->create([
                'model' => 'gpt-4',
                'messages' => $this->conversationHistory,
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);
            
            $aiResponse = $response['data']['content'];
            
            // AI yanıtını geçmişe ekle
            $this->conversationHistory[] = [
                'role' => 'assistant',
                'content' => $aiResponse
            ];
            
            return $aiResponse;
            
        } catch (ZAPIException $e) {
            return "Üzgünüm, bir hata oluştu: " . $e->getMessage();
        }
    }
    
    public function clearHistory(): void {
        $this->conversationHistory = [];
    }
    
    public function getHistory(): array {
        return $this->conversationHistory;
    }
}

// Kullanım
$bot = new SimpleChatBot('your_api_key', 'your_app_id');

echo $bot->chat("Merhaba! Nasılsın?");
echo $bot->chat("PHP hakkında bilgi ver");
echo $bot->chat("Teşekkürler!");
```

### Gelişmiş Chat Bot

```php
<?php
use ZAPI\ZAPI;

class AdvancedChatBot {
    private ZAPI $zapi;
    private array $conversationHistory = [];
    private array $userContext = [];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function chat(string $userMessage, array $context = []): array {
        try {
            // Kullanıcı bağlamını güncelle
            $this->userContext = array_merge($this->userContext, $context);
            
            // Sistem mesajı ekle
            $systemMessage = $this->buildSystemMessage();
            
            // Mesajları hazırla
            $messages = [
                ['role' => 'system', 'content' => $systemMessage]
            ];
            
            // Konuşma geçmişini ekle
            $messages = array_merge($messages, $this->conversationHistory);
            
            // Kullanıcı mesajını ekle
            $messages[] = ['role' => 'user', 'content' => $userMessage];
            
            // AI'dan yanıt al
            $response = $this->zapi->responses->create([
                'model' => 'gpt-4',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 1000
            ]);
            
            $aiResponse = $response['data']['content'];
            
            // Geçmişi güncelle
            $this->conversationHistory[] = ['role' => 'user', 'content' => $userMessage];
            $this->conversationHistory[] = ['role' => 'assistant', 'content' => $aiResponse];
            
            // Geçmişi sınırla (son 20 mesaj)
            if (count($this->conversationHistory) > 20) {
                $this->conversationHistory = array_slice($this->conversationHistory, -20);
            }
            
            return [
                'success' => true,
                'response' => $aiResponse,
                'context' => $this->userContext
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function buildSystemMessage(): string {
        $context = json_encode($this->userContext, JSON_UNESCAPED_UNICODE);
        return "Sen yardımcı bir AI asistanısın. Kullanıcı bağlamı: $context";
    }
    
    public function setContext(string $key, mixed $value): void {
        $this->userContext[$key] = $value;
    }
    
    public function getContext(): array {
        return $this->userContext;
    }
}

// Kullanım
$bot = new AdvancedChatBot('your_api_key', 'your_app_id');

// Kullanıcı bağlamı ayarla
$bot->setContext('name', 'John');
$bot->setContext('language', 'tr');

$result = $bot->chat("Merhaba! Benim adım John.");
if ($result['success']) {
    echo $result['response'];
}
```

## 👥 Kullanıcı Yönetim Sistemi

### Kullanıcı Kayıt ve Giriş Sistemi

```php
<?php
use ZAPI\ZAPI;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

class UserAuthSystem {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function registerUser(array $userData): array {
        try {
            // E-posta doğrulama
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'error' => 'Geçersiz e-posta adresi'];
            }
            
            // Şifre güçlülük kontrolü
            if (strlen($userData['password']) < 8) {
                return ['success' => false, 'error' => 'Şifre en az 8 karakter olmalı'];
            }
            
            // Kullanıcı kaydı
            $result = $this->zapi->auth->register([
                'email' => $userData['email'],
                'password' => $userData['password'],
                'name' => $userData['name']
            ]);
            
            return [
                'success' => true,
                'message' => 'Kullanıcı başarıyla kaydedildi',
                'user' => $result['data']['user']
            ];
            
        } catch (ValidationException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Kayıt sırasında hata oluştu'];
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
            
        } catch (AuthenticationException $e) {
            return ['success' => false, 'error' => 'Geçersiz e-posta veya şifre'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Giriş sırasında hata oluştu'];
        }
    }
    
    public function getUserProfile(string $token): array {
        try {
            $this->zapi->setBearerToken($token);
            $profile = $this->zapi->user->getProfile();
            
            return [
                'success' => true,
                'profile' => $profile['data']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Profil bilgileri alınamadı'];
        }
    }
    
    public function updateUserProfile(string $token, array $profileData): array {
        try {
            $this->zapi->setBearerToken($token);
            $result = $this->zapi->user->updateProfile($profileData);
            
            return [
                'success' => true,
                'message' => 'Profil başarıyla güncellendi',
                'profile' => $result['data']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Profil güncellenemedi'];
        }
    }
}

// Kullanım
$authSystem = new UserAuthSystem('your_api_key', 'your_app_id');

// Kullanıcı kaydı
$registerResult = $authSystem->registerUser([
    'email' => 'user@example.com',
    'password' => 'password123',
    'name' => 'John Doe'
]);

if ($registerResult['success']) {
    echo "✅ " . $registerResult['message'];
} else {
    echo "❌ " . $registerResult['error'];
}

// Kullanıcı girişi
$loginResult = $authSystem->loginUser('user@example.com', 'password123');

if ($loginResult['success']) {
    echo "✅ Giriş başarılı!";
    
    // Profil bilgilerini al
    $profile = $authSystem->getUserProfile($loginResult['token']);
    if ($profile['success']) {
        echo "Kullanıcı: " . $profile['profile']['name'];
    }
}
```

## 📁 Dosya Yönetim Sistemi

### Dosya Yükleme ve Yönetim Sistemi

```php
<?php
use ZAPI\ZAPI;

class FileManagementSystem {
    private ZAPI $zapi;
    private array $allowedTypes = [
        'image' => ['jpg', 'jpeg', 'png', 'gif'],
        'document' => ['pdf', 'doc', 'docx', 'txt'],
        'audio' => ['mp3', 'wav', 'ogg'],
        'video' => ['mp4', 'avi', 'mov']
    ];
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function uploadFile(string $filePath, string $type = 'document'): array {
        try {
            // Dosya varlığını kontrol et
            if (!file_exists($filePath)) {
                return ['success' => false, 'error' => 'Dosya bulunamadı'];
            }
            
            // Dosya tipini kontrol et
            $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (!in_array($fileExtension, $this->allowedTypes[$type] ?? [])) {
                return ['success' => false, 'error' => 'Desteklenmeyen dosya tipi'];
            }
            
            // Dosya boyutunu kontrol et (10MB limit)
            if (filesize($filePath) > 10 * 1024 * 1024) {
                return ['success' => false, 'error' => 'Dosya boyutu çok büyük (max 10MB)'];
            }
            
            // Dosyayı yükle
            $result = $this->zapi->upload->uploadFile([
                'file' => $filePath,
                'type' => $type
            ]);
            
            return [
                'success' => true,
                'file_id' => $result['data']['file_id'],
                'url' => $result['data']['url'],
                'size' => $result['data']['size'],
                'type' => $result['data']['type']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
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
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function deleteFile(string $fileId): array {
        try {
            $result = $this->zapi->upload->deleteFile($fileId);
            
            return [
                'success' => true,
                'message' => 'Dosya başarıyla silindi'
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getUploadStats(): array {
        try {
            $stats = $this->zapi->upload->getStats();
            
            return [
                'success' => true,
                'stats' => $stats['data']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// Kullanım
$fileSystem = new FileManagementSystem('your_api_key', 'your_app_id');

// Dosya yükleme
$uploadResult = $fileSystem->uploadFile('/path/to/document.pdf', 'document');

if ($uploadResult['success']) {
    echo "✅ Dosya yüklendi: " . $uploadResult['file_id'];
    
    // Dosya bilgilerini al
    $fileInfo = $fileSystem->getFileInfo($uploadResult['file_id']);
    if ($fileInfo['success']) {
        echo "Dosya boyutu: " . $fileInfo['file']['size'] . " bytes";
    }
} else {
    echo "❌ " . $uploadResult['error'];
}
```

## 📊 Analitik Dashboard

### Kullanım İstatistikleri Dashboard'u

```php
<?php
use ZAPI\ZAPI;

class AnalyticsDashboard {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function getDashboardData(string $period = 'monthly'): array {
        try {
            // Kullanım istatistikleri
            $usage = $this->zapi->user->getUsage(['period' => $period]);
            
            // Sistem durumu
            $health = $this->zapi->system->getHealth();
            
            // Bellek kullanımı
            $memory = $this->zapi->system->getMemory();
            
            // Yükleme istatistikleri
            $uploadStats = $this->zapi->upload->getStats();
            
            return [
                'success' => true,
                'data' => [
                    'usage' => $usage['data'],
                    'health' => $health['data'],
                    'memory' => $memory['data'],
                    'uploads' => $uploadStats['data']
                ]
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function generateReport(string $startDate, string $endDate): array {
        try {
            $report = [
                'period' => "$startDate - $endDate",
                'generated_at' => date('Y-m-d H:i:s'),
                'data' => []
            ];
            
            // Günlük kullanım verileri
            $currentDate = $startDate;
            while ($currentDate <= $endDate) {
                $dailyUsage = $this->zapi->user->getUsage([
                    'period' => 'daily',
                    'date' => $currentDate
                ]);
                
                $report['data'][$currentDate] = $dailyUsage['data'];
                $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
            }
            
            return ['success' => true, 'report' => $report];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// Kullanım
$dashboard = new AnalyticsDashboard('your_api_key', 'your_app_id');

// Dashboard verilerini al
$dashboardData = $dashboard->getDashboardData('monthly');

if ($dashboardData['success']) {
    $data = $dashboardData['data'];
    
    echo "📊 Dashboard Verileri:\n";
    echo "API Durumu: " . $data['health']['status'] . "\n";
    echo "Bellek Kullanımı: " . $data['memory']['usage'] . "%\n";
    echo "Toplam İstek: " . $data['usage']['total_requests'] . "\n";
    echo "Yüklenen Dosya: " . $data['uploads']['total_files'] . "\n";
}

// Rapor oluştur
$report = $dashboard->generateReport('2025-01-01', '2025-01-31');
if ($report['success']) {
    echo "📈 Rapor oluşturuldu: " . $report['report']['generated_at'];
}
```

## 🔔 Bildirim Sistemi

### E-posta ve SMS Bildirim Sistemi

```php
<?php
use ZAPI\ZAPI;

class NotificationSystem {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function sendEmailNotification(array $emailData): array {
        try {
            $result = $this->zapi->notifications->sendEmail([
                'to' => $emailData['to'],
                'subject' => $emailData['subject'],
                'body' => $emailData['body'],
                'template' => $emailData['template'] ?? null
            ]);
            
            return [
                'success' => true,
                'message_id' => $result['data']['message_id']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function sendSMSNotification(array $smsData): array {
        try {
            $result = $this->zapi->notifications->sendSMS([
                'to' => $smsData['to'],
                'message' => $smsData['message']
            ]);
            
            return [
                'success' => true,
                'message_id' => $result['data']['message_id']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function sendBulkNotifications(array $notifications): array {
        $results = [];
        
        foreach ($notifications as $notification) {
            if ($notification['type'] === 'email') {
                $result = $this->sendEmailNotification($notification['data']);
            } elseif ($notification['type'] === 'sms') {
                $result = $this->sendSMSNotification($notification['data']);
            }
            
            $results[] = [
                'type' => $notification['type'],
                'recipient' => $notification['data']['to'],
                'result' => $result
            ];
        }
        
        return ['success' => true, 'results' => $results];
    }
}

// Kullanım
$notificationSystem = new NotificationSystem('your_api_key', 'your_app_id');

// E-posta bildirimi
$emailResult = $notificationSystem->sendEmailNotification([
    'to' => 'user@example.com',
    'subject' => 'Hoş Geldiniz!',
    'body' => 'ZAPI sistemine hoş geldiniz!'
]);

if ($emailResult['success']) {
    echo "✅ E-posta gönderildi: " . $emailResult['message_id'];
}

// Toplu bildirim
$bulkNotifications = [
    [
        'type' => 'email',
        'data' => [
            'to' => 'user1@example.com',
            'subject' => 'Güncelleme',
            'body' => 'Sistem güncellendi'
        ]
    ],
    [
        'type' => 'sms',
        'data' => [
            'to' => '+905551234567',
            'message' => 'Sistem güncellendi'
        ]
    ]
];

$bulkResult = $notificationSystem->sendBulkNotifications($bulkNotifications);
echo "📤 Toplu bildirim tamamlandı";
```

## 🛒 E-ticaret Entegrasyonu

### Ürün Yönetim Sistemi

```php
<?php
use ZAPI\ZAPI;

class EcommerceIntegration {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function createProduct(array $productData): array {
        try {
            // Ürün resmini yükle
            if (isset($productData['image'])) {
                $imageResult = $this->zapi->upload->uploadFile([
                    'file' => $productData['image'],
                    'type' => 'image'
                ]);
                
                $productData['image_url'] = $imageResult['data']['url'];
            }
            
            // Ürün açıklamasını AI ile oluştur
            if (empty($productData['description'])) {
                $aiDescription = $this->zapi->responses->create([
                    'model' => 'gpt-4',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => "Bu ürün için kısa bir açıklama yaz: " . $productData['name']
                        ]
                    ]
                ]);
                
                $productData['description'] = $aiDescription['data']['content'];
            }
            
            // Metadata olarak ürün bilgilerini kaydet
            $result = $this->zapi->metadata->set('product.' . $productData['id'], $productData);
            
            return [
                'success' => true,
                'product' => $productData
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getProduct(string $productId): array {
        try {
            $product = $this->zapi->metadata->get('product.' . $productId);
            
            return [
                'success' => true,
                'product' => $product['data']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function generateProductRecommendations(string $userId): array {
        try {
            // Kullanıcı geçmişini al
            $userHistory = $this->zapi->metadata->get('user.history.' . $userId);
            
            // AI ile öneri oluştur
            $recommendations = $this->zapi->responses->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Bu kullanıcı geçmişine göre ürün önerileri yap: " . json_encode($userHistory['data'])
                    ]
                ]
            ]);
            
            return [
                'success' => true,
                'recommendations' => $recommendations['data']['content']
            ];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}

// Kullanım
$ecommerce = new EcommerceIntegration('your_api_key', 'your_app_id');

// Ürün oluştur
$productResult = $ecommerce->createProduct([
    'id' => 'prod_001',
    'name' => 'Akıllı Telefon',
    'price' => 15000,
    'category' => 'Elektronik',
    'image' => '/path/to/phone.jpg'
]);

if ($productResult['success']) {
    echo "✅ Ürün oluşturuldu: " . $productResult['product']['name'];
}
```

## 📱 Mobil Uygulama Backend

### RESTful API Backend

```php
<?php
use ZAPI\ZAPI;

class MobileAppBackend {
    private ZAPI $zapi;
    
    public function __construct(string $apiKey, string $appId) {
        $this->zapi = new ZAPI($apiKey, $appId);
    }
    
    public function handleRequest(string $endpoint, array $data = []): array {
        try {
            switch ($endpoint) {
                case 'auth/login':
                    return $this->handleLogin($data);
                    
                case 'auth/register':
                    return $this->handleRegister($data);
                    
                case 'user/profile':
                    return $this->handleGetProfile($data);
                    
                case 'chat/send':
                    return $this->handleChat($data);
                    
                case 'upload/file':
                    return $this->handleFileUpload($data);
                    
                default:
                    return ['success' => false, 'error' => 'Geçersiz endpoint'];
            }
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    private function handleLogin(array $data): array {
        $result = $this->zapi->auth->login([
            'email' => $data['email'],
            'password' => $data['password']
        ]);
        
        return [
            'success' => true,
            'token' => $result['data']['token'],
            'user' => $result['data']['user']
        ];
    }
    
    private function handleRegister(array $data): array {
        $result = $this->zapi->auth->register([
            'email' => $data['email'],
            'password' => $data['password'],
            'name' => $data['name']
        ]);
        
        return [
            'success' => true,
            'user' => $result['data']['user']
        ];
    }
    
    private function handleGetProfile(array $data): array {
        $this->zapi->setBearerToken($data['token']);
        $profile = $this->zapi->user->getProfile();
        
        return [
            'success' => true,
            'profile' => $profile['data']
        ];
    }
    
    private function handleChat(array $data): array {
        $this->zapi->setBearerToken($data['token']);
        
        $response = $this->zapi->responses->create([
            'model' => 'gpt-4',
            'messages' => $data['messages']
        ]);
        
        return [
            'success' => true,
            'response' => $response['data']['content']
        ];
    }
    
    private function handleFileUpload(array $data): array {
        $this->zapi->setBearerToken($data['token']);
        
        $result = $this->zapi->upload->uploadFile([
            'file' => $data['file'],
            'type' => $data['type']
        ]);
        
        return [
            'success' => true,
            'file_id' => $result['data']['file_id'],
            'url' => $result['data']['url']
        ];
    }
}

// Kullanım
$backend = new MobileAppBackend('your_api_key', 'your_app_id');

// Giriş isteği
$loginResult = $backend->handleRequest('auth/login', [
    'email' => 'user@example.com',
    'password' => 'password123'
]);

if ($loginResult['success']) {
    echo "✅ Giriş başarılı!";
    
    // Chat isteği
    $chatResult = $backend->handleRequest('chat/send', [
        'token' => $loginResult['token'],
        'messages' => [
            ['role' => 'user', 'content' => 'Merhaba!']
        ]
    ]);
    
    if ($chatResult['success']) {
        echo "AI Yanıtı: " . $chatResult['response'];
    }
}
```

---

**Örnekler tamamlandı!** Artık gerçek dünya uygulamaları oluşturmak için gerekli tüm örnekleri öğrendiniz. 🚀
