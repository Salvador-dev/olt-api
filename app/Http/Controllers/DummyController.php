<?php

namespace App\Http\Controllers;

use App\Imports\OnusImport;
use Illuminate\Support\Facades\Cache;
use App\Models\EthernetPort;
use App\Models\Dummy;
use App\Models\ServicePort;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class DummyController extends Controller
{
    //
    public function getData(Request $request)
    {

        
      
        $search = $request->input("search") ?? null;
        $status = $request->input("status") ?? null;
        $oltName = $request->input("oltName") ?? null;


        $data = Dummy::join('olts', 'dummy.olt_id', 'olts.id')
            ->join('zones', 'dummy.zone_id', 'zones.id')
            ->join('onu_types', 'dummy.onu_type_id', 'onu_types.id')
            ->select(
                'dummy.id',
                'dummy.name',
                'dummy.unique_external_id',
                'dummy.status',
                'dummy.sn',
                'dummy.olt_id',
                'olts.name as olt_name',
                'dummy.zone_id',
                'zones.name as zone_name',
                'onu_types.name as onu_type',
            );


        $data = $data->orderBy('id', 'DESC')
        ->search($search)
        ->status($status);

        if($oltName){
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        $data = $data->get();


        return response()->json(['data' => $data], 200);
    }

    public function index()
    {
        $data = Dummy::join('olts', 'dummy.olt_id', 'olts.id')
            ->join('zones', 'dummy.zone_id', 'zones.id')
            ->join('onu_types', 'dummy.onu_type_id', 'onu_types.id')
            ->select(
                'dummy.id',
                'dummy.name',
                'dummy.unique_external_id',
                'dummy.status',
                'dummy.sn',
                'dummy.olt_id',
                'olts.name as olt_name',
                'dummy.zone_id',
                'zones.name as zone_name',
                'onu_types.name as onu_type',
            )
            ->distinct()
            ->get();
        return response()->json(['data' => $data], 200);
    }

    public function onusUnconfigureds()
    {
        // Intenta obtener datos de la caché
        $cachedData = Cache::get('onus_unconfigured_data');
    
        if ($cachedData) {
            // Si los datos están en caché, devuélvelos
            return response()->json(['data' => $cachedData], 200);
        }
    
        try {
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/onu/get_no_configurados');
            $res = $client->sendAsync($request)->wait();
            $data = json_decode($res->getBody());
    
            $index = 1;
    
            $response = array_map(function ($item) use (&$index) {
                return [
                    'tipo_puerto' => $item->tipo_puerto ?? null,
                    'slot' => $item->slot ?? null,
                    'puerto' => $item->puerto ?? null,
                    'onu_id' => $item->onu_id ?? null,
                    'numero_serial' => $item->numero_serial ?? null,
                    'tipo_onu_id' => $item->tipo_onu_id ?? null,
                    'tipo_onu_nombre' => $item->tipo_onu_nombre ?? null,
                    'olt_id' => $item->olt_id ?? null,
                ];
            }, $data);
    
            Cache::put('onus_unconfigured_data', $response, 3600);
    
            return response()->json(['data' => $response], 200);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
    
    
    
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // puertos por defecto para cada ONU
        $ports = [
            [
                "port" => "eth_0/1",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/2",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/3",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ],
            [
                "port" => "eth_0/4",
                "status" => true,
                "mode" => "LAN",
                "dhcp" => true
            ]
        ];

        $json = json_encode($ports);

        $data = Dummy::create([
            'autoincrement' => $request['autoincrement'],
            'unique_external_id' => $request['onu_external'],
            'pon_type_id' => $request['pon_type'],
            'sn' => $request['sn'],
            'onu_type_id' => $request['onu_type_id'],
            'name' => $request['name'],
            'olt_id' => $request['olt_id'],
            'board' => $request['board'],
            'port' => $request['port'],
            'allocated_onu' => $request['allocated_onu'],
            'zone_id' => $request['zone'],
            'address' => $request['address'],
            'lat' => $request['lat'],
            'lng' => $request['lng'],
            'odb_name' => $request['odb'],
            'mode' => $request['mode'],
            'wam_mode' => $request['wam_mode'],
            'ip_address' => $request['ip_address'],
            'subnet_mask' => $request['subnet_mask'],
            'default_gateway' => $request['default_gateway'],
            'dns1' => $request['dns1'],
            'dns2' => $request['dns2'],
            'username' => $request['username'],
            'password' => $request['password'],
            'catv' => $request['catv'],
            'administrative_status' => $request['administrative_status'],
            'auth_date' => $request['auth_date'],
            'status' => $request['status'],
            'signal' => $request['signal'],
            'signal_1310' => $request['signal_1310'],
            'signal_1490' => $request['signal_1490'],
            'distance' => $request['distance'],
            'service_port' => $request['service_port'],
            'service_port_vlan' => $request['service_port_vlan'],
            'service_port_cvlan' => $request['service_port_cvlan'],
            'service_port_svlan' => $request['service_port_svlan'],
            'service_port_tag_transform_mode' => $request['service_port_tag_transform_mode'],
            'speed_download_id' => $request['speed_download_id'],
        ]);
        $data->save();

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        try {

            $onu = Dummy::where('dummy.id', $id)
                 ->join('olts', 'dummy.olt_id', 'olts.id')
                 ->join('pon_types', 'dummy.pon_type_id', 'pon_types.id')
                 ->join('onu_types', 'dummy.onu_type_id', 'onu_types.id')
                 ->leftJoin('service_ports', 'service_ports.onu_id', 'dummy.id')
                 ->join('zones', 'dummy.zone_id', 'zones.id')
                 ->select(
                     'dummy.id',
                     'dummy.name as name', 
                     'dummy.unique_external_id as onu_external',
                     'dummy.status',
                     'dummy.sn',
                     'dummy.signal',
                     'dummy.signal_1310',
                     'dummy.catv',
                     'dummy.authorization_date',
                     'dummy.olt_id',
                     'dummy.zone_id as zone',
                     'dummy.board',
                     'dummy.odb_name as odb',
                     'dummy.port',
                     'dummy.address',
                     'dummy.mode',
                     'service_ports.vlan_id as vlan',
                     'olts.name as olt_name',
                     'pon_types.name as pon_type',
                     'onu_types.name as onu_type_id',
                     'zones.name as zone_name',
                 )
                ->first();

                if ($onu) {
                    $ethernet_ports = EthernetPort::where('onu_id', $onu->id)->get();
                    $service_ports = ServicePort::join('speed_profiles', 'service_ports.download_speed_id', 'speed_profiles.id')
                        ->leftJoin('speed_profiles as up_speed', 'service_ports.up_speed_id', 'up_speed.id')
                        ->where('service_ports.onu_id', $onu->id)
                        ->select(
                            'service_ports.id as service_port',
                            'speed_profiles.name as download_speed',
                            'up_speed.name as upload_speed',
                            'service_ports.vlan_id as vlan',
                            'service_ports.cvlan_id as cvlan',
                            'service_ports.svlan_id as svlan',
                            'service_ports.tag_mode'
                        )
                        ->get();

                    $onu['ethernet_ports'] = $ethernet_ports;
                    $onu['service_ports'] = $service_ports;
                }
        } catch (Exception $e) {

            return response()->json(array('error' => $e), 200);
        }
        return response()->json(['data' => [$onu]], 200);
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('onus')->where('id', $id)->update([
            'name' => $request['name'],
        ]);
        return response()->json(['data' => $data], 200);
    }

    public function destroy($id)
    {
        $data = DB::table('dummy')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }

    public function showByOlt($id)
    {
        $data = Dummy::where('olt_id', $id)->get();
        return response()->json(['data' => $data], 200);
    }

    public function getOnuFullStatus($extenal_id)
    {

        try {
            $client = new \GuzzleHttp\Client();
            $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL2') . '/get_onu_full_status_info/' . $extenal_id);
            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            $res = json_decode($res[0]);
            $data = $res->full_status_info;
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(['data' => $data], 200);
    }

    /*

    public function getOnuRunningConfig($extenal_id)
    {
        try {
            $client = new Client();
            $request = new Request('GET', env('API_URL2') . '/get_running_config/' . $extenal_id);
            $res = $client->sendAsync($request)->wait();
            $res = json_decode($res->getBody(), true);
            $res = json_decode($res[0]);
            $data = $res->running_config;
        } catch (Exception $e) {
            return response()->json(['error' => $e], 500);
        }

        return response()->json(['data' => $data], 200);
    } */


    public function importOnus(Request $request)
    {
        $file = $request->file('import_file');
        Excel::import(new OnusImport, $file);
        return response()->json(['status' => true], 200);
    }
}

