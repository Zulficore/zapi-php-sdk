<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Realtime - Gerçek zamanlı sohbet endpoint'leri
 * 
 * Bu sınıf gerçek zamanlı sohbet oturumlarını yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Realtime
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Oturumları listeler
     */
    public function getSessions(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/realtime/sessions', $options);
    }
    
    /**
     * Oturumu devam ettirir
     */
    public function resumeSession(string $sessionId): array
    {
        if (empty($sessionId)) {
            throw new ValidationException('Session ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/realtime/sessions/{$sessionId}/resume");
    }
    
    /**
     * Oturum geçmişini getirir
     */
    public function getSessionHistory(string $sessionId, array $options = []): array
    {
        if (empty($sessionId)) {
            throw new ValidationException('Session ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/realtime/sessions/{$sessionId}/history", $options);
    }
    
    /**
     * Yeni oturum oluşturur
     */
    public function createSession(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/realtime/sessions', $data);
    }
    
    /**
     * Oturum detaylarını getirir
     */
    public function getSession(string $sessionId): array
    {
        if (empty($sessionId)) {
            throw new ValidationException('Session ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/realtime/sessions/{$sessionId}");
    }
    
    /**
     * Oturumu siler
     */
    public function deleteSession(string $sessionId): array
    {
        if (empty($sessionId)) {
            throw new ValidationException('Session ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/realtime/sessions/{$sessionId}");
    }
    
    /**
     * Gerçek zamanlı modelleri listeler
     */
    public function getModels(): array
    {
        return $this->zapi->getHttpClient()->get('/realtime/models');
    }
    
    /**
     * Stream bilgilerini getirir
     */
    public function getStreamInfo(): array
    {
        return $this->zapi->getHttpClient()->get('/realtime/stream/info');
    }
    
    /**
     * Gerçek zamanlı istatistikleri getirir
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/realtime/stats');
    }
}
