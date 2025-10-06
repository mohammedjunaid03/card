<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthCard;
use App\Models\User;
use App\Services\CardGeneratorService;

class CardApprovalController extends Controller
{
    protected $cardGenerator;

    public function __construct(CardGeneratorService $cardGenerator)
    {
        $this->cardGenerator = $cardGenerator;
    }

    public function index()
    {
        $pendingCards = HealthCard::where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.card-approvals', compact('pendingCards'));
    }

    public function approve($id)
    {
        $healthCard = HealthCard::with('user')->findOrFail($id);

        // Update card status
        $healthCard->update([
            'status' => 'active',
            'approved_at' => now(),
        ]);

        // Generate PDF card
        $this->cardGenerator->generateCard($healthCard->user, $healthCard);

        // TODO: Send email notification to user

        return redirect()->back()->with('success', 'Health card approved successfully!');
    }
}
