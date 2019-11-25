<?php


use http\Cookie;

class Validator extends Book
{
    use CalculateAge;

    protected $errors =  array();
    public $flash = array();
    public $months  = [1 => "Janvier", "Février",  "Mars",  "Avril", "Mai", "Juin",  "Juillet", "Août",  "Septembre", "Octobre", "Novembre", "Décembre"];
    public $categories = ['tablets' => 'tablets', 'mobiles' => 'mobiles'];
    /**
     * @param string $email
     * @return int
     */
    public function preg_email(string $email){

        $pattern = "/(^[a-z0-9]+)@([a-z])+(\.)([a-z]{2,3})/";
        if(preg_match($pattern, $email) == 1){

            $msg = 1;
        }else{

            $msg = 0;
        }
        return $msg;
    }

    /**
     * @param string $string
     * @return int
     */
    function preg_string(string $string){

        $pattern = "/^-?[a-zA-Zàçéèêëíìîïñóòôöõúùûüýÿæ -\ ]+$/";
        if(preg_match($pattern, $string) == 1){

            $msg = 1;
        }else{
            $msg = 0;
        }

        return $msg;
    }

    /**
     * @param string $string
     * @return int
     */
    function preg_string_general(string $string){

        $pattern = "/^-?[a-zA-Zàçéèêëíìîïñóòôöõúùûüýÿæ0-9 -\ ]+$/";
        if(preg_match($pattern, $string) == 1){

            $msg = 1;
        }else{
            $msg = 0;
        }

        return $msg;
    }

    /**
     * @param string $num
     * @return int
     */
    function preg_number(string $num){

        $pattern = "/^(\+)[0-9]{11,12}/";
        if(preg_match($pattern, $num) == 1){

            $msg = 1;
        }else{

            $msg = 0;
        }

        return $msg;
    }
    function preg_int(string $num){
        $pattern = "/^[0-9]+$/";
        if (preg_match($pattern, $num)){
            $msg = 1;
        }else{
            $msg = 0;
        }
        return $msg;
    }

    function preg_decimal(string $num){
        $pattern = "/^[0-9 .]+$/";
        if (preg_match($pattern, $num)){
            $msg = 1;
        }else{
            $msg = 0;
        }
        return $msg;
    }

    /**
     * @param string $name
     * @return string
     */
    public function post(string $name){

        if(isset($_POST[$name])) return $this->cleaningString($_POST[$name]);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function files(string $name){

        if(isset($_FILES[$name])) return $_FILES[$name];
    }

    /**
     * @param $name
     * @return string
     */
    function integer($name){
        $value  = $this->post($name);

        if($this->preg_int($value) === 1){
            return $value;
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }

    /**
     * @param $name
     * @return string
     */
    function decimal($name){
        $value  = $this->post($name);

        if($this->preg_decimal($value) === 1){
            return $value;
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }
    /**
     * @param string $name
     * @return string
     */
    public function phone(string $name){

        $value = $this->post($name);

        if(!empty($value)){

            if($this->preg_number($value) == 1){

                return $value;
            }else{

                $this->errors[$name] = "Your number is incorrect.<br/> Ex +380633471236";
            }
        }else{

            $this->errors[$name] = "Field $name is required";
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function text(string $name){

        $value = $this->post($name);

        $detach = explode('_', $name);
        $string = implode(' ', $detach);

        if(!empty($value)){

            if($this->preg_string_general($value) == 1){

                return $value;
            }else{

                $this->errors[$name] = "Your $string contains characters  not allowed";
            }
        }else{

            $this->errors[$name] = "Field $string is required";
        }
    }

    public function select_category(string $name){

        $value = $this->post($name);

        $detach = explode('_', $name);
        $string = implode(' ', $detach);

        if(!empty($value)){

            if(array_key_exists($value, $this->categories) == true){

                if($this->preg_string_general($value) == 1){

                    return $value;
                }else{

                    $this->errors[$name] = "Your $string contains characters  not allowed";
                }
            }else{

                $this->errors[$name] = "The value of the field must not be changed";
            }
        }else{
            $this->errors[$name] = "Field $string is required";
        }
    }
    /**
     * @param string $name
     * @return string
     */
    public function select_day(string $name){
        $value = $this->post($name);

        if($this->preg_int($value) === 1){
            return $value;
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function select_month(string $name){

        $value = $this->post($name);

        if(array_key_exists($value, $this->months) == true){

            if($this->preg_int($value) === 1){
                return $value;
            }else{
                $this->errors[$name] = "Field $name is required";
            }
        }else{
            $this->errors[$name] = "The value of the field must not be changed";
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function select_year(string $name){
        $value = $this->post($name);

        $pattern  = "/^(19|20)[0-9]{2}/";

        if($value !== ''){

            if(preg_match($pattern, $value) === 1){

                if($value < 2012){

                    return $value;
                }else{

                    $this->errors[$name] = "Field $name is incorrect";
                }
            }else{

                $this->errors[$name] = "Field $name is incorrect";
            }
        }else{
            $this->errors[$name] = "Field $name is required";
        }

    }

    /**
     * @param int $month
     * @param int $day
     * @param int $year
     * @return bool
     */
    public function check_date($month, $day, $year){
        if(!checkdate($month, $day, $year))
            $this->errors['date'] = "The date format is incorrect";
        else
            return true;
    }

    /**
     * @param string $name
     * @return string
     */
    public function emplacement(string $name){
        $value = $this->post($name);

        $pattern = "/^[a-zA-Z ,]+$/";

        if($value !== ''){

            if(preg_match($pattern, $value) === 1){

                $location = explode(',', $value);
                $city = ucfirst($location[0]);
                $country = strtoupper(end($location));

                //return [$city, $country];
                return  $value;

            }else{
                $this->errors[$name] = "Field $name is incorrect";
            }
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function radio($name){
        $value = $this->post($name);
        $option = ['male' => 'Male', 'female' => 'Female'];

        if($value !== null){

            if(array_key_exists($value, $option) == true){

                return $value;
            }else{
                $this->errors[$name] = "The value of the field must not be changed";
            }
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }


    /**
     * @param $name
     * @return string
     */
    public function email_or_phone($name){
        $value = $this->post($name);
        $pattern = "/^[0-9 +]+$/";

        $detach = explode('_', $name);
        $string = implode(' ', $detach);

        if($value !== ''){

            if(preg_match($pattern, $value)){
                return $this->phone($name);
            }else{
                return $this->email($name);
            }
        }else{
            $this->errors[$name] = "Field $string is required";
        }
    }

    /**
     * @param $name
     * @return string
     */
    public function password($name){
        $value = $this->post($name);
        if($value !== ''){
            if(strlen($value) >= 5){

                return sha1($value);
            }else{
                $this->errors[$name] = "Password must be at least 5 characters long";
            }
        }else{
            $this->errors[$name] = "Field $name is required";
        }
    }

    /**
     * @param $name
     * @param $confirm_name
     * @return bool
     */
    function password_confirm($name, $confirm_name){

        $value = $this->post($confirm_name);
        $confirm = sha1($value);

        if($confirm === $this->password($name)){
            return true;
        }else
            $this->errors[$confirm_name] = "Passwords are not same";
    }

    /**
     * @param $name
     * @return bool
     */
    public function remember($name){

        $value = $this->post($name);

        if($value == 1){
            return true;
        }
        return false;
    }


    /**
     * @param string $name
     * @return string
     */
    public function upload_file(string $name){

        $file = $this->files($name);

        $extensions = ['png', 'jpeg', 'jpg', 'gif'];

        $file_name = $file["name"];
        $file_error = $file['error'];

        if($file_error === 0  && strlen($file_name) > 0){

            $detach = explode('.', $file_name);
            $extension = strtolower(end($detach));

            if(in_array($extension, $extensions)){
              
              if($file_error == 0){
                
              
                $file_size = $file['size'];
                if($file_size <= 3200000){

                    $new_name_db = strtolower($this->alphaNumCode(3));
                    $new_name = $new_name_db.'.'.$extension;


                    $tmp_name = $file['tmp_name'];

                    //create the storage location of the files
                    $stone = PUBLIC_FOLDER.DS."storage";
                    if(!is_dir($stone)){
                        mkdir($stone);
                    }
                    $storage = $stone.DS.$new_name;

                    if(move_uploaded_file($tmp_name, $storage)){

                        return $new_name_db;

                        //return "The file has been downloaded successfully";
                    }else{

                        $this->errors[$name] = "There was a problem uploading";
                    }
                }else{

                    $this->errors[$name] = "The file exceeds 32 Mo";
                }
                
             }else{
               
               $this->errors[$name] = "Errored with code $file_error";
             }

            }else{
                $this->errors[$name] = "The file is not allowed";
            }

        }else{
            $this->errors[$name] = "Select your file";
        }

    }

    function upload_multiple_files(string $name){

        $files = $this->files($name);



        if(!empty($files['name'][0])){

            $file_names  = $files['name'];

            $extensions      = ['png', 'jpeg', 'jpg', 'gif', 'pdf', 'doc', 'txt'];
            $file_extensions = [];

            foreach ($file_names as $file_name){


                $file_extension_tmp    = explode('.', $file_name);
                $file_extensions[]     = strtolower(end($file_extension_tmp));
            }

            $file_sizes   = $files['size'];
            $sizes = [];

            foreach ($file_sizes as $file_size){
                $sizes[] = $file_size;
            }

            $file_errors = $files['error'];
            $f_errors    = [];

            foreach ($file_errors as $file_error){
                $f_errors[] = $file_error;
            }

            $tmp_files    = $files['tmp_name'];
            $tmp_fs       = [];
            $new_file_names = [];

            foreach ($tmp_files as $tmp_file){

                $tmp_fs[] = $tmp_file;

                $new_file_names[]    = strtolower($this->alphaNumCode(3));
            }

            //return $tmp_fs;

            // $sizes;
            $storage = [];
            foreach ($file_names as $position => $file_name){

                if(in_array($file_extensions[$position], $extensions)){

                    if($f_errors[$position] === 0){

                        //$re[] = $f_errors[$position];

                        if($sizes[$position] <= 3200000){

                            //create the storage location of the files
                            $stone = PUBLIC_FOLDER.DS."storage";
                            if(!is_dir($stone)){
                                mkdir($stone);
                            }

                            $storage[] = $stone.DS.$new_file_names[$position].'.'.$file_extensions[$position];



                            if(move_uploaded_file($tmp_fs[$position], $storage[$position])){

                                return $new_file_names;

                                //return "The file has been downloaded successfully";
                            }else{

                                if(!empty($file_name))
                                    $this->errors[$name.$position] = "<strong>$file_name</strong>  was a problem uploading";
                            }


                        }else{

                            if(!empty($file_name))
                                $this->errors[$name.$position] = "<strong>$file_name</strong>   exceeds <span style='color: red'>32 Mo</span>";
                        }

                    }else{

                        if(!empty($file_name))
                            $this->errors[$name.$position] = "<strong>$file_name</strong>  errored with code  <span style='color: red'>$file_error</span> ";
                    }


                }else{

                    if(!empty($file_name))
                        $this->errors[$name.$position] = "<strong>$file_name</strong>  file extension  <span style='color: red'>$file_extensions[$position]</span> is not allowed";
                }
            }



            /*die();

            foreach ($file_names as $position => $file_name){

                $file_error  = $files['error'][$position];
                $file_tmp    = $files['tmp_name'][$position];
                $file_size   = $files['size'][$position];



                if(in_array($file_extension, $extensions)){

                    if($file_error === 0){
                        $t = [];
                        if($file_size <= 3200000){

                            $new_file_name    = strtolower($this->alphaNumCode(3).'.'.$file_extension);
                            $new_file_name_db = strtolower($this->alphaNumCode(3));

                            return $t[] = $new_file_name;
                            //create the storage location of the files
                            $stone = PUBLIC_FOLDER.DS."storage";
                            if(!is_dir($stone)){
                                mkdir($stone);
                            }

                            $storage = $stone.DS.$new_file_name;

                            if(move_uploaded_file($file_tmp, $storage)){

                                return $new_file_name_db;

                                //return "The file has been downloaded successfully";
                            }else{

                                if(!empty($file_name))
                                $this->errors[$name.$position] = "<strong>$file_name</strong>  was a problem uploading";
                            }

                        }else{

                            if(!empty($file_name))
                                $this->errors[$name.$position] = "<strong>$file_name</strong>   exceeds <span style='color: red'>32 Mo</span>";
                        }

                    }else{

                        if(!empty($file_name))
                            $this->errors[$name.$position] = "<strong>$file_name</strong>  errored with code  <span style='color: red'>$file_error</span> ";
                    }

                }else{

                    if(!empty($file_name))
                     $this->errors[$name.$position] = "<strong>$file_name</strong>  file extension  <span style='color: red'>$file_extension</span> is not allowed";
                }

            }*/

        }else
            $this->errors[$name] = 'Select your file';
    }

    /**
     * @param string $name
     * @return string
     */
    public function email(string $name){

        $value = $this->post($name);

        if(!empty($value)){

            if($this->preg_email($value) == 1){

                return $value;
            }else{

                $this->errors[$name] = "Your email is incorrect";
            }
        }else{
            $this->errors[$name] = "Field $name is required";
        }

    }


    /**
     * @param string $name
     * @return mixed
     */
    public function error(string $name){

        if(isset($this->errors[$name]) && !empty($this->errors[$name])) return $this->errors[$name];
    }

    /**
     * @return array
     */
    public function all(){

        if(!empty($this->errors)) return $this->errors;
    }


    /**
     * @param string $name
     * @return mixed
     */
    public function message_flash(string $name){

        if(isset($this->flash[$name])) return $this->flash[$name];
    }

    /**
     * @return string
     */
    public function success(){
        if(empty($this->errors)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @return string
     */
    public function flash(){
        if(empty($this->flash)){
            return true;
        }else{
            return false;
        }

    }


    /**
     * @param $name
     * @param $args
     */
    public function __call($name, $args){

        echo $name," doesn't exist in this class";
    }
}
