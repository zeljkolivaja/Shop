<?php

namespace App;

use Illuminate\Support\Facades\DB;

class ShoppingCart
{

    public function insert($array)
    {
        list($sku, $quantity) = $array;
        $checkSKU = DB::table("shopping_cart")->where("sku", $sku)->get();

        if ($checkSKU->count() > 0) {

            $newQuantity = $checkSKU[0]->quantity + $quantity;

            DB::table('shopping_cart')->where("sku", $sku)->update(
                ['quantity' => $newQuantity]
            );

            return true;

        } else {

            DB::table('shopping_cart')->insert(
                ['sku' => $sku, 'quantity' => $quantity]
            );
            return true;
        }
    }

    public function checkout()
    {
        $racun = DB::table('products')
            ->join('shopping_cart', 'products.sku', '=', 'shopping_cart.sku')
            ->select('products.*', 'shopping_cart.quantity as cartQuantity')
            ->get();

        return $racun;
    }

    public function select($sku)
    {
        return DB::table("shopping_cart")->where("sku", $sku)->first();
    }

    public function delete($sku)
    {
        return DB::table('shopping_cart')->where("sku", $sku)->delete();
    }

    public function update($sku, $quantity)
    {
        return DB::table('shopping_cart')->where("sku", $sku)->update(
            ['quantity' => $quantity]
        );
    }
}