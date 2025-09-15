# ZAPI PHP SDK

[![Latest Version](https://img.shields.io/packagist/v/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)
[![License](https://img.shields.io/packagist/l/zulficore/zapi-php-sdk.svg?style=flat-square)](https://packagist.org/packages/zulficore/zapi-php-sdk)

ZAPI PHP SDK - Complete API wrapper for ZAPI services with authentication, AI chat, realtime features.

## Features

- 🔐 **Authentication** - Complete auth system with JWT tokens
- 🤖 **AI Chat** - AI responses with multiple models
- ⚡ **Realtime** - WebSocket support for real-time communication
- 📁 **File Upload** - File upload and management
- 🔑 **API Keys** - API key management
- 👥 **User Management** - User profiles and settings
- 📊 **Analytics** - Usage statistics and monitoring
- 🌐 **Multi-language** - Turkish and English support

## Installation

```bash
composer require zulficore/zapi-php-sdk
```

## Quick Start

```php
<?php

require_once 'vendor/autoload.php';

use ZAPI\ZAPI;

// Initialize SDK
$zapi = new ZAPI('your_api_key', 'your_app_id');

// Get system configuration
$config = $zapi->config->get();
echo "Environment: " . $config['data']['environment'];

// User registration
$register = $zapi->auth->register([
    'email' => 'user@example.com',
    'password' => 'password123',
    'firstName' => 'John',
    'lastName' => 'Doe'
]);

// Set Bearer token after login
$zapi->setBearerToken($register['data']['token']);

// AI Chat
$response = $zapi->responses->create([
    'model' => 'gpt-3.5-turbo',
    'messages' => [
        ['role' => 'user', 'content' => 'Hello!']
    ]
]);

echo $response['data']['choices'][0]['message']['content'];
```

## Requirements

- PHP 8.2+
- Composer
- Guzzle HTTP Client

## Documentation

- [API Documentation](https://dev.zulficoresystem.net/docs)
- [Examples](examples/)
- [Changelog](CHANGELOG.md)

## License

MIT License. See [LICENSE](LICENSE) file for details.

## Support

- [Issues](https://github.com/Zulficore/zapi-php-sdk/issues)
- [Documentation](https://dev.zulficoresystem.net/docs)
- [Email](mailto:dev@zapi.com)

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Changelog

### v1.0.0
- Initial release
- Complete API wrapper
- Authentication system
- AI chat integration
- Realtime WebSocket support
- File upload management
- User management
- Analytics and monitoring