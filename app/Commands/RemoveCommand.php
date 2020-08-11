<?php

namespace App\Commands;

use App\ShoppingCart;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class RemoveCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'REMOVE {sku} {quantity}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Removes the product from the shopping cart.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $shopingCart = new ShoppingCart;

        $sku = $this->argument('sku');
        $quantity = $this->argument('quantity');

        $checkSKU = $shopingCart->select($sku);

        //if checkSKU fails it means there is no such product saved in inventory
        if (!$checkSKU) {
            $this->info("Product does not exist in shopping cart.");
            exit;
        }

        //if checkSKU is larger then 0 it means we have that product in shopping cart
        if ($checkSKU->quantity > 0) {

            //calculating the new quantity of the product, to be stored in shopping cart
            $newQuantity = $checkSKU->quantity - $quantity;

            if ($newQuantity <= 0) {

                /* if the newQuantity is smaller or equeal to 0 it means we need to delete the product
                from the shopping cart  */
                $shopingCart->delete($sku);

            } else {

                //if the newQuantity is larger then 0 it means we just need to update quantity
                $shopingCart->update($sku, $newQuantity);
            }

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