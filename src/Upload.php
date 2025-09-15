<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Upload - Dosya yükleme endpoint'leri
 * 
 * Bu sınıf dosya yükleme, listeleme ve yönetme işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class Upload
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Dosya yükler
     */
    public function upload(string $filePath, array $options = []): array
    {
        if (!file_exists($filePath)) {
            throw new ValidationException('Dosya bulunamadı: ' . $filePath);
        }
        
        return $this->zapi->getHttpClient()->postMultipart('/upload', $options, ['file' => $filePath]);
    }
    
    /**
     * Yüklenen dosyaları listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/upload', $options);
    }
    
    /**
     * Dosya detaylarını getirir
     */
    public function get(string $fileId): array
    {
        if (empty($fileId)) {
            throw new ValidationException('Dosya ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/upload/{$fileId}");
    }
    
    /**
     * Dosyayı siler
     */
    public function delete(string $fileId): array
    {
        if (empty($fileId)) {
            throw new ValidationException('Dosya ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/upload/{$fileId}");
    }
    
    /**
     * Upload istatistiklerini getirir
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/upload/stats');
    }
    
    /**
     * Orphaned dosyaları temizler
     */
    public function cleanup(): array
    {
        return $this->zapi->getHttpClient()->delete('/upload/cleanup');
    }
    
    /**
     * Upload progress bilgilerini getirir
     */
    public function getProgress(string $uploadId): array
    {
        if (empty($uploadId)) {
            throw new ValidationException('Upload ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/upload/progress/{$uploadId}");
    }
    
    /**
     * Tüm upload progress bilgilerini getirir
     */
    public function getAllProgress(): array
    {
        return $this->zapi->getHttpClient()->get('/upload/progress/all');
    }
    
    /**
     * Signed URL oluşturur
     */
    public function createSignedUrl(string $fileId, array $options = []): array
    {
        if (empty($fileId)) {
            throw new ValidationException('Dosya ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/upload/url/{$fileId}", $options);
    }
}
