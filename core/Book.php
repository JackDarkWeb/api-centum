<?php


class Book
{
    private $alpha, $num,
        $nbr, $ch;

    protected  $date = [];
    protected $msg = "";



    /**
     * @param $len
     * @param $min
     * @param $max
     * @return mixed
     */
    function alphaCode($len, $min, $max)
    {
        for ($i = 0; $i < $len; $i++) {
            $this->nbr = rand($min, $max);
            $this->alpha .= chr($this->nbr);
        }
        return $this->alpha;
    }

    /**
     * @param $len
     * @param $min
     * @param $max
     * @return mixed
     */
    function numCode($len, $min, $max)
    {
        for ($i = 0; $i < $len; $i++) {
            $this->nbr = rand($min, $max);
            $this->num .= $this->nbr;
        }
        return $this->num;
    }

    /**
     * @param $len
     * @return mixed
     */
    function alphaNumCode($len){

        for($i = 0; $i < $len; $i++){
            $this->nbr = rand(0, 9);
            $this->ch .= $this->nbr;
            $this->ch .= chr(rand(65, 90));
        }
        return $this->ch;
    }

    /**
     * Get the length of a character string
     * get the count of a array and double array
     * @ return string
     */
    function  getLengthOrCount(){

        $n = func_num_args();
        $args = func_get_args();

        if($n == 1){

            foreach($args as $arg){

                if(gettype($arg) == "array"){

                    $count = 0;
                    foreach($arg as $val){

                        if(gettype($val) == "array"){

                            $count += sizeof($val);
                        }else{

                            $count++;
                        }
                    }

                    echo $count;

                }else{

                    echo strlen($arg);
                }
            }
        }else{

            echo "Please enter the string or the array as argument";
        }
    }


    /**
     * @param $name
     * @param mixed ...$extensions
     * @return array
     */
    function uploadFile($name, ...$extensions):array
    {
        $msg = [];
        if (isset($name) && count($extensions) != 0) {

            $ext = [];
            foreach($extensions as $val){
                $ext[] = $val;
            }

            $file = $_FILES[$name];
            if (isset($file) && strlen($file["name"]) > 0) {

                $file_name = $file["name"];
                $detach = explode(".",$file_name);
                $extension = strtolower(end($detach));
                if(in_array($extension, $ext)){

                    $size_file = $file["size"];
                    if($size_file <= 3200000){

                        $new_name = $this->alphaNumCode(3).'.'.$extension;
                        $tmp_name = $file["tmp_name"];

                        // The storage location of the files
                        $storage  = "storage/".$new_name;
                        if(move_uploaded_file($tmp_name, $storage) == TRUE){

                            $msg[] .= "true";//"The file has been downloaded successfully";
                            $msg[] .= $new_name;

                        }else{

                            $msg[] .= "There was a problem uploading";
                        }

                    }else{

                        $msg[] .= "The file exceeds 32 Mo";
                    }
                }else{

                    $msg[] .= "The file is not allowed";
                }

            } else {

                $msg[] .= "Select your file";
            }
        }else{

            $msg[] .= "Enter the arguments(file name and extensions) of the function";
        }

        return $msg;
    }


    /**
     * @param int $nday
     * @return object
     */
    function dateFr(int $nday = 0){

        $timestamp = time() + $nday*24*3600;

        $weeks  = ["Dimanche", "Lundi" ,"Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
        $months  = [1 => "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

        $date = ["date" => $weeks[date("w", $timestamp)].' '.date("j", $timestamp).' '.$months[date("n", $timestamp)].' '.date("Y", $timestamp),
            "hour" => date("H:i:s", $timestamp)
        ];
        return (object)$date;
    }

    /**
     * @param mixed ...$names
     */
    function deleteFiles(...$names){
        $msg = [];
        if(sizeof($names) !== 0){
            foreach ($names as $name){

                if(file_exists($name)){
                    if(unlink($name)){
                        $msg[] .= "Le fichier $name a été bien supprimé";
                    }else{
                        $msg[] .= "Impossible de supprimer, veuillez fermer $name et réessayer";
                    }
                }else{

                    $msg[] .= "Le file $name do not exist";
                }
            }
        }else{

            $msg[] .= "Veuillez sélectionner un fichier";
        }
        foreach ($msg as $item){
            echo $item,"<br/>";
        }
    }

    /**
     * @param string $var
     * @param string|null $list
     * @return string
     */
    public function cleaningString(string $var, string $list = null){

        return ($list == null)? htmlspecialchars(trim($var), ENT_QUOTES, 'UTF-8', false) : htmlspecialchars(trim($var, $list), ENT_QUOTES, 'UTF-8', false);
    }


}