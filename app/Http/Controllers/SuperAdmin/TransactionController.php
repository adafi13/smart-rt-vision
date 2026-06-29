<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['tenant', 'plan'])->latest();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('tenant', function($t) use ($search) {
                      $t->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Subscription::count(),
            'paid' => Subscription::whereNotNull('paid_at')->sum('amount'),
            'pending' => Subscription::where('status', 'pending_payment')->count(),
        ];

        return view('super-admin.transactions.index', compact('transactions', 'stats'));
    }
}
