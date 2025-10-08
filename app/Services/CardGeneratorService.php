<?php

namespace App\Services;

use App\Models\HealthCard;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class CardGeneratorService
{
    /**
     * Generate a Health Card for a user.
     *
     * @param User $user
     * @return HealthCard
     */
    public function generateCard(User $user)
    {
        // Generate unique card number
        $cardNumber = $this->generateCardNumber();
        
        // Calculate dates - 1 year validity
        $issuedDate = now();
        $expiryDate = now()->addYear();
        
        // Generate QR Code
        $qrCodePath = $this->generateQRCode($cardNumber);
        
        // Generate PDF
        $pdfPath = $this->generatePDF($user, $cardNumber, $issuedDate, $expiryDate, $qrCodePath);
        
        // Create Health Card record with pending approval
        $healthCard = HealthCard::create([
            'user_id' => $user->id,
            'card_number' => $cardNumber,
            'qr_code_path' => $qrCodePath,
            'pdf_path' => $pdfPath,
            'issued_date' => $issuedDate,
            'expiry_date' => $expiryDate,
            'status' => 'pending',
            'approval_status' => 'pending',
        ]);
        
        return $healthCard;
    }

    /**
     * Approve a health card
     */
    public function approveCard(HealthCard $healthCard, User $approver)
    {
        $healthCard->update([
            'approval_status' => 'approved',
            'status' => 'active',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ]);

        // Send notification to user
        $healthCard->user->notifications()->create([
            'type' => 'health_card_approved',
            'title' => 'Health Card Approved',
            'message' => "Your health card ({$healthCard->card_number}) has been approved and is now active. You can now use it at partner hospitals.",
            'data' => [
                'card_number' => $healthCard->card_number,
                'approved_at' => now()->format('d-m-Y H:i:s')
            ]
        ]);

        return $healthCard;
    }

    /**
     * Reject a health card
     */
    public function rejectCard(HealthCard $healthCard, User $approver, string $reason = null)
    {
        $healthCard->update([
            'approval_status' => 'rejected',
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Send notification to user
        $healthCard->user->notifications()->create([
            'type' => 'health_card_rejected',
            'title' => 'Health Card Rejected',
            'message' => "Your health card application has been rejected. Reason: " . ($reason ?: 'Please contact support for more information.'),
            'data' => [
                'card_number' => $healthCard->card_number,
                'rejection_reason' => $reason,
                'rejected_at' => now()->format('d-m-Y H:i:s')
            ]
        ]);

        return $healthCard;
    }
    
    /**
     * Generate a unique card number.
     */
    private function generateCardNumber()
    {
        do {
            $cardNumber = 'HC' . date('Y') . Str::upper(Str::random(8));
        } while (HealthCard::where('card_number', $cardNumber)->exists());
        
        return $cardNumber;
    }
    
    /**
     * Generate a QR code PNG for the card number.
     */
    private function generateQRCode($cardNumber)
    {
        $qrCodeFileName = 'qr_' . $cardNumber . '.png';
        $qrCodePath = 'qrcodes/' . $qrCodeFileName;
        
        QrCode::format('png')
              ->size(300)
              ->generate($cardNumber, storage_path('app/public/' . $qrCodePath));
        
        return $qrCodePath;
    }
    
    /**
     * Generate PDF health card for the user.
     */
    private function generatePDF($user, $cardNumber, $issuedDate, $expiryDate, $qrCodePath)
    {
        $data = [
            'user' => $user,
            'cardNumber' => $cardNumber,
            'issuedDate' => $issuedDate->format('d-m-Y'),
            'expiryDate' => $expiryDate->format('d-m-Y'),
            'qrCodePath' => storage_path('app/public/' . $qrCodePath),
        ];
        
        $pdf = PDF::loadView('pdf.health-card', $data);
        
        $pdfFileName = 'card_' . $cardNumber . '.pdf';
        $pdfPath = 'health-cards/' . $pdfFileName;
        
        $pdf->save(storage_path('app/public/' . $pdfPath));
        
        return $pdfPath;
    }
}
