<?php

/**
 * ZAPI PHP SDK - AI İşlemleri Örnekleri
 * 
 * Bu dosya AI yanıtları, embeddings, resim ve ses işlemlerinin örneklerini gösterir.
 * 
 * @author ZAPI Team
 * @version 1.0.0
 */

require_once '../vendor/autoload.php';

use ZAPI\ZAPI;
use ZAPI\Exceptions\ZAPIException;
use ZAPI\Exceptions\AuthenticationException;
use ZAPI\Exceptions\ValidationException;

$zapi = new ZAPI('your_api_key', 'your_app_id');

// Önce giriş yap
try {
    $login = $zapi->auth->login('user@example.com', 'password');
    if ($login['success']) {
        $zapi->setBearerToken($login['token']);
        echo "Giriş başarılı!" . PHP_EOL . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Giriş hatası: " . $e->getMessage() . PHP_EOL;
    exit;
}

echo "=== ZAPI AI İşlemleri Örnekleri ===" . PHP_EOL . PHP_EOL;

try {
    // 1. Temel AI Sohbet
    echo "1. Temel AI Sohbet" . PHP_EOL;
    echo "==================" . PHP_EOL;
    
    $chatResponse = $zapi->responses->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'Merhaba! Python programlama hakkında kısa bilgi verir misin?']
        ],
        'temperature' => 0.7,
        'max_tokens' => 200
    ]);
    
    echo "AI Yanıtı: " . $chatResponse['choices'][0]['message']['content'] . PHP_EOL;
    echo "Model: " . $chatResponse['model'] . PHP_EOL;
    echo "Token kullanımı: " . $chatResponse['usage']['totalTokens'] . PHP_EOL;
    echo "Yanıt ID: " . $chatResponse['id'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "AI sohbet hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 2. Çok Mesajlı Konuşma
    echo "2. Çok Mesajlı Konuşma" . PHP_EOL;
    echo "======================" . PHP_EOL;
    
    $conversationResponse = $zapi->responses->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'Sen yardımcı bir asistansın. Türkçe yanıt ver.'],
            ['role' => 'user', 'content' => 'Merhaba, adım Ali.'],
            ['role' => 'assistant', 'content' => 'Merhaba Ali! Size nasıl yardımcı olabilirim?'],
            ['role' => 'user', 'content' => 'JavaScript öğrenmek istiyorum. Nereden başlamalıyım?']
        ],
        'temperature' => 0.8,
        'max_tokens' => 300
    ]);
    
    echo "AI Yanıtı: " . $conversationResponse['choices'][0]['message']['content'] . PHP_EOL;
    echo "Token kullanımı: " . $conversationResponse['usage']['totalTokens'] . PHP_EOL;
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Konuşma hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 3. Text Embedding
    echo "3. Text Embedding" . PHP_EOL;
    echo "=================" . PHP_EOL;
    
    $embedding = $zapi->embeddings->create(
        'Bu bir test metnidir. Embedding oluşturmak için kullanılıyor.',
        'text-embedding-ada-002'
    );
    
    echo "Embedding boyutu: " . count($embedding['data'][0]['embedding']) . PHP_EOL;
    echo "Model: " . $embedding['model'] . PHP_EOL;
    echo "Token kullanımı: " . $embedding['usage']['totalTokens'] . PHP_EOL;
    echo "İlk 5 değer: " . implode(', ', array_slice($embedding['data'][0]['embedding'], 0, 5)) . PHP_EOL;
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Embedding hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 4. Çoklu Text Embedding
    echo "4. Çoklu Text Embedding" . PHP_EOL;
    echo "=======================" . PHP_EOL;
    
    $multipleEmbeddings = $zapi->embeddings->create([
        'İlk metin örneği',
        'İkinci metin örneği',
        'Üçüncü metin örneği'
    ]);
    
    echo "Embedding sayısı: " . count($multipleEmbeddings['data']) . PHP_EOL;
    echo "Toplam token kullanımı: " . $multipleEmbeddings['usage']['totalTokens'] . PHP_EOL;
    
    foreach ($multipleEmbeddings['data'] as $index => $embedding) {
        echo "Embedding " . ($index + 1) . " boyutu: " . count($embedding['embedding']) . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Çoklu embedding hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 5. AI Resim Oluşturma
    echo "5. AI Resim Oluşturma" . PHP_EOL;
    echo "=====================" . PHP_EOL;
    
    $imageGeneration = $zapi->images->generate(
        'Güzel bir gün batımı manzarası, yüksek kalite, realistik',
        [
            'size' => '1024x1024',
            'n' => 1,
            'quality' => 'standard'
        ]
    );
    
    echo "Resim oluşturuldu!" . PHP_EOL;
    echo "Resim URL: " . $imageGeneration['data'][0]['url'] . PHP_EOL;
    echo "Oluşturma zamanı: " . date('Y-m-d H:i:s', $imageGeneration['created']) . PHP_EOL;
    
    if (isset($imageGeneration['data'][0]['revised_prompt'])) {
        echo "Düzenlenmiş prompt: " . $imageGeneration['data'][0]['revised_prompt'] . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Resim oluşturma hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 6. Text-to-Speech
    echo "6. Text-to-Speech" . PHP_EOL;
    echo "=================" . PHP_EOL;
    
    $speech = $zapi->audio->textToSpeech(
        'Merhaba, bu bir test ses dosyasıdır. ZAPI ile oluşturulmuştur.',
        'alloy',
        ['response_format' => 'mp3']
    );
    
    echo "Ses dosyası oluşturuldu!" . PHP_EOL;
    echo "Ses URL: " . $speech['audio']['url'] . PHP_EOL;
    echo "Dosya boyutu: " . number_format($speech['audio']['size'] / 1024, 2) . " KB" . PHP_EOL;
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Text-to-Speech hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 7. AI Yanıt Geçmişi
    echo "7. AI Yanıt Geçmişi" . PHP_EOL;
    echo "===================" . PHP_EOL;
    
    $responseHistory = $zapi->user->getResponses([
        'page' => 1,
        'limit' => 5,
        'status' => 'completed'
    ]);
    
    echo "Toplam yanıt: " . $responseHistory['pagination']['totalItems'] . PHP_EOL;
    echo "Son 5 yanıt:" . PHP_EOL;
    
    foreach ($responseHistory['data'] as $response) {
        echo "- " . date('Y-m-d H:i:s', strtotime($response['createdAt'])) . 
             " | Model: " . $response['model'] . 
             " | Token: " . $response['usage']['totalTokens'] . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Yanıt geçmişi hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 8. AI Model Listesi
    echo "8. AI Model Listesi" . PHP_EOL;
    echo "===================" . PHP_EOL;
    
    $models = $zapi->info->getAIModels();
    
    echo "Toplam sağlayıcı: " . count($models['providers']) . PHP_EOL;
    echo "Toplam model: " . count($models['models']) . PHP_EOL;
    echo PHP_EOL;
    
    echo "Aktif modeller:" . PHP_EOL;
    foreach ($models['models'] as $model) {
        if ($model['status'] === 'active') {
            echo "- " . $model['name'] . " (" . $model['type'] . ")" . PHP_EOL;
        }
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Model listesi hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 9. Yanıt Arama
    echo "9. Yanıt Arama" . PHP_EOL;
    echo "==============" . PHP_EOL;
    
    $searchResults = $zapi->responses->search([
        'query' => 'python',
        'model' => 'gpt-3.5-turbo',
        'dateFrom' => '2024-01-01',
        'dateTo' => date('Y-m-d')
    ]);
    
    echo "Arama sonucu: " . count($searchResults['data']) . " yanıt bulundu" . PHP_EOL;
    
    foreach ($searchResults['data'] as $result) {
        echo "- " . substr($result['messages'][0]['content'], 0, 50) . "..." . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Yanıt arama hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

try {
    // 10. Yanıt Export
    echo "10. Yanıt Export" . PHP_EOL;
    echo "================" . PHP_EOL;
    
    // İlk yanıtı export et (varsa)
    if (isset($chatResponse['id'])) {
        $export = $zapi->user->exportResponse($chatResponse['id'], 'json');
        
        echo "Export başarılı!" . PHP_EOL;
        echo "Dosya adı: " . $export['export']['filename'] . PHP_EOL;
        echo "Boyut: " . $export['export']['size'] . " bytes" . PHP_EOL;
        echo "Format: " . $export['export']['format'] . PHP_EOL;
        
        // Export içeriğini dosyaya kaydet
        file_put_contents(__DIR__ . '/exported_response.json', $export['export']['content']);
        echo "Dosya kaydedildi: exported_response.json" . PHP_EOL;
    }
    echo PHP_EOL;
    
} catch (Exception $e) {
    echo "Export hatası: " . $e->getMessage() . PHP_EOL . PHP_EOL;
}

echo "=== AI Örnekleri Tamamlandı ===" . PHP_EOL;
