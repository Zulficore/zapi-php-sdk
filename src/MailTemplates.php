<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * MailTemplates - E-posta şablonu yönetimi endpoint'leri
 * 
 * Bu sınıf e-posta şablonlarını yönetmek için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class MailTemplates
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * E-posta şablonlarını listeler
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/mail-templates', $options);
    }
    
    /**
     * Yeni e-posta şablonu oluşturur
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/mail-templates', $data);
    }
    
    /**
     * E-posta şablonu detaylarını getirir
     */
    public function get(string $templateId): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/mail-templates/{$templateId}");
    }
    
    /**
     * E-posta şablonu bilgilerini günceller
     */
    public function update(string $templateId, array $data): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/mail-templates/{$templateId}", $data);
    }
    
    /**
     * E-posta şablonunu siler
     */
    public function delete(string $templateId): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/mail-templates/{$templateId}");
    }
    
    /**
     * E-posta şablonu durumunu değiştirir
     */
    public function toggleStatus(string $templateId): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/mail-templates/{$templateId}/toggle-status");
    }
    
    /**
     * E-posta şablonunu önizler
     */
    public function preview(string $templateId, array $variables = []): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/mail-templates/{$templateId}/preview", [
            'variables' => $variables
        ]);
    }
    
    /**
     * E-posta şablonunu klonlar
     */
    public function clone(string $templateId, array $data = []): array
    {
        if (empty($templateId)) {
            throw new ValidationException('Şablon ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/mail-templates/{$templateId}/clone", $data);
    }
}
