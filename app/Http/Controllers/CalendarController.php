<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(){
        dd(Carbon::now()->year);

        // $response = Http::get('http://api.aladhan.com/v1/calendar?latitude=51.508515&longitude=-0.1254872&method=2&year=2021&annual=true')
    }
}