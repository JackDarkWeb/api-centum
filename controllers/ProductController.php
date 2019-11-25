<?php


class ProductController extends Controller
{
    function index(){

        $product   = new Product();
        $products  = $product->all();
        $products = json_encode($products, true);
        header('Content-Type: application/json');
        echo ($products);
        die();
    }

    function create(){

        $post = json_decode(file_get_contents('php://input'), true);
        //dd($post);

        $product_name         = $post['product_name'];
        $description          = $post['description'];
        $price                = (int)$post['price'];
        $category_id          = (int)$post['category_id'];
        $created_at           = date('Y-m-d H:i:s');
        $updated_at           = date('Y-m-d H:i:s');


        $product  = new Product();
        $category_product = new Category_product();

        $insert_product = $product->insert(['product_name' => $product_name, 'description' => $description,
                                            'price' => $price, 'created_at' => $created_at, 'updated_at' => $updated_at
        ]);



        $insert_product_category = $category_product->insert(['product_id' => $product->lastId(), 'category_id' => $category_id]);


        $result = [];
        if($insert_product && $insert_product_category){

            $result[] = [
                'error' => false,
                'success' => true,
            ];
        }else{
            $result[] = [
                'error' => true,
                'success' => false,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        die();
    }

    function show($id){

        $product = new Product();

        $product = $product->findById($id);
        //dd($product);
        $product = json_encode($product, true);

        header('Content-Type: application/json');
        echo $product;
        die();
    }

    function update($id){

        $post = json_decode(file_get_contents('php://input'), true);
        $post['updated_at']  = date('Y-m-d H:i:s');

        $product =  new Product();

        $update  = $product->update(['id', $id], $post);

        $result = [];
        if($update){

            $result[] = [
                'error' => false,
                'success' => true,
            ];
        }else{
            $result[] = [
                'error' => true,
                'success' => false,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        die();
    }

    function delete($id){

        $product = new Product();

        $delete  = $product->delete($id);
        
        $result = [];
        if($delete){

            $result[] = [
                'error' => false,
                'success' => true,
            ];
        }else{
            $result[] = [
                'error' => true,
                'success' => false,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        die();
    }
}