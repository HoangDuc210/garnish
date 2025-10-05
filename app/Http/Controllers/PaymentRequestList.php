<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentRequestList extends Controller
{
    /**
     * Return view list order
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        return view('payment-request-list.index');
    }

}
