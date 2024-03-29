<?php


class Product extends Model
{

    protected $join_table    = 'categories';
    protected $join_table_id = 'category_id';
    protected $pivot_table   = 'category_products';

    //object properties

    public $id,
           $product_name,
           $description,
           $price,
           $category_name,
           $created_at;
}