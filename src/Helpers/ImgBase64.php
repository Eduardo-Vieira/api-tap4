<?php

namespace App\Helpers;

class ImgBase64
{
  public static function saveBase64Image($base64_image_string, $output_file_without_extentnion, $path_with_end_slash="" )
 {
      $splited = explode(',', substr( $base64_image_string , 5 ) , 2);
      $mime=$splited[0];
      $data=$splited[1];
      $mime_split_without_base64=explode(';', $mime,2);
      $mime_split=explode('/', $mime_split_without_base64[0],2);
      if(count($mime_split)==2)
      {
          $extension=$mime_split[1];
          if($extension=='jpeg')$extension='jpg';
          $output_file_with_extentnion.=$output_file_without_extentnion.'.'.$extension;
      }
      file_put_contents($path_with_end_slash . $output_file_with_extentnion, base64_decode($data));
      return $output_file_with_extentnion;
  }
}