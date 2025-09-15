# API Referansı

ZAPI PHP SDK'nın tüm endpoint'lerini ve kullanım örneklerini içeren kapsamlı referans dokümantasyonu.

## 📋 İçindekiler

- [🔐 Kimlik Doğrulama (Auth)](#kimlik-doğrulama-auth)
- [👥 Kullanıcı Yönetimi (User)](#kullanıcı-yönetimi-user)
- [🤖 AI Sohbet (Responses)](#ai-sohbet-responses)
- [⚡ Gerçek Zamanlı (Realtime)](#gerçek-zamanlı-realtime)
- [📁 Dosya Yönetimi (Upload)](#dosya-yönetimi-upload)
- [🔑 API Anahtarları (APIKeys)](#api-anahtarları-apikeys)
- [📊 Analitik (Analytics)](#analitik-analytics)
- [🛠️ Sistem (System)](#sistem-system)

## 🔐 Kimlik Doğrulama (Auth)

### Kullanıcı Kaydı

```php
$result = $zapi->auth->register([
    'email' => 'user@example.com',
    'password' => 'password123',
    'name' => 'John Doe'
]);
```

**Parametreler:**
- `email` (string, gerekli): E-posta adresi
- `password` (string, gerekli): Şifre (min 8 karakter)
- `name` (string, gerekli): Kullanıcı adı

**Yanıt:**
```json
{
    "success": true,
    "data": {
        "message": "Kullanıcı başarıyla kaydedildi",
        "user": {
            "id": "user_id",
            "email": "user@example.com",
            "name": "John Doe"
        }
    }
}
```

### Kullanıcı Girişi

```php
$result = $zapi->auth->login([
    'email' => 'user@example.com',
    'password' => 'password123'
]);
```

**Parametreler:**
- `email` (string, gerekli): E-posta adresi
- `password` (string, gerekli): Şifre

**Yanıt:**
```json
{
    "success": true,
    "data": {
        "token": "jwt_token_here",
        "user": {
            "id": "user_id",
            "email": "user@example.com",
            "name": "John Doe"
        }
    }
}
```

### E-posta Doğrulama

```php
$result = $zapi->auth->verifyEmail([
    'token' => 'verification_token'
]);
```

### Şifre Sıfırlama

```php
$result = $zapi->auth->resetPassword([
    'email' => 'user@example.com'
]);
```

### OTP ile Giriş

```php
// OTP gönder
$result = $zapi->auth->sendOTP([
    'email' => 'user@example.com'
]);

// OTP doğrula ve giriş yap
$result = $zapi->auth->loginWithOTP([
    'email' => 'user@example.com',
    'otp' => '123456'
]);
```

## 👥 Kullanıcı Yönetimi (User)

### Profil Bilgilerini Al

```php
$zapi->setBearerToken('your_bearer_token');
$profile = $zapi->user->getProfile();
```

### Profil Güncelle

```php
$result = $zapi->user->updateProfile([
    'name' => 'John Updated',
    'bio' => 'Yeni biyografi',
    'website' => 'https://example.com'
]);
```

### Kullanıcı Ayarları

```php
// Ayarları al
$settings = $zapi->user->getSettings();

// Ayarları güncelle
$result = $zapi->user->updateSettings([
    'language' => 'tr',
    'timezone' => 'Europe/Istanbul',
    'notifications' => true
]);
```

### Kullanım İstatistikleri

```php
$usage = $zapi->user->getUsage([
    'period' => 'monthly' // daily, weekly, monthly
]);
```

## 🤖 AI Sohbet (Responses)

### Basit Sohbet

```php
$response = $zapi->responses->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Merhaba!']
    ]
]);
```

**Parametreler:**
- `model` (string, gerekli): AI model adı
- `messages` (array, gerekli): Mesaj dizisi
- `temperature` (float, opsiyonel): Yaratıcılık seviyesi (0-1)
- `max_tokens` (int, opsiyonel): Maksimum token sayısı

### Stream Sohbet

```php
$streamResponse = $zapi->responses->createStream([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Uzun bir hikaye yaz']
    ]
]);
```

### Sohbet Geçmişi

```php
// Sohbet geçmişini al
$history = $zapi->responses->getHistory([
    'limit' => 50,
    'offset' => 0
]);

// Belirli bir sohbeti al
$conversation = $zapi->responses->getConversation('conversation_id');
```

## ⚡ Gerçek Zamanlı (Realtime)

### WebSocket Bağlantısı

```php
$realtime = $zapi->realtime->connect([
    'room' => 'chat_room_1',
    'user_id' => 'user_123'
]);
```

### Mesaj Gönderme

```php
$result = $realtime->sendMessage([
    'content' => 'Merhaba dünya!',
    'type' => 'text'
]);
```

### Mesaj Dinleme

```php
$realtime->onMessage(function($message) {
    echo "Yeni mesaj: " . $message['content'];
});
```

## 📁 Dosya Yönetimi (Upload)

### Dosya Yükleme

```php
$result = $zapi->upload->uploadFile([
    'file' => '/path/to/file.pdf',
    'type' => 'document'
]);
```

**Desteklenen Tipler:**
- `document`: PDF, DOC, DOCX
- `image`: JPG, PNG, GIF
- `audio`: MP3, WAV
- `video`: MP4, AVI

### Dosya Bilgilerini Al

```php
$fileInfo = $zapi->upload->getFileInfo('file_id');
```

### Dosya Silme

```php
$result = $zapi->upload->deleteFile('file_id');
```

### Yükleme İstatistikleri

```php
$stats = $zapi->upload->getStats();
```

## 🔑 API Anahtarları (APIKeys)

### API Anahtarlarını Listele

```php
$keys = $zapi->apiKeys->list();
```

### Yeni API Anahtarı Oluştur

```php
$result = $zapi->apiKeys->create([
    'name' => 'My API Key',
    'permissions' => ['read', 'write']
]);
```

### API Anahtarı Güncelle

```php
$result = $zapi->apiKeys->update('key_id', [
    'name' => 'Updated Name',
    'isActive' => true
]);
```

### API Anahtarı Sil

```php
$result = $zapi->apiKeys->delete('key_id');
```

## 📊 Analitik (Analytics)

### Kullanım İstatistikleri

```php
$analytics = $zapi->analytics->getUsage([
    'period' => 'monthly',
    'start_date' => '2025-01-01',
    'end_date' => '2025-01-31'
]);
```

### Performans Metrikleri

```php
$metrics = $zapi->analytics->getMetrics([
    'type' => 'response_time',
    'period' => 'daily'
]);
```

## 🛠️ Sistem (System)

### Sistem Durumu

```php
$health = $zapi->system->getHealth();
```

### Sistem Bilgileri

```php
$info = $zapi->system->getInfo();
```

### Bellek Kullanımı

```php
$memory = $zapi->system->getMemory();
```

## 🔧 Gelişmiş Özellikler

### Batch İşlemler

```php
$batch = $zapi->batch->create([
    'operations' => [
        ['type' => 'response', 'data' => [...]],
        ['type' => 'upload', 'data' => [...]]
    ]
]);
```

### Webhook Yönetimi

```php
// Webhook oluştur
$webhook = $zapi->webhook->create([
    'url' => 'https://your-app.com/webhook',
    'events' => ['user.created', 'response.created']
]);

// Webhook test et
$test = $zapi->webhook->test('webhook_id');
```

### Metadata Yönetimi

```php
// Metadata ekle
$result = $zapi->metadata->set('user.preferences', [
    'theme' => 'dark',
    'language' => 'tr'
]);

// Metadata al
$preferences = $zapi->metadata->get('user.preferences');
```

## 📝 Hata Kodları

| Kod | Açıklama |
|-----|----------|
| 400 | Geçersiz istek |
| 401 | Kimlik doğrulama gerekli |
| 403 | Yetki yok |
| 404 | Kaynak bulunamadı |
| 429 | Rate limit aşıldı |
| 500 | Sunucu hatası |

## 🔍 Debug ve Logging

### Debug Modu

```php
$zapi = new ZAPI($apiKey, $appId, null, ['debug' => true]);
```

### Log Seviyeleri

```php
// Log seviyesini ayarla
$zapi->setLogLevel('debug'); // debug, info, warning, error
```

---

**API Referansı tamamlandı!** Tüm endpoint'leri ve kullanım örneklerini öğrendiniz. 🚀
