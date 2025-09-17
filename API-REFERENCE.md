# ZAPI PHP SDK - API Referansı

## Genel Bakış
ZAPI SDK, ZAPI servislerine erişim için geliştirilmiş kapsamlı bir SDK'dır. **248 metod** ile tüm API endpoint'lerine erişim sağlar.

## Kurulum
```bash
composer require zapi/php-sdk
```

## Temel Kullanım
```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

$zapi = new ZAPI('your-api-key', 'your-app-id', 'https://api.zapi.com');
```

## Endpoint'ler

### Auth Endpoint (18 metod)
- `register(array $data)` - Kullanıcı kaydı
- `login(?string $email, ?string $phone, string $password, array $options)` - Kullanıcı girişi
- `sendVerification(?string $email, ?string $phone, string $type)` - Doğrulama kodu gönder
- `verifyEmail(string $token)` - E-posta doğrulama
- `verify(?string $email, ?string $phone, string $code, string $type)` - Kod doğrulama
- `forgotPassword(?string $email, ?string $phone)` - Şifre sıfırlama talebi
- `resetPassword(string $code, string $newPassword)` - Şifre sıfırlama
- `sendOTP(?string $mail, ?string $phone, string $phonePrefix, string $firstName, string $lastName, string $name, string $surname, ?string $appId)` - OTP gönder
- `verifyOTP(?string $phone, ?string $phonePrefix, ?string $email, string $otpCode)` - OTP doğrulama
- `getProfile()` - Profil bilgilerini getir
- `updateProfile(array $data)` - Profil güncelle
- `changePassword(string $currentPassword, string $newPassword)` - Şifre değiştir
- `refresh(string $refreshToken)` - Token yenile
- `logout()` - Çıkış yap
- `verifyToken(string $token)` - Token doğrula
- `health()` - Sağlık kontrolü

### User Endpoint (15 metod)
- `getProfile()` - Profil bilgilerini getir
- `updateProfile(array $data)` - Profil güncelle
- `getUsage()` - Kullanım istatistiklerini getir
- `getResponses(array $options)` - Yanıtları listele
- `getResponse(string $responseId)` - Yanıt detayını getir
- `deleteResponse(string $responseId)` - Yanıtı sil
- `getLastResponse()` - Son yanıtı getir
- `deleteAccount()` - Hesabı sil
- `getMetadata(string $path)` - Metadata getir
- `updateMetadata(string $path, array $value)` - Metadata güncelle
- `patchMetadata(string $path, array $value)` - Metadata kısmi güncelle
- `deleteMetadata(string $path)` - Metadata sil
- `getConversations(array $options)` - Konuşmaları listele
- `getConversation(string $responseId)` - Konuşma detayını getir

### Admin Endpoint (15 metod)
- `getDashboard()` - Dashboard bilgilerini getir
- `clearCache(?string $pattern)` - Cache temizle
- `getStats()` - İstatistikleri getir
- `getQueueStats()` - Kuyruk istatistiklerini getir
- `pauseQueue()` - Kuyruğu duraklat
- `resumeQueue()` - Kuyruğu devam ettir
- `cleanQueue(string $type)` - Kuyruğu temizle
- `getCronStatus()` - Cron durumunu getir
- `startCron(string $jobName)` - Cron başlat
- `stopCron(string $jobName)` - Cron durdur
- `triggerDailyReset()` - Günlük sıfırlama tetikle
- `getSystemInfo()` - Sistem bilgilerini getir
- `triggerMonthlyReset()` - Aylık sıfırlama tetikle
- `getBackup(string $key)` - Backup bilgilerini getir
- `getRestore(string $key, ?string $backup, ?string $tables)` - Restore bilgilerini getir

### Apps Endpoint (11 metod)
- `list(array $options)` - Uygulamaları listele
- `create(array $data)` - Uygulama oluştur
- `get(string $appId)` - Uygulama detayını getir
- `update(string $appId, array $data)` - Uygulama güncelle
- `delete(string $appId)` - Uygulama sil
- `getStats(array $options)` - Uygulama istatistiklerini getir
- `getAppStats(string $appId, array $options)` - Belirli uygulama istatistiklerini getir
- `resetUsage(string $appId)` - Kullanım sayaçlarını sıfırla
- `getMetadata(string $appId, string $path)` - Metadata getir
- `updateMetadata(string $appId, string $path, array $value)` - Metadata güncelle
- `deleteMetadata(string $appId, string $path)` - Metadata sil

### AIProvider Endpoint (14 metod)
- `list(array $options)` - AI sağlayıcıları listele
- `create(array $data)` - AI sağlayıcı oluştur
- `get(string $providerId)` - AI sağlayıcı detayını getir
- `update(string $providerId, array $data)` - AI sağlayıcı güncelle
- `delete(string $providerId)` - AI sağlayıcı sil
- `testProvider(string $providerId, ?string $overrideKey)` - Sağlayıcı test et
- `getModels(array $options)` - Modelleri listele
- `getModel(string $modelId)` - Model detayını getir
- `updateModel(string $modelId, array $data)` - Model güncelle
- `deleteModel(string $modelId)` - Model sil
- `getDefaultModels(string $category)` - Varsayılan modelleri getir
- `createModel(array $data)` - Model oluştur
- `testModel(string $modelId)` - Model test et
- `clearCache()` - Cache temizle

### Diğer Endpoint'ler

#### Functions (3 metod)
- `list(array $options)` - Fonksiyonları listele
- `create(array $data)` - Fonksiyon oluştur
- `test(string $functionId, array $testData)` - Fonksiyon test et

#### Audio (3 metod)
- `speech(array $data)` - Metni sese çevir
- `transcriptions(array $data)` - Sesi metne çevir
- `translations(array $data)` - Ses çevirisi yap

#### Images (3 metod)
- `generations(array $data)` - Görsel oluştur
- `edits(array $data)` - Görsel düzenle
- `variations(array $data)` - Görsel varyasyonu oluştur

#### Video (2 metod)
- `analyze(string $filePath, array $options)` - Video analiz et
- `transcribe(string $filePath, array $options)` - Video transkript et

#### Users (8 metod)
- `list(array $options)` - Kullanıcıları listele
- `getStats()` - Kullanıcı istatistiklerini getir
- `get(string $userId)` - Kullanıcı detayını getir
- `update(string $userId, array $data)` - Kullanıcı güncelle
- `delete(string $userId)` - Kullanıcı sil
- `getMetadata(string $userId, string $path)` - Metadata getir
- `updateMetadata(string $userId, string $path, array $value)` - Metadata güncelle
- `deleteMetadata(string $userId, string $path)` - Metadata sil

#### APIKeys (9 metod)
- `list(array $options)` - API anahtarlarını listele
- `create(array $data)` - API anahtarı oluştur
- `get(string $keyId)` - API anahtarı detayını getir
- `update(string $keyId, array $data)` - API anahtarı güncelle
- `delete(string $keyId)` - API anahtarı sil
- `getUsage(string $keyId)` - Kullanım istatistiklerini getir
- `getAvailableRoles()` - Mevcut rolleri getir
- `rotate(string $keyId)` - API anahtarı yenile
- `lookup(string $apiKey)` - API anahtarı ara

#### Content (14 metod)
- `list(array $options)` - İçerikleri listele
- `create(array $data)` - İçerik oluştur
- `get(string $contentId)` - İçerik detayını getir
- `update(string $contentId, array $data)` - İçerik güncelle
- `delete(string $contentId)` - İçerik sil
- `getCategories()` - Kategorileri getir
- `getTypes()` - Türleri getir
- `getLanguages()` - Dilleri getir
- `searchAdvanced(array $options)` - Gelişmiş arama
- `getStats()` - İstatistikleri getir
- `getMetadata(string $contentId, string $path)` - Metadata getir
- `updateMetadata(string $contentId, string $path, array $value)` - Metadata güncelle
- `deleteMetadata(string $contentId, string $path)` - Metadata sil
- `getPublic(string $slug)` - Genel içerik getir

#### Debug (1 metod)
- `getModels()` - Debug modellerini getir

#### Upload (9 metod)
- `upload(string $filePath, array $options)` - Dosya yükle
- `list(array $options)` - Yüklenen dosyaları listele
- `get(string $fileId)` - Dosya detayını getir
- `delete(string $fileId)` - Dosya sil
- `getStats()` - Yükleme istatistiklerini getir
- `cleanup()` - Temizlik yap
- `getProgress(string $uploadId)` - Yükleme ilerlemesini getir
- `getAllProgress()` - Tüm yükleme ilerlemelerini getir
- `createSignedUrl(string $fileId, array $options)` - İmzalı URL oluştur

#### System (3 metod)
- `restart()` - Sistemi yeniden başlat
- `getStatus()` - Sistem durumunu getir
- `getMemory()` - Bellek kullanımını getir

#### Notifications (12 metod)
- `list(array $options)` - Bildirimleri listele
- `sendEmail(array $data)` - E-posta gönder
- `sendBulkEmail(array $data)` - Toplu e-posta gönder
- `sendSMS(array $data)` - SMS gönder
- `sendBulkSMS(array $data)` - Toplu SMS gönder
- `getLog(string $logId)` - Bildirim logunu getir
- `getAnalytics(array $options)` - Analitikleri getir
- `retry(string $logId)` - Bildirimi yeniden dene
- `getSettings()` - Ayarları getir
- `updateSettings(array $data)` - Ayarları güncelle
- `trackEmail(string $trackingId)` - E-posta takip et
- `track(string $logId)` - Bildirim takip et

#### Webhook (5 metod)
- `list(array $options)` - Webhook'ları listele
- `create(array $data)` - Webhook oluştur
- `delete(string $webhookId)` - Webhook sil
- `test(string $webhookId)` - Webhook test et

#### Plans (11 metod)
- `list(array $options)` - Planları listele
- `compare(array $planIds)` - Planları karşılaştır
- `create(array $data)` - Plan oluştur
- `get(string $planId)` - Plan detayını getir
- `update(string $planId, array $data)` - Plan güncelle
- `delete(string $planId)` - Plan sil
- `getSubscribers(string $planId, array $options)` - Aboneleri listele
- `getAnalytics(string $planId, array $options)` - Analitikleri getir
- `getMetadata(string $planId, string $path)` - Metadata getir
- `updateMetadata(string $planId, string $path, array $value)` - Metadata güncelle
- `deleteMetadata(string $planId, string $path)` - Metadata sil

#### Subscription (6 metod)
- `create(array $data)` - Abonelik oluştur
- `cancel(string $reason)` - Abonelik iptal et
- `renew(array $data)` - Abonelik yenile
- `getAnalytics(array $options)` - Analitikleri getir
- `getDetails()` - Abonelik detaylarını getir
- `checkUpgrade()` - Yükseltme kontrolü yap

#### Roles (8 metod)
- `list(array $options)` - Rolleri listele
- `create(array $data)` - Rol oluştur
- `get(string $roleId)` - Rol detayını getir
- `update(string $roleId, array $data)` - Rol güncelle
- `delete(string $roleId)` - Rol sil
- `getUsers(string $roleId, array $options)` - Rol kullanıcılarını listele
- `getAvailablePermissions()` - Mevcut izinleri getir
- `getAnalytics()` - Rol analitiklerini getir

#### Backup (4 metod)
- `list(array $options)` - Backup'ları listele
- `get(string $backupId)` - Backup detayını getir
- `getRecordBackups(string $model, string $recordId)` - Kayıt backup'larını getir
- `delete(string $backupId)` - Backup sil

#### Logs (5 metod)
- `list(array $options)` - Logları listele
- `get(string $logId)` - Log detayını getir
- `getStats()` - Log istatistiklerini getir
- `cleanup(array $options)` - Log temizliği yap
- `clear()` - Logları temizle

#### Info (4 metod)
- `getHealth()` - Sağlık kontrolü yap
- `getMetrics()` - Metrikleri getir
- `getStatus()` - Durum bilgilerini getir
- `getAIModels()` - AI modellerini getir

#### Docs (2 metod)
- `list()` - Dokümantasyonları listele
- `get(string $filename)` - Dokümantasyon getir

#### Embeddings (1 metod)
- `create($input, string $model, array $options)` - Embedding oluştur

#### Config (1 metod)
- `get()` - Konfigürasyon getir

#### Realtime (9 metod)
- `getSessions(array $options)` - Oturumları listele
- `resumeSession(string $sessionId)` - Oturumu devam ettir
- `getSessionHistory(string $sessionId, array $options)` - Oturum geçmişini getir
- `createSession(array $data)` - Oturum oluştur
- `getSession(string $sessionId)` - Oturum detayını getir
- `deleteSession(string $sessionId)` - Oturum sil
- `getModels()` - Modelleri getir
- `getStreamInfo()` - Stream bilgilerini getir
- `getStats()` - İstatistikleri getir

#### Responses (9 metod)
- `create(array $data)` - Yanıt oluştur
- `list(array $options)` - Yanıtları listele
- `get(string $responseId)` - Yanıt detayını getir
- `delete(string $responseId)` - Yanıt sil
- `getStats(array $options)` - İstatistikleri getir
- `search(array $options)` - Yanıt ara
- `getHistory(string $responseId)` - Yanıt geçmişini getir
- `getStatusHistory(string $responseId)` - Durum geçmişini getir
- `cancel(string $responseId)` - Yanıtı iptal et

#### MailTemplates (7 metod)
- `list(array $options)` - Şablonları listele
- `create(array $data)` - Şablon oluştur
- `get(string $templateId)` - Şablon detayını getir
- `update(string $templateId, array $data)` - Şablon güncelle
- `delete(string $templateId)` - Şablon sil
- `preview(string $templateId, array $variables)` - Şablon önizle
- `clone(string $templateId, array $data)` - Şablon klonla

#### AuthOAuth (18 metod)
- `initiateGoogleLogin(string $appId, array $options)` - Google giriş başlat
- `initiateAppleLogin(string $appId, array $options)` - Apple giriş başlat
- `handleGoogleCallback(string $code, string $state, array $options)` - Google geri çağırma
- `handleAppleCallback(string $code, string $state, array $options)` - Apple geri çağırma
- `linkAccount(string $provider, string $accessToken, array $options)` - Hesap bağla
- `unlinkAccount(string $provider)` - Hesap bağlantısını kaldır
- `getSuccessPage(array $options)` - Başarı sayfasını getir
- `getErrorPage(array $options)` - Hata sayfasını getir
- `sandboxTest(string $provider)` - Sandbox test
- `getDebugInfo(string $provider)` - Debug bilgilerini getir
- `getMetadata(string $appId, string $path)` - Metadata getir
- `updateMetadata(string $appId, string $path, array $value)` - Metadata güncelle
- `patchMetadata(string $appId, string $path, array $value)` - Metadata kısmi güncelle
- `deleteMetadata(string $appId, string $path)` - Metadata sil
- `getProviders(string $appId)` - OAuth sağlayıcılarını getir
- `generateUrl(array $data)` - OAuth URL oluştur
- `testSecret(array $data)` - OAuth secret test et

#### AuthFirebase (9 metod)
- `loginWithGoogle(string $firebaseToken, array $options)` - Google ile Firebase giriş
- `loginWithApple(string $firebaseToken, array $options)` - Apple ile Firebase giriş
- `refreshToken(string $refreshToken)` - Token yenile
- `updateProfile(array $data)` - Profil güncelle
- `logout()` - Çıkış yap
- `getSDKStatus()` - SDK durumunu getir
- `getDebugInfo()` - Debug bilgilerini getir
- `healthCheck()` - Sağlık kontrolü yap
- `getUserStatus()` - Kullanıcı durumunu getir

#### Logger (2 metod)
- `get()` - Logger bilgilerini getir
- `getStats()` - Logger istatistiklerini getir

#### AppleTest (7 metod)
- `get()` - Apple test sayfasını getir
- `getTest()` - Apple test sayfasını getir
- `setConfig(array $data)` - Apple konfigürasyonunu ayarla
- `generateUrl(array $data)` - Apple URL oluştur
- `testSecret(array $data)` - Apple secret test et
- `handleCallback(array $data)` - Apple callback işlemi
- `exchangeToken(array $data)` - Apple token değişimi

## Hata Yönetimi

```php
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\ValidationException;
use ZAPI\Exceptions\AuthenticationException;

try {
    $result = $zapi->auth->login('user@example.com', null, 'password');
} catch (ValidationException $e) {
    echo "Geçersiz veri: " . $e->getMessage();
} catch (AuthenticationException $e) {
    echo "Kimlik doğrulama hatası: " . $e->getMessage();
} catch (ZAPIException $e) {
    echo "API hatası: " . $e->getMessage();
}
```

## Örnekler

### Kullanıcı Kaydı ve Girişi
```php
// Kayıt
$register = $zapi->auth->register([
    'email' => 'user@example.com',
    'password' => 'password123',
    'firstName' => 'John',
    'lastName' => 'Doe',
    'appId' => 'your-app-id'
]);

// Giriş
$login = $zapi->auth->login('user@example.com', null, 'password123', [
    'appId' => 'your-app-id'
]);

// API anahtarını ayarla
$zapi->setApiKey($login['token']);
```

### AI Yanıt Oluşturma
```php
$response = $zapi->responses->create([
    'prompt' => 'Merhaba, nasılsın?',
    'model' => 'gpt-4',
    'appId' => 'your-app-id'
]);
```

### Dosya Yükleme
```php
$upload = $zapi->upload->upload('/path/to/file.jpg', [
    'type' => 'image',
    'appId' => 'your-app-id'
]);
```

### Görsel Oluşturma
```php
$image = $zapi->images->generations([
    'prompt' => 'A beautiful sunset over the ocean',
    'model' => 'dall-e-3',
    'size' => '1024x1024'
]);
```

### Ses Çevirisi
```php
$audio = $zapi->audio->transcriptions([
    'file' => '/path/to/audio.mp3',
    'model' => 'whisper-1'
]);
```

## Destek

- **Dokümantasyon**: https://docs.zapi.com
- **GitHub**: https://github.com/zapi/php-sdk
- **Discord**: https://discord.gg/zapi
