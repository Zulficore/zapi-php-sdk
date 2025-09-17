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
    public function clearCache(?string $pattern = null): array
    {
        $data = [];
        if ($pattern) {
            $data['pattern'] = $pattern;
        }
        
        return $this->zapi->getHttpClient()->post('/admin/system/cache/clear', $data);
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

    /**
     * Admin istatistiklerini getirir
     * 
     * Bu metod admin paneli için detaylı istatistikleri getirir.
     * 
     * @return array İstatistik verileri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $stats = $zapi->admin->getStats();
     * echo "Toplam kullanıcı: " . $stats['data']['users']['total'];
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/stats');
    }

    /**
     * Queue istatistiklerini getirir
     * 
     * Bu metod queue sisteminin istatistiklerini getirir.
     * 
     * @return array Queue istatistikleri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $queueStats = $zapi->admin->getQueueStats();
     * echo "Bekleyen işler: " . $queueStats['data']['waiting'];
     */
    public function getQueueStats(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/queue/stats');
    }

    /**
     * Queue'yu duraklatır
     * 
     * Bu metod queue sistemini duraklatır.
     * 
     * @return array Duraklatma sonucu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->pauseQueue();
     * echo "Queue duraklatıldı: " . $result['message'];
     */
    public function pauseQueue(): array
    {
        return $this->zapi->getHttpClient()->post('/admin/queue/pause');
    }

    /**
     * Queue'yu devam ettirir
     * 
     * Bu metod duraklatılmış queue'yu devam ettirir.
     * 
     * @return array Devam ettirme sonucu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->resumeQueue();
     * echo "Queue devam ettirildi: " . $result['message'];
     */
    public function resumeQueue(): array
    {
        return $this->zapi->getHttpClient()->post('/admin/queue/resume');
    }

    /**
     * Queue'yu temizler
     * 
     * Bu metod queue'daki tüm işleri temizler.
     * 
     * @return array Temizleme sonucu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->cleanQueue();
     * echo "Queue temizlendi: " . $result['message'];
     */
    public function cleanQueue(string $type = 'completed'): array
    {
        return $this->zapi->getHttpClient()->post('/admin/queue/clean', ['type' => $type]);
    }

    /**
     * Cron job durumunu getirir
     * 
     * Bu metod tüm cron job'ların durumunu getirir.
     * 
     * @return array Cron durumları
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $cronStatus = $zapi->admin->getCronStatus();
     * echo "Cron durumu: " . $cronStatus['data']['status'];
     */
    public function getCronStatus(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/cron/status');
    }

    /**
     * Belirli bir cron job'ı başlatır
     * 
     * Bu metod belirtilen cron job'ı başlatır.
     * 
     * @param string $jobName Cron job adı
     * @return array Başlatma sonucu
     * @throws ValidationException Geçersiz job adı
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->startCron('daily-cleanup');
     * echo "Cron başlatıldı: " . $result['message'];
     */
    public function startCron(string $jobName): array
    {
        if (empty($jobName)) {
            throw new ValidationException('Job adı boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/admin/cron/{$jobName}/start");
    }

    /**
     * Belirli bir cron job'ı durdurur
     * 
     * Bu metod belirtilen cron job'ı durdurur.
     * 
     * @param string $jobName Cron job adı
     * @return array Durdurma sonucu
     * @throws ValidationException Geçersiz job adı
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->stopCron('daily-cleanup');
     * echo "Cron durduruldu: " . $result['message'];
     */
    public function stopCron(string $jobName): array
    {
        if (empty($jobName)) {
            throw new ValidationException('Job adı boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/admin/cron/{$jobName}/stop");
    }

    /**
     * Günlük sıfırlama işlemini tetikler
     * 
     * Bu metod günlük sıfırlama cron job'ını manuel olarak tetikler.
     * 
     * @return array Tetikleme sonucu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->admin->triggerDailyReset();
     * echo "Günlük sıfırlama tetiklendi: " . $result['message'];
     */
    public function triggerDailyReset(): array
    {
        return $this->zapi->getHttpClient()->post('/admin/cron/trigger/daily-reset');
    }

    /**
     * Sistem bilgilerini getirir
     * 
     * Bu metod sistem hakkında detaylı bilgileri getirir.
     * 
     * @return array Sistem bilgileri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $systemInfo = $zapi->admin->getSystemInfo();
     * echo "Sistem versiyonu: " . $systemInfo['data']['version'];
     */
    public function getSystemInfo(): array
    {
        return $this->zapi->getHttpClient()->get('/admin/system/info');
    }
    
    /**
     * Aylık sıfırlama cron işlemini tetikler
     */
    public function triggerMonthlyReset(): array
    {
        return $this->zapi->getHttpClient()->post('/admin/cron/trigger/monthly-reset');
    }
    
    /**
     * Sistem backup bilgilerini getirir
     */
    public function getBackup(string $key): array
    {
        return $this->zapi->getHttpClient()->get('/admin/system/backup', ['key' => $key]);
    }
    
    /**
     * Sistem restore bilgilerini getirir
     */
    public function getRestore(string $key, ?string $backup = null, ?string $tables = null): array
    {
        $params = ['key' => $key];
        if ($backup) $params['backup'] = $backup;
        if ($tables) $params['tables'] = $tables;
        
        return $this->zapi->getHttpClient()->get('/admin/system/restore', $params);
    }
}
