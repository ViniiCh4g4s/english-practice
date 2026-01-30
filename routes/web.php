<?php

use App\Services\AiCorrectionService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/test-ai', function () {
    $service = new AiCorrectionService;

    try {
        $result = $service->correctTranslation(
            textPt: 'Eu vou na escola hoje',
            textEnReference: "I'm going to school today",
            userTextEn: 'I go at school today'
        );

        // ✅ Adiciona charset UTF-8 corretamente
        return response()->json($result, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/list-models', function () {
    $apiKey = config('services.gemini.key');
    $url = "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}";

    $response = Http::get($url);

    return response()->json($response->json(), 200, [], JSON_PRETTY_PRINT);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
