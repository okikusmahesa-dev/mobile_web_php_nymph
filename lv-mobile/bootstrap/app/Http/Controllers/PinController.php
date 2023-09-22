<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Session;

class PinController extends Controller
{

    private $time, $client;
    public function __construct()
    {
        $this->time = Carbon::now();
        $this->client = new Client(['base_uri' => 'https://mobile.bcasekuritas.co.id/json/']);
    }
    public function index()
    {
        return view('layouts.settings.inPin');
        // if($this->tr800000() == 1 && $this->tr800001()){
        //     $checkRet = '1';
        //     return view('layouts.settings.inPin' , compact('checkRet'));
        // }
    }

    public function tr800000()
    {
        $body = ['tr' => '800000','userID' => Session::get('userId'),'clientID' => Session::get('loginId'),'date' => $this->time->isoFormat('YYYYMMDD')];
        $response = $this->client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        // print_r($getRes);
        return $getRes;
    }
    public function tr800001()
    {
        $body = ['tr' => '800001','clientID' => Session::get('userId'),'date' => $this->time->isoFormat('YYYYMMDD')];
        $response = $this->client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        // print_r($getRes);
        return $getRes;
    }
    public function checkpin(Request $request)
    {
        $body = ['tr' => '000203','id' => Session::get('userId'),'pin' => $request->pin,'clientId' => Session::get('loginId')];
        $response = $this->client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        if ($getRes['out'][0]['status'] == 1) {
            Session::put('pinState', 1);
            Session::put('pin', $request->pin);
            return redirect('/');
        }else{
            return redirect('/inPin')->with('status', $getRes['out'][0]['mesg']);
        }
    }
}
