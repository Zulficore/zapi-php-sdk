<?php

namespace ZAPI;

use ZAPI\Exceptions\ValidationException;
use ZAPI\Exceptions\ZAPIException;

/**
 * Video Endpoint
 * 
 * Bu sınıf video analizi ve transkripsiyon işlemlerini yönetir.
 * 
 * @package ZAPI
 * @author ZAPI Team
 * @version 1.0.0
 */
class Video extends BaseEndpoint
{
    /**
     * Video analizi yapar
     * 
     * Bu metod video dosyasını analiz eder ve içeriğini çıkarır.
     * 
     * @param string $filePath Video dosya yolu
     * @param array $options Analiz seçenekleri
     * @return array Analiz sonucu
     * @throws ValidationException Geçersiz dosya yolu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->video->analyze('/path/to/video.mp4', [
     *     'language' => 'tr',
     *     'extractAudio' => true
     * ]);
     * echo "Video analiz edildi: " . $result['message'];
     */
    public function analyze(string $filePath, array $options = []): array
    {
        if (empty($filePath)) {
            throw new ValidationException('Video dosya yolu boş olamaz');
        }
        
        $data = array_merge(['filePath' => $filePath], $options);
        return $this->zapi->getHttpClient()->post('/video/analyze', $data);
    }

    /**
     * Video transkripsiyon yapar
     * 
     * Bu metod video dosyasından ses çıkarıp metne dönüştürür.
     * 
     * @param string $filePath Video dosya yolu
     * @param array $options Transkripsiyon seçenekleri
     * @return array Transkripsiyon sonucu
     * @throws ValidationException Geçersiz dosya yolu
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $result = $zapi->video->transcribe('/path/to/video.mp4', [
     *     'language' => 'tr',
     *     'format' => 'text'
     * ]);
     * echo "Transkripsiyon: " . $result['data']['text'];
     */
    public function transcribe(string $filePath, array $options = []): array
    {
        if (empty($filePath)) {
            throw new ValidationException('Video dosya yolu boş olamaz');
        }
        
        $data = array_merge(['filePath' => $filePath], $options);
        return $this->zapi->getHttpClient()->post('/video/transcribe', $data);
    }
}
