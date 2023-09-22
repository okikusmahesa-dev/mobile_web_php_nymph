<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OrderbookController extends Controller
{
    private $time, $client;
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://mobile.bcasekuritas.co.id/json/']);
    }
    public function index()
    {
        return view('layouts.quotes.curPrice');
    }
    public function tr100000(Request $request)
    {
        $body = ['tr' => '100000','code' => $request->code,'board' => $request->board];
        $response = $this->client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        // print_r($getRes);
        return $getRes;
    }
    public function tr100001(Request $request)
    {
        $body = ['tr' => '100001','code' => $request->code,'board' => $request->board];
        $response = $this->client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        // print_r($getRes);
        return $getRes;
    }
}
