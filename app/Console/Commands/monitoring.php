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
        $olts = json_decode($olts[0]);
        $olts = $olts->response;
        Cache::put('olts', $olts);

        // get Zones
        $zones = Http::get(env('API_URL') . '/get_zones');
        $zones = json_decode($zones[0]);
        $zones = $zones->response;
        Cache::put('zones', $zones);

        // get speed profiles
        $speed_profiles = Http::get(env('API_URL') . '/get_speed_profiles');
        $speed_profiles = json_decode($speed_profiles[0]);
        $speed_profiles = $speed_profiles->response;
        Cache::put('speed_profiles', $speed_profiles);

        // get onu_types
        $onu_types = Http::get(env('API_URL') . '/get_onu_types');
        $onu_types = json_decode($onu_types[0]);
        $onu_types = $onu_types->response;
        Cache::put('onu_types', $onu_types);

        // get ONUS unconfigured
        $client = new Client();
        $request = new Request('GET', env('API_URL') . '/unconfigured_onus');
        $res = $client->sendAsync($request)->wait();
        $res = json_decode($res->getBody(), true);
        $res = json_decode($res[0]);
        $res = $res->response;
        Cache::put('onusUnconfigured', $res);

        // get ONUS List
        $onus = array();

        if ($olts) {
            Cache::put('onus', $onus);
            $olt_ids = array();

            foreach ($olts as $id_olt) {
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
