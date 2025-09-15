<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Notifications - Bildirim yönetimi endpoint'leri
 * 
 * Bu sınıf e-posta ve SMS bildirimleri göndermek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Notifications
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Bildirim loglarını listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/notifications', $options);
    }
    
    /**
     * E-posta bildirimi gönderir
     */
    public function sendEmail(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/notifications/email/send', $data);
    }
    
    /**
     * Toplu e-posta bildirimi gönderir
     */
    public function sendBulkEmail(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/notifications/email/send-bulk', $data);
    }
    
    /**
     * SMS bildirimi gönderir
     */
    public function sendSMS(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/notifications/sms/send', $data);
    }
    
    /**
     * Toplu SMS bildirimi gönderir
     */
    public function sendBulkSMS(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/notifications/sms/send-bulk', $data);
    }
    
    /**
     * Bildirim log detaylarını getirir
     */
    public function getLog(string $logId): array
    {
        if (empty($logId)) {
            throw new ValidationException('Log ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/notifications/logs/{$logId}");
    }
    
    /**
     * Bildirim analitiğini getirir
     */
    public function getAnalytics(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/notifications/analytics', $options);
    }
    
    /**
     * Başarısız bildirimi yeniden dener
     */
    public function retry(string $logId): array
    {
        if (empty($logId)) {
            throw new ValidationException('Log ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/notifications/retry/{$logId}");
    }
    
    /**
     * Bildirim ayarlarını getirir
     */
    public function getSettings(): array
    {
        return $this->zapi->getHttpClient()->get('/notifications/settings');
    }
    
    /**
     * Bildirim ayarlarını günceller
     */
    public function updateSettings(array $data): array
    {
        return $this->zapi->getHttpClient()->put('/notifications/settings', $data);
    }
    
    /**
     * E-posta açılma takibini getirir
     */
    public function trackEmail(string $trackingId): array
    {
        if (empty($trackingId)) {
            throw new ValidationException('Tracking ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/notifications/track/email/{$trackingId}");
    }
    
    /**
     * Bildirim takibini getirir
     */
    public function track(string $logId): array
    {
        if (empty($logId)) {
            throw new ValidationException('Log ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/notifications/track/{$logId}");
    }
}
