<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Backup - Backup yönetimi endpoint'leri
 * 
 * Bu sınıf backup listeleme, detay görüntüleme ve silme işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $backup = $zapi->backup;
 * $backups = $backup->list();
 * $backupDetail = $backup->get('backup_123');
 */
class Backup
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Backup'ları listeler
     * 
     * @param array $options Listeleme seçenekleri
     * @return array Backup'lar listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $backups = $zapi->backup->list();
     * foreach ($backups['data'] as $backup) {
     *     echo "Backup: " . $backup['name'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/backup', $options);
    }
    
    /**
     * Backup detaylarını getirir
     * 
     * @param string $backupId Backup ID'si
     * @return array Backup detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $backup = $zapi->backup->get('backup_123');
     * echo "Backup: " . $backup['backup']['name'];
     * echo "Boyut: " . $backup['backup']['size'];
     */
    public function get(string $backupId): array
    {
        if (empty($backupId)) {
            throw new ValidationException('Backup ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/backup/{$backupId}");
    }
    
    /**
     * Kayıt backup'larını getirir
     * 
     * @param string $model Model adı
     * @param string $recordId Kayıt ID'si
     * @return array Kayıt backup'ları
     * @throws ValidationException Geçersiz model veya record ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $recordBackups = $zapi->backup->getRecordBackups('User', 'user_123');
     * foreach ($recordBackups['backups'] as $backup) {
     *     echo "Kayıt backup: " . $backup['createdAt'];
     * }
     */
    public function getRecordBackups(string $model, string $recordId): array
    {
        if (empty($model)) {
            throw new ValidationException('Model adı boş olamaz');
        }
        
        if (empty($recordId)) {
            throw new ValidationException('Kayıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/backup/record/{$model}/{$recordId}");
    }
    
    /**
     * Backup'ı siler
     * 
     * @param string $backupId Backup ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->backup->delete('backup_123');
     * echo "Backup silindi: " . $result['message'];
     */
    public function delete(string $backupId): array
    {
        if (empty($backupId)) {
            throw new ValidationException('Backup ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/backup/{$backupId}");
    }
}
