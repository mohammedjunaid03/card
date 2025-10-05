<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use Illuminate\Support\Facades\Storage;
use App\Services\EncryptionService;

class HealthCardController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $card = $user->healthCard;

        if (!$card) {
            return response()->json(['message'=>'No health card found.'], 404);
        }

        return response()->json([
            'card_number' => $card->card_number,
            'download_url' => route('api.healthcard.download', $card->card_number)
        ]);
    }

    public function download($card_number, EncryptionService $encryptionService)
    {
        $card = HealthCard::where('card_number', $card_number)->firstOrFail();

        $pdfContent = Storage::disk('private')->get($card->card_path);

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename=health_card_{$card_number}.pdf");
    }
}
