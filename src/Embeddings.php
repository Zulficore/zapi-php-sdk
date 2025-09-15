<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Embeddings - Metin embedding endpoint'leri
 * 
 * Bu sınıf metin embedding işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $embeddings = $zapi->embeddings;
 * $embedding = $embeddings->create('Merhaba dünya');
 */
class Embeddings
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * Metin embedding oluşturur
     * 
     * Bu metod belirtilen metin için vektör embedding oluşturur.
     * 
     * @param string|array $input Embedding oluşturulacak metin(ler)
     * @param string $model Kullanılacak model
     * @param array $options Ek seçenekler
     * @return array Embedding sonucu
     * @throws ValidationException Geçersiz input
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $embedding = $zapi->embeddings->create('Merhaba dünya');
     * echo "Embedding boyutu: " . count($embedding['data'][0]['embedding']);
     * echo "Model: " . $embedding['model'];
     * echo "Token kullanımı: " . $embedding['usage']['totalTokens'];
     */
    public function create($input, string $model = 'text-embedding-ada-002', array $options = []): array
    {
        if (empty($input)) {
            throw new ValidationException('Input boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post('/embeddings', array_merge([
            'input' => $input,
            'model' => $model
        ], $options));
    }
}
