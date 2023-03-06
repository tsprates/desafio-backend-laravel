<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class EnderecosController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cep = preg_replace('/[^0-9]/', '', $request->input('cep'));
        if (Cache::has($cep)) {
            return Cache::get($cep);
        }
        $url = sprintf('viacep.com.br/ws/%s/json/', $cep);
        $json = Http::get($url)->json();
        Cache::set($cep, $json);
        return $json;
    }
}
