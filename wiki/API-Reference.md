# API Referansı

ZAPI PHP SDK'nın tüm endpoint'lerini ve kullanım örneklerini içeren kapsamlı referans dokümantasyonu.

## 📋 İçindekiler

- [🔐 Kimlik Doğrulama (Auth)](#kimlik-doğrulama-auth)
- [🔥 Firebase Kimlik Doğrulama (AuthFirebase)](#firebase-kimlik-doğrulama-authfirebase)
- [🔑 OAuth Kimlik Doğrulama (AuthOAuth)](#oauth-kimlik-doğrulama-authoauth)
- [👥 Kullanıcı Yönetimi (User)](#kullanıcı-yönetimi-user)
- [🤖 AI Sohbet (Responses)](#ai-sohbet-responses)
- [🧠 AI Sağlayıcıları (AIProvider)](#ai-sağlayıcıları-aiprovider)
- [📝 İçerik Yönetimi (Content)](#i̇çerik-yönetimi-content)
- [🎵 Ses İşleme (Audio)](#ses-i̇şleme-audio)
- [🖼️ Görsel İşleme (Images)](#görsel-i̇şleme-images)
- [🔍 Embeddings (Embeddings)](#embeddings-embeddings)
- [⚡ Gerçek Zamanlı (Realtime)](#gerçek-zamanlı-realtime)
- [📁 Dosya Yönetimi (Upload)](#dosya-yönetimi-upload)
- [🔑 API Anahtarları (APIKeys)](#api-anahtarları-apikeys)
- [📱 Uygulama Yönetimi (Apps)](#uygulama-yönetimi-apps)
- [👑 Admin İşlemleri (Admin)](#admin-i̇şlemleri-admin)
- [📊 Plan Yönetimi (Plans)](#plan-yönetimi-plans)
- [💳 Abonelik (Subscription)](#abonelik-subscription)
- [👥 Rol Yönetimi (Roles)](#rol-yönetimi-roles)
- [🔔 Bildirimler (Notifications)](#bildirimler-notifications)
- [📧 Mail Şablonları (MailTemplates)](#mail-şablonları-mailtemplates)
- [🔗 Webhook Yönetimi (Webhook)](#webhook-yönetimi-webhook)
- [📋 Metadata Yönetimi (Metadata)](#metadata-yönetimi-metadata)
- [🔧 OAuth Metadata (OAuthMetadata)](#oauth-metadata-oauthmetadata)
- [⚙️ Fonksiyonlar (Functions)](#fonksiyonlar-functions)
- [🛠️ Sistem (System)](#sistem-system)
- [📊 Bilgi (Info)](#bilgi-info)
- [🔧 Yapılandırma (Config)](#yapılandırma-config)
- [📝 Loglar (Logs)](#loglar-logs)
- [🐛 Debug (Debug)](#debug-debug)
- [📚 Dokümantasyon (Docs)](#dokümantasyon-docs)
- [💾 Yedekleme (Backup)](#yedekleme-backup)

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

### Şifre Değiştirme

```php
$result = $zapi->auth->changePassword([
    'current_password' => 'old_password',
    'new_password' => 'new_password123'
]);
```

### OTP İşlemleri

```php
// OTP gönder
$result = $zapi->auth->sendOTP([
    'email' => 'user@example.com'
]);

// OTP doğrula
$result = $zapi->auth->verifyOTP([
    'email' => 'user@example.com',
    'otp' => '123456'
]);

// OTP ile giriş
$result = $zapi->auth->loginWithOTP([
    'email' => 'user@example.com',
    'otp' => '123456'
]);
```

### Profil İşlemleri

```php
// Profil bilgilerini al
$profile = $zapi->auth->getProfile();

// Çıkış yap
$result = $zapi->auth->logout();
```

## 🔥 Firebase Kimlik Doğrulama (AuthFirebase)

### Google ile Giriş

```php
$result = $zapi->authFirebase->googleLogin([
    'id_token' => 'google_id_token',
    'access_token' => 'google_access_token'
]);
```

### Apple ile Giriş

```php
$result = $zapi->authFirebase->appleLogin([
    'identity_token' => 'apple_identity_token',
    'authorization_code' => 'apple_authorization_code'
]);
```

### Firebase Token Doğrulama

```php
$result = $zapi->authFirebase->verifyToken([
    'firebase_token' => 'firebase_id_token'
]);
```

### Firebase Kullanıcı Oluşturma

```php
$result = $zapi->authFirebase->createUser([
    'uid' => 'firebase_uid',
    'email' => 'user@example.com',
    'display_name' => 'John Doe'
]);
```

### Firebase Kullanıcı Güncelleme

```php
$result = $zapi->authFirebase->updateUser([
    'uid' => 'firebase_uid',
    'display_name' => 'Updated Name',
    'photo_url' => 'https://example.com/photo.jpg'
]);
```

### Firebase Kullanıcı Silme

```php
$result = $zapi->authFirebase->deleteUser([
    'uid' => 'firebase_uid'
]);
```

### Firebase Custom Token Oluşturma

```php
$result = $zapi->authFirebase->createCustomToken([
    'uid' => 'firebase_uid',
    'claims' => ['role' => 'admin']
]);
```

### Firebase Token Yenileme

```php
$result = $zapi->authFirebase->refreshToken([
    'refresh_token' => 'firebase_refresh_token'
]);
```

## 🔑 OAuth Kimlik Doğrulama (AuthOAuth)

### OAuth Sağlayıcıları Listele

```php
$providers = $zapi->authOAuth->getProviders();
```

### OAuth URL Oluştur

```php
$result = $zapi->authOAuth->getAuthUrl([
    'provider' => 'google',
    'redirect_uri' => 'https://yourapp.com/callback'
]);
```

### OAuth Callback İşle

```php
$result = $zapi->authOAuth->handleCallback([
    'provider' => 'google',
    'code' => 'authorization_code',
    'state' => 'state_parameter'
]);
```

### OAuth Token Yenile

```php
$result = $zapi->authOAuth->refreshToken([
    'provider' => 'google',
    'refresh_token' => 'refresh_token'
]);
```

### OAuth Kullanıcı Bilgileri

```php
$result = $zapi->authOAuth->getUserInfo([
    'provider' => 'google',
    'access_token' => 'access_token'
]);
```

### OAuth Bağlantıyı Kaldır

```php
$result = $zapi->authOAuth->disconnect([
    'provider' => 'google'
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

### Kullanıcı Yanıtları

```php
// Kullanıcının yanıtlarını listele
$responses = $zapi->user->getResponses([
    'limit' => 50,
    'offset' => 0
]);

// Belirli bir yanıtı al
$response = $zapi->user->getResponse('response_id');

// Yanıtı sil
$result = $zapi->user->deleteResponse('response_id');

// Yanıtı dışa aktar
$export = $zapi->user->exportResponse('response_id');
```

### Kullanıcı Deaktivasyonu

```php
$result = $zapi->user->deactivate();
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->user->getMetadata('key');

// Metadata güncelle
$result = $zapi->user->updateMetadata('key', 'value');
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

// Sohbeti sil
$result = $zapi->responses->deleteConversation('conversation_id');
```

### Yanıt İşlemleri

```php
// Yanıtı al
$response = $zapi->responses->get('response_id');

// Yanıtı güncelle
$result = $zapi->responses->update('response_id', [
    'title' => 'Yeni Başlık'
]);

// Yanıtı sil
$result = $zapi->responses->delete('response_id');

// Yanıtı dışa aktar
$export = $zapi->responses->export('response_id');
```

## 🧠 AI Sağlayıcıları (AIProvider)

### Sağlayıcıları Listele

```php
$providers = $zapi->aiProvider->list();
```

### Sağlayıcı Oluştur

```php
$result = $zapi->aiProvider->create([
    'name' => 'OpenAI',
    'type' => 'openai',
    'api_key' => 'your_api_key',
    'base_url' => 'https://api.openai.com'
]);
```

### Sağlayıcı Güncelle

```php
$result = $zapi->aiProvider->update('provider_id', [
    'name' => 'Updated OpenAI',
    'is_active' => true
]);
```

### Sağlayıcı Sil

```php
$result = $zapi->aiProvider->delete('provider_id');
```

### Sağlayıcı Test Et

```php
$result = $zapi->aiProvider->test('provider_id');
```

### Modelleri Listele

```php
$models = $zapi->aiProvider->getModels('provider_id');
```

### Model Oluştur

```php
$result = $zapi->aiProvider->createModel([
    'provider_id' => 'provider_id',
    'name' => 'gpt-4',
    'display_name' => 'GPT-4',
    'category' => 'chat',
    'max_tokens' => 4096
]);
```

### Model Güncelle

```php
$result = $zapi->aiProvider->updateModel('model_id', [
    'display_name' => 'Updated GPT-4',
    'is_active' => true
]);
```

### Model Sil

```php
$result = $zapi->aiProvider->deleteModel('model_id');
```

## 📝 İçerik Yönetimi (Content)

### İçerik Listele

```php
$content = $zapi->content->list([
    'type' => 'article',
    'limit' => 50,
    'offset' => 0
]);
```

### İçerik Oluştur

```php
$result = $zapi->content->create([
    'title' => 'Makale Başlığı',
    'content' => 'Makale içeriği...',
    'type' => 'article',
    'category' => 'technology'
]);
```

### İçerik Güncelle

```php
$result = $zapi->content->update('content_id', [
    'title' => 'Güncellenmiş Başlık',
    'content' => 'Güncellenmiş içerik...'
]);
```

### İçerik Sil

```php
$result = $zapi->content->delete('content_id');
```

### İçerik Al

```php
$content = $zapi->content->get('content_id');
```

### İçerik Arama

```php
$results = $zapi->content->search([
    'query' => 'arama terimi',
    'type' => 'article',
    'limit' => 20
]);
```

### İçerik Kategorileri

```php
// Kategorileri listele
$categories = $zapi->content->getCategories();

// Kategori oluştur
$result = $zapi->content->createCategory([
    'name' => 'Yeni Kategori',
    'description' => 'Kategori açıklaması'
]);

// Kategori güncelle
$result = $zapi->content->updateCategory('category_id', [
    'name' => 'Güncellenmiş Kategori'
]);

// Kategori sil
$result = $zapi->content->deleteCategory('category_id');
```

### İçerik Etiketleri

```php
// Etiketleri listele
$tags = $zapi->content->getTags();

// Etiket oluştur
$result = $zapi->content->createTag([
    'name' => 'yeni-etiket',
    'display_name' => 'Yeni Etiket'
]);

// Etiket güncelle
$result = $zapi->content->updateTag('tag_id', [
    'display_name' => 'Güncellenmiş Etiket'
]);

// Etiket sil
$result = $zapi->content->deleteTag('tag_id');
```

### İçerik İstatistikleri

```php
$stats = $zapi->content->getStats([
    'period' => 'monthly'
]);
```

## 🎵 Ses İşleme (Audio)

### Ses Transkripsiyonu

```php
$result = $zapi->audio->transcribe([
    'file' => '/path/to/audio.mp3',
    'model' => 'whisper-1',
    'language' => 'tr'
]);
```

### Ses Çevirisi

```php
$result = $zapi->audio->translate([
    'file' => '/path/to/audio.mp3',
    'model' => 'whisper-1',
    'target_language' => 'en'
]);
```

### Ses Formatları

```php
$formats = $zapi->audio->getSupportedFormats();
```

## 🖼️ Görsel İşleme (Images)

### Görsel Oluşturma

```php
$result = $zapi->images->generate([
    'prompt' => 'A beautiful sunset over mountains',
    'model' => 'dall-e-3',
    'size' => '1024x1024',
    'quality' => 'standard'
]);
```

### Görsel Düzenleme

```php
$result = $zapi->images->edit([
    'image' => '/path/to/image.png',
    'mask' => '/path/to/mask.png',
    'prompt' => 'Add a rainbow to the sky'
]);
```

### Görsel Varyasyonları

```php
$result = $zapi->images->createVariation([
    'image' => '/path/to/image.png',
    'n' => 4
]);
```

### Desteklenen Formatlar

```php
$formats = $zapi->images->getSupportedFormats();
```

## 🔍 Embeddings (Embeddings)

### Embedding Oluştur

```php
$result = $zapi->embeddings->create([
    'input' => 'Bu metin için embedding oluştur',
    'model' => 'text-embedding-ada-002'
]);
```

### Çoklu Embedding

```php
$result = $zapi->embeddings->create([
    'input' => [
        'İlk metin',
        'İkinci metin',
        'Üçüncü metin'
    ],
    'model' => 'text-embedding-ada-002'
]);
```

### Embedding Modelleri

```php
$models = $zapi->embeddings->getModels();
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

### Oda Yönetimi

```php
// Oda oluştur
$result = $zapi->realtime->createRoom([
    'name' => 'Yeni Oda',
    'type' => 'public'
]);

// Odaya katıl
$result = $zapi->realtime->joinRoom('room_id');

// Odadan ayrıl
$result = $zapi->realtime->leaveRoom('room_id');

// Oda sil
$result = $zapi->realtime->deleteRoom('room_id');
```

### Kullanıcı Yönetimi

```php
// Kullanıcıları listele
$users = $zapi->realtime->getUsers('room_id');

// Kullanıcı durumu
$status = $zapi->realtime->getUserStatus('user_id');
```

### Mesaj Geçmişi

```php
$history = $zapi->realtime->getHistory([
    'room_id' => 'room_id',
    'limit' => 50
]);
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

### Yükleme Temizliği

```php
$result = $zapi->upload->cleanup();
```

### Yükleme İlerlemesi

```php
// Tüm yüklemelerin ilerlemesi
$progress = $zapi->upload->getAllProgress();

// Belirli dosyanın ilerlemesi
$progress = $zapi->upload->getProgress('file_id');
```

### URL ile Yükleme

```php
$result = $zapi->upload->uploadFromUrl([
    'url' => 'https://example.com/file.pdf',
    'type' => 'document'
]);
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

### API Anahtarı Kullanımı

```php
$usage = $zapi->apiKeys->getUsage('key_id');
```

### Mevcut Roller

```php
$roles = $zapi->apiKeys->getAvailableRoles();
```

### API Anahtarı Döndür

```php
$result = $zapi->apiKeys->rotate('key_id');
```

### API Anahtarı Durumu

```php
$result = $zapi->apiKeys->toggleStatus('key_id');
```

### API Anahtarı Bilgisi

```php
$info = $zapi->apiKeys->getByKey('api_key_string');
```

## 📱 Uygulama Yönetimi (Apps)

### Uygulamaları Listele

```php
$apps = $zapi->apps->list();
```

### Uygulama Oluştur

```php
$result = $zapi->apps->create([
    'name' => 'My App',
    'description' => 'App açıklaması',
    'domain' => 'myapp.com'
]);
```

### Uygulama Güncelle

```php
$result = $zapi->apps->update('app_id', [
    'name' => 'Updated App Name',
    'description' => 'Güncellenmiş açıklama'
]);
```

### Uygulama Sil

```php
$result = $zapi->apps->delete('app_id');
```

### Uygulama Bilgisi

```php
$app = $zapi->apps->get('app_id');
```

### Uygulama İstatistikleri

```php
$stats = $zapi->apps->getStats('app_id');
```

### Kullanım Sıfırlama

```php
$result = $zapi->apps->resetUsage('app_id');
```

### Durum Değiştirme

```php
$result = $zapi->apps->toggleStatus('app_id');
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->apps->getMetadata('app_id', 'key');

// Metadata güncelle
$result = $zapi->apps->updateMetadata('app_id', 'key', 'value');

// Metadata sil
$result = $zapi->apps->deleteMetadata('app_id', 'key');

// Tüm metadata
$allMetadata = $zapi->apps->getAllMetadata('app_id');
```

## 👑 Admin İşlemleri (Admin)

### Sistem Durumu

```php
$status = $zapi->admin->getSystemStatus();
```

### Kullanıcı Yönetimi

```php
// Kullanıcıları listele
$users = $zapi->admin->getUsers();

// Kullanıcı detayı
$user = $zapi->admin->getUser('user_id');

// Kullanıcı güncelle
$result = $zapi->admin->updateUser('user_id', [
    'role' => 'admin',
    'isActive' => true
]);

// Kullanıcı sil
$result = $zapi->admin->deleteUser('user_id');
```

### Sistem Ayarları

```php
// Ayarları al
$settings = $zapi->admin->getSettings();

// Ayar güncelle
$result = $zapi->admin->updateSetting('key', 'value');
```

### Cron İşlemleri

```php
// Cron görevlerini listele
$crons = $zapi->admin->getCronJobs();

// Cron tetikle
$result = $zapi->admin->triggerCron('job_name');

// Aylık sıfırlama
$result = $zapi->admin->triggerMonthlyReset();
```

### Sistem Temizliği

```php
// Cache temizle
$result = $zapi->admin->clearCache();

// Log temizle
$result = $zapi->admin->clearLogs();

// Geçici dosyaları temizle
$result = $zapi->admin->clearTempFiles();
```

## 📊 Plan Yönetimi (Plans)

### Planları Listele

```php
$plans = $zapi->plans->list();
```

### Plan Oluştur

```php
$result = $zapi->plans->create([
    'name' => 'Pro Plan',
    'description' => 'Profesyonel plan',
    'price' => 29.99,
    'currency' => 'USD',
    'interval' => 'monthly',
    'features' => ['unlimited_requests', 'priority_support']
]);
```

### Plan Güncelle

```php
$result = $zapi->plans->update('plan_id', [
    'name' => 'Updated Pro Plan',
    'price' => 39.99
]);
```

### Plan Sil

```php
$result = $zapi->plans->delete('plan_id');
```

### Plan Detayı

```php
$plan = $zapi->plans->get('plan_id');
```

### Plan Özellikleri

```php
// Özellikleri listele
$features = $zapi->plans->getFeatures('plan_id');

// Özellik ekle
$result = $zapi->plans->addFeature('plan_id', [
    'name' => 'new_feature',
    'value' => 'unlimited'
]);

// Özellik güncelle
$result = $zapi->plans->updateFeature('plan_id', 'feature_id', [
    'value' => '1000'
]);

// Özellik sil
$result = $zapi->plans->removeFeature('plan_id', 'feature_id');
```

### Plan İstatistikleri

```php
$stats = $zapi->plans->getStats('plan_id');
```

### Plan Aktivasyonu

```php
$result = $zapi->plans->toggleStatus('plan_id');
```

## 💳 Abonelik (Subscription)

### Abonelik Detayları

```php
$subscription = $zapi->subscription->getDetails();
```

### Abonelik Yükseltme Kontrolü

```php
$upgrade = $zapi->subscription->checkUpgrade();
```

### Abonelik Güncelle

```php
$result = $zapi->subscription->update([
    'plan_id' => 'new_plan_id'
]);
```

### Abonelik İptal

```php
$result = $zapi->subscription->cancel();
```

### Abonelik Yenile

```php
$result = $zapi->subscription->renew();
```

## 👥 Rol Yönetimi (Roles)

### Rolleri Listele

```php
$roles = $zapi->roles->list();
```

### Rol Oluştur

```php
$result = $zapi->roles->create([
    'name' => 'Editor',
    'description' => 'İçerik editörü',
    'permissions' => ['content.read', 'content.write']
]);
```

### Rol Güncelle

```php
$result = $zapi->roles->update('role_id', [
    'name' => 'Senior Editor',
    'permissions' => ['content.read', 'content.write', 'content.delete']
]);
```

### Rol Sil

```php
$result = $zapi->roles->delete('role_id');
```

### Rol Detayı

```php
$role = $zapi->roles->get('role_id');
```

### Rol Kullanıcıları

```php
$users = $zapi->roles->getUsers('role_id');
```

### Mevcut İzinler

```php
$permissions = $zapi->roles->getAvailablePermissions();
```

### Rol Analitikleri

```php
$analytics = $zapi->roles->getAnalytics('role_id');
```

## 🔔 Bildirimler (Notifications)

### Bildirimleri Listele

```php
$notifications = $zapi->notifications->list([
    'limit' => 50,
    'offset' => 0
]);
```

### Bildirim Oluştur

```php
$result = $zapi->notifications->create([
    'title' => 'Yeni Bildirim',
    'message' => 'Bildirim mesajı',
    'type' => 'info',
    'recipients' => ['user_id_1', 'user_id_2']
]);
```

### Bildirim Gönder

```php
$result = $zapi->notifications->send([
    'notification_id' => 'notification_id'
]);
```

### E-posta Bildirimi

```php
$result = $zapi->notifications->sendEmail([
    'to' => 'user@example.com',
    'subject' => 'E-posta Konusu',
    'body' => 'E-posta içeriği',
    'template' => 'welcome'
]);
```

### SMS Bildirimi

```php
$result = $zapi->notifications->sendSMS([
    'to' => '+905551234567',
    'message' => 'SMS mesajı'
]);
```

### Push Bildirimi

```php
$result = $zapi->notifications->sendPush([
    'device_token' => 'device_token',
    'title' => 'Push Başlığı',
    'body' => 'Push mesajı'
]);
```

### Bildirim Şablonları

```php
// Şablonları listele
$templates = $zapi->notifications->getTemplates();

// Şablon oluştur
$result = $zapi->notifications->createTemplate([
    'name' => 'welcome',
    'subject' => 'Hoş Geldiniz',
    'body' => '{{name}} hoş geldiniz!'
]);

// Şablon güncelle
$result = $zapi->notifications->updateTemplate('template_id', [
    'subject' => 'Güncellenmiş Konu'
]);

// Şablon sil
$result = $zapi->notifications->deleteTemplate('template_id');
```

### Bildirim İstatistikleri

```php
$stats = $zapi->notifications->getStats();
```

## 📧 Mail Şablonları (MailTemplates)

### Şablonları Listele

```php
$templates = $zapi->mailTemplates->list();
```

### Şablon Oluştur

```php
$result = $zapi->mailTemplates->create([
    'name' => 'welcome_email',
    'subject' => 'Hoş Geldiniz',
    'body' => 'Merhaba {{name}}, hoş geldiniz!',
    'variables' => ['name', 'email']
]);
```

### Şablon Güncelle

```php
$result = $zapi->mailTemplates->update('template_id', [
    'subject' => 'Güncellenmiş Konu',
    'body' => 'Güncellenmiş içerik'
]);
```

### Şablon Sil

```php
$result = $zapi->mailTemplates->delete('template_id');
```

### Şablon Detayı

```php
$template = $zapi->mailTemplates->get('template_id');
```

### Şablon Test Et

```php
$result = $zapi->mailTemplates->test('template_id', [
    'variables' => ['name' => 'Test User']
]);
```

### Şablon Durumu

```php
$result = $zapi->mailTemplates->toggleStatus('template_id');
```

### Şablon Klonla

```php
$result = $zapi->mailTemplates->clone('template_id', [
    'name' => 'cloned_template'
]);
```

## 🔗 Webhook Yönetimi (Webhook)

### Webhook'ları Listele

```php
$webhooks = $zapi->webhook->list();
```

### Webhook Oluştur

```php
$result = $zapi->webhook->create([
    'url' => 'https://yourapp.com/webhook',
    'events' => ['user.created', 'response.created'],
    'secret' => 'webhook_secret'
]);
```

### Webhook Güncelle

```php
$result = $zapi->webhook->update('webhook_id', [
    'url' => 'https://newapp.com/webhook',
    'events' => ['user.updated']
]);
```

### Webhook Sil

```php
$result = $zapi->webhook->delete('webhook_id');
```

### Webhook Detayı

```php
$webhook = $zapi->webhook->get('webhook_id');
```

### Webhook Test Et

```php
$result = $zapi->webhook->test('webhook_id');
```

### Webhook Durumu

```php
$result = $zapi->webhook->toggleStatus('webhook_id');
```

### Webhook Logları

```php
$logs = $zapi->webhook->getLogs('webhook_id');
```

## 📋 Metadata Yönetimi (Metadata)

### Metadata Al

```php
$metadata = $zapi->metadata->get('key');
```

### Metadata Güncelle

```php
$result = $zapi->metadata->set('key', 'value');
```

### Metadata Sil

```php
$result = $zapi->metadata->delete('key');
```

### Tüm Metadata

```php
$allMetadata = $zapi->metadata->getAll();
```

### Metadata Arama

```php
$results = $zapi->metadata->search('pattern');
```

### Metadata İstatistikleri

```php
$stats = $zapi->metadata->getStats();
```

## 🔧 OAuth Metadata (OAuthMetadata)

### OAuth Metadata Al

```php
$metadata = $zapi->oauthMetadata->get('provider', 'key');
```

### OAuth Metadata Güncelle

```php
$result = $zapi->oauthMetadata->set('provider', 'key', 'value');
```

### OAuth Metadata Sil

```php
$result = $zapi->oauthMetadata->delete('provider', 'key');
```

### OAuth Metadata Listele

```php
$allMetadata = $zapi->oauthMetadata->list('provider');
```

## ⚙️ Fonksiyonlar (Functions)

### Fonksiyonları Listele

```php
$functions = $zapi->functions->list();
```

### Fonksiyon Oluştur

```php
$result = $zapi->functions->create([
    'name' => 'calculate_sum',
    'description' => 'İki sayıyı toplar',
    'code' => 'function calculateSum(a, b) { return a + b; }',
    'parameters' => ['a', 'b']
]);
```

### Fonksiyon Güncelle

```php
$result = $zapi->functions->update('function_id', [
    'description' => 'Güncellenmiş açıklama',
    'code' => 'function calculateSum(a, b) { return a + b; }'
]);
```

### Fonksiyon Sil

```php
$result = $zapi->functions->delete('function_id');
```

### Fonksiyon Çalıştır

```php
$result = $zapi->functions->execute('function_id', [
    'a' => 5,
    'b' => 3
]);
```

### Fonksiyon Test Et

```php
$result = $zapi->functions->test('function_id', [
    'a' => 5,
    'b' => 3
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

### Sistem Metrikleri

```php
$metrics = $zapi->system->getMetrics();
```

## 📊 Bilgi (Info)

### Sistem Durumu

```php
$status = $zapi->info->getStatus();
```

### AI Modelleri

```php
$models = $zapi->info->getAIModels();
```

### Sistem Bilgileri

```php
$info = $zapi->info->getInfo();
```

## 🔧 Yapılandırma (Config)

### Yapılandırma Al

```php
$config = $zapi->config->get();
```

### Yapılandırma Güncelle

```php
$result = $zapi->config->update([
    'setting_name' => 'setting_value'
]);
```

## 📝 Loglar (Logs)

### Logları Listele

```php
$logs = $zapi->logs->list([
    'level' => 'error',
    'limit' => 100
]);
```

### Log Temizle

```php
$result = $zapi->logs->clear();
```

### Log Temizliği

```php
$result = $zapi->logs->cleanup();
```

## 🐛 Debug (Debug)

### Debug Modelleri

```php
$models = $zapi->debug->getModels();
```

### Debug Bilgileri

```php
$info = $zapi->debug->getInfo();
```

## 📚 Dokümantasyon (Docs)

### Dokümantasyon Listesi

```php
$docs = $zapi->docs->list();
```

### Dokümantasyon Al

```php
$doc = $zapi->docs->get('filename');
```

## 💾 Yedekleme (Backup)

### Yedek Oluştur

```php
$result = $zapi->backup->create([
    'type' => 'full',
    'description' => 'Tam yedek'
]);
```

### Yedekleri Listele

```php
$backups = $zapi->backup->list();
```

### Yedek Geri Yükle

```php
$result = $zapi->backup->restore('backup_id');
```

### Yedek Sil

```php
$result = $zapi->backup->delete('backup_id');
```

### Yedek Detayı

```php
$backup = $zapi->backup->get('backup_id');
```

### Yedek Kaydı

```php
$record = $zapi->backup->getRecord('model', 'record_id');
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

**API Referansı tamamlandı!** Tüm 31 endpoint sınıfı ve 200+ metod ile eksiksiz referans dokümantasyonu. 🚀