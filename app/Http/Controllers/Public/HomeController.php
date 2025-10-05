<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home');
    }
    
    public function about()
    {
        return view('public.about');
    }
    
    public function howItWorks()
    {
        return view('public.how-it-works');
    }
    
    public function hospitalNetwork()
    {
        $hospitals = Hospital::where('status', 'approved')
                             ->with('services')
                             ->paginate(12);
        
        return view('public.hospital-network', compact('hospitals'));
    }
    
    public function faqs()
    {
        $faqs = [
            [
                'question' => 'What is a Health Card?',
                'answer' => 'A Health Card is a digital card that provides you with exclusive discounts at our partner hospitals. It makes healthcare more affordable and accessible.'
            ],
            [
                'question' => 'How do I get a Health Card?',
                'answer' => 'Simply register on our website, verify your email, and your digital health card will be generated instantly. You can download it as a PDF or access it from your dashboard.'
            ],
            [
                'question' => 'Is there any registration fee?',
                'answer' => 'No, registration is completely free. There are no hidden charges or annual fees.'
            ],
            [
                'question' => 'How do I use the Health Card?',
                'answer' => 'Visit any partner hospital, show your health card at reception, they will scan your QR code, and discounts will be applied automatically to your bill.'
            ],
            [
                'question' => 'What is the validity of the Health Card?',
                'answer' => 'The Health Card is valid for 5 years from the date of issue. You can renew it before expiry from your dashboard.'
            ],
            [
                'question' => 'Can I use the card for my family members?',
                'answer' => 'Each person needs their own individual health card. You can register multiple family members separately.'
            ],
        ];
        
        return view('public.faqs', compact('faqs'));
    }
    
    public function contact()
    {
        return view('public.contact');
    }
    
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Send email to admin
        \Mail::to('admin@healthcard.com')->send(new \App\Mail\ContactForm($request->all()));
        
        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
    
    public function getStats()
    {
        return response()->json([
            'users' => User::count(),
            'hospitals' => Hospital::where('status', 'approved')->count(),
            'cards' => HealthCard::count(),
            'savings' => PatientAvailment::sum('discount_amount'),
        ]);
    }
}