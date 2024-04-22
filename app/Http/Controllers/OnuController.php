<?php

namespace App\Http\Controllers;

use App\Imports\OnusImport;
use App\Models\AdministrativeStatus;
use Illuminate\Support\Facades\Cache;
use App\Models\EthernetPort;
use App\Models\Onu;
use App\Models\ServicePort;
use App\Models\SpeedProfile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class OnuController extends Controller
{
    public function index(Request $request)
    {

        $search = $request->input("search") ?? null;
        $status = $request->input("status") ?? null;
        $signal = $request->input("signal") ?? null;
        $oltName = $request->input("oltName") ?? null;
        $zoneName = $request->input("zone") ?? null;
        $onuType = $request->input("onuType") ?? null;
        $ponType = $request->input("ponType") ?? null;
        $board = (string) $request->input("board") ?? null;
        $port = (string) $request->input("port") ?? null;
        $odb = $request->input("odb") ?? null;
        $speedProfile = $request->input("speedProfile") ?? null;
        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;


        $data = Onu::join('olts', 'onus.olt_id', 'olts.id')
            ->join('status', 'onus.status_id', 'status.id')
            ->join('signal', 'onus.signal_id', 'signal.id')
            ->join('zones', 'onus.zone_id', 'zones.id')
            ->join('odbs', 'onus.odb_id', 'odbs.id')
            ->leftJoin('service_ports', 'service_ports.onu_id', 'onus.id')
            ->join('onu_types', 'onus.onu_type_id', 'onu_types.id')
            ->join('pon_types', 'pon_types.id', 'onu_types.pon_type_id')
            ->select(
                'onus.id',
                'onus.name',
                'onus.unique_external_id',
                'status.description as status',
                'onus.serial',
                'signal.description as signal',
                'onus.olt_id',
                'olts.name as olt_name',
                'onus.zone_id',
                'zones.name as zone_name',
                'onu_types.name as onu_type',
                'pon_types.name as pon_type',
                'onus.catv',
                'onus.authorization_date',
            );

        $data = $data->orderBy('id', $orderBy)
            ->search($search)
            ->port($port)
            ->board($board);

        if ($status) {
            $data = $data->where('status.description', $status);
        }

        if ($signal) {
            $data = $data->where('signal.description', $signal);
        }

        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        if ($ponType) {
            $data = $data->where('pon_types.name', 'LIKE', "%$ponType%");
        }

        if ($zoneName) {
            $data = $data->where('zones.name', 'LIKE', "%$zoneName%");
        }

        if ($onuType) {
            $data = $data->where('onu_types.name', 'LIKE', "%$onuType%");
        }

        if ($odb) {
            $data = $data->where('odbs.name', 'LIKE', "%$odb%");
        }

        if ($speedProfile) {
            $data = $data->join('speed_profiles', 'speed_profiles.id', 'onus.speed_profile_id')->where('speed_profiles.name', 'LIKE', "%$speedProfile%");
        }

        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);
    }

    public function configuredOnus(Request $request)
    {

        $search = $request->input("search") ?? null;
        $status = $request->input("status") ?? null;
        $signal = $request->input("signal") ?? null;
        $oltName = $request->input("oltName") ?? null;
        $zoneName = $request->input("zone") ?? null;
        $onuType = $request->input("onuType") ?? null;
        $ponType = $request->input("ponType") ?? null;
        $board = (string) $request->input("board") ?? null;
        $port = (string) $request->input("port") ?? null;
        $odb = $request->input("odb") ?? null;
        $speedProfile = $request->input("speedProfile") ?? null;
        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;


        $data = Onu::join('administrative_status', 'onus.administrative_status_id', 'administrative_status.id')
            // ->where('administrative_status.description', 'Enabled') // TODO verificar si se quieren ver onus desactivadas en seccion de configuradas
            ->where('onus.speed_profile_id', '!=',  null)
            ->join('olts', 'onus.olt_id', 'olts.id')
            ->join('status', 'onus.status_id', 'status.id')
            ->join('signal', 'onus.signal_id', 'signal.id')
            ->join('zones', 'onus.zone_id', 'zones.id')
            ->join('odbs', 'onus.odb_id', 'odbs.id')
            ->leftJoin('service_ports', 'service_ports.onu_id', 'onus.id')
            ->join('onu_types', 'onus.onu_type_id', 'onu_types.id')
            ->join('pon_types', 'pon_types.id', 'onu_types.pon_type_id')
            ->join('speed_profiles', 'speed_profiles.id', 'onus.speed_profile_id')
            ->select(
                'onus.id',
                'onus.name',
                'onus.unique_external_id',
                'status.description as status',
                'onus.serial',
                'signal.description as signal',
                'onus.olt_id',
                'olts.name as olt_name',
                'onus.zone_id',
                'zones.name as zone_name',
                'onu_types.name as onu_type',
                'pon_types.name as pon_type',
                'onus.catv',
                'onus.authorization_date',
            );

        $data = $data->orderBy('id', $orderBy)
            ->search($search)
            ->port($port)
            ->board($board);

        if ($status) {
            $data = $data->where('status.description', $status);
        }

        if ($signal) {
            $data = $data->where('signal.description', $signal);
        }

        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

        if ($ponType) {
            $data = $data->where('pon_types.name', 'LIKE', "%$ponType%");
        }

        if ($zoneName) {
            $data = $data->where('zones.name', 'LIKE', "%$zoneName%");
        }

        if ($onuType) {
            $data = $data->where('onu_types.name', 'LIKE', "%$onuType%");
        }

        if ($odb) {
            $data = $data->where('odbs.name', 'LIKE', "%$odb%");
        }

        if ($speedProfile) {
            $data = $data->where('speed_profiles.name', 'LIKE', "%$speedProfile%");
        }

        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);
    }

    public function unconfiguredOnus(Request $request){

        $orderBy = $request->input("orderBy") ?? 'DESC';
        $pageOffset = $request->input("pageOffset") ?? 10;
        $search = $request->input("search") ?? null;
        $oltName = $request->input("oltName") ?? null;


        $data = Onu::join('administrative_status', 'onus.administrative_status_id', 'administrative_status.id')
            ->where('speed_profile_id', null)
            ->join('olts', 'onus.olt_id', 'olts.id')
            ->join('onu_types', 'onus.onu_type_id', 'onu_types.id')
            ->join('pon_types', 'pon_types.id', 'onu_types.pon_type_id')
            ->select(
                'onus.id',
                'pon_types.name as pon_type',
                'onus.board',
                'onus.port',
                'onus.serial',
                'onu_types.name as onu_type',
                'onus.olt_id',
                'olts.name as olt_name',
            );


        $data = $data->orderBy('id', $orderBy)
        ->search($search);
 
        if ($oltName) {
            $data = $data->where('olts.name', 'LIKE', "%$oltName%");
        }

    
        $data = $data->paginate($pageOffset);
        
        return response()->json($data, 200);

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

        $data = Onu::create([
            'autoincrement' => $request['autoincrement'],
            'unique_external_id' => $request['unique_external_id'],
            'pon_type_id' => $request['pon_type'],
            'serial' => $request['serial'],
            'onu_type_id' => $request['onu_type_id'],
            'name' => $request['name'],
            'olt_id' => $request['olt_id'],
            'board' => $request['board'],
            'port' => $request['port'],
            'allocated_onu' => $request['allocated_onu'],
            'zone_id' => $request['zone_id'],
            'address' => $request['address'],
            'odb_id' => $request['odb_id'],
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
            'auth_date' => $request['auth_date'],
            'signal_1310' => $request['signal_1310'],
            'distance' => $request['distance'],
            'service_port' => $request['service_port'],
            'service_port_vlan' => $request['service_port_vlan'],
            'service_port_cvlan' => $request['service_port_cvlan'],
            'service_port_svlan' => $request['service_port_svlan'],
            'service_port_tag_transform_mode' => $request['service_port_tag_transform_mode'],
        ]);

        return response()->json(['data' => $data], 200);
    }

    public function show($id)
    {
        try {

            $onu = Onu::where('onus.id', $id)
                ->join('administrative_status', 'onus.administrative_status_id', 'administrative_status.id')
                ->join('olts', 'onus.olt_id', 'olts.id')
                ->join('onu_types', 'onus.onu_type_id', 'onu_types.id')
                ->join('pon_types', 'onu_types.pon_type_id', 'pon_types.id')
                ->leftJoin('service_ports', 'service_ports.onu_id', 'onus.id')
                ->join('zones', 'onus.zone_id', 'zones.id')
                ->join('odbs', 'onus.odb_id', 'odbs.id')
                ->join('status', 'onus.status_id', 'status.id')
                ->join('signal', 'onus.signal_id', 'signal.id')
                ->select(
                    'onus.id',
                    'onus.name as name',
                    'onus.unique_external_id',
                    'status.description as status',
                    'onus.serial',
                    'signal.description as signal',
                    'signal.frequency as signal frequency',
                    'onus.catv',
                    'onus.authorization_date',
                    'onus.olt_id',
                    'olts.name as olt_name',
                    'zones.name as zone_name',
                    'zones.id as zone_id',
                    'onus.board',
                    'onus.port',
                    'onus.address',
                    'onus.mode',
                    'odbs.name as odb_name',
                    'onus.speed_profile_id',
                    'odbs.id as odb_id',
                    'service_ports.vlan_id as vlan',
                    'pon_types.name as pon_type',
                    'pon_types.id as pon_type_id',
                    'onu_types.name as onu_type',
                    'onu_types.id as onu_type_id',
                    'administrative_status.description as administrative_status'
                )
                ->first();

            if ($onu) {
                $ethernet_ports = EthernetPort::where('onu_id', $onu->id)->get();
                $service_ports = ServicePort::join('speed_profiles', 'service_ports.speed_profile_id', 'speed_profiles.id')
                    ->where('service_ports.onu_id', $onu->id)
                    ->select(
                        'service_ports.id as service_port',
                        'speed_profiles.download_speed',
                        'speed_profiles.upload_speed',
                        'service_ports.vlan_id as vlan',
                        'service_ports.cvlan_id as cvlan',
                        'service_ports.svlan_id as svlan',
                        'service_ports.tag_mode'
                    )
                    ->get();

                $onu['ethernet_ports'] = $ethernet_ports;
                $onu['service_ports'] = $service_ports;
            }

            return response()->json(['data' => $onu], 200);
            
        } catch (Exception $e) {

            return response()->json(array('error' => $e), 200);
        }

    }

    public function update(Request $request, $id)
    {

        $data = Onu::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $data->name = $request->input('name');
        $data->unique_external_id = $request->input('unique_external_id');
        $data->pon_type_id = $request->input('pon_type_id');
        $data->serial = $request->input('serial');
        $data->onu_type_id = $request->input('onu_type_id');
        $data->olt_id = $request->input('olt_id');
        $data->board = $request->input('board');
        $data->port = $request->input('port');
        $data->odb_id = $request->input('odb_id');
        $data->mode = $request->input('mode');
        $data->speed_profile_id = $request->input('speed_profile_id');
        $data->zone_id = $request->input('zone_id');
    
        $data->save();

        return response()->json(['data' => $data], 200);
    }

    public function authorize_onu(Request $request, $id)
    {

        $data = Onu::findOrFail($id);

        if (!$data) {
            return back()->with('error', 'Usuario no encontrado');
        }

        $data->name = $request->input('name');
        $data->unique_external_id = $request->input('unique_external_id');
        $data->pon_type_id = $request->input('pon_type_id');
        $data->serial = $request->input('serial');
        $data->onu_type_id = $request->input('onu_type_id');
        $data->olt_id = $request->input('olt_id');
        $data->board = $request->input('board');
        $data->port = $request->input('port');
        $data->odb_id = $request->input('odb_id');
        $data->mode = $request->input('mode');
        $data->speed_profile_id = $request->input('speed_profile_id');
        $data->zone_id = $request->input('zone_id');
        $data->authorization_date = now();
    
        $data->save();

        return response()->json(['data' => $data], 200);
    }
    
    public function destroy($id)
    {
        $data = DB::table('onus')->where('id', $id)->delete();
        return response()->json(['data' => $data], 200);
    }

    public function showByOlt($id)
    {
        $data = Onu::where('olt_id', $id)->get();
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

        // public function onusUnconfigureds()
    // {
    //     // Intenta obtener datos de la caché
    //     $cachedData = Cache::get('onus_unconfigured_data');

    //     if ($cachedData) {
    //         // Si los datos están en caché, devuélvelos
    //         return response()->json(['data' => $cachedData], 200);
    //     }

    //     try {
    //         $client = new \GuzzleHttp\Client();
    //         $request = new \GuzzleHttp\Psr7\Request('GET', env('API_URL') . '/onu/get_no_configurados');
    //         $res = $client->sendAsync($request)->wait();
    //         $data = json_decode($res->getBody());

    //         $index = 1;

    //         $response = array_map(function ($item) use (&$index) {
    //             return [
    //                 'tipo_puerto' => $item->tipo_puerto ?? null,
    //                 'slot' => $item->slot ?? null,
    //                 'puerto' => $item->puerto ?? null,
    //                 'onu_id' => $item->onu_id ?? null,
    //                 'numero_serial' => $item->numero_serial ?? null,
    //                 'tipo_onu_id' => $item->tipo_onu_id ?? null,
    //                 'tipo_onu_nombre' => $item->tipo_onu_nombre ?? null,
    //                 'olt_id' => $item->olt_id ?? null,
    //             ];
    //         }, $data);

    //         Cache::put('onus_unconfigured_data', $response, 3600);

    //         return response()->json(['data' => $response], 200);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }



    

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
