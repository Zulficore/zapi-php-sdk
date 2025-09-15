<?php

declare(strict_types=1);

namespace ZAPI\Exceptions;

/**
 * Authentication Exception - Kimlik doğrulama hatası
 * 
 * Bu exception API anahtarı geçersiz, süresi dolmuş veya
 * yetkilendirme hatası durumunda fırlatılır.
 * 
 * @package ZAPI\Exceptions
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * try {
 *     $zapi->user->getProfile();
 * } catch (AuthenticationException $e) {
 *     echo "Kimlik doğrulama hatası: " . $e->getMessage();
 *     // API anahtarını yenile
 *     $zapi->setApiKey('new_api_key');
 * }
 */
class AuthenticationException extends ZAPIException
{
    /**
     * AuthenticationException constructor
     * 
     * @param string $message Hata mesajı
     * @param int $code Hata kodu
     * @param array|null $responseData API yanıt verisi
     * @param int|null $httpStatusCode HTTP status kodu
     * @param \Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = "Kimlik doğrulama hatası",
        int $code = 401,
        ?array $responseData = null,
        ?int $httpStatusCode = 401,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $responseData, $httpStatusCode, $previous);
    }
    
    /**
     * Hata türünü döndürür
     * 
     * @return string
     */
    public function getErrorType(): string
    {
        return 'AuthenticationException';
    }
    
    /**
     * API anahtarının geçersiz olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isInvalidApiKey(): bool
    {
        $message = strtolower($this->getMessage());
        return strpos($message, 'invalid') !== false || 
               strpos($message, 'geçersiz') !== false ||
               strpos($message, 'unauthorized') !== false;
    }
    
    /**
     * API anahtarının süresinin dolup dolmadığını kontrol eder
     * 
     * @return bool
     */
    public function isExpiredApiKey(): bool
    {
        $message = strtolower($this->getMessage());
        return strpos($message, 'expired') !== false || 
               strpos($message, 'süresi dolmuş') !== false ||
               strpos($message, 'token expired') !== false;
    }
    
    /**
     * Yetkilendirme hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isAuthorizationError(): bool
    {
        $message = strtolower($this->getMessage());
        return strpos($message, 'forbidden') !== false || 
               strpos($message, 'yetkisiz') !== false ||
               strpos($message, 'access denied') !== false;
    }
}
