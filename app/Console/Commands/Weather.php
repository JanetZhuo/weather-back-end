<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Symfony\Component\Console\Helper\Table;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:forecast {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for provide a table of Australian cities weather forecast. 
    Please type in cities and seperate by comma. eg. php artisan weather:forecast canberra,melbourne';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $city = $this->argument('city');
        $cityArr = explode(',',$city);

        $request = new Request();
        $header = [];
        $tableData = [];

        foreach ($cityArr as $k=>$v){
            $request->offsetSet('city',$v);

            //valiate if it's a valid city and if it's a city of Australia
            $validateDataRes = (new ApiController())->currentWeather($request);
            $validate = $validateDataRes->getData(true);
            if($validate['status'] !== 1){
                $this->line("<fg=red;bg=yellow>{$v}:{$validate['message']}</>");
                continue;
            }
            if($validate['data']['country'] != 'AU') {
                $this->line("<fg=red;bg=yellow>{$v} does not belong to the AU</>");
                continue;
            }

            $res = (new ApiController())->weather($request);
            $data = $res->getData(true);
            if($data['status'] !== 1){
                $this->line("<fg=red;bg=yellow>{$v}:{$data['message']}</>");
                continue;
            }
            $weatherData = $data['data'];

            //draw the table header for date
            if(empty($header)){
                $header[] = '';
                foreach ($weatherData['list'] as $vv){
                    $date = $vv[0]['dt_txt'];
                    $header[] = Carbon::parse($date)->isoFormat('ddd MMM D');
                }
            }

            //draw temprature for each city
            $tempData = [];
            $tempData[] = $v;
            foreach ($weatherData['min_max'] as $vv){
                $tempData[] = floor($vv['min']).'/'.floor($vv['max']).' °C';
            }

            $tableData[] = $tempData;
        }

        $table = new Table($this->output);
        $table->setHeaders($header);
        $table->setRows($tableData);
        $table->render();

        return;
    }
}
