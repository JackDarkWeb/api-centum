<?php


class Product extends Model
{

    protected $join_table    = 'categories';
    protected $join_table_id = 'category_id';
    protected $pivot_table   = 'Category_products';

    //object properties

    public $id,
           $name,
           $description,
           $price,
           $category_id,
           $category_name,
           $created_at;
}