<?php

declare(strict_types=1);

namespace ZAPI\Exceptions;

/**
 * Rate Limit Exception - Rate limit hatası
 * 
 * Bu exception API rate limit aşıldığında fırlatılır.
 * 
 * @package ZAPI\Exceptions
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * try {
 *     $zapi->user->getProfile();
 * } catch (RateLimitException $e) {
 *     echo "Rate limit aşıldı: " . $e->getMessage();
 *     echo "Yeniden deneme süresi: " . $e->getRetryAfter() . " saniye";
 *     sleep($e->getRetryAfter());
 * }
 */
class RateLimitException extends ZAPIException
{
    /**
     * Yeniden deneme süresi (saniye)
     */
    private ?int $retryAfter = null;
    
    /**
     * Rate limit türü
     */
    private ?string $rateLimitType = null;
    
    /**
     * RateLimitException constructor
     * 
     * @param string $message Hata mesajı
     * @param int $code Hata kodu
     * @param array|null $responseData API yanıt verisi
     * @param int|null $httpStatusCode HTTP status kodu
     * @param \Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = "Rate limit aşıldı",
        int $code = 429,
        ?array $responseData = null,
        ?int $httpStatusCode = 429,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $responseData, $httpStatusCode, $previous);
        
        // Rate limit bilgilerini çıkar
        if ($responseData) {
            $this->retryAfter = $responseData['retry_after'] ?? $responseData['retryAfter'] ?? null;
            $this->rateLimitType = $responseData['rate_limit_type'] ?? $responseData['rateLimitType'] ?? null;
        }
    }
    
    /**
     * Hata türünü döndürür
     * 
     * @return string
     */
    public function getErrorType(): string
    {
        return 'RateLimitException';
    }
    
    /**
     * Yeniden deneme süresini döndürür
     * 
     * @return int|null
     */
    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }
    
    /**
     * Rate limit türünü döndürür
     * 
     * @return string|null
     */
    public function getRateLimitType(): ?string
    {
        return $this->rateLimitType;
    }
    
    /**
     * Yeniden deneme süresinin belirtilip belirtilmediğini kontrol eder
     * 
     * @return bool
     */
    public function hasRetryAfter(): bool
    {
        return $this->retryAfter !== null;
    }
    
    /**
     * Rate limit türünün belirtilip belirtilmediğini kontrol eder
     * 
     * @return bool
     */
    public function hasRateLimitType(): bool
    {
        return $this->rateLimitType !== null;
    }
    
    /**
     * Günlük rate limit hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isDailyRateLimit(): bool
    {
        return $this->rateLimitType === 'daily' || 
               strpos(strtolower($this->getMessage()), 'günlük') !== false;
    }
    
    /**
     * Saatlik rate limit hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isHourlyRateLimit(): bool
    {
        return $this->rateLimitType === 'hourly' || 
               strpos(strtolower($this->getMessage()), 'saatlik') !== false;
    }
    
    /**
     * Dakikalık rate limit hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isMinutelyRateLimit(): bool
    {
        return $this->rateLimitType === 'minutely' || 
               strpos(strtolower($this->getMessage()), 'dakikalık') !== false;
    }
}
