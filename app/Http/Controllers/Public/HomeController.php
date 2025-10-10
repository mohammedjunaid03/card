<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Models\HealthCard;
use App\Models\PatientAvailment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    
    public function hospitalNetwork(Request $request)
    {
        $query = Hospital::where('status', 'active')
                         ->with('ownedServices');

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->whereHas('ownedServices', function ($q) use ($request) {
                $q->where('category', 'like', '%' . $request->service . '%');
            });
        }

        // Filter by minimum discount
        if ($request->filled('discount')) {
            $query->whereHas('ownedServices', function ($q) use ($request) {
                $q->where('discount_percentage', '>=', $request->discount);
            });
        }

        $hospitals = $query->orderBy('name')->paginate(12);
        
        // Get all services for filter dropdown
        $services = \App\Models\Service::distinct('category')->pluck('category');
        
        // Get all cities for filter dropdown
        $cities = Hospital::where('status', 'active')
                          ->distinct('city')
                          ->pluck('city')
                          ->filter()
                          ->sort();
        
        return view('public.hospital-network', compact('hospitals', 'services', 'cities'));
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
        try {
            Mail::to('admin@kcchealthcard.com')->send(new \App\Mail\ContactForm($request->all()));
            return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Contact form email failed: ' . $e->getMessage());
            return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
        }
    }
    
    public function getStats()
    {
        // Get real statistics from database
        $users = User::count();
        $hospitals = Hospital::where('status', 'active')->count();
        $cards = HealthCard::count();
        $savings = PatientAvailment::sum('discount_amount') ?? 0;
        
        // Ensure all values are positive integers
        return response()->json([
            'users' => max(0, (int) $users),
            'hospitals' => max(0, (int) $hospitals),
            'cards' => max(0, (int) $cards),
            'savings' => max(0, (int) $savings),
        ]);
    }
    
    public function registerHospital()
    {
        return view('public.register-hospital');
    }
    
    public function partnerBenefits()
    {
        return view('public.partner-benefits');
    }
    
    public function termsConditions()
    {
        return view('public.terms-conditions');
    }
    
    public function privacyPolicy()
    {
        return view('public.privacy-policy');
    }
    
    public function hospitalDetails($id)
    {
        $hospital = Hospital::where('status', 'active')
                           ->with('ownedServices')
                           ->findOrFail($id);
        
        return view('public.hospital-details', compact('hospital'));
    }
}