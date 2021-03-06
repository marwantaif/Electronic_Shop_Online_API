<?php


namespace App\Repositories\Product;


interface ProductRepositoryInterface
{
    public function getProducts();
    public function getProductByCategory($id);
    public function showProductById($id);
    public function getSaleProduct();
    public function getNewProduct();
    public function getBestSellProduct();
}
