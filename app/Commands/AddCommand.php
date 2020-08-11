<?php

namespace App\Commands;

use App\Product;
use App\ShoppingCart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class AddCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ADD {data*}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add the product to the inventory or the shopping cart.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $product = new Product;
        $shoppingCart = new ShoppingCart;

        //get arguments from user
        $array = $this->argument('data');

        //check the stage of the app
        $stage = DB::table("stages")->first();

        /*check the number of parameters user sent and stage of the app,
        if the stage is not set and number of parameters is 4 create new product,
        if the stage==2 and number of parameters user sent is 2 then insert/update the
        shoping cart, else display error*/

        if (!$stage) {

            //adding products stage
            //validating user input
            $validate = $this->validateStageOne($array);

            if ($validate !== true) {
                $this->info($validate);
                exit;
            }
            //insert the product to the products table
            $product->insert($array);

        } elseif ($stage) {

            //shopping cart stage
            $validate = $this->validateStageTwo($array);

            if ($validate !== true) {
                $this->info($validate);
                exit;
            }

            //insert the products to the shopping cart
            $shoppingCart->insert($array);

        } else {
            $this->info("Wrong number of parameters entered.");
        }
    }

    //data validation, adding products stage
    public function validateStageOne($array)
    {
        list($sku) = $array;

        $product = new Product;

        //check if the product already exists, if it does return error
        if ($product->findProduct($sku)) {
            return "Product already exists, please enter another one.";
        }

        if (count($array) !== 4) {
            return "Wrong numbers of parameters, expecting 4 parameters.";
        }

        return true;
    }

    //data validation, shopping cart stage
    public function validateStageTwo($array)
    {

        $product = new Product;

        if (count($array) !== 2) {
            return "Wrong number of parameters, expecting 2 parameters.";
        }

        if ($product->select($array) == false) {
            return "The product you are trying to add does not exist.";
        }

        if ($product->checkInventory($array) == false) {
            return "Not enough product in stock.";
            exit;
        }

        return true;
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