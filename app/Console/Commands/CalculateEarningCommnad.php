<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Seller;
use App\Models\SellerCommission;
use App\Models\SellerEarning;
use App\Traits\EraningsTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateEarningCommnad extends Command
{
    use EraningsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:earning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate earning for seller for every month this command working every day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $carbon = new Carbon();
        $year = $carbon->year;

//        $carbon->setMonth(1);
//        $carbon->setDay(1);
        $month = $carbon->month;
        $carbon_last_day = $carbon->copy();
        $carbon_last_day->subDay();
        $year_last_day = $carbon_last_day->year;
        $month_last_day = $carbon_last_day->month;
        if ($year == $year_last_day && $month == $month_last_day) {
            $this->calculate_eranings($year, $month);
        } else {
            $this->calculate_eranings($year, $month);
            $this->calculate_eranings($year_last_day, $month_last_day);
        }
        $carbon = new Carbon();
        $carbon->subMonth();
        $this->calculate_eranings($carbon->year, $carbon->month);


    }

}
