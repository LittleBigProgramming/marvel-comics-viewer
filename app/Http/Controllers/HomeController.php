<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $client;

    public function __construct()
    {
        $timestamp = time();
        $hash = md5($timestamp . config('marvel.private_key') . config('marvel.public_key'));

        $this->client = new Client([
            'base_uri' => 'http://gateway.marvel.com/v1/public/',
            'query' => [
                'apikey' => config('marvel.public_key'),
                'ts' => $timestamp,
                'hash' => $hash
            ]
        ]);

    }

    public function comics(Request $request)
    {
        $searchEntry = '';

        if ($request->has('query')) {
            $searchEntry = $request->input('query');

            $query = $this->client->getConfig('query');
            $query['titleStartsWith'] = $searchEntry;

            $response = $this->client->get('comics', ['query' => $query]);
            $response = json_decode($response->getBody(), true);

            $comics = $response['data']['results'];

        } else{
            $comics = Cache::get('comics');
            shuffle($comics);
            $comics = array_slice($comics, 0, 20);
        }

        return view('comics', ['comics' => $comics, 'query' => $searchEntry]);

    }

}
