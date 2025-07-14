<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_message = $_POST['message'] ?? '';
    $api_url = 'http://localhost:11434/api/generate';

    $system_prompt = "Sen Fluffy adlı bir peluş oyuncak e-ticaret sitesinin yardımcı sohbet botusun. Sadece Türkçe, kısa, net ve samimi cevaplar ver. Gereksiz uzun açıklamalardan kaçın. Kullanıcıya siteyle ilgili bilgiler, ürünler, kampanyalar ve alışveriş hakkında yardımcı ol. Teslimat gibi konularda kısa ve öz bilgi ver (ör: 'Teslimat süresi genellikle 5-7 gündür.'). Kullanıcıya dostça, anlaşılır ve pratik cevaplar sun. İngilizce veya başka dilde asla cevap verme.";
    $full_prompt = $system_prompt . "\nKullanıcı: " . $user_message;

    $data = [
        'model' => 'mistral:7b',
        'prompt' => $full_prompt,
        'stream' => false
    ];

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    file_put_contents('ollama_debug.txt', $response); // DEBUG

    $result = json_decode($response, true);
    echo $result['response'] ?? $result['message'] ?? $result['content'] ?? 'Cevap alınamadı.';
}
?> 