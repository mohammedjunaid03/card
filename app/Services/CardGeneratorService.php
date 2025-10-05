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
        
        // Calculate dates
        $issuedDate = now();
        $expiryDate = now()->addYears(config('app.card_validity_years', 5));
        
        // Generate QR Code
        $qrCodePath = $this->generateQRCode($cardNumber);
        
        // Generate PDF
        $pdfPath = $this->generatePDF($user, $cardNumber, $issuedDate, $expiryDate, $qrCodePath);
        
        // Create Health Card record
        $healthCard = HealthCard::create([
            'user_id' => $user->id,
            'card_number' => $cardNumber,
            'qr_code_path' => $qrCodePath,
            'pdf_path' => $pdfPath,
            'issued_date' => $issuedDate,
            'expiry_date' => $expiryDate,
            'status' => 'active',
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
