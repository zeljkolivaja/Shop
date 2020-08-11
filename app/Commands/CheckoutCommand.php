<?php

namespace App\Commands;

use App\Product;
use App\ShoppingCart;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class CheckoutCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'CHECKOUT';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Gets you the bill.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $shoppingCart = new ShoppingCart;
        $products = new Product;

        /*get all the data from the shopping cart table and join it with the
        products table*/

        $bill = $shoppingCart->checkout();

        //set the total price variable needed to sum the products at the end
        $totalPrice = 0;

        /*loop through all the products user added to the shopping cart,
        calculate and display product prices,
        remove the bought products from the products table */

        foreach ($bill as $index => $object) {

            $productPrice = $object->cartQuantity * $object->price;
            $this->info($object->name . " " . $object->cartQuantity . " x " . $object->price . " = " . $productPrice);
            $totalPrice += $productPrice;
            $products->updateQuantity($object->quantity, $object->cartQuantity, $object->sku);

        }

        //display the total bill price
        $this->info("TOTAL = " . $totalPrice);

        //delete everything from the shopping cart
        DB::table("shopping_cart")->truncate();

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