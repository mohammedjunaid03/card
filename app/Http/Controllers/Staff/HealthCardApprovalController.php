<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\HealthCard;
use App\Models\User;
use App\Services\CardGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthCardApprovalController extends Controller
{
    protected $cardGeneratorService;

    public function __construct(CardGeneratorService $cardGeneratorService)
    {
        $this->cardGeneratorService = $cardGeneratorService;
    }

    /**
     * Display pending health card approvals
     */
    public function index()
    {
        $pendingCards = HealthCard::with(['user'])
            ->where('approval_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.health-cards.pending', compact('pendingCards'));
    }

    /**
     * Show details of a specific health card for approval
     */
    public function show(HealthCard $healthCard)
    {
        $healthCard->load(['user']);
        
        return view('staff.health-cards.show', compact('healthCard'));
    }

    /**
     * Approve a health card
     */
    public function approve(Request $request, HealthCard $healthCard)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500'
        ]);

        $approver = Auth::user();
        
        $this->cardGeneratorService->approveCard($healthCard, $approver);

        return redirect()->route('staff.health-cards.pending')
            ->with('success', "Health card {$healthCard->card_number} has been approved successfully.");
    }

    /**
     * Reject a health card
     */
    public function reject(Request $request, HealthCard $healthCard)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $approver = Auth::user();
        
        $this->cardGeneratorService->rejectCard($healthCard, $approver, $request->rejection_reason);

        return redirect()->route('staff.health-cards.pending')
            ->with('success', "Health card {$healthCard->card_number} has been rejected.");
    }

    /**
     * Display all health cards (approved, rejected, expired)
     */
    public function all()
    {
        $healthCards = HealthCard::with(['user', 'approver'])
            ->where('approval_status', '!=', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('staff.health-cards.all', compact('healthCards'));
    }

    /**
     * Search health cards by phone number or card number
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        $searchType = $request->get('type', 'phone'); // phone or card_number

        $healthCards = collect();

        if ($query) {
            if ($searchType === 'phone') {
                $healthCards = HealthCard::with(['user', 'approver'])
                    ->whereHas('user', function($q) use ($query) {
                        $q->where('mobile', 'like', "%{$query}%");
                    })
                    ->get();
            } else {
                $healthCards = HealthCard::with(['user', 'approver'])
                    ->where('card_number', 'like', "%{$query}%")
                    ->get();
            }
        }

        return view('staff.health-cards.search', compact('healthCards', 'query', 'searchType'));
    }

    /**
     * Verify health card for hospital use
     */
    public function verify(Request $request)
    {
        $query = $request->get('query');
        $searchType = $request->get('type', 'phone');

        $healthCard = null;
        $user = null;

        if ($query) {
            if ($searchType === 'phone') {
                $user = User::where('mobile', $query)->first();
                if ($user) {
                    $healthCard = $user->healthCard;
                }
            } else {
                $healthCard = HealthCard::with('user')->where('card_number', $query)->first();
                if ($healthCard) {
                    $user = $healthCard->user;
                }
            }
        }

        return view('staff.health-cards.verify', compact('healthCard', 'user', 'query', 'searchType'));
    }
}