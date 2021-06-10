<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function weather(Request $request)
    {
        $city = $request->get('city');
        $state = $request->get('state');
        $country = $request->get('country');
        $appid = config('weather.appid');
        if (!$city) {
            return failed('city is empty');
        }

        $q = "{$city}";

        if ($state) {
            $q .= ",{$state}";
        }

        if ($country) {
            $q .= ",{$country}";
        }

        $url = "api.openweathermap.org/data/2.5/forecast?q={$q}&units=metric&appid={$appid}";

        $data = curlGet($url);
        if ($data['cod'] != 200) {
            return failed($data['message']);
        }

        $returnData = [];
        foreach ($data['list'] as $k=>$v) {
            $date = explode(' ', $v['dt_txt']);
            $returnData[$date[0]][] = $v;
        }


        $data['list'] = [];

        foreach ($returnData as $v) {
            foreach ($v as $k1=>$v1) {
                $v[$k1] = [
                    'dt_txt' => $v1['dt_txt'],
                    'temp' => $v1['main']['temp'],
                    'humidity' => $v1['main']['humidity'],
                    'icon' => $v1['weather'][0]['icon'],
                    'description' => $v1['weather'][0]['description']
                ];
            }
            $data['list'][] = $v;
        }

        $data['min_max'] = [];

        foreach ($data['list'] as $k=>$v) {
            $max = 0;
            $min = 100;
            foreach ($v as $k1 => $v1) {
                $temp = $v1['temp'];
                if ($min > $temp) {
                    $min = $temp;
                }
                if ($max < $temp) {
                    $max = $temp;
                }
            }
            $data['min_max'][] = ['max' => $max, 'min' => $min];
        }

        unset($data['city']);

        return succeed('success', $data);
    }

    public function currentWeather(Request $request)
    {
        $city = $request->get('city', 'sydney');
        $state = $request->get('state');
        $country = $request->get('country');
        $appid = config('weather.appid');
        if (!$city) {
            return failed('city is empty');
        }

        $q = "{$city}";

        if ($state) {
            $q .= ",{$state}";
        }

        if ($country) {
            $q .= ",{$country}";
        }

        $url = "api.openweathermap.org/data/2.5/weather?q={$q}&units=metric&appid={$appid}";

        $data = curlGet($url);

        if ($data['cod'] == 200) {
            $data = [
                'name' => $data['name'],
                'country' => $data['sys']['country'],
                'temp' => $data['main']['temp'],
                'description' => $data['weather'][0]['description'],
                'icon' => $data['weather'][0]['icon']
            ];
            return succeed('success', $data);
        } else {
            return failed($data['message']);
        }
    }
}
