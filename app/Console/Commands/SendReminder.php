<?php

namespace App\Console\Commands;

use App\Mail\ReminderEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        dispatch(new \App\Jobs\SendReminder());
        return 0;
    }
}
