<?php

namespace ZAPI;

use ZAPI\Exceptions\ValidationException;

/**
 * AppleTest Endpoint
 * 
 * Bu sınıf Apple test işlemlerini yönetir.
 */
class AppleTest
{
    private ZAPI $zapi;

    /**
     * AppleTest constructor
     * 
     * @param ZAPI $zapi ZAPI instance
     */
    public function __construct(ZAPI $zapi)
    {
        $this->zapi = $zapi;
    }

    /**
     * Apple test sayfasını getirir
     * 
     * Bu metod Apple test sayfasını getirir.
     * 
     * @return array Apple test sayfası
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $page = $zapi->appleTest->get();
     * echo "Apple test sayfası: " . $page['html'];
     */
    public function get(): array
    {
        return $this->zapi->getHttpClient()->get('/apple-test');
    }

    /**
     * Apple test sayfasını getirir
     * 
     * Bu metod Apple test sayfasını getirir.
     * 
     * @return array Apple test sayfası
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $page = $zapi->appleTest->getTest();
     * echo "Apple test sayfası: " . $page['html'];
     */
    public function getTest(): array
    {
        return $this->zapi->getHttpClient()->get('/apple-test/test');
    }

    /**
     * Apple konfigürasyonunu ayarlar
     * 
     * Bu metod Apple konfigürasyonunu ayarlar.
     * 
     * @param array $data Konfigürasyon verileri
     * @return array Konfigürasyon sonucu
     * @throws ValidationException Geçersiz parametre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $config = $zapi->appleTest->setConfig([
     *     'clientId' => 'your_client_id',
     *     'teamId' => 'your_team_id'
     * ]);
     * echo "Konfigürasyon ayarlandı: " . $config['message'];
     */
    public function setConfig(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apple-test/config', $data);
    }

    /**
     * Apple URL oluşturur
     * 
     * Bu metod Apple URL oluşturur.
     * 
     * @param array $data URL verileri
     * @return array URL sonucu
     * @throws ValidationException Geçersiz parametre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $url = $zapi->appleTest->generateUrl([
     *     'redirectUri' => 'https://example.com/callback'
     * ]);
     * echo "Apple URL: " . $url['url'];
     */
    public function generateUrl(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apple-test/generate-url', $data);
    }

    /**
     * Apple secret test eder
     * 
     * Bu metod Apple secret test eder.
     * 
     * @param array $data Secret verileri
     * @return array Test sonucu
     * @throws ValidationException Geçersiz parametre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $test = $zapi->appleTest->testSecret([
     *     'clientSecret' => 'your_client_secret'
     * ]);
     * echo "Secret test sonucu: " . $test['message'];
     */
    public function testSecret(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apple-test/test-secret', $data);
    }

    /**
     * Apple callback işlemi yapar
     * 
     * Bu metod Apple callback işlemi yapar.
     * 
     * @param array $data Callback verileri
     * @return array Callback sonucu
     * @throws ValidationException Geçersiz parametre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $callback = $zapi->appleTest->handleCallback([
     *     'code' => 'authorization_code',
     *     'state' => 'state_value'
     * ]);
     * echo "Callback işlemi: " . $callback['message'];
     */
    public function handleCallback(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apple-test/callback', $data);
    }

    /**
     * Apple token değişimi yapar
     * 
     * Bu metod Apple token değişimi yapar.
     * 
     * @param array $data Token verileri
     * @return array Token sonucu
     * @throws ValidationException Geçersiz parametre
     * @throws ZAPIException Sunucu hatası
     * 
     * @example
     * $token = $zapi->appleTest->exchangeToken([
     *     'code' => 'authorization_code',
     *     'clientId' => 'your_client_id'
     * ]);
     * echo "Token: " . $token['access_token'];
     */
    public function exchangeToken(array $data): array
    {
        return $this->zapi->getHttpClient()->post('/apple-test/exchange-token', $data);
    }
}
