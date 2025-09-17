<?php

declare(strict_types=1);

namespace ZAPI;

use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

/**
 * Content - İçerik yönetimi endpoint'leri
 * 
 * Bu sınıf içerik oluşturma, güncelleme, silme, listeleme ve arama işlemleri için endpoint'leri içerir.
 * 
 * @package ZAPI
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * $content = $zapi->content;
 * $contents = $content->list();
 * $newContent = $content->create(['title' => 'Yeni İçerik']);
 */
class Content
{
    private ZAPI $zapi;
    
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }
    
    /**
     * İçerikleri listeler
     * 
     * @param array $options Listeleme seçenekleri
     * @return array İçerikler listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $contents = $zapi->content->list();
     * foreach ($contents['data'] as $content) {
     *     echo "İçerik: " . $content['title'];
     * }
     */
    public function list(array $options = []): array
    {
        return $this->zapi->getHttpClient()->get('/content', $options);
    }
    
    /**
     * Yeni içerik oluşturur
     * 
     * @param array $data İçerik verileri
     * @return array Oluşturulan içerik bilgileri
     * @throws ValidationException Geçersiz veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $content = $zapi->content->create([
     *     'title' => 'Yeni İçerik',
     *     'content' => 'İçerik metni',
     *     'type' => 'article',
     *     'category' => 'news'
     * ]);
     */
    public function create(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/content', $data);
    }
    
    /**
     * İçerik detaylarını getirir
     * 
     * @param string $contentId İçerik ID'si
     * @return array İçerik detayları
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $content = $zapi->content->get('507f1f77bcf86cd799439011');
     * echo "İçerik: " . $content['content']['title'];
     */
    public function get(string $contentId): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/content/{$contentId}");
    }
    
    /**
     * İçerik bilgilerini günceller
     * 
     * @param string $contentId İçerik ID'si
     * @param array $data Güncellenecek veriler
     * @return array Güncellenmiş içerik bilgileri
     * @throws ValidationException Geçersiz ID veya veri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->content->update('507f1f77bcf86cd799439011', [
     *     'title' => 'Güncellenmiş İçerik',
     *     'content' => 'Yeni içerik metni'
     * ]);
     */
    public function update(string $contentId, array $data): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/content/{$contentId}", $data);
    }
    
    /**
     * İçeriği siler
     * 
     * @param string $contentId İçerik ID'si
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->content->delete('507f1f77bcf86cd799439011');
     * echo "İçerik silindi: " . $result['message'];
     */
    public function delete(string $contentId): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/content/{$contentId}");
    }
    
    /**
     * İçerik kategorilerini listeler
     * 
     * @return array Kategoriler listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $categories = $zapi->content->getCategories();
     * foreach ($categories['categories'] as $category) {
     *     echo "Kategori: " . $category['name'];
     * }
     */
    public function getCategories(): array
    {
        return $this->zapi->getHttpClient()->get('/content/categories/list');
    }
    
    /**
     * İçerik türlerini listeler
     * 
     * @return array Türler listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $types = $zapi->content->getTypes();
     * foreach ($types['types'] as $type) {
     *     echo "Tür: " . $type['name'];
     * }
     */
    public function getTypes(): array
    {
        return $this->zapi->getHttpClient()->get('/content/types/list');
    }
    
    /**
     * İçerik dillerini listeler
     * 
     * @return array Diller listesi
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $languages = $zapi->content->getLanguages();
     * foreach ($languages['languages'] as $language) {
     *     echo "Dil: " . $language['name'];
     * }
     */
    public function getLanguages(): array
    {
        return $this->zapi->getHttpClient()->get('/content/languages/list');
    }
    
    /**
     * Gelişmiş içerik arama yapar
     * 
     * @param array $options Arama seçenekleri
     * @return array Arama sonuçları
     * @throws ValidationException Geçersiz arama parametreleri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $results = $zapi->content->searchAdvanced([
     *     'query' => 'merhaba',
     *     'category' => 'news',
     *     'type' => 'article',
     *     'language' => 'tr'
     * ]);
     */
    public function searchAdvanced(array $options): array
    {
        if (empty($options['query'])) {
            throw new ValidationException('Arama sorgusu boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get('/content/search/advanced', $options);
    }
    
    /**
     * İçerik istatistiklerini getirir
     * 
     * @return array İstatistik verileri
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $stats = $zapi->content->getStats();
     * echo "Toplam içerik: " . $stats['totalContent'];
     * echo "Aktif içerik: " . $stats['activeContent'];
     */
    public function getStats(): array
    {
        return $this->zapi->getHttpClient()->get('/content/stats/summary');
    }
    
    /**
     * İçerik metadata bilgilerini getirir
     * 
     * @param string $contentId İçerik ID'si
     * @param string $path Metadata path (opsiyonel)
     * @return array Metadata bilgileri
     * @throws ValidationException Geçersiz ID
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $metadata = $zapi->content->getMetadata('507f1f77bcf86cd799439011', 'seo');
     * echo "SEO metadata: " . json_encode($metadata['value']);
     */
    public function getMetadata(string $contentId, string $path = ''): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        $endpoint = $path ? "/content/{$contentId}/metadata/{$path}" : "/content/{$contentId}/metadata";
        return $this->zapi->getHttpClient()->get($endpoint);
    }
    
    /**
     * İçerik metadata bilgilerini günceller
     * 
     * @param string $contentId İçerik ID'si
     * @param string $path Metadata path
     * @param array $value Metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->content->updateMetadata('507f1f77bcf86cd799439011', 'seo', [
     *     'title' => 'SEO Başlık',
     *     'description' => 'SEO Açıklama',
     *     'keywords' => ['seo', 'içerik']
     * ]);
     */
    public function updateMetadata(string $contentId, string $path, array $value): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->put("/content/{$contentId}/metadata/{$path}", ['value' => $value]);
    }
    
    /**
     * İçerik metadata bilgilerini kısmi olarak günceller
     * 
     * @param string $contentId İçerik ID'si
     * @param string $path Metadata path
     * @param array $value Güncellenecek metadata değeri
     * @return array Güncellenmiş metadata
     * @throws ValidationException Geçersiz ID, path veya değer
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $updated = $zapi->content->patchMetadata('507f1f77bcf86cd799439011', 'seo', [
     *     'title' => 'Güncellenmiş SEO Başlık'
     * ]);
     */
    
    /**
     * İçerik metadata bilgilerini siler
     * 
     * @param string $contentId İçerik ID'si
     * @param string $path Metadata path
     * @return array Silme işlemi sonucu
     * @throws ValidationException Geçersiz ID veya path
     * @throws AuthenticationException API anahtarı geçersizse
     * 
     * @example
     * $result = $zapi->content->deleteMetadata('507f1f77bcf86cd799439011', 'seo');
     * echo "Metadata silindi: " . $result['message'];
     */
    public function deleteMetadata(string $contentId, string $path): array
    {
        if (empty($contentId)) {
            throw new ValidationException('İçerik ID\'si boş olamaz');
        }
        
        if (empty($path)) {
            throw new ValidationException('Metadata path boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->delete("/content/{$contentId}/metadata/{$path}");
    }
    
    /**
     * Public içerik getirir
     * 
     * @param string $slug İçerik slug'ı
     * @return array İçerik bilgileri
     * @throws ValidationException Geçersiz slug
     * @throws ZAPIException İçerik bulunamadı
     * 
     * @example
     * $content = $zapi->content->getPublic('merhaba-dunya');
     * echo "Public içerik: " . $content['content']['title'];
     */
    public function getPublic(string $slug): array
    {
        if (empty($slug)) {
            throw new ValidationException('Slug boş olamaz');
        }
        
        return $this->zapi->getHttpClient()->get("/content/public/{$slug}");
    }
}
