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
        // get OLTs
        $olts = Http::get(env('API_URL') . '/get_olts');
        $data = json_decode($olts[0]);
        $arr = $data->response;
        Cache::put('olts', $arr);

        $olt_ids = array();
        $onus = array();

        // get ONUS unconfigured
        $client = new Client();
        $request = new Request('GET', env('API_URL') . '/unconfigured_onus');
        $res = $client->sendAsync($request)->wait();
        $res = json_decode($res->getBody(), true);
        $res = json_decode($res[0]);
        $res = $res->response;
        Cache::put('onusUnconfigured', $res);

        if ($arr) {
            Cache::put('onus', $onus);


            foreach ($arr as $id_olt) {
                array_push($olt_ids, $id_olt->id);
            }


            for ($i = 0; $i < 10; $i++) {

                $client = new Client();
                $request = new Request('GET', env('API_URL3') . '/get_all_onus_details/' . $olt_ids[$i], ['Accept' => 'application/json']);
                $res = $client->sendAsync($request)->wait();
                $res = json_decode($res->getBody(), true);
                $res = json_decode($res[0]);
                $res = $res->onus;
                $onus = array_merge($onus, $res);
                Cache::set('onus', $onus);
            }
        }

        return Command::SUCCESS;
    }
}
