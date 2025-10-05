<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentReport extends Controller
{
    /**
     * Return view list order
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('payment-report.index');
    }

}
