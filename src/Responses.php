<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Responses - AI yanıtları endpoint'leri
 * 
 * Bu sınıf AI yanıtları oluşturma, listeleme, detay görüntüleme,
 * silme ve export işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $responses = $zapi->responses;
 * $response = $responses->create([
 *     'model' => 'gpt-3.5-turbo',
 *     'messages' => [['role' => 'user', 'content' => 'Merhaba']]
 * ]);
 */
class Responses
{
    /**
     * ZAPI instance
     */
    private ZAPI $zapi;
    
    /**
     * Responses constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * AI yanıtı oluşturur
     * 
     * Bu metod belirtilen model ile AI yanıtı oluşturur.
     * 
     * @param array $data Yanıt verileri
     * @return array AI yanıtı
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $response = $zapi->responses->create([
     *     'model' => 'gpt-3.5-turbo',
     *     'messages' => [
     *         ['role' => 'user', 'content' => 'Merhaba, nasılsın?']
     *     ],
     *     'temperature' => 0.7,
     *     'max_tokens' => 1000
     * ]);
     * echo "AI Yanıtı: " . $response['choices'][0]['message']['content'];
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/responses', $data);
    }
    
    /**
     * AI yanıtlarını listeler
     * 
     * Bu metod kullanıcının AI yanıtlarını sayfalama ile listeler.
     * 
     * @param array $options Listeleme seçenekleri
     * @return array AI yanıtları listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $responses = $zapi->responses->list([
     *     'page' => 1,
     *     'limit' => 10,
     *     'model' => 'gpt-3.5-turbo',
     *     'status' => 'completed'
     * ]);
     * foreach ($responses['data'] as $response) {
     *     echo "Model: " . $response['model'];
     *     echo "Durum: " . $response['status'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/responses', $options);
    }
    
    /**
     * AI yanıt detaylarını getirir
     * 
     * Bu metod belirtilen ID'ye sahip AI yanıtının
     * detaylı bilgilerini getirir.
     * 
     * @param string $responseId Yanıt ID'si
     * @return array Yanıt detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $response = $zapi->responses->get('507f1f77bcf86cd799439012');
     * echo "Model: " . $response['response']['model'];
     * echo "Kullanım: " . $response['response']['usage']['totalTokens'] . " token";
     */
    public function get(string $responseId): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/responses/{$responseId}");
    }
    
    /**
     * AI yanıtını günceller
     * 
     * Bu metod belirtilen AI yanıtının bilgilerini günceller.
     * 
     * @param string $responseId Yanıt ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş yanıt bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->responses->update('507f1f77bcf86cd799439012', [
     *     'title' => 'Yeni Başlık',
     *     'tags' => ['ai', 'chat']
     * ]);
     */
    public function update(string $responseId, array $data): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/responses/{$responseId}", $data);
    }
    
    /**
     * AI yanıtını siler
     * 
     * Bu metod belirtilen AI yanıtını siler.
     * 
     * @param string $responseId Yanıt ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->responses->delete('507f1f77bcf86cd799439012');
     * echo "Yanıt silindi: " . $result['message'];
     */
    public function delete(string $responseId): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/responses/{$responseId}");
    }
    
    /**
     * AI yanıtını export eder
     * 
     * Bu metod belirtilen AI yanıtını farklı formatlarda export eder.
     * 
     * @param string $responseId Yanıt ID'si
     * @param string $format Export formatı (json, txt, markdown, pdf)
     * @return array Export verisi
     * @throws ValidationException Geçersiz ID veya format
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $export = $zapi->responses->export('507f1f77bcf86cd799439012', 'json');
     * file_put_contents('response.json', $export['content']);
     */
    public function export(string $responseId, string $format = 'json'): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        $allowedFormats = ['json', 'txt', 'markdown', 'pdf'];
        if (!in_array($format, $allowedFormats)) {
            throw new ValidationException('Desteklenmeyen format: ' . $format);
        }
        
        return $this->zapi->getHttpClient()->get("/responses/{$responseId}/export", ['format' => $format]);
    }
    
    /**
     * AI yanıt istatistiklerini getirir
     * 
     * Bu metod kullanıcının AI yanıt istatistiklerini getirir.
     * 
     * @param array $options İstatistik seçenekleri
     * @return array İstatistik verileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $stats = $zapi->responses->getStats(['period' => 'monthly']);
     * echo "Toplam yanıt: " . $stats['totalResponses'];
     * echo "Toplam token: " . $stats['totalTokens'];
     * echo "Ortalama token: " . $stats['averageTokens'];
     */
    public function getStats(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/responses/stats', $options);
    }
    
    /**
     * AI yanıt arama yapar
     * 
     * Bu metod AI yanıtlarında arama yapar.
     * 
     * @param array $options Arama seçenekleri
     * @return array Arama sonuçları
     * @throws ValidationException Geçersiz arama parametreleri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $results = $zapi->responses->search([
     *     'query' => 'merhaba',
     *     'model' => 'gpt-3.5-turbo',
     *     'dateFrom' => '2024-01-01',
     *     'dateTo' => '2024-01-31'
     * ]);
     * foreach ($results['data'] as $result) {
     *     echo "Bulunan: " . $result['title'];
     * }
     */
    public function search(array $options): array
    {
        if (empty($options['query'])) {
            throw new ValidationException('Arama sorgusu boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get('/responses/search', $options);
    }
    
    /**
     * AI yanıt kategorilerini listeler
     * 
     * Bu metod mevcut AI yanıt kategorilerini listeler.
     * 
     * @return array Kategoriler listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $categories = $zapi->responses->getCategories();
     * foreach ($categories['categories'] as $category) {
     *     echo "Kategori: " . $category['name'];
     * }
     */
    public function getCategories(): array
    {
        return $this->zapi->getHttpClient()->get('/responses/categories');
    }
    
    /**
     * AI yanıt etiketlerini listeler
     * 
     * Bu metod mevcut AI yanıt etiketlerini listeler.
     * 
     * @return array Etiketler listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $tags = $zapi->responses->getTags();
     * foreach ($tags['tags'] as $tag) {
     *     echo "Etiket: " . $tag['name'] . " (" . $tag['count'] . ")";
     * }
     */
    public function getTags(): array
    {
        return $this->zapi->getHttpClient()->get('/responses/tags');
    }
    
    /**
     * AI yanıtını favorilere ekler/çıkarır
     * 
     * Bu metod AI yanıtını favorilere ekler veya çıkarır.
     * 
     * @param string $responseId Yanıt ID'si
     * @return array Favori işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->responses->toggleFavorite('507f1f77bcf86cd799439012');
     * echo "Favori durumu değiştirildi: " . $result['message'];
     */
    public function toggleFavorite(string $responseId): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->patch("/responses/{$responseId}/favorite");
    }
    
    /**
     * AI yanıtını paylaşır
     * 
     * Bu metod AI yanıtını paylaşılabilir link oluşturur.
     * 
     * @param string $responseId Yanıt ID'si
     * @param array $options Paylaşım seçenekleri
     * @return array Paylaşım sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $share = $zapi->responses->share('507f1f77bcf86cd799439012', [
     *     'expires' => '7d',
     *     'password' => 'secret123'
     * ]);
     * echo "Paylaşım linki: " . $share['shareUrl'];
     */
    public function share(string $responseId, array $options = []): array
    {
        if (empty($responseId)) {
            throw new ValidationException('Yanıt ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->post("/responses/{$responseId}/share", $options);
    }
}
