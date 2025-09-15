<?php

declare(strict_types=1);

namespace ZAPI\Exceptions;

/**
 * ZAPI Exception - Ana exception sınıfı
 * 
 * Bu sınıf ZAPI SDK'sında oluşan tüm hatalar için temel exception sınıfıdır.
 * HTTP status kodları ve API yanıt verilerini içerir.
 * 
 * @package ZAPI\Exceptions
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * try {
 *     $zapi->user->getProfile();
 * } catch (ZAPIException $e) {
 *     echo "Hata: " . $e->getMessage();
 *     echo "HTTP Status: " . $e->getHttpStatusCode();
 * }
 */
class ZAPIException extends \Exception
{
    /**
     * API yanıt verisi
     */
    private ?array $responseData;
    
    /**
     * HTTP status kodu
     */
    private ?int $httpStatusCode;
    
    /**
     * Hata kodu
     */
    private ?string $errorCode;
    
    /**
     * Hata detayları
     */
    private ?array $errorDetails;
    
    /**
     * ZAPIException constructor
     * 
     * @param string $message Hata mesajı
     * @param int $code Hata kodu
     * @param array|null $responseData API yanıt verisi
     * @param int|null $httpStatusCode HTTP status kodu
     * @param \Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?array $responseData = null,
        ?int $httpStatusCode = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        
        $this->responseData = $responseData;
        $this->httpStatusCode = $httpStatusCode;
        $this->errorCode = $responseData['code'] ?? null;
        $this->errorDetails = is_array($responseData['details'] ?? null) ? $responseData['details'] : null;
    }
    
    /**
     * API yanıt verisini döndürür
     * 
     * @return array|null
     */
    public function getResponseData(): ?array
    {
        return $this->responseData;
    }
    
    /**
     * HTTP status kodunu döndürür
     * 
     * @return int|null
     */
    public function getHttpStatusCode(): ?int
    {
        return $this->httpStatusCode;
    }
    
    /**
     * Hata kodunu döndürür
     * 
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }
    
    /**
     * Hata detaylarını döndürür
     * 
     * @return array|null
     */
    public function getErrorDetails(): ?array
    {
        return $this->errorDetails;
    }
    
    /**
     * Hata türünü döndürür
     * 
     * @return string
     */
    public function getErrorType(): string
    {
        return 'ZAPIException';
    }
    
    /**
     * Hata mesajını detaylı olarak döndürür
     * 
     * @return string
     */
    public function getDetailedMessage(): string
    {
        $message = $this->getMessage();
        
        if ($this->httpStatusCode) {
            $message .= " (HTTP {$this->httpStatusCode})";
        }
        
        if ($this->errorCode) {
            $message .= " [{$this->errorCode}]";
        }
        
        return $message;
    }
    
    /**
     * Hata bilgilerini array olarak döndürür
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'type' => $this->getErrorType(),
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'errorCode' => $this->errorCode,
            'httpStatusCode' => $this->httpStatusCode,
            'responseData' => $this->responseData,
            'errorDetails' => $this->errorDetails,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString()
        ];
    }
    
    /**
     * Hata bilgilerini JSON olarak döndürür
     * 
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * String representation
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDetailedMessage();
    }
}
