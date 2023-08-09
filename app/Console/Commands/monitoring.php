<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class monitoring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitoring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $olts = Http::get(env('API_URL') . '/get_olts');
        $data = json_decode($olts[0]);
        $arr = $data->response;

        $olt_ids = [];
        $onus = [];

        if ($arr) {
            $fechadevencimiento = \Carbon\Carbon::now()->addMinutes(1);
            Cache::put('onus', $onus, $fechadevencimiento);


            foreach ($arr as $id_olt) {
                array_push($olt_ids, $id_olt->id);
            }


            for ($i = 0; $i < 10; $i++) {
                $client = new Client();
                $request = new Request('GET', env('API_URL3') . '/get_all_onus_details/'. $olt_ids[$i], ['Accept' => 'application/json']);
                $res = $client->sendAsync($request)->wait();
                $res = json_decode($res->getBody(), true);
                $res = json_decode($res[0]);
                $res = $res->onus;
                array_push($onus, $res);
                Cache::set('onus', $onus);
            }
        }

        return Command::SUCCESS;
    }
}
