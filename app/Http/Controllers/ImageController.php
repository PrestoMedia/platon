<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use App\Image;
use App\User;
use DB;


class ImageController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

   public function index(  $image , Request $request ) {
        
       $data = Image::where([['id', '=', $image]])
                ->orderBy('id', 'desc')
                ->with('User')
                ->get();
        
       $data[0]['image'] =  \URL::to('/') . "/files/" . $data[0]['id'] .'.jpg' ;
       
        return $data[0] ; 
    }    
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(  $image ) {
        
        if ( $image != 0){
        $data = Image::where([['id', '=', $image]])
                ->orderBy('id', 'desc')
                ->with('User')
                ->get();
            return view( 'edit', array( 'source' => $data[0]['source'] , 'user' => $data[0]['user'] , 'provider' => $data[0]['provider'] , 'file' => $data[0]['id'] , 'name' => $data[0]['name']  ));     
        }else{
            return view( 'edit', array( 'source'=> '' ,'user'=> '' , 'provider'=> '' ,  'file' => 0 , 'name' =>  '' )); 
        }
    }

    public function save( Request $request) {
       
        $new_record = Image::create($request->except(['id','mode','action'])); 
          
        $client = new Client();
        $client->get(
        $request->input('url') ,
        [
            'save_to' => public_path() . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $new_record->id . '.jpg',
        ]);

        return array('id' => $new_record->id); 
    }
 
    
 public function update( Request $request) {

      if ( $request->input('data') == 'data:,' ){
           die();
      }   
      $data = explode(',', $request->input('data') );
      $ext = "";
      switch ($data[0]) {
          case "data:image/png;base64";
              $ext = "png";
              break;
          case "data:image/jpg;base64";
              $ext = "jpg";
              break;
          case "data:image/jpeg;base64";
              $ext = "jpg";
              break;
          case "data:image/gif;base64";
              $ext = "gif";
              break;
      }

          
      if ( $request->input('id') == 0 ){
           $new_record = Image::create(
                 array( 
                    'name'   =>   '' ,
                    'provider'   =>   'local' ,
                    'source'   =>   ''
              ) 
         );
          $id = $new_record->id  ; 
      }else{
          $id = $request->input('id') ;  
      }
       
      $file = public_path() . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $id . '.jpg' ;     
      $ifp = fopen( $file , "wb"); 
          
          
      
      fwrite($ifp, base64_decode($data[1])); 
      fclose($ifp); 
      switch ($data[0]) {
          case "data:image/png;base64";
             $image = imagecreatefrompng($file);
              break;
          case "data:image/jpg;base64";
              $image = imagecreatefromgif($file);
              break;
          case "data:image/jpeg;base64";
              $image = imagecreatefromgif($file);
              break;
          case "data:image/gif;base64";
              $image = imagecreatefromgif($file);
              break;
      }
   
       imagejpeg($image, $file, $request->input('compress') );
 

       return array('id' =>$id  ); 
    }
 
    
}
