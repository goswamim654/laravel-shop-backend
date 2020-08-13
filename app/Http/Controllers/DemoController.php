<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class DemoController extends Controller
{
    public function demo(Request $request) {
        return view('Demo.demo1');
    }

    public function getData() {
        $client = new Client();
        $response = $client->get('http://httpbin.org/get');
        return $response->getBody();
    }

    public function getPostData() {
        $client = new Client();
        $response = $client->post('http://httpbin.org/post',[
            'form_params' => [
                'fruit' => 'apple'
            ]
        ]);
        return $response->getBody();
    }
}
