<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use App\Models\HadithUrdu;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Sebbmyr\LaravelTeams\Cards\CustomCard;

class SendDailyTiming extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:timing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends daily notification to user';

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
        $datetime = Carbon::now();
        $year = $datetime->year;
        $month = $datetime->month;
        $day = $datetime->day;
        $calendar = Calendar::where('month', $month)->where('year', $year)->get()->first();
        $data = $calendar['data'];
        $data = json_decode($data);
        $date_timings = $data [$day -1];
        
        $timings = $date_timings->timings;
        $date = $date_timings->date;
        $readable_date = $date->readable;
        $card = new CustomCard("Prayer Times", $readable_date);
        $card->addColor('800080')
            ->addFactsText('Gregorian Date: ', [$date->gregorian->date])
            ->addFactsText('Islamic Date: ', [$date->hijri->date])
            ->addFactsText('Islamic Month: ', [$date->hijri->month->ar])
            ->addFactsText('Islamic Day: ', [$date->hijri->weekday->ar])
            ->addFactsText('Holidays: ', [count($date->hijri->holidays) > 0 ? implode(", ",$date->hijri->holidays) : 'No upcoming holidays'])
            ->addFacts('Timings: ', ['Fajr' => $timings->Fajr , 'Sunrise' => $timings->Sunrise, 'Dhuhr' => $timings->Dhuhr, 'Asr' => $timings->Asr, 'Sunset' => $timings->Sunset, 'Maghrib' => $timings->Maghrib, 'Isha' => $timings->Isha, 'Imsak' => $timings->Imsak, 'Midnight' => $timings->Midnight]);

        app('TeamsConnector')->send($card);
    }
}