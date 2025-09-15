<?php

declare(strict_types=1);

namespace ZAPI\Exceptions;

/**
 * Validation Exception - Doğrulama hatası
 * 
 * Bu exception gönderilen verilerin doğrulama kurallarına
 * uymadığı durumlarda fırlatılır.
 * 
 * @package ZAPI\Exceptions
 * @version 1.0.0
 * @author ZAPI Team
 * 
 * @example
 * try {
 *     $zapi->user->updateProfile(['email' => 'invalid-email']);
 * } catch (ValidationException $e) {
 *     echo "Doğrulama hatası: " . $e->getMessage();
 *     $errors = $e->getValidationErrors();
 *     foreach ($errors as $field => $error) {
 *         echo "{$field}: {$error}";
 *     }
 * }
 */
class ValidationException extends ZAPIException
{
    /**
     * Doğrulama hataları
     */
    private array $validationErrors = [];
    
    /**
     * ValidationException constructor
     * 
     * @param string $message Hata mesajı
     * @param int $code Hata kodu
     * @param array|null $responseData API yanıt verisi
     * @param int|null $httpStatusCode HTTP status kodu
     * @param \Throwable|null $previous Önceki exception
     */
    public function __construct(
        string $message = "Doğrulama hatası",
        int $code = 400,
        ?array $responseData = null,
        ?int $httpStatusCode = 400,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $responseData, $httpStatusCode, $previous);
        
        // Doğrulama hatalarını çıkar
        if ($responseData && isset($responseData['errors'])) {
            $this->validationErrors = $responseData['errors'];
        } elseif ($responseData && isset($responseData['validation_errors'])) {
            $this->validationErrors = $responseData['validation_errors'];
        }
    }
    
    /**
     * Hata türünü döndürür
     * 
     * @return string
     */
    public function getErrorType(): string
    {
        return 'ValidationException';
    }
    
    /**
     * Doğrulama hatalarını döndürür
     * 
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
    
    /**
     * Belirli bir alan için hata mesajını döndürür
     * 
     * @param string $field Alan adı
     * @return string|null
     */
    public function getFieldError(string $field): ?string
    {
        return $this->validationErrors[$field] ?? null;
    }
    
    /**
     * Hatalı alanları döndürür
     * 
     * @return array
     */
    public function getInvalidFields(): array
    {
        return array_keys($this->validationErrors);
    }
    
    /**
     * Belirli bir alanın hatalı olup olmadığını kontrol eder
     * 
     * @param string $field Alan adı
     * @return bool
     */
    public function hasFieldError(string $field): bool
    {
        return isset($this->validationErrors[$field]);
    }
    
    /**
     * Toplam hata sayısını döndürür
     * 
     * @return int
     */
    public function getErrorCount(): int
    {
        return count($this->validationErrors);
    }
    
    /**
     * Hata mesajlarını string olarak döndürür
     * 
     * @param string $separator Ayırıcı
     * @return string
     */
    public function getErrorsAsString(string $separator = "\n"): string
    {
        $errors = [];
        foreach ($this->validationErrors as $field => $error) {
            $errors[] = "{$field}: {$error}";
        }
        return implode($separator, $errors);
    }
}
