<?php

namespace App\Console\Commands;

use App\Models\EthernetPort;
use App\Models\Onu;
use App\Models\ServicePort;
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

        /* $onus = Cache::get('onus');


        foreach ($onus as $onu) {
            $data = Onu::create([
                'unique_external_id' => $onu->unique_external_id,
                'pon_type_id' => 1,
                'sn' => $onu->sn,
                'olt_id' => intval($onu->olt_id),
                'board' => $onu->board,
                'port' => $onu->port,
                'onu_type_id' => 11,
                'zone_id' => intval($onu->zone_id),
                'name' => $onu->name,
                'address' => $onu->address,
                'mode' => $onu->mode,
                'wan_mode' => $onu->wan_mode,
                'ip_address' => $onu->ip_address,
                'subnet_mask' => $onu->subnet_mask,
                'default_gateway' => $onu->default_gateway,
                'dns1' => $onu->dns1,
                'dns2' => $onu->dns2,
                'username' => $onu->username,
                'password' => $onu->password,
                'catv' => $onu->catv,
                'administrative_status' => $onu->administrative_status,
                'authorization_date' => $onu->authorization_date,
                'status' => $onu->status,
                'signal' => $onu->signal,
                'signal_1310' => $onu->signal_1310,
                'latitude' => $onu->latitude,
                'longitude' => $onu->longitude
            ]);

            if (count($onu->service_ports) > 0) {

                foreach ($onu->service_ports as $service) {
                    ServicePort::create([
                        'onu_id' => $data->id,
                        'service_port' => $service->service_port,
                        'vlan_id' => $service->vlan,
                        'svlan_id' => $service->svlan,
                        'cvlan_id' => $service->cvlan,
                        'tag_mode' => $service->tag_transform_mode,
                        'download_speed_id' => 120,
                        'up_speed_id' => 121,
                    ]);
                }
            }

            if (count($onu->ethernet_ports) > 0) {

                foreach ($onu->ethernet_ports as $ethernet_port) {
                    EthernetPort::create([
                        'onu_id' => $data->id,
                        'port' => $ethernet_port->port,
                        'admin_state' => $ethernet_port->admin_state,
                        'mode' => $ethernet_port->mode,
                        'dhcp' => $ethernet_port->dhcp,
                    ]);
                }
            }
        } */


        /* $olt_ids = array();

        for ($i = 76; $i < count($olts) ;$i++) {
            array_push($olt_ids, $i);
        }

        $olt_cards = array();
        foreach ($olt_ids as $key => $olt_id) {
            $request = Http::get(env('API_URL2') . '/get_olt_cards_details/' . $olt_id);
            $res = json_decode($request[0]);
            $res = $res->response;
            foreach($res as $key => $r){
                $res[$key]->olt_id = $olt_id;
            }
            $backup = Cache::get('backup');
            array_push($backup, $res);
            $olt_cards = array_merge($olt_cards, $res);
            Cache::set('olt_cards', $olt_cards);
            sleep(0.3);
        } */





        /* // get Zones
        $zones = Http::get(env('API_URL') . '/get_zones');
        $zones = json_decode($zones[0]);
        $zones = $zones->response;
        Cache::put('zones', $zones);

        // get ODBS
        $id_zones = array();
        foreach ($zones as $zone) {
            array_push($id_zones, $zone->id);
        }
        $odbs = array();
        Cache::put('odbs', $odbs);
        for ($i = 0; $i < count($id_zones); $i++) {
            $request = Http::get(env('API_URL2') . '/get_odbs/' . $id_zones[$i]);
            $res = json_decode($request[0]);
            $res = $res->response;
            $odbs = array_merge($odbs, $res);
            Cache::set('odbs', $res);
        }

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
                sleep(0.2);
            }
        } */

        $this->info("Proceso culminado con exito!");

        return Command::SUCCESS;
    }
}
