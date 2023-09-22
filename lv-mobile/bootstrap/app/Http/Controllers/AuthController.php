<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Session;



class AuthController extends Controller
{


    public function login()
    {
        return view('layouts.login');
    }
    public function postlogin(Request $request)
    {
        $client = new Client(['base_uri' => 'https://mobile.bcasekuritas.co.id/json/']);
        $body = ['tr' => '000102','loginId' => $request->loginid,'loginPw' => $request->passwd,'pw2' => '12345678901234567890'];
        $response = $client->request('POST', 'trproc.php', ['form_params' => $body]);
        $getRes = json_decode($response->getBody()->getContents(), true);
        print_r($getRes['out']);
        if ($getRes['out'][0]['status'] == 1) {
            Session::put('isLogin', 1);
            Session::put('loginId', $request->loginid);
            Session::put('userId', $getRes['out'][0]['userId']);
            return redirect('/');
        }else{
            return redirect('/login')->with('status', $getRes['out'][0]['mesg']);
        }

    }
    public function logout()
    {
        session()->flush();
        return redirect('/login');
    }
}
