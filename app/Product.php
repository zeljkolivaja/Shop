<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Product
{
    public function select($array)
    {
        list($sku) = $array;
        $checkSKU = DB::table("products")->where("sku", $sku)->first();

        if (!$checkSKU) {
            return false;
        }

        return true;
    }

    public function insert($array)
    {
        list($sku, $name, $quantity, $price) = $array;
        DB::table('products')->insert(
            ['sku' => $sku, 'name' => $name, 'price' => $price, 'quantity' => $quantity]
        );
    }

    public function findProduct($sku)
    {
        return DB::table("products")->where("sku", $sku)->first();
    }

    public function checkInventory($array)
    {
        list($sku, $quantity) = $array;

        $product = $this->findProduct($sku);
        $productStock = $product->quantity;

        if ($quantity > $productStock) {
            return false;
        }

        return true;
    }

    public function updateQuantity($quantity, $cartQuantity, $sku)
    {
        $newQuantity = $quantity - $cartQuantity;
        DB::table('products')->where("sku", $sku)->update(
            ['quantity' => $newQuantity]
        );
    }
}