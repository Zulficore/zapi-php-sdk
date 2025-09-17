<?php

namespace ZAPI;

use ZAPI\Exceptions\ValidationException;

/**
 * Logger Endpoint
 * 
 * Bu sınıf logger işlemlerini yönetir.
 */
class Logger
{
    private ZAPI $zapi;

    /**
     * Logger constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }

    /**
     * Logger bilgilerini getirir
     * 
     * Bu metod logger bilgilerini getirir.
     * 
     * @return array Logger bilgileri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $logger = $zapi->logger->get();
     * echo "Logger durumu: " . $logger['status'];
     */
    public function get(): array
    {
        return $this->zapi->getHttpClient()->get('/logger');
    }

    /**
     * Logger istatistiklerini getirir
     * 
     * Bu metod logger istatistiklerini getirir.
     * 
     * @return array Logger istatistikleri
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $stats = $zapi->logger->getStats();
     * echo "Toplam log: " . $stats['totalLogs'];
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/logger/stats');
    }
}
