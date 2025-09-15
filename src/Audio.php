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
    public function textToSpeech(string $text, string $voice = 'alloy', array $options = []): array
    {
        if (empty($text)) {
            throw new ValidationException('Metin boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/audio/speech', array_merge([
            'input' => $text,
            'voice' => $voice
        ], $options));
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
    public function speechToText(string $filePath, array $options = []): array
    {
        throw new ZAPIException('Bu özellik henüz implement edilmemiş', 501);
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
    public function translateAudio(string $filePath, string $targetLanguage, array $options = []): array
    {
        throw new ZAPIException('Bu özellik henüz implement edilmemiş', 501);
    }
}
