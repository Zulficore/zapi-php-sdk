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
    'firstName' => 'John',
    'lastName' => 'Doe'
]);
```

### Kullanıcı Girişi

```php
$result = $zapi->auth->login('user@example.com', 'password123');
```

### E-posta Doğrulama

```php
$result = $zapi->auth->sendVerification('user@example.com', 'email');
$result = $zapi->auth->verifyEmail('user@example.com', '123456');
```

### Şifre İşlemleri

```php
// Şifre sıfırlama isteği
$result = $zapi->auth->requestPasswordReset('user@example.com');

// Şifre sıfırlama
$result = $zapi->auth->resetPassword('reset_token', 'new_password');

// Şifre değiştirme
$result = $zapi->auth->changePassword('old_password', 'new_password');
```

### OTP İşlemleri

```php
// OTP gönder (telefon numarasına)
$result = $zapi->auth->sendOTP('+905551234567', 'login');

// OTP doğrula
$result = $zapi->auth->verifyOTP('+905551234567', '123456', 'login');
```

### Token İşlemleri

```php
// Token yenile
$result = $zapi->auth->refreshToken('refresh_token');

// Çıkış yap
$result = $zapi->auth->logout();

// Sağlık kontrolü
$result = $zapi->auth->healthCheck();
```

## 🔥 Firebase Kimlik Doğrulama (AuthFirebase)

### Google ile Giriş

```php
$result = $zapi->authFirebase->loginWithGoogle('firebase_token', [
    'returnSecureToken' => true
]);
```

### Apple ile Giriş

```php
$result = $zapi->authFirebase->loginWithApple('firebase_token', [
    'returnSecureToken' => true
]);
```

### Token İşlemleri

```php
// Token yenile
$result = $zapi->authFirebase->refreshToken('refresh_token');

// Profil güncelle
$result = $zapi->authFirebase->updateProfile([
    'displayName' => 'John Doe',
    'photoURL' => 'https://example.com/photo.jpg'
]);

// Çıkış yap
$result = $zapi->authFirebase->logout();
```

### Sistem Bilgileri

```php
// SDK durumu
$result = $zapi->authFirebase->getSDKStatus();

// Debug bilgileri
$result = $zapi->authFirebase->getDebugInfo();

// Sağlık kontrolü
$result = $zapi->authFirebase->healthCheck();
```

## 🔑 OAuth Kimlik Doğrulama (AuthOAuth)

### Google OAuth

```php
// Google giriş başlat
$result = $zapi->authOAuth->initiateGoogleLogin('app_id', [
    'redirect_uri' => 'https://yourapp.com/callback'
]);

// Google callback işle
$result = $zapi->authOAuth->handleGoogleCallback('code', 'state', [
    'redirect_uri' => 'https://yourapp.com/callback'
]);
```

### Apple OAuth

```php
// Apple giriş başlat
$result = $zapi->authOAuth->initiateAppleLogin('app_id', [
    'redirect_uri' => 'https://yourapp.com/callback'
]);

// Apple callback işle
$result = $zapi->authOAuth->handleAppleCallback('code', 'state', [
    'redirect_uri' => 'https://yourapp.com/callback'
]);
```

### Hesap Bağlama

```php
// Hesap bağla
$result = $zapi->authOAuth->linkAccount('google', 'access_token', [
    'merge_data' => true
]);

// Hesap bağlantısını kaldır
$result = $zapi->authOAuth->unlinkAccount('google');
```

### Sayfa Yönetimi

```php
// Başarı sayfası
$result = $zapi->authOAuth->getSuccessPage([
    'message' => 'Giriş başarılı!'
]);

// Hata sayfası
$result = $zapi->authOAuth->getErrorPage([
    'error' => 'Giriş başarısız!'
]);
```

### Test ve Debug

```php
// Sandbox test
$result = $zapi->authOAuth->sandboxTest('google');

// Debug bilgileri
$result = $zapi->authOAuth->getDebugInfo('google');
```

### Metadata Yönetimi

```php
// Metadata al
$result = $zapi->authOAuth->getMetadata('app_id', 'path');

// Metadata güncelle
$result = $zapi->authOAuth->updateMetadata('app_id', 'path', ['value']);

// Metadata patch
$result = $zapi->authOAuth->patchMetadata('app_id', 'path', ['value']);

// Metadata sil
$result = $zapi->authOAuth->deleteMetadata('app_id', 'path');
```

## 👥 Kullanıcı Yönetimi (User)

### Profil İşlemleri

```php
// Profil bilgilerini al
$profile = $zapi->user->getProfile();

// Profil güncelle
$result = $zapi->user->updateProfile([
    'firstName' => 'John',
    'lastName' => 'Doe',
    'bio' => 'Yeni biyografi'
]);
```

### Avatar İşlemleri

```php
// Avatar yükle
$result = $zapi->user->uploadAvatar('/path/to/avatar.jpg');

// Avatar sil
$result = $zapi->user->deleteAvatar();
```

### Kullanım İstatistikleri

```php
$usage = $zapi->user->getUsage();
```

### Yanıt Yönetimi

```php
// Kullanıcının yanıtlarını listele
$responses = $zapi->user->getResponses();

// Belirli bir yanıtı al
$response = $zapi->user->getResponse('response_id');

// Yanıtı sil
$result = $zapi->user->deleteResponse('response_id');

// Yanıtı dışa aktar
$export = $zapi->user->exportResponse('response_id', 'json');

// Son yanıtı al
$lastResponse = $zapi->user->getLastResponse();
```

### Hesap Yönetimi

```php
// Hesabı deaktive et
$result = $zapi->user->deactivateAccount('Hesap kapatma nedeni');

// Hesabı sil
$result = $zapi->user->deleteAccount('password');
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->user->getMetadata('key');

// Metadata güncelle
$result = $zapi->user->updateMetadata('key', ['value' => 'data']);

// Metadata patch
$result = $zapi->user->patchMetadata('key', ['new_field' => 'value']);

// Metadata sil
$result = $zapi->user->deleteMetadata('key');
```

## 🤖 AI Sohbet (Responses)

### Sohbet Oluşturma

```php
$response = $zapi->responses->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Merhaba!']
    ],
    'temperature' => 0.7,
    'max_tokens' => 1000
]);
```

### Yanıt Yönetimi

```php
// Yanıtları listele
$responses = $zapi->responses->list();

// Yanıtı al
$response = $zapi->responses->get('response_id');

// Yanıtı güncelle
$result = $zapi->responses->update('response_id', [
    'title' => 'Yeni Başlık'
]);

// Yanıtı sil
$result = $zapi->responses->delete('response_id');

// Yanıtı dışa aktar
$export = $zapi->responses->export('response_id', 'json');
```

### İstatistikler ve Arama

```php
// İstatistikleri al
$stats = $zapi->responses->getStats();

// Yanıt ara
$results = $zapi->responses->search([
    'query' => 'arama terimi',
    'limit' => 20
]);

// Kategorileri al
$categories = $zapi->responses->getCategories();

// Etiketleri al
$tags = $zapi->responses->getTags();
```

### Favori ve Paylaşım

```php
// Favori durumunu değiştir
$result = $zapi->responses->toggleFavorite('response_id');

// Yanıtı paylaş
$result = $zapi->responses->share('response_id', [
    'public' => true,
    'expires_at' => '2025-12-31'
]);
```

## 🧠 AI Sağlayıcıları (AIProvider)

### Sağlayıcı Yönetimi

```php
// Sağlayıcıları listele
$providers = $zapi->aiProvider->list();

// Sağlayıcı oluştur
$result = $zapi->aiProvider->create([
    'name' => 'OpenAI',
    'type' => 'openai',
    'api_key' => 'your_api_key'
]);

// Sağlayıcıyı al
$provider = $zapi->aiProvider->get('provider_id');

// Sağlayıcıyı güncelle
$result = $zapi->aiProvider->update('provider_id', [
    'name' => 'Updated OpenAI'
]);

// Sağlayıcıyı sil
$result = $zapi->aiProvider->delete('provider_id');

// Sağlayıcıyı test et
$result = $zapi->aiProvider->test('provider_id');
```

### Model Yönetimi

```php
// Modelleri listele
$models = $zapi->aiProvider->getModels();

// Modeli al
$model = $zapi->aiProvider->getModel('model_id');

// Modeli güncelle
$result = $zapi->aiProvider->updateModel('model_id', [
    'display_name' => 'Updated Model'
]);

// Modeli sil
$result = $zapi->aiProvider->deleteModel('model_id');

// Varsayılan modelleri al
$defaultModels = $zapi->aiProvider->getDefaultModels();
```

## 📝 İçerik Yönetimi (Content)

### İçerik İşlemleri

```php
// İçerikleri listele
$content = $zapi->content->list();

// İçerik oluştur
$result = $zapi->content->create([
    'title' => 'Makale Başlığı',
    'content' => 'Makale içeriği...',
    'type' => 'article'
]);

// İçeriği al
$content = $zapi->content->get('content_id');

// İçeriği güncelle
$result = $zapi->content->update('content_id', [
    'title' => 'Güncellenmiş Başlık'
]);

// İçeriği sil
$result = $zapi->content->delete('content_id');
```

### Kategoriler ve Tipler

```php
// Kategorileri al
$categories = $zapi->content->getCategories();

// Tipleri al
$types = $zapi->content->getTypes();

// Dilleri al
$languages = $zapi->content->getLanguages();
```

### Arama ve İstatistikler

```php
// Gelişmiş arama
$results = $zapi->content->searchAdvanced([
    'query' => 'arama terimi',
    'type' => 'article',
    'category' => 'technology'
]);

// İstatistikleri al
$stats = $zapi->content->getStats();
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->content->getMetadata('content_id', 'path');

// Metadata güncelle
$result = $zapi->content->updateMetadata('content_id', 'path', ['value']);

// Metadata patch
$result = $zapi->content->patchMetadata('content_id', 'path', ['value']);

// Metadata sil
$result = $zapi->content->deleteMetadata('content_id', 'path');
```

### Public İçerik

```php
// Public içeriği al
$publicContent = $zapi->content->getPublic('slug');
```

## 🎵 Ses İşleme (Audio)

### Metin-Ses Dönüşümü

```php
$result = $zapi->audio->textToSpeech('Merhaba dünya!', 'alloy', [
    'response_format' => 'mp3',
    'speed' => 1.0
]);
```

### Ses-Metin Dönüşümü

```php
$result = $zapi->audio->speechToText('/path/to/audio.mp3', [
    'model' => 'whisper-1',
    'language' => 'tr'
]);
```

### Ses Çevirisi

```php
$result = $zapi->audio->translateAudio('/path/to/audio.mp3', 'en', [
    'model' => 'whisper-1'
]);
```

## 🖼️ Görsel İşleme (Images)

### Görsel Oluşturma

```php
$result = $zapi->images->generate('A beautiful sunset over mountains', [
    'model' => 'dall-e-3',
    'size' => '1024x1024',
    'quality' => 'standard'
]);
```

### Görsel Düzenleme

```php
$result = $zapi->images->edit('/path/to/image.png', 'Add a rainbow to the sky', [
    'mask' => '/path/to/mask.png'
]);
```

### Görsel Varyasyonları

```php
$result = $zapi->images->createVariations('/path/to/image.png', [
    'n' => 4,
    'size' => '1024x1024'
]);
```

## 🔍 Embeddings (Embeddings)

### Embedding Oluşturma

```php
$result = $zapi->embeddings->create('Bu metin için embedding oluştur', 'text-embedding-ada-002', [
    'encoding_format' => 'float'
]);
```

## ⚡ Gerçek Zamanlı (Realtime)

### Session Yönetimi

```php
// Session'ları listele
$sessions = $zapi->realtime->getSessions();

// Session'ı devam ettir
$result = $zapi->realtime->resumeSession('session_id');

// Session geçmişini al
$history = $zapi->realtime->getSessionHistory('session_id');

// Session oluştur
$result = $zapi->realtime->createSession([
    'name' => 'Chat Session',
    'model' => 'gpt-4'
]);

// Session'ı al
$session = $zapi->realtime->getSession('session_id');

// Session'ı sil
$result = $zapi->realtime->deleteSession('session_id');
```

### Sistem Bilgileri

```php
// Modelleri al
$models = $zapi->realtime->getModels();

// Stream bilgilerini al
$streamInfo = $zapi->realtime->getStreamInfo();

// İstatistikleri al
$stats = $zapi->realtime->getStats();
```

## 📁 Dosya Yönetimi (Upload)

### Dosya İşlemleri

```php
// Dosya yükle
$result = $zapi->upload->upload('/path/to/file.pdf', [
    'type' => 'document',
    'public' => false
]);

// Dosyaları listele
$files = $zapi->upload->list();

// Dosyayı al
$file = $zapi->upload->get('file_id');

// Dosyayı sil
$result = $zapi->upload->delete('file_id');
```

### İstatistikler ve Temizlik

```php
// İstatistikleri al
$stats = $zapi->upload->getStats();

// Temizlik yap
$result = $zapi->upload->cleanup();
```

### İlerleme Takibi

```php
// Yükleme ilerlemesini al
$progress = $zapi->upload->getProgress('upload_id');

// Tüm ilerlemeleri al
$allProgress = $zapi->upload->getAllProgress();
```

### Signed URL

```php
// Signed URL oluştur
$result = $zapi->upload->createSignedUrl('file_id', [
    'expires_in' => 3600
]);
```

## 🔑 API Anahtarları (APIKeys)

### Anahtar Yönetimi

```php
// Anahtarları listele
$keys = $zapi->apiKeys->list();

// Anahtar oluştur
$result = $zapi->apiKeys->create([
    'name' => 'My API Key',
    'permissions' => ['read', 'write']
]);

// Anahtarı al
$key = $zapi->apiKeys->get('key_id');

// Anahtarı güncelle
$result = $zapi->apiKeys->update('key_id', [
    'name' => 'Updated Name'
]);

// Anahtarı sil
$result = $zapi->apiKeys->delete('key_id');
```

### Kullanım ve Roller

```php
// Anahtar kullanımını al
$usage = $zapi->apiKeys->getUsage('key_id');

// Mevcut rolleri al
$roles = $zapi->apiKeys->getAvailableRoles();

// Anahtarı döndür
$result = $zapi->apiKeys->rotate('key_id');

// Anahtar lookup
$result = $zapi->apiKeys->lookup('api_key_string');

// Anahtar durumunu değiştir
$result = $zapi->apiKeys->toggleStatus('key_id');
```

## 📱 Uygulama Yönetimi (Apps)

### Uygulama İşlemleri

```php
// Uygulamaları listele
$apps = $zapi->apps->list();

// Uygulama oluştur
$result = $zapi->apps->create([
    'name' => 'My App',
    'description' => 'App açıklaması'
]);

// Uygulamayı al
$app = $zapi->apps->get('app_id');

// Uygulamayı güncelle
$result = $zapi->apps->update('app_id', [
    'name' => 'Updated App Name'
]);

// Uygulamayı sil
$result = $zapi->apps->delete('app_id');
```

### İstatistikler ve Yönetim

```php
// Genel istatistikleri al
$stats = $zapi->apps->getStats();

// Uygulama istatistiklerini al
$appStats = $zapi->apps->getAppStats('app_id');

// Kullanımı sıfırla
$result = $zapi->apps->resetUsage('app_id');

// Durumu değiştir
$result = $zapi->apps->toggleStatus('app_id');
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->apps->getMetadata('app_id', 'path');

// Metadata güncelle
$result = $zapi->apps->updateMetadata('app_id', 'path', ['value']);

// Metadata patch
$result = $zapi->apps->patchMetadata('app_id', 'path', ['value']);

// Metadata sil
$result = $zapi->apps->deleteMetadata('app_id', 'path');
```

## 👑 Admin İşlemleri (Admin)

### Dashboard ve Sistem

```php
// Dashboard'u al
$dashboard = $zapi->admin->getDashboard();

// Queue'yu al
$queue = $zapi->admin->getQueue();

// Cron'ları al
$crons = $zapi->admin->getCrons();

// Cron tetikle
$result = $zapi->admin->triggerCron('job_name');

// Aylık sıfırlama
$result = $zapi->admin->triggerMonthlyReset();
```

### Sistem Durumu

```php
// Sağlık durumunu al
$health = $zapi->admin->getHealth();

// Metrikleri al
$metrics = $zapi->admin->getMetrics();

// Cache temizle
$result = $zapi->admin->clearCache('all');
```

### Yedekleme

```php
// Yedek oluştur
$result = $zapi->admin->createBackup([
    'type' => 'full'
]);

// Yedek geri yükle
$result = $zapi->admin->restoreBackup('backup_id');
```

## 📊 Plan Yönetimi (Plans)

### Plan İşlemleri

```php
// Planları listele
$plans = $zapi->plans->list();

// Plan karşılaştır
$compare = $zapi->plans->compare(['plan1', 'plan2']);

// Plan oluştur
$result = $zapi->plans->create([
    'name' => 'Pro Plan',
    'price' => 29.99,
    'currency' => 'USD'
]);

// Planı al
$plan = $zapi->plans->get('plan_id');

// Planı güncelle
$result = $zapi->plans->update('plan_id', [
    'name' => 'Updated Plan'
]);

// Planı sil
$result = $zapi->plans->delete('plan_id');

// Plan durumunu değiştir
$result = $zapi->plans->toggleStatus('plan_id');
```

### Aboneler ve Analitik

```php
// Aboneleri al
$subscribers = $zapi->plans->getSubscribers('plan_id');

// Analitikleri al
$analytics = $zapi->plans->getAnalytics('plan_id');
```

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->plans->getMetadata('plan_id', 'path');

// Metadata güncelle
$result = $zapi->plans->updateMetadata('plan_id', 'path', ['value']);

// Metadata patch
$result = $zapi->plans->patchMetadata('plan_id', 'path', ['value']);

// Metadata sil
$result = $zapi->plans->deleteMetadata('plan_id', 'path');
```

## 💳 Abonelik (Subscription)

### Abonelik İşlemleri

```php
// Abonelik oluştur
$result = $zapi->subscription->create([
    'plan_id' => 'plan_id'
]);

// Aboneliği iptal et
$result = $zapi->subscription->cancel('İptal nedeni');

// Aboneliği yenile
$result = $zapi->subscription->renew();

// Analitikleri al
$analytics = $zapi->subscription->getAnalytics();

// Detayları al
$details = $zapi->subscription->getDetails();

// Yükseltme kontrolü
$upgrade = $zapi->subscription->checkUpgrade();
```

## 👥 Rol Yönetimi (Roles)

### Rol İşlemleri

```php
// Rolleri listele
$roles = $zapi->roles->list();

// Rol oluştur
$result = $zapi->roles->create([
    'name' => 'Editor',
    'permissions' => ['content.read', 'content.write']
]);

// Rolü al
$role = $zapi->roles->get('role_id');

// Rolü güncelle
$result = $zapi->roles->update('role_id', [
    'name' => 'Senior Editor'
]);

// Rolü sil
$result = $zapi->roles->delete('role_id');
```

### Kullanıcılar ve İzinler

```php
// Rol kullanıcılarını al
$users = $zapi->roles->getUsers('role_id');

// Mevcut izinleri al
$permissions = $zapi->roles->getAvailablePermissions();

// Analitikleri al
$analytics = $zapi->roles->getAnalytics();
```

## 🔔 Bildirimler (Notifications)

### Bildirim İşlemleri

```php
// Bildirimleri listele
$notifications = $zapi->notifications->list();

// E-posta gönder
$result = $zapi->notifications->sendEmail([
    'to' => 'user@example.com',
    'subject' => 'Konu',
    'body' => 'İçerik'
]);

// Toplu e-posta gönder
$result = $zapi->notifications->sendBulkEmail([
    'recipients' => ['user1@example.com', 'user2@example.com'],
    'subject' => 'Toplu E-posta',
    'body' => 'İçerik'
]);

// SMS gönder
$result = $zapi->notifications->sendSMS([
    'to' => '+905551234567',
    'message' => 'SMS mesajı'
]);

// Toplu SMS gönder
$result = $zapi->notifications->sendBulkSMS([
    'recipients' => ['+905551234567', '+905559876543'],
    'message' => 'Toplu SMS'
]);
```

### Log ve Analitik

```php
// Log'u al
$log = $zapi->notifications->getLog('log_id');

// Analitikleri al
$analytics = $zapi->notifications->getAnalytics();

// Tekrar dene
$result = $zapi->notifications->retry('log_id');
```

### Ayarlar ve Takip

```php
// Ayarları al
$settings = $zapi->notifications->getSettings();

// Ayarları güncelle
$result = $zapi->notifications->updateSettings([
    'email_enabled' => true,
    'sms_enabled' => false
]);

// E-posta takibi
$result = $zapi->notifications->trackEmail('tracking_id');

// Genel takip
$result = $zapi->notifications->track('log_id');
```

## 📧 Mail Şablonları (MailTemplates)

### Şablon İşlemleri

```php
// Şablonları listele
$templates = $zapi->mailTemplates->list();

// Şablon oluştur
$result = $zapi->mailTemplates->create([
    'name' => 'welcome_email',
    'subject' => 'Hoş Geldiniz',
    'body' => 'Merhaba {{name}}, hoş geldiniz!'
]);

// Şablonu al
$template = $zapi->mailTemplates->get('template_id');

// Şablonu güncelle
$result = $zapi->mailTemplates->update('template_id', [
    'subject' => 'Güncellenmiş Konu'
]);

// Şablonu sil
$result = $zapi->mailTemplates->delete('template_id');

// Şablon durumunu değiştir
$result = $zapi->mailTemplates->toggleStatus('template_id');
```

### Önizleme ve Klonlama

```php
// Şablon önizlemesi
$result = $zapi->mailTemplates->preview('template_id', [
    'name' => 'Test User'
]);

// Şablon klonla
$result = $zapi->mailTemplates->clone('template_id', [
    'name' => 'cloned_template'
]);
```

## 🔗 Webhook Yönetimi (Webhook)

### Webhook İşlemleri

```php
// Webhook'ları listele
$webhooks = $zapi->webhook->list();

// Webhook oluştur
$result = $zapi->webhook->create([
    'url' => 'https://yourapp.com/webhook',
    'events' => ['user.created', 'response.created']
]);

// Webhook'u al
$webhook = $zapi->webhook->get('webhook_id');

// Webhook'u güncelle
$result = $zapi->webhook->update('webhook_id', [
    'url' => 'https://newapp.com/webhook'
]);

// Webhook'u sil
$result = $zapi->webhook->delete('webhook_id');

// Webhook'u test et
$result = $zapi->webhook->test('webhook_id');
```

## 📋 Metadata Yönetimi (Metadata)

### Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->metadata->get('entity_type', 'entity_id', 'path');

// Metadata güncelle
$result = $zapi->metadata->update('entity_type', 'entity_id', 'path', ['value']);

// Metadata patch
$result = $zapi->metadata->patch('entity_type', 'entity_id', 'path', ['value']);

// Metadata sil
$result = $zapi->metadata->delete('entity_type', 'entity_id', 'path');
```

## 🔧 OAuth Metadata (OAuthMetadata)

### OAuth Metadata İşlemleri

```php
// Metadata al
$metadata = $zapi->oauthMetadata->get('app_id', 'path');

// Metadata güncelle
$result = $zapi->oauthMetadata->update('app_id', 'path', ['value']);

// Metadata patch
$result = $zapi->oauthMetadata->patch('app_id', 'path', ['value']);

// Metadata sil
$result = $zapi->oauthMetadata->delete('app_id', 'path');
```

## ⚙️ Fonksiyonlar (Functions)

### Fonksiyon İşlemleri

```php
// Fonksiyonları listele
$functions = $zapi->functions->list();

// Fonksiyon oluştur
$result = $zapi->functions->create([
    'name' => 'calculate_sum',
    'description' => 'İki sayıyı toplar',
    'code' => 'function calculateSum(a, b) { return a + b; }'
]);

// Fonksiyonu al
$function = $zapi->functions->get('function_id');

// Fonksiyonu güncelle
$result = $zapi->functions->update('function_id', [
    'description' => 'Güncellenmiş açıklama'
]);

// Fonksiyonu sil
$result = $zapi->functions->delete('function_id');
```

### Fonksiyon Çalıştırma

```php
// Fonksiyonu çalıştır
$result = $zapi->functions->execute('function_id', [
    'a' => 5,
    'b' => 3
]);

// Fonksiyonu test et
$result = $zapi->functions->test('function_id', [
    'a' => 5,
    'b' => 3
]);
```

## 🛠️ Sistem (System)

### Sistem İşlemleri

```php
// Sistemi yeniden başlat
$result = $zapi->system->restart();

// Sistem durumunu al
$status = $zapi->system->getStatus();

// Bellek kullanımını al
$memory = $zapi->system->getMemory();
```

## 📊 Bilgi (Info)

### Sistem Bilgileri

```php
// Sağlık durumunu al
$health = $zapi->info->getHealth();

// Metrikleri al
$metrics = $zapi->info->getMetrics();

// Durumu al
$status = $zapi->info->getStatus();

// AI modellerini al
$models = $zapi->info->getAIModels();
```

## 🔧 Yapılandırma (Config)

### Yapılandırma İşlemleri

```php
// Yapılandırmayı al
$config = $zapi->config->get();
```

## 📝 Loglar (Logs)

### Log İşlemleri

```php
// Logları listele
$logs = $zapi->logs->list();

// Log'u al
$log = $zapi->logs->get('log_id');

// İstatistikleri al
$stats = $zapi->logs->getStats();

// Temizlik yap
$result = $zapi->logs->cleanup();

// Logları temizle
$result = $zapi->logs->clear();
```

## 🐛 Debug (Debug)

### Debug İşlemleri

```php
// Modelleri al
$models = $zapi->debug->getModels();

// Provider manager'ı al
$providerManager = $zapi->debug->getProviderManager();
```

## 📚 Dokümantasyon (Docs)

### Dokümantasyon İşlemleri

```php
// Dokümantasyon listesini al
$docs = $zapi->docs->list();

// Dokümantasyonu al
$doc = $zapi->docs->get('filename');
```

## 💾 Yedekleme (Backup)

### Yedekleme İşlemleri

```php
// Yedekleri listele
$backups = $zapi->backup->list();

// Yedeği al
$backup = $zapi->backup->get('backup_id');

// Kayıt yedeklerini al
$recordBackups = $zapi->backup->getRecordBackups('model', 'record_id');

// Yedeği sil
$result = $zapi->backup->delete('backup_id');
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

**API Referansı tamamlandı!** Tüm 31 endpoint sınıfı ve 200+ gerçek metod ile eksiksiz referans dokümantasyonu. 🚀
