<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use App\Search;
use App\User;
use DB;

class SearchController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
//        $this->middleware('auth');
    }

    public function filter($query, $filter, $page) {

        $data = Search::where([['id', '=', $query]])
                ->orderBy('id', 'desc')
                ->with('User')
                ->get();
        $temp = explode(",", $filter);
        return view('result', array('type' => 'filter', 'providers' => count($temp), 'filter' => $filter, 'text' => $data[0]['query'], 'query' => $query, 'page' => $page));
    }

    public function result($query, $page) {

        $data = Search::where([['id', '=', $query]])
                ->orderBy('id', 'desc')
                ->with('User')
                ->get();

        return view('result', array('type' => 'result', 'providers' =>  $data[0]['provider'] , 'filter' => '', 'text' => $data[0]['query'], 'query' => $query, 'page' => $page));
    }

    public function query(Request $request) {
 
        $new_record = Search::create($request->except(['id','mode']));
        Session::put('Search', Session::getId());
        Session::put('Query', $new_record->id);

        $Status = array('in' => 'Start', 'percent' => 1);
        $this->StateFile($Status);
        $unsplash = array();
        if ($request->input('provider') == 'flickr' || $request->input('provider') == 'All providers') {
            $flickr = $this->flickr($request->input('query'), 1);
        } else {
            $flickr = array();
        }
        if ($request->input('provider') == 'wikimedia' || $request->input('provider') == 'All providers') {
            $wikimedia = $this->wikimedia($request->input('query'));
        } else {
            $wikimedia = array();
        }
        if ($request->input('provider') == 'pixabay' || $request->input('provider') == 'All providers') {
            $pixabay = $this->pixabay($request->input('query'), 1);
        } else {
            $pixabay = array();
        }
        if ($request->input('provider') == 'unsplash' || $request->input('provider') == 'All providers') {
            for ($i = 1; $i <= 6; $i++) {
                $temp = $this->unsplash($request->input('query'), $i);
                $unsplash = array_merge($unsplash, $temp);
            }
        } else {
            $unsplash = array();
        }

        if ($request->input('provider') == 'giphy' || $request->input('provider') == 'All providers') {
            $giphy = $this->giphy($request->input('query') );
        } else {
            $giphy = array();
        }
        
        
        $Status = array('in' => 'Complated', 'percent' => 100);
        $this->StateFile($Status);

        $data = array_merge($flickr, $wikimedia, $unsplash, $pixabay , $giphy );
        if (  $request->input('provider') == 'All providers') {
            array_multisort(array_column($data, "name"), SORT_DESC, $data);
        }
        
        
        DB::table('searches')
        ->where('id', $new_record->id)  // find your user by their email
        ->limit(1)  // optional - to ensure only one record is updated.
        ->update(array('count' => count($data)));  // update the record in the DB. 
        
        
   
        
        
        $this->ResultFile($data);
        if ( $request->input('provider') == 'ajax' ){
            return array('id' => $new_record->id);
        }else{
            return response()->redirectTo( $request->path() . '/../result/'. $new_record->id .'/1'  ) ;
        }
    }

    public function find(Request $request) {

        $data = Search::where([['query', 'like', "%{$request->input('query')}%"], ['provider', '=', "{$request->input('provider')}"]])
                ->whereRaw('id IN (select MAX(id) FROM searches GROUP BY query)')
               // ->select('*', DB::raw('count(id) as total') )
                ->orderBy('id', 'DESC')
                ->with('User')
                ->get();
        if (count($data) == 0) {
            $data = array();
        }
        //$data[] = array('user' => array('name' => 'Anonymous', 'id' => 0 ), 'count' => 0 , 'total' => 1  , 'created_at' => date('d.m.Y H:i:s'), 'id' => 0, 'provider' => $request->input('provider'), 'query' => 'New search repository [ ' . $request->input('query') . ' ]');

        return $data;
    }

    public function state() {
        $data = file_get_contents(public_path() . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . Session::get('Search') . '.json', 'w');
        return $data;
    }

    private function flickr($text, $page = 1) {
        $flickr = array('key' => '4a74df77259615998070798d6641de7d', 'Secret' => '6964c580017227c2');
        $url = "https://www.flickr.com/services/rest/?method=flickr.photos.search&api_key={$flickr['key']}&text={$text}&format=json&page={$page}&per_page=500";

        $client = new Client();
        $response = $client->get($url);

        $result = $response->getBody()->getContents();
        $result = str_replace('jsonFlickrApi(', '', $result);
        $result = rtrim($result, ")");
        $result = json_decode($result, true);
        $Return = array();
        if (isset($result['photos'])) {
            foreach ($result['photos']['photo'] as $key => $value) {
                $url = "http://farm" . $value['farm'] . ".staticflickr.com/" . $value['server'] . "/" . $value['id'] . "_" . $value['secret'] . ".jpg";
                
                $src = "https://www.flickr.com/photos/{$value['owner']}/{$value['id']}";   

                // $ImageData = $this->remoteImage($url);
                $ImageData = array('h' => 0, 'w' => 0);
                array_push($Return, array('id' => $key + 1, 'provider' => 'flickr', 'src' => $src  ,  'user' => '' ,  'height' => $ImageData['h'], 'width' => $ImageData['w'], 'name' => $value['title'], 'url' => $url, 'thumbnail' => $url));

                $Status = array('in' => 'flickr', 'percent' => number_format(((($key + 1) / 2500) * 100), 0));
                $this->StateFile($Status);
            }
        }
        return $Return;
    }

    private function wikimedia($text) {

        $url = "https://commons.wikimedia.org/w/api.php?action=query&titles={$text}&generator=images&prop=imageinfo&gimlimit=500&iiprop=url&format=json&redirects";

        $client = new Client();
        $response = $client->get($url);

        $result = $response->getBody()->getContents();

        $result = json_decode($result, true);

        $Return = array();
        $i = 0;
        if (isset($result['query'])) {
            foreach ($result['query']['pages'] as $key => $value) {
                $i++;
                // $ImageData = $this->remoteImage($value['imageinfo'][0]['url']);
                $ImageData = array('h' => 0, 'w' => 0);
                array_push($Return, array('id' => $i + 501, 'src' => $value['imageinfo'][0]['descriptionshorturl']  ,  'provider' => 'wikimedia', 'user' => '' ,  'height' => $ImageData['h'], 'width' => $ImageData['w'], 'name' => str_replace('File:', '', $value['title']), 'url' => $value['imageinfo'][0]['url'], 'thumbnail' => $value['imageinfo'][0]['url']));
                $Status = array('in' => 'wikimedia', 'percent' => number_format(((($i + 501) / 2500) * 100), 0));

                $this->StateFile($Status);
            }
        }
        return $Return;
    }

    private function pixabay($text, $page = 1) {
        $pixabay = array('key' => '9016838-8542c144d0682ad5e14df3622');

        $url = "https://pixabay.com/api/?key={$pixabay['key']}&q={$text}&per_page=200&page={$page}&lang=en";

        $client = new Client();
        $response = $client->get($url);

        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        $Return = array();
        foreach ($result['hits'] as $key => $value) {
            $tags = explode(' ', $value['tags']);
            $tags = array_unique($tags);
            array_push($Return, array('id' => $key + 1001,  'src' => $value['pageURL'] , 'provider' => 'pixabay', 'user' => $value['user'] ,  'height' => $value['imageHeight'], 'width' => $value['imageWidth'], 'name' => implode(" ", $tags), 'url' => $value['largeImageURL'], 'thumbnail' => $value['webformatURL']));
            $Status = array('in' => 'pixabay', 'percent' => number_format(((($key + 1001) / 2500) * 100), 0));
            $this->StateFile($Status);
        }
        return $Return;
    }

    
    
    
    
   private function giphy($text) {
        $giphy = array('key' => 'HbAAl9nvLZ2T3AdRWr9wrF93S2enx01f');

        $url = "https://api.giphy.com/v1/gifs/search?api_key={$giphy['key']}&q={$text}&limit=200&offset=0&rating=G&lang=en";

        $client = new Client();
        $response = $client->get($url);

        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        $Return = array();
        foreach ($result['data'] as $key => $value) {
            array_push($Return, array('id' => $key + 2001 ,  'src' => $value['url'] , 'provider' => 'giphy', 'user' => $value['username'] ,  'height' => $value['images']['original']['height'], 'width' => $value['images']['original']['width'] , 'name' =>  $value['title'] , 'url' => $value['images']['original']['url'] , 'thumbnail' => $value['images']['fixed_height_still']['url']));
            $Status = array('in' => 'giphy', 'percent' => number_format(((($key + 2001) / 2500) * 100), 0));
            $this->StateFile($Status);
        }
        return $Return;
    }

    
    
    private function unsplash($text, $page = 1) {

        $url = "https://unsplash.com/napi/search/photos?query={$text}&xp=&per_page=30&page={$page}";

        $client = new Client();
        $response = $client->get($url);

        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        $Return = array();
        if ($result['total'] != 0) {
            foreach ($result['results'] as $key => $value) {
                array_push($Return, array('id' => $key + 1501, 'src' => $value['links']['html'] , 'user' => $value['user']['name'],   'provider' => 'unsplash', 'height' => $value['height'], 'width' => $value['width'], 'name' => $value['description'], 'url' => $value['urls']['full'], 'thumbnail' => $value['urls']['small']));
                $Status = array( 'in' => 'unsplash', 'percent' => number_format(((($key + 1501 + ( $page * 30 ) ) / 2500) * 100), 0));
                $this->StateFile($Status);
            }
        }
        return $Return;
    }

    private function StateFile($array) {
        $fp = fopen(public_path() . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . Session::get('Search') . '.json', 'w');
        array_push($array, array('file' => Session::get('Search'), 'query' => Session::get('Query')));
        fwrite($fp, json_encode($array));
        fclose($fp);
    }

    private function ResultFile($array) {
        $fp = fopen(public_path() . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . Session::get('Search') . '.result.json', 'w');
        fwrite($fp, json_encode($array));
        fclose($fp);

        $fp = fopen(public_path() . DIRECTORY_SEPARATOR . 'json' . DIRECTORY_SEPARATOR . Session::get('Query') . '.repository.json', 'w');
        fwrite($fp, json_encode($array));
        fclose($fp);
    }

    private function remoteImage($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_RANGE, "0-10240");
        $fn = public_path() . DIRECTORY_SEPARATOR . 'partial.jpg';
        $raw = curl_exec($ch);
        $result = array();
        if (file_exists($fn)) {
            unlink($fn);
        }
        if ($raw !== false) {
            $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($status == 200 || $status == 206) {
                $result["w"] = 0;
                $result["h"] = 0;
                $fp = fopen($fn, 'x');
                fwrite($fp, $raw);
                fclose($fp);
                $size = getImageSize($fn);
                if ($size === false) {
                    
                } else {
                    list($result["w"], $result["h"]) = $size;
                }
            }
        }

        curl_close($ch);
        return $result;
    }

}
