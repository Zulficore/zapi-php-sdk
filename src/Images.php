<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Images - Resim işleme endpoint'leri
 * 
 * Bu sınıf AI ile resim oluşturma, düzenleme ve varyasyon işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $images = $zapi->images;
 * $image = $images->generate('Güzel bir manzara');
 */
class Images
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * AI ile resim oluşturur
     * 
     * Bu metod belirtilen prompt ile AI resim oluşturur.
     * 
     * @param string $prompt Resim açıklaması
     * @param array $options Ek seçenekler
     * @return array Resim oluşturma sonucu
     * @throws ValidationException Geçersiz prompt
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $image = $zapi->images->generate('Güzel bir manzara', [
     *     'size' => '1024x1024',
     *     'n' => 1,
     *     'quality' => 'standard'
     * ]);
     * echo "Resim URL: " . $image['data'][0]['url'];
     */
    public function generate(string $prompt, array $options = []): array
    {
        if (empty($prompt)) {
            throw new ValidationException('Prompt boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/images/generations', array_merge([
            'prompt' => $prompt
        ], $options));
    }
    
    /**
     * Resim düzenleme yapar (Henüz implement edilmemiş)
     * 
     * Bu metod mevcut bir resimi düzenler.
     * Şu anda implement edilmemiştir.
     * 
     * @param string $imagePath Düzenlenecek resim yolu
     * @param string $prompt Düzenleme açıklaması
     * @param array $options Ek seçenekler
     * @return array Düzenleme sonucu
     * @throws ZAPIException Henüz implement edilmemiş
     * 
     * @example
     * // Bu metod henüz implement edilmemiş
     * // $edited = $zapi->images->edit('/path/to/image.png', 'Kırmızı araba ekle');
     */
    public function edit(string $imagePath, string $prompt, array $options = []): array
    {
        throw new ZAPIException('Bu özellik henüz implement edilmemiş', 501);
    }
    
    /**
     * Resim varyasyonu oluşturur (Henüz implement edilmemiş)
     * 
     * Bu metod mevcut bir resmin varyasyonlarını oluşturur.
     * Şu anda implement edilmemiştir.
     * 
     * @param string $imagePath Kaynak resim yolu
     * @param array $options Ek seçenekler
     * @return array Varyasyon sonucu
     * @throws ZAPIException Henüz implement edilmemiş
     * 
     * @example
     * // Bu metod henüz implement edilmemiş
     * // $variations = $zapi->images->createVariations('/path/to/image.png');
     */
    public function createVariations(string $imagePath, array $options = []): array
    {
        throw new ZAPIException('Bu özellik henüz implement edilmemiş', 501);
    }
}
