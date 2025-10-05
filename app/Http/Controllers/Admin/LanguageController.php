<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/*
 * Class LanguageController
 * @package App\Http\Controllers\Admin
 * @author Phan Xuan Dung <dungpx@vaixgroup.com>
 * @created_at 2021-06-24
 * */
class LanguageController extends Controller
{
    public function switch(Request $request, $language)
    {
        app()->setLocale($language);

        //Change language
        //session()->put('locale', $language);
        //$request->session()->put('locale', $language);
        session(['locale' => $language]);
        setlocale(LC_TIME, $language);

        Carbon::setLocale($language);
        App::setLocale($language);

        $request->session()->flash('status', 'Language changed to'.' '.strtoupper($language));

        return redirect()->back()->with('msg', 'Language changed to'.' '.strtoupper($language));
    }
}
