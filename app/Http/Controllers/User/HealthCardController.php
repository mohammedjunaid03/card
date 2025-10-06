<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HealthCard;
use App\Services\CardGeneratorService;

class HealthCardController extends Controller
{
    protected $cardGenerator;

    public function __construct(CardGeneratorService $cardGenerator)
    {
        $this->cardGenerator = $cardGenerator;
    }

    public function show()
    {
        $user = Auth::guard('web')->user();
        $healthCard = HealthCard::where('user_id', $user->id)->first();

        return view('user.health-card', compact('healthCard', 'user'));
    }

    public function download()
    {
        $user = Auth::guard('web')->user();
        $healthCard = HealthCard::where('user_id', $user->id)->first();

        if (!$healthCard) {
            return redirect()->back()->with('error', 'Health card not found.');
        }

        $pdfPath = $this->cardGenerator->generateCard($user, $healthCard);

        return response()->download(storage_path('app/' . $pdfPath));
    }
}
