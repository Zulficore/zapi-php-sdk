# ZAPI PHP SDK

ZAPI servislerine erişim için geliştirilmiş kapsamlı PHP SDK'sı.

## 🚀 Özellikler

- **248 metod** ile tüm API endpoint'lerine erişim
- **33 endpoint** sınıfı
- **Tam tip güvenliği** ve **hata yönetimi**
- **Orijinal API ile %100 uyumlu**

## 📦 Kurulum

```bash
composer require zapi/php-sdk
```

## 🔧 Temel Kullanım

```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

$zapi = new ZAPI('your-api-key', 'your-app-id', 'https://api.zapi.com');

// Kullanıcı kaydı
$register = $zapi->auth->register([
    'email' => 'user@example.com',
    'password' => 'password123',
    'firstName' => 'John',
    'lastName' => 'Doe',
    'appId' => 'your-app-id'
]);

// Kullanıcı girişi
$login = $zapi->auth->login('user@example.com', null, 'password123', [
    'appId' => 'your-app-id'
]);
```

## 📖 Dökümantasyon

- [API Referansı](API-REFERENCE.md) - Tüm 248 metod
- [Auth Endpoint](docs/AUTH.md) - 18 metod
- [User Endpoint](docs/USER.md) - 15 metod
- [Admin Endpoint](docs/ADMIN.md) - 15 metod
- [Apps Endpoint](docs/APPS.md) - 11 metod
- [AIProvider Endpoint](docs/AIPROVIDER.md) - 14 metod
- [Functions Endpoint](docs/FUNCTIONS.md) - 3 metod
- [Audio Endpoint](docs/AUDIO.md) - 3 metod
- [Images Endpoint](docs/IMAGES.md) - 3 metod
- [Video Endpoint](docs/VIDEO.md) - 2 metod
- [Users Endpoint](docs/USERS.md) - 8 metod
- [APIKeys Endpoint](docs/APIKEYS.md) - 9 metod
- [Content Endpoint](docs/CONTENT.md) - 14 metod
- [Debug Endpoint](docs/DEBUG.md) - 1 metod
- [Upload Endpoint](docs/UPLOAD.md) - 9 metod
- [System Endpoint](docs/SYSTEM.md) - 3 metod
- [Notifications Endpoint](docs/NOTIFICATIONS.md) - 12 metod
- [Webhook Endpoint](docs/WEBHOOK.md) - 5 metod
- [Plans Endpoint](docs/PLANS.md) - 11 metod
- [Subscription Endpoint](docs/SUBSCRIPTION.md) - 6 metod
- [Roles Endpoint](docs/ROLES.md) - 8 metod
- [Backup Endpoint](docs/BACKUP.md) - 4 metod
- [Logs Endpoint](docs/LOGS.md) - 5 metod
- [Info Endpoint](docs/INFO.md) - 4 metod
- [Docs Endpoint](docs/DOCS.md) - 2 metod
- [Embeddings Endpoint](docs/EMBEDDINGS.md) - 1 metod
- [Config Endpoint](docs/CONFIG.md) - 1 metod
- [Realtime Endpoint](docs/REALTIME.md) - 9 metod
- [Responses Endpoint](docs/RESPONSES.md) - 9 metod
- [MailTemplates Endpoint](docs/MAILTEMPLATES.md) - 7 metod
- [AuthOAuth Endpoint](docs/AUTHOAUTH.md) - 18 metod
- [AuthFirebase Endpoint](docs/AUTHFIREBASE.md) - 9 metod
- [Logger Endpoint](docs/LOGGER.md) - 2 metod
- [AppleTest Endpoint](docs/APPLETEST.md) - 7 metod

## 🛠️ Hata Yönetimi

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

## 📞 Destek

- **Dokümantasyon**: https://docs.zapi.com
- **GitHub**: https://github.com/zapi/php-sdk
- **Discord**: https://discord.gg/zapi

## 📄 Lisans

MIT License