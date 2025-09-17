<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Auth;
use ZAPI\User;
use ZAPI\Admin;
use ZAPI\Apps;
use ZAPI\AIProvider;
use ZAPI\APIKeys;
use ZAPI\Audio;
use ZAPI\AuthFirebase;
use ZAPI\AuthOAuth;
use ZAPI\Backup;
use ZAPI\Config;
use ZAPI\Content;
use ZAPI\Debug;
use ZAPI\Docs;
use ZAPI\Embeddings;
use ZAPI\Images;
use ZAPI\Info;
use ZAPI\Logs;
use ZAPI\MailTemplates;
use ZAPI\Notifications;
use ZAPI\Plans;
use ZAPI\Realtime;
use ZAPI\Roles;
use ZAPI\Subscription;
use ZAPI\System;
use ZAPI\Upload;
use ZAPI\Responses;
use ZAPI\Webhook;
use ZAPI\Functions;
use ZAPI\OAuthMetadata;
use ZAPI\Metadata;
use ZAPI\Video;
use ZAPI\Users;
use ZAPI\Logger;
use ZAPI\AppleTest;
use ZAPI\Http\Client;
use ZAPI\Exceptions\ZAPIException;

/**
 * ZAPI PHP SDK - Ana sınıf
 * 
 * Bu sınıf ZAPI servislerine erişim için ana giriş noktasıdır.
 * Tüm endpoint sınıflarına erişim sağlar ve HTTP client'ı yönetir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $zapi = new ZAPI('your_api_key', 'your_app_id');
 * $profile = $zapi->user->getProfile();
 */
class ZAPI
{
    /**
     * API base URL
     */
    private string $baseUrl;
    
    /**
     * API anahtarı
     */
    private string $apiKey;
    
    /**
     * Bearer token (kimlik doğrulama için)
     */
    private ?string $bearerToken = null;
    
    /**
     * Uygulama ID'si
     */
    private string $appId;
    
    /**
     * HTTP client instance
     */
    private ?Client $httpClient = null;
    
    /**
     * Debug modu
     */
    private bool $debug = false;
    
    /**
     * Timeout süresi (saniye)
     */
    private int $timeout = 30;
    
    // Endpoint sınıfları
    public Auth $auth;
    public User $user;
    public Admin $admin;
    public Apps $apps;
    public AIProvider $aiProvider;
    public APIKeys $apiKeys;
    public Audio $audio;
    public AuthFirebase $authFirebase;
    public AuthOAuth $authOAuth;
    public Backup $backup;
    public Config $config;
    public Content $content;
    public Debug $debugEndpoint;
    public Docs $docs;
    public Embeddings $embeddings;
    public Images $images;
    public Info $info;
    public Logs $logs;
    public MailTemplates $mailTemplates;
    public Notifications $notifications;
    public Plans $plans;
    public Realtime $realtime;
    public Roles $roles;
    public Subscription $subscription;
    public System $system;
    public Upload $upload;
    public Responses $responses;
    public Webhook $webhook;
    public Functions $functions;
    public OAuthMetadata $oauthMetadata;
    public Metadata $metadata;
    public Video $video;
    public Users $users;
    public Logger $logger;
    public AppleTest $appleTest;
    
    /**
     * ZAPI constructor
     * 
     * @param string $apiKey API anahtarı
     * @param string $appId Uygulama ID'si
     * @param string|null $baseUrl API base URL (opsiyonel)
     * @param array $options Ek seçenekler
     * 
     * @throws ZAPIException Geçersiz parametreler verilirse
     * 
     * @example
     * $zapi = new ZAPI('your_api_key', 'app_1234567890');
     * $zapi = new ZAPI('your_api_key', 'app_1234567890', 'https://api.example.com');
     */
    public function __construct(
        string $apiKey,
        string $appId,
        ?string $baseUrl = null,
        array $options = []
    ) {
        if (empty($apiKey)) {
            throw new ZAPIException('API anahtarı boş olamaz');
        }
        
        if (empty($appId)) {
            throw new ZAPIException('Uygulama ID\'si boş olamaz');
        }
        
        $this->apiKey = $apiKey;
        $this->appId = $appId;
        $this->baseUrl = $baseUrl ?? 'https://dev.zulficoresystem.net';
        $this->debug = $options['debug'] ?? false;
        $this->timeout = $options['timeout'] ?? 30;
        $this->bearerToken = $options['bearerToken'] ?? null;
        
        // Endpoint sınıflarını başlat
        $this->initializeEndpoints();
    }
    
    /**
     * Endpoint sınıflarını başlatır
     * 
     * @return void
     */
    private function initializeEndpoints(): void
    {
        $this->auth = new Auth($this);
        $this->user = new User($this);
        $this->admin = new Admin($this);
        $this->apps = new Apps($this);
        $this->aiProvider = new AIProvider($this);
        $this->apiKeys = new APIKeys($this);
        $this->audio = new Audio($this);
        $this->authFirebase = new AuthFirebase($this);
        $this->authOAuth = new AuthOAuth($this);
        $this->backup = new Backup($this);
        $this->config = new Config($this);
        $this->content = new Content($this);
        $this->debugEndpoint = new Debug($this);
        $this->docs = new Docs($this);
        $this->embeddings = new Embeddings($this);
        $this->images = new Images($this);
        $this->info = new Info($this);
        $this->logs = new Logs($this);
        $this->mailTemplates = new MailTemplates($this);
        $this->notifications = new Notifications($this);
        $this->plans = new Plans($this);
        $this->realtime = new Realtime($this);
        $this->roles = new Roles($this);
        $this->subscription = new Subscription($this);
        $this->system = new System($this);
        $this->upload = new Upload($this);
        $this->responses = new Responses($this);
        $this->webhook = new Webhook($this);
        $this->functions = new Functions($this);
        $this->oauthMetadata = new OAuthMetadata($this);
        $this->metadata = new Metadata($this);
        $this->video = new Video($this);
        $this->users = new Users($this);
        $this->logger = new Logger($this);
        $this->appleTest = new AppleTest($this);
    }
    
    /**
     * HTTP client'ı ayarlar
     * 
     * @param Client $client HTTP client instance
     * @return self
     * 
     * @example
     * $customClient = new CustomHttpClient();
     * $zapi->setHttpClient($customClient);
     */
    public function setHttpClient(Client $client): self
    {
        $this->httpClient = $client;
        return $this;
    }
    
    /**
     * HTTP client'ı döndürür
     * 
     * @return Client
     */
    public function getHttpClient(): Client
    {
        if ($this->httpClient === null) {
        $this->httpClient = new Client(
            $this->baseUrl,
            $this->apiKey,
            $this->appId,
            $this->timeout,
            $this->debug,
            $this->bearerToken
        );
        }
        
        return $this->httpClient;
    }
    
    /**
     * API anahtarını günceller
     * 
     * @param string $apiKey Yeni API anahtarı
     * @return self
     * 
     * @example
     * $zapi->setApiKey('new_api_key');
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Uygulama ID'sini günceller
     * 
     * @param string $appId Yeni uygulama ID'si
     * @return self
     * 
     * @example
     * $zapi->setAppId('new_app_id');
     */
    public function setAppId(string $appId): self
    {
        $this->appId = $appId;
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Base URL'i günceller
     * 
     * @param string $baseUrl Yeni base URL
     * @return self
     * 
     * @example
     * $zapi->setBaseUrl('https://api.production.com');
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Debug modunu ayarlar
     * 
     * @param bool $debug Debug modu
     * @return self
     * 
     * @example
     * $zapi->setDebug(true);
     */
    public function setDebug(bool $debug): self
    {
        $this->debug = $debug;
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Timeout süresini ayarlar
     * 
     * @param int $timeout Timeout süresi (saniye)
     * @return self
     * 
     * @example
     * $zapi->setTimeout(60);
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Mevcut API anahtarını döndürür
     * 
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
    
    /**
     * Bearer token'ı ayarlar
     * 
     * @param string $bearerToken Bearer token
     * @return self
     * 
     * @example
     * $zapi->setBearerToken('jwt_token_here');
     */
    public function setBearerToken(string $bearerToken): self
    {
        $this->bearerToken = $bearerToken;
        $this->httpClient = null; // Client'ı yeniden oluştur
        return $this;
    }
    
    /**
     * Mevcut Bearer token'ı döndürür
     * 
     * @return string|null
     */
    public function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }
    
    /**
     * Mevcut uygulama ID'sini döndürür
     * 
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }
    
    /**
     * Mevcut base URL'i döndürür
     * 
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }
    
    /**
     * Debug modunun aktif olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isDebugMode(): bool
    {
        return $this->debug;
    }
    
    /**
     * Mevcut timeout süresini döndürür
     * 
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
    
    /**
     * SDK versiyonunu döndürür
     * 
     * @return string
     */
    public static function getVersion(): string
    {
        return '1.0.0';
    }
    
    /**
     * SDK bilgilerini döndürür
     * 
     * @return array
     * 
     * @example
     * $info = $zapi->getInfo();
     * echo "SDK Version: " . $info['version'];
     */
    public function getInfo(): array
    {
        return [
            'version' => self::getVersion(),
            'baseUrl' => $this->baseUrl,
            'appId' => $this->appId,
            'debug' => $this->debug,
            'timeout' => $this->timeout,
            'endpoints' => [
                'auth', 'user', 'admin', 'apps', 'aiProvider', 'apiKeys',
                'audio', 'authFirebase', 'authOAuth', 'backup', 'config',
                'content', 'debug', 'docs', 'embeddings', 'images', 'info',
                'logs', 'mailTemplates', 'notifications', 'plans', 'realtime',
                'roles', 'subscription', 'system', 'upload', 'responses',
                'webhook', 'functions', 'oauthMetadata', 'metadata'
            ]
        ];
    }
}
