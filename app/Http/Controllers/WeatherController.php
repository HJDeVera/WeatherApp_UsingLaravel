<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather');
    }

    public function getWeather(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $apiKey = 'OPENWEATHER_API_KEY'; 
        $city = $request->input('city');
        $url = "http://api.openweathermap.org/data/2.5/weather?q=$city&appid=$apiKey&units=metric";

        $client = new Client();
        $response = $client->request('GET', $url);

        $data = json_decode($response->getBody(), true);

        if ($response->getStatusCode() == 200) {
            $weather = [
                'city' => $data['name'],
                'temperature' => $data['main']['temp'],
                'description' => $data['weather'][0]['description'],
                'icon' => $data['weather'][0]['icon'],
            ];
            return view('weather')->with('weather', $weather);
        } else {
            return redirect('/')->with('error', 'Failed to fetch weather data. Please try again later.');
        }
    }
}
