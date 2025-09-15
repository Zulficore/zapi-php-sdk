<?php

declare(strict_types=1);

namespace ZAPI\Exceptions;

/**
 * Server Exception - Sunucu hatası
 * 
 * Bu exception sunucu tarafında oluşan hatalar için fırlatılır.
 * 
 * @package ZAPI\Exceptions
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * try {
 *     $zapi->user->getProfile();
 * } catch (ServerException $e) {
 *     echo "Sunucu hatası: " . $e->getMessage();
 *     echo "Hata kodu: " . $e->getErrorCode();
 *     // Loglama yap
 *     error_log($e->toJson());
 * }
 */
class ServerException extends ZAPIException
{
    /**
     * ServerException constructor
     * 
     * @param string $message Hata mesajı
     * @param int $code Hata kodu
     * @param array|null $responseData API yanıt verisi
     * @param int|null $httpStatusCode HTTP status kodu
     * @param \Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = "Sunucu hatası",
        int $code = 500,
        ?array $responseData = null,
        ?int $httpStatusCode = 500,
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
        return 'ServerException';
    }
    
    /**
     * Internal server error olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isInternalServerError(): bool
    {
        return $this->getHttpStatusCode() === 500;
    }
    
    /**
     * Bad gateway hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isBadGateway(): bool
    {
        return $this->getHttpStatusCode() === 502;
    }
    
    /**
     * Service unavailable hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isServiceUnavailable(): bool
    {
        return $this->getHttpStatusCode() === 503;
    }
    
    /**
     * Gateway timeout hatası olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isGatewayTimeout(): bool
    {
        return $this->getHttpStatusCode() === 504;
    }
    
    /**
     * Geçici bir hata olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isTemporary(): bool
    {
        $statusCode = $this->getHttpStatusCode();
        return in_array($statusCode, [502, 503, 504]);
    }
    
    /**
     * Hata mesajının geçici olup olmadığını kontrol eder
     * 
     * @return bool
     */
    public function isTemporaryByMessage(): bool
    {
        $message = strtolower($this->getMessage());
        $temporaryKeywords = [
            'geçici', 'temporary', 'maintenance', 'bakım',
            'overloaded', 'aşırı yüklenmiş', 'timeout', 'zaman aşımı'
        ];
        
        foreach ($temporaryKeywords as $keyword) {
            if (strpos($message, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
}
