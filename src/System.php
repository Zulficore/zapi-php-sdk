<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;

/**
 * System - Sistem yönetimi endpoint'leri
 * 
 * Bu sınıf temel sistem yönetimi işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 */
class System
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Sistemi yeniden başlatır
     */
    public function restart(): array
    {
        return $this->zapi->getHttpClient()->post('/system/restart');
    }
    
    /**
     * Sistem durumunu getirir
     */
    public function getStatus(): array
    {
        return $this->zapi->getHttpClient()->get('/system/status');
    }
    
    /**
     * Bellek kullanımını getirir
     */
    public function getMemory(): array
    {
        return $this->zapi->getHttpClient()->get('/system/memory');
    }
}
