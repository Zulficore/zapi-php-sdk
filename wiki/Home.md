# ZAPI PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)
[![License](https://img.shields.io/packagist/l/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)

**ZAPI PHP SDK**, ZAPI servislerinin tüm özelliklerini kullanmanızı sağlayan kapsamlı bir PHP kütüphanesidir. Kimlik doğrulama, AI sohbet, gerçek zamanlı iletişim, dosya yükleme ve daha fazlası için hazır çözümler sunar.

## 🚀 Özellikler

- **🔐 Kimlik Doğrulama** - JWT token tabanlı güvenli kimlik doğrulama
- **🤖 AI Sohbet** - Çoklu AI modeli desteği ile akıllı sohbet
- **⚡ Gerçek Zamanlı** - WebSocket desteği ile anlık iletişim
- **📁 Dosya Yönetimi** - Dosya yükleme ve yönetim sistemi
- **🔑 API Anahtarları** - API anahtarı yönetimi ve güvenliği
- **👥 Kullanıcı Yönetimi** - Kullanıcı profilleri ve ayarları
- **📊 Analitik** - Kullanım istatistikleri ve raporlama
- **🌍 Çoklu Dil** - Türkçe/İngilizce dil desteği
- **🛡️ Güvenlik** - Kapsamlı hata yönetimi ve güvenlik kontrolleri

## 📋 Gereksinimler

- **PHP 8.2+**
- **Composer**
- **cURL** (PHP extension)
- **JSON** (PHP extension)

## 🎯 Hızlı Başlangıç

### Kurulum

```bash
composer require zulficore/zapi-php-sdk
```

### Temel Kullanım

```php
<?php
require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

// SDK'yı başlat
$zapi = new ZAPI(
    apiKey: 'your_api_key',
    appId: 'your_app_id',
    baseUrl: 'https://dev.zulficoresystem.net' // opsiyonel
);

// Kullanıcı girişi
$loginResult = $zapi->auth->login([
    'email' => 'user@example.com',
    'password' => 'password123'
]);

// AI sohbet
$chatResponse = $zapi->responses->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Merhaba!']
    ]
]);

echo $chatResponse['data']['content'];
```

## 📚 Dokümantasyon

### Temel Rehberler
- [📖 Kurulum ve Yapılandırma](Installation-and-Configuration)
- [🚀 Hızlı Başlangıç](Quick-Start-Guide)
- [🔧 Temel Kullanım](Basic-Usage)

### API Referansı
- [🔐 Kimlik Doğrulama](Authentication-API)
- [🤖 AI Sohbet](AI-Chat-API)
- [⚡ Gerçek Zamanlı](Realtime-API)
- [👥 Kullanıcı Yönetimi](User-Management-API)
- [📁 Dosya Yönetimi](File-Management-API)
- [🔑 API Anahtarları](API-Keys-Management)
- [📊 Analitik](Analytics-API)

### Gelişmiş Konular
- [🛡️ Hata Yönetimi](Error-Handling)
- [🔧 Gelişmiş Kullanım](Advanced-Usage)
- [📝 Best Practices](Best-Practices)
- [❓ FAQ ve Troubleshooting](FAQ-and-Troubleshooting)

## 🎨 Örnekler

### Kimlik Doğrulama
```php
// Kullanıcı kaydı
$registerResult = $zapi->auth->register([
    'email' => 'user@example.com',
    'password' => 'password123',
    'name' => 'John Doe'
]);

// E-posta doğrulama
$verifyResult = $zapi->auth->verifyEmail([
    'token' => 'verification_token'
]);
```

### AI Sohbet
```php
// Basit sohbet
$response = $zapi->responses->create([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'PHP hakkında bilgi ver']
    ]
]);

// Stream sohbet
$streamResponse = $zapi->responses->createStream([
    'model' => 'gpt-4',
    'messages' => [
        ['role' => 'user', 'content' => 'Uzun bir hikaye yaz']
    ]
]);
```

### Gerçek Zamanlı İletişim
```php
// WebSocket bağlantısı
$realtime = $zapi->realtime->connect([
    'room' => 'chat_room_1',
    'user_id' => 'user_123'
]);

// Mesaj gönderme
$realtime->sendMessage([
    'content' => 'Merhaba dünya!',
    'type' => 'text'
]);
```

## 🔧 Yapılandırma

### Environment Variables
```bash
ZAPI_API_KEY=your_api_key_here
ZAPI_APP_ID=your_app_id_here
ZAPI_BASE_URL=https://dev.zulficoresystem.net
ZAPI_DEBUG=false
ZAPI_TIMEOUT=30
```

### Composer Configuration
```json
{
    "require": {
        "zulficore/zapi-php-sdk": "^1.0"
    },
    "config": {
        "allow-plugins": {
            "php-http/discovery": true
        }
    }
}
```

## 🛡️ Güvenlik

- **API Anahtarı Güvenliği**: API anahtarlarınızı güvenli bir şekilde saklayın
- **HTTPS Kullanımı**: Tüm API istekleri HTTPS üzerinden yapılır
- **Token Yönetimi**: JWT token'ları güvenli bir şekilde saklayın
- **Rate Limiting**: API rate limit'lerini göz önünde bulundurun

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit yapın (`git commit -m 'Add amazing feature'`)
4. Push yapın (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için [LICENSE](LICENSE) dosyasına bakın.

## 📞 Destek

- **GitHub Issues**: [Issues](https://github.com/Zulficore/zapi-php-sdk/issues)
- **E-posta**: dev@zapi.com
- **Dokümantasyon**: [Wiki](https://github.com/Zulficore/zapi-php-sdk/wiki)

## 🔄 Sürüm Geçmişi

### v1.0.0 (2025-01-15)
- İlk sürüm yayınlandı
- Tüm temel API endpoint'leri eklendi
- Kimlik doğrulama sistemi
- AI sohbet entegrasyonu
- Gerçek zamanlı iletişim desteği
- Kapsamlı hata yönetimi
- PSR standartları uyumluluğu

---

**ZAPI PHP SDK** ile güçlü ve güvenli API entegrasyonları oluşturun! 🚀
