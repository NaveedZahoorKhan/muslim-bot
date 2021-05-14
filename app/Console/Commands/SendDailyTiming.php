<?php

namespace App\Console\Commands;

use App\Models\Calendar;
use App\Models\HadithUrdu;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Sebbmyr\LaravelTeams\Cards\CustomCard;

class SendDailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:notify';

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
        $hadith = HadithUrdu::inRandomOrder()->first();
        $card = new CustomCard("Today's Hadith", $hadith->title);
        $card->addColor('800080')
            ->addFactsText($hadith->hadith_text);
        app('TeamsConnector')->send($card);
    }
}