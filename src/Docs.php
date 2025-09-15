<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Docs - Dokümantasyon endpoint'leri
 * 
 * Bu sınıf markdown dokümantasyon dosyalarını listeler ve içeriklerini getirir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $docs = $zapi->docs;
 * $docsList = $docs->list();
 * $docContent = $docs->get('README.md');
 */
class Docs
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Dokümantasyon dosyalarını listeler
     * 
     * @return array Dokümantasyon dosyaları listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $docsList = $zapi->docs->list();
     * echo "Toplam dokuman: " . $docsList['total'];
     * foreach ($docsList['docs'] as $doc) {
     *     echo "Dosya: " . $doc['filename'];
     * }
     */
    public function list(): array
    {
        return $this->zapi->getHttpClient()->get('/api/docs');
    }
    
    /**
     * Dokümantasyon dosyasının içeriğini getirir
     * 
     * @param string $filename Dosya adı
     * @return array Dokümantasyon içeriği
     * @throws ValidationException Geçersiz dosya adı
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $doc = $zapi->docs->get('README.md');
     * echo "Dosya: " . $doc['filename'];
     * echo "İçerik: " . $doc['content'];
     * echo "Son güncelleme: " . $doc['lastModified'];
     */
    public function get(string $filename): array
    {
        if (empty($filename)) {
            throw new ValidationException('Dosya adı boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/api/docs/{$filename}");
    }
}
