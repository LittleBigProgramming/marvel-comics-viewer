<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $client;

    /**
     * HomeController constructor.
     */
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

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

        } else {
            $comics = Cache::get('comics');
            shuffle($comics);
            $comics = array_slice($comics, 0, 20);
        }

        return view('comics', ['comics' => $comics, 'query' => $searchEntry]);

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function comic($id)
    {
        $pageData = [];

        $response = $this->client->get('comics/' . $id);
        $response = json_decode($response->getBody(), true);

        $comic = $response['data']['results'][0];
        $pageData['comic'] = $comic;

        if(!empty($comic['series'])){
            $series_response = $this->client->get($comic['series']['resourceURI']);
            $series_response = json_decode($series_response->getBody(), true);

            $pageData['series'] = $series_response['data']['results'][0];
        }

        return view('comic', $pageData);
    }

    public function characters(Request $request)
    {
        $characters = Cache::get('characters');

        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if (is_null($currentPage)) {
            $currentPage = 1;
        }

        $characters_collection = new Collection($characters);

        $items_per_page = 8;

        $current_page_results = $characters_collection->slice(($currentPage - 1) * $items_per_page, $items_per_page)->all();
        $paginated_results = new LengthAwarePaginator($current_page_results, count($characters_collection), $items_per_page);

        return view('characters', ['paginated_results' => $paginated_results, 'characters' => $characters]);

    }
}
