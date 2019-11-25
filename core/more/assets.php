<?php

/**
 * @param $file
 * @return bool|string
 */
function assets($file){

    $file = str_replace('.', DS, $file);
    $detach = explode(DS, $file);



    if(sizeof($detach) >= 2){

        $folder   = $detach[0];
        $tmp_file     = $detach[1];
        $folders  = scandir(PUBLIC_FOLDER);


        if(in_array($folder, $folders)){


            $files        = scandir($folder);



            $new_file    = [];
            $extension_file    = [];

            foreach ($files as $value){

                if(strlen($value) > 3){

                    $extension_file[] = end(explode('.', $value));
                }
            }

            $extension_file = array_unique($extension_file);


            foreach ($extension_file as $ext){

                if(in_array($tmp_file.'.'.$ext, $files)){

                    $file =  $tmp_file.'.'.$ext;
                }
            }



           if(in_array($file, $files))
               return DS.'public'.DS.$folder.DS.$file;
           else
               return DS.'public'.DS.$file.'.'.$folder;

        }else
            return DS.'public'.DS.$tmp_file.'.css';
    }
    return false;
}
