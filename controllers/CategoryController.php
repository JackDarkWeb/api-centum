<?php


class CategoryController extends Controller
{

    function index(){

        $category    = new Category();
        $categories  = $category->all();
        $categories  = json_encode($categories, true);

        header('Content-Type: application/json');
        echo ($categories);
        die();
    }

    function create(){

        $post = json_decode(file_get_contents('php://input'), true);

        $category_name  = $post['category_name'];
        $created_at     = date('Y-m-d H:i:s');
        $updated_at     = date('Y-m-d H:i:s');

        $category  = new Category();

        $insert_category = $category->insert(['category_name' => $category_name, 'created_at' => $created_at, 'updated_at' => $updated_at]);

        $result = [];
        if($insert_category){

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



    function show($name){

        $category = new Category();

        if(is_numeric($name)){
            $categories = $category->findById($name);
        }else{
            $categories = $category->findByName(['category_name', '=', $name]);
        }

        $categories = json_encode($categories, true);

        header('Content-Type: application/json');
        echo $categories;
        die();
    }

    function update($id){

        $post = json_decode(file_get_contents('php://input'), true);
        $post['updated_at']  = date('Y-m-d H:i:s');

        $category =  new Category();
        $update  = $category->update(['id', $id], $post);

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

        $category = new Category();
        $delete  = $category->delete($id);

        //dd($delete);
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