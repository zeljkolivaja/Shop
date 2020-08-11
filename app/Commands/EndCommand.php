<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class EndCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'END';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Controlls the stage of the app.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /* this command controls the stage of the app,
        we get the stage of the app from the stages table,
        if the stage is not set and this command is called we set the stage to 2,
        if the command is called again we truncate both stages and shopping cart table
        and end the app */

        $stage = DB::table("stages")->first();

        if (!$stage) {
            DB::table('stages')->insert(
                ['stage' => 2]
            );
            $this->info("Shopping cart stage.");

        } elseif ($stage->stage == 2) {

            /* if the END line is called when stage==2 then it means we are ending the program,
            we reset both stages and shopping_cart table and exit the script */
            DB::table("stages")->truncate();
            DB::table("shopping_cart")->truncate();

            $this->info("See you soon :) . Application is now back at the stage 1 (input of products).");
            exit();

        } else {
            $this->info("error");
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}