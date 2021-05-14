<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CreateYearlyCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:yearly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'gets yearly calendar and saves data to database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $year = Carbon::now()->year;
        $lang = 31.5055976;
        $lat = 74.3462127;
        $method = 2;
        $response = Http::get(
            "http://api.aladhan.com/v1/calendar?latitude=$lat&longitude=$lang&method=$method&year=$year&annual=true"
        );

        $status = $response['status'];
        $code = $response['code'];
        if ($status === 'OK' && $code === 200)
            $items = $response['data'];
            foreach ($items as $month=>$item) {
                $calendar = new Calendar();
                $calendar->month = $month;
                $calendar->year = $year;
                $calendar->data = json_encode($items);
                $calendar->save();
            }
    }
}