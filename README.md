# api-centum

=> UML.mwb


   This file contains the uml schema of the three tables.
   
   
   * Insertions of both categories
   
   
       INSERT INTO `categories`(`category_name`, `created_at`, `updated_at`) VALUES (`mobiles`,`2019-11-24 7:10:25`,`2019-11-24 7:10:25`),
                                                                                    (`tablets`,`2019-11-24 7:10:25`,`2019-11-24 7:10:25`)
   
=> core/setting/Config


    The Config class is used to configure the database. For example
    
    
    class Config
    {
        static $debug = 1;

        static $databases = [
            'dev' => [
                'host' => '127.0.0.1',
                'dbname' => 'api_centum',
                'user' => 'root',
                'password' => ''
            ],

            'production' => [
                'host' => '',
                'dbname' => '',
                'user' => '',
                'password' => ''
            ]
        ];
    }
    
=> controllers
    This folder contains all the controllers and each controller contains the possible actions of the http requests.
    For example ProductController
   


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


=> models


   This folder contains all category and product entities.
   
   
   For example
 

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

=> Implement the PHP REST API
   
   *For category
   
   // return all records
   
   
    GET /category

  // return a specific record
  
  
   GET /category/show/{id}

  // create a new record
  
  
   POST /category/create
   
   
    {
      "product_name": "Mobiles",
    }


  // update an existing record
  
  
   PUT /category/update/{id}
   
   

  // delete an existing record
  
  
  DELETE /category/delete/{id}
  
  
  *For product
  
    // return all records
    
    
    GET /product

  // return a specific record
  
  
   GET /product/show/{id}

  // create a new record
  
  
   POST /product/create
   
   
    {
     
      "category_id": "2",
      "product_name": "Apple iPhone",
      "description": "Communication standards: GSM, 3G, 4G (LTE) Number of SIM cards: 1 SIM SIM card format: Nano-SIM, e-SIM Communication standards: FDD-LTE (ranges 1, 2, 3, 4, 5, 7, 8, 11, 12, 13, 17, 18, 19, 20, 21, 25, 26, 28, 29, 30, 32, 66) TD ‑ LTE (ranges 34, 38, 39, 40, 41, 42, 46 , 48) UMTS / HSPA + / DC ‑ HSDPA (850, 900, 1700/2100, 1900, 2100 MHz) GSM / EDGE (850, 900, 1800, 1900 MHz)",
      "price": "68000"
   }

  // update an existing record
  
  
   PUT /product/update/{id}
   

  // delete an existing record
  
  
  DELETE /product/delete/{id}








