<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Audio - Ses işleme endpoint'leri
 * 
 * Bu sınıf ses işleme, text-to-speech, speech-to-text ve
 * ses çeviri işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $audio = $zapi->audio;
 * $speech = $audio->textToSpeech('Merhaba dünya', 'tr-TR');
 */
class Audio
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Metni sese dönüştürür
     * 
     * Bu metod belirtilen metni ses dosyasına dönüştürür.
     * 
     * @param string $text Dönüştürülecek metin
     * @param string $voice Ses türü
     * @param array $options Ek seçenekler
     * @return array Ses dosyası bilgileri
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $speech = $zapi->audio->textToSpeech('Merhaba dünya', 'tr-TR');
     * echo "Ses dosyası: " . $speech['audio']['url'];
     */
    public function speech(array $data): array
    {
        if (empty($data['input'])) {
            throw new ValidationException('Input boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/audio/speech', $data);
    }
    
    /**
     * Sesi metne dönüştürür (Henüz implement edilmemiş)
     * 
     * Bu metod ses dosyasını metne dönüştürür.
     * Şu anda implement edilmemiştir.
     * 
     * @param string $filePath Ses dosya yolu
     * @param array $options Ek seçenekler
     * @return array Dönüştürme sonucu
     * @throws ZAPIException Henüz implement edilmemiş
     * 
     * @example
     * // Bu metod henüz implement edilmemiş
     * // $transcription = $zapi->audio->speechToText('/path/to/audio.mp3');
     */
    public function transcriptions(array $data): array
    {
        if (empty($data['file'])) {
            throw new ValidationException('File boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/audio/transcriptions', $data);
    }
    
    /**
     * Ses çevirisi yapar (Henüz implement edilmemiş)
     * 
     * Bu metod ses dosyasını farklı bir dile çevirir.
     * Şu anda implement edilmemiştir.
     * 
     * @param string $filePath Ses dosya yolu
     * @param string $targetLanguage Hedef dil
     * @param array $options Ek seçenekler
     * @return array Çeviri sonucu
     * @throws ZAPIException Henüz implement edilmemiş
     * 
     * @example
     * // Bu metod henüz implement edilmemiş
     * // $translation = $zapi->audio->translateAudio('/path/to/audio.mp3', 'en');
     */
    public function translations(array $data): array
    {
        if (empty($data['file'])) {
            throw new ValidationException('File boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/audio/translations', $data);
    }
}
