<?php

namespace App\Http\Controllers;

use App\Charts\CovidChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CovidController extends Controller
{
    public function chart()
    {
        $suspects = collect(Http::get('https://api.kawalcorona.com/indonesia/provinsi')->json());
        $suspectsData = $suspects->flatten(1);

        $labels = $suspectsData->pluck('Provinsi');
        $data = $suspectsData->pluck('Kasus_Posi');
        $deaths = $suspectsData->pluck('Kasus_Meni');
        $colors = $labels->map(function($item) {
            return '#' .substr(md5(mt_rand()), 0, 6);
        });

        $chart = new CovidChart;
        $chart->labels($labels);
        $chart->dataset('Data Kasus Positif di Indonesia', 'bar', $data)->backgroundColor($colors);

        $chart2 = new CovidChart;
        $chart2->labels($labels);
        $chart2->dataset('Data Kasus Meninggal di Indonesia', 'bar', $deaths)->backgroundColor($colors);

        return view('corona', compact('chart', 'chart2'));


    }
}
