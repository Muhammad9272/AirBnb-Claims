<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitWalletController extends Controller
{
     public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }
    public function index()
    {
        $user = Auth::user();
        $gs = GeneralSetting::first();
        $transactions = $user->bitTransactions()
            ->latest()
            ->paginate(15);
            
        return view('user.bit-wallet.index', compact('user', 'transactions', 'gs'));
    }
}
