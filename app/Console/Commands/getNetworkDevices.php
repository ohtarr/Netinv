<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Asset;
use App\Partner;
use App\Part;
use App\ServiceNowLocation;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\Log;

class getNetworkDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'netinv:getNetworkDevices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Network Devices from Network Management System to add to inventory if missing.';

    public $devicearray = [];
    public $locations;
    public $ciscodevices;
    public $arubadevices;
    public $opengeardevices;
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
        //$this->importCsv();
        $this->getCiscoDevices();
        $this->getArubaWlcs();
        $this->getArubaWaps();
        $this->getOpengearDevices();
        $this->addAssets();
        //print_r($this->devicearray);
    }

    public function getCiscoDevices()
    {
        $manufacturer = Partner::where("name","Cisco")->first();
        $url = env('NETMAN_BASE_URL') . env('NETMAN_CISCO_LIST');
        $params = [
        ];
        $client = new GuzzleHttpClient($params);
        $response = $client->request("GET", $url, $params);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);

        foreach($array as $device)
        {
            unset($tmp);
            $tmp['serial'] = $device['serial'];
            if(!$device['serial'])
            {
                continue;
            }
            $tmp['online'] = $device['status'];
            $tmp['ip'] = $device['ip'];
            $tmp['name'] = $device['name'];
            $tmp['location'] = strtoupper(substr($device['name'], 0, 8));
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['part']['part_number'] = $device['model'];
            $this->devicearray[] = $tmp;
        }
    }

    public function getArubaWlcs()
    {
        $manufacturer = Partner::where("name","Aruba")->first();
        $url = env('NETMAN_BASE_URL') . env('NETMAN_WLC_LIST');
        $params = [
        ];
        $client = new GuzzleHttpClient($params);
        $response = $client->request("GET", $url, $params);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);

        foreach($array as $device)
        {
            unset($tmp);
            $tmp['serial'] = $device['serial'];
            if(!$device['serial'])
            {
                continue;
            }

            if($device['status'] == 1)
            {
                $tmp['online'] = 1;
            } else {
                $tmp['online'] = 0;
                //continue;
            }
            $tmp['ip'] = $device['ip'];
            $tmp['name'] = $device['name'];
            $tmp['location'] = strtoupper(substr($device['name'], 0, 8));
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['part']['part_number'] = $device['model'];
            $this->devicearray[] = $tmp;
        }
    }

    public function getArubaWaps()
    {
        $manufacturer = Partner::where("name","Aruba")->first();
        $url = env('NETMAN_BASE_URL') . env('NETMAN_AP_LIST');
        $params = [
        ];
        $client = new GuzzleHttpClient($params);
        $response = $client->request("GET", $url, $params);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $device)
        {
            unset($tmp);
            if(!$device['serial'])
            {
                continue;
            } else {
                $tmp['serial'] = $device['serial'];
            }

            if($device['status'] == 1)
            {
                $tmp['online'] = 1;
            } else {
                //$tmp['online'] = null;
                continue;
            }
            $tmp['location'] = strtoupper(substr($device['name'], 0, 8));
            $tmp['ip'] = $device['ip'];
            $tmp['name'] = $device['name'];
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['part']['part_number'] = $device['model'];
            $this->devicearray[] = $tmp;
        }
    }

    public function getOpengearDevices()
    {
        $manufacturer = Partner::where("name","Opengear")->first();
        $url = env('NETMAN_BASE_URL') . env('NETMAN_OPENGEAR_LIST');
        $params = [
        ];
        $client = new GuzzleHttpClient($params);
        $response = $client->request("GET", $url, $params);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        foreach($array as $device)
        {
            unset($tmp);
            if(!$device['serial'])
            {
                continue;
            } else {
                $tmp['serial'] = $device['serial'];
            }

            $tmp['location'] = strtoupper(substr($device['name'], 0, 8));
            $tmp['ip'] = $device['ip'];
            $tmp['name'] = $device['name'];
            $tmp['online'] = 0;
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['part']['part_number'] = $device['model'];
            $this->devicearray[] = $tmp;
        }
    }

    public function importCsv()
    {
        $manufacturer = Partner::where("name","Cisco")->first();  //default to Cisco for Manufacturer.
        //Grab csv file contents and convert to array.
        if(file_exists(env("CSV_IMPORT_PATH")))
        {
            $file = file(env("CSV_IMPORT_PATH"));
            if($file)
            {
                $csv = array_map('str_getcsv', $file);
                //Grab the title row in the CSV and use it for the associative array keys
                array_walk($csv, function(&$a) use ($csv) {
                $a = array_combine($csv[0], $a);
                });
                array_shift($csv); //remove column header
                //Go through each entry in the CSV.  If it has a valid serial, add it to our array of stuff to attempt to add.
                foreach($csv as $item)
                {
                    print "Importing device " . $item['serial'] . "\n";
                    unset($tmp);
                    if($item['serial'])
                    {
                        $tmp['online'] = null;
                        $tmp['part']['part_number'] = $item['model'];
                        $tmp['part']['manufacturer_id'] = $manufacturer->id;
                        if($item['Site'])
                        {
                            $tmp['location'] = $item['Site'];
                        } else {
                            $tmp['location'] = env("DEFAULT_LOCATION");  //If there is no SITE, assume it's in the DEPOT.
                        }
                        $this->devicearray[$item['serial']] = $tmp;
                    }
                }
            }
        }
    }

    public function addAssets()
    {
        $this->locations = ServiceNowLocation::all();
        foreach($this->devicearray as $device)
        {
            print "*****************************\n";
            if(!$device['serial'])
            {
                print "No Serial found!  Skipping!\n";
                continue;
            }
            print "Device Serial : " . $device['serial'] . "\n";
            $asset = Asset::where("serial",$device['serial'])->withTrashed()->first();
            $part = Part::where("part_number",$device['part']['part_number'])->withTrashed()->first();
            $location = $this->locations->where("name",$device['location'])->first();

            if(!$part)
            {
                print "No part found\n";
                if($device['part']['part_number'])
                {
                    print "Creating new part : " . $device['part']['part_number'] . "\n";
                    $part = new Part;
                    $part->manufacturer_id = $device['part']['manufacturer_id'];
                    $part->part_number = $device['part']['part_number'];
                    $part->save();
                }
            }
            if($asset)
            {
                print "Asset found : " . $asset->serial . "\n";
                if($asset->trashed())
                {
                    $message = "Device restored back to ACTIVE in the Assets database.";
                    $asset->addLog($device['name'], $device['ip'], $device['location'], $message);
                    $asset->restore();
                }
                if($device['online'] == 1)
                {
                    $asset->last_online = Carbon::now();
                }
                if($part)
                {
                    if($part->trashed())
                    {
                        $part->restore();
                    }
                    print "Updating Asset with part : " . $part->part_number . "\n";
                    $asset->part_id = $part->id;
                }
                if($location)
                {
                    print "Updating Asset with Location : " . strtoupper($location->name) . "\n";
                    $asset->location_id = $location->sys_id;
                }
                $asset->save();
                $asset->logChanges(strtoupper($device['name']), $device['ip'], strtoupper($device['location']));
            } else {
                print "No existing Asset found....\n";
                if($device['serial'] && $part)
                {
                    print "Serial and Part exist, creating a new Asset!\n";
                    $asset = new Asset;
                    $asset->serial = $device['serial'];
                    $asset->part_id = $part->id;
                    if($location)
                    {
                        $asset->location_id = $location->sys_id;
                    }
                    if($device['online'])
                    {
                        $asset->last_online = Carbon::now();
                    }
                    $asset->save();
                    $asset->logChanges(strtoupper($device['name']), $device['ip'], strtoupper($device['location']));
                }
            }

        }
    }

}