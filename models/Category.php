<?php


class Category extends Model
{
    protected $table            = 'categories',
              $table_id         = 'category_id',
              $join_table       = 'products',
              $join_table_id    = 'product_id',
              $pivot_table      = 'Category_products';
}