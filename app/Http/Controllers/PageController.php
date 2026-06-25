<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\Document;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Investment;

class PageController extends Controller
{
    public function about()
    {
        $stats = [
            'users' => User::count(),
            'deposits' => Deposit::where('status', 'approved')->sum('amount'),
            'withdrawals' => Withdrawal::where('status', 'approved')->sum('amount'),
            'investments' => Investment::sum('amount'),
        ];
        
        $documents = Document::where('is_active', true)->get();
        $faqs = Faq::where('is_active', true)->orderBy('sort_order')->get();

        return view('pages.about', compact('stats', 'documents', 'faqs'));
    }

    public function terms()
    {
        $title = "Terms & Conditions";
        $content = setting('terms_content', 'Default Terms and Conditions text. Please update in Admin Settings.');
        return view('pages.legal', compact('title', 'content'));
    }

    public function privacy()
    {
        $title = "Privacy Policy";
        $content = setting('privacy_content', 'Default Privacy Policy text. Please update in Admin Settings.');
        return view('pages.legal', compact('title', 'content'));
    }

    public function risk()
    {
        $title = "Risk Disclosure";
        $content = setting('risk_content', 'Default Risk Disclosure text. Please update in Admin Settings.');
        return view('pages.legal', compact('title', 'content'));
    }

    public function contact()
    {
        $title = "Contact Us";
        $content = setting('contact_content', 'Contact Information. Please update in Admin Settings.');
        return view('pages.legal', compact('title', 'content'));
    }
}
