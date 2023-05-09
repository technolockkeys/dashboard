<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateCurrecnyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for update currency by api ';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $curl = curl_init();

        $currencies_data = Currency::query()->where('status', 1)->get();
        $currencies = [];
        foreach ($currencies_data as $key => $item) {
            $currencies[] = strtoupper($item->code);
        }
        $currencies = implode(',', $currencies);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.apilayer.com/currency_data/live?source=USD&currencies=" . $currencies,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain",
                "apikey: " . get_setting('api_currency_key')
            ),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response, true);
        foreach ($currencies_data as $item) {
            if (strtoupper($item->code) != 'USD') {
                $currency_code = 'USD' . strtoupper($item->code);
                if (!empty($data['quotes'])) {
                    if (!empty($data['quotes'][$currency_code]))
                        Currency::query()->where('code', $item->code)->update(['value' => $data['quotes'][$currency_code]]);
                } else {
                    Log::info("please change currency key or upgrade");
                }
            }

        }

    }
}
