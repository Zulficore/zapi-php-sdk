<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Admin - Yönetici endpoint'leri
 * 
 * Bu sınıf sistem yönetimi, kuyruk yönetimi, cron işlemleri,
 * sağlık kontrolü ve backup/restore işlemleri için endpoint'leri içerir.
 * Sadece admin ve superadmin yetkisine sahip kullanıcılar erişebilir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $admin = $zapi->admin;
 * $dashboard = $admin->getDashboard();
 * $queue = $admin->getQueue();
 */
class Admin
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * Admin constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Admin dashboard bilgilerini getirir
     * 
     * Bu metod admin dashboard için gerekli sistem bilgilerini getirir.
     * 
     * @return array Dashboard bilgileri
     * @throws AuthenticationException Admin yetkisi yoksa
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $dashboard = $zapi->admin->getDashboard();
     * echo "Toplam kullanıcı: " . $dashboard['totalUsers'];
     * echo "Toplam uygulama: " . $dashboard['totalApps'];
     * echo "Sistem durumu: " . $dashboard['systemStatus'];
     */
    public function getDashboard(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/dashboard');
    }
    
    /**
     * Kuyruk bilgilerini getirir
     * 
     * Bu metod sistem kuyruklarının durumunu getirir.
     * 
     * @return array Kuyruk bilgileri
     * @throws AuthenticationException Admin yetkisi yoksa
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $queue = $zapi->admin->getQueue();
     * echo "Bekleyen işler: " . $queue['pendingJobs'];
     * echo "Aktif işler: " . $queue['activeJobs'];
     * echo "Tamamlanan işler: " . $queue['completedJobs'];
     */
    public function getQueue(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/queue');
    }
    
    /**
     * Cron işlemlerini listeler
     * 
     * Bu metod sistem cron işlemlerini listeler.
     * 
     * @return array Cron işlemleri
     * @throws AuthenticationException Admin yetkisi yoksa
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $crons = $zapi->admin->getCrons();
     * foreach ($crons['crons'] as $cron) {
     *     echo "Cron: " . $cron['name'] . " - " . $cron['status'];
     * }
     */
    public function getCrons(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/cron');
    }
    
    /**
     * Cron işlemini tetikler
     * 
     * Bu metod belirtilen cron işlemini manuel olarak tetikler.
     * 
     * @param string $cronName Cron işlemi adı
     * @return array Tetikleme sonucu
     * @throws ValidationException Geçersiz cron adı
     * @throws AuthenticationException Admin yetkisi yoksa
     * 
     * @example
     * $result = $zapi->admin->triggerCron('daily-cleanup');
     * echo "Cron tetiklendi: " . $result['message'];
     */
    public function triggerCron(string $cronName): array
    {
        if (empty($cronName)) {
            throw new ValidationException('Cron adı boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/admin/cron/trigger/{$cronName}");
    }
    
    /**
     * Aylık sıfırlama cron işlemini tetikler
     * 
     * Bu metod aylık kullanım sayaçlarını sıfırlama işlemini tetikler.
     * 
     * @return array Tetikleme sonucu
     * @throws AuthenticationException Admin yetkisi yoksa
     * 
     * @example
     * $result = $zapi->admin->triggerMonthlyReset();
     * echo "Aylık sıfırlama tetiklendi: " . $result['message'];
     */
    public function triggerMonthlyReset(): array
    {
        return $this->zapi->getHttpClient()->post('/admin/cron/trigger/monthly-reset');
    }
    
    /**
     * Sistem sağlık kontrolü yapar
     * 
     * Bu metod sistem bileşenlerinin sağlık durumunu kontrol eder.
     * 
     * @return array Sağlık durumu
     * @throws AuthenticationException Admin yetkisi yoksa
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $health = $zapi->admin->getHealth();
     * echo "Veritabanı: " . $health['database']['status'];
     * echo "Redis: " . $health['redis']['status'];
     * echo "Disk: " . $health['disk']['status'];
     */
    public function getHealth(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/health');
    }
    
    /**
     * Sistem metriklerini getirir
     * 
     * Bu metod sistem performans metriklerini getirir.
     * 
     * @return array Sistem metrikleri
     * @throws AuthenticationException Admin yetkisi yoksa
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $metrics = $zapi->admin->getMetrics();
     * echo "CPU kullanımı: " . $metrics['cpu']['usage'] . "%";
     * echo "Bellek kullanımı: " . $metrics['memory']['usage'] . "%";
     * echo "Disk kullanımı: " . $metrics['disk']['usage'] . "%";
     */
    public function getMetrics(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/metrics');
    }
    
    /**
     * Cache temizleme işlemi yapar
     * 
     * Bu metod sistem cache'ini temizler.
     * 
     * @param string $type Cache türü (all, redis, memory)
     * @return array Temizleme sonucu
     * @throws ValidationException Geçersiz cache türü
     * @throws AuthenticationException Admin yetkisi yoksa
     * 
     * @example
     * $result = $zapi->admin->clearCache('all');
     * echo "Cache temizlendi: " . $result['message'];
     */
    public function clearCache(string $type = 'all'): array
    {
        $allowedTypes = ['all', 'redis', 'memory'];
        if (!in_array($type, $allowedTypes)) {
            throw new ValidationException('Geçersiz cache türü: ' . $type);
        }
        
        return $this->zapi->getHttpClient()->post("/admin/cache/clear/{$type}");
    }
    
    /**
     * Backup oluşturur
     * 
     * Bu metod sistem backup'ı oluşturur.
     * 
     * @param array $options Backup seçenekleri
     * @return array Backup sonucu
     * @throws AuthenticationException Admin yetkisi yoksa
     * 
     * @example
     * $backup = $zapi->admin->createBackup([
     *     'type' => 'full',
     *     'compress' => true
     * ]);
     * echo "Backup oluşturuldu: " . $backup['backupId'];
     */
    public function createBackup(array $options = []): array
    {
        return $this->zapi->getHttpClient()->post('/admin/backup', $options);
    }
    
    /**
     * Backup'ı geri yükler
     * 
     * Bu metod belirtilen backup'ı geri yükler.
     * 
     * @param string $backupId Backup ID'si
     * @return array Geri yükleme sonucu
     * @throws ValidationException Geçersiz backup ID
     * @throws AuthenticationException Admin yetkisi yoksa
     * 
     * @example
     * $result = $zapi->admin->restoreBackup('backup_123');
     * echo "Backup geri yüklendi: " . $result['message'];
     */
    public function restoreBackup(string $backupId): array
    {
        if (empty($backupId)) {
            throw new ValidationException('Backup ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/admin/backup/{$backupId}/restore");
    }
}
