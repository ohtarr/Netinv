<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\NetworkDeviceCisco;
use App\NetworkDeviceAruba;
use App\Asset;
use App\Partner;
use App\Part;
use App\ServiceNowLocation;
use Carbon\Carbon;
use GuzzleHttp\Cookie\CookieJar;
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
        $this->importCsv();
        $this->getCiscoDevices();
        $this->getArubaWlcs();
        $this->getArubaWaps();
        $this->addAssets();
        //print_r($this->devicearray);
    }

    public function getCiscoDevices()
    {
        $manufacturer = Partner::where("name","Cisco")->first();//default to Cisco for Manufacturer.
        $this->ciscodevices = NetworkDeviceCisco::all();
        foreach($this->ciscodevices as $device)
        {
            if($device->protocol = "ssh2")
            {
                $online = 1;
            } else {
                $online = null;
            }
            if($device->ip)
            {
                $ip = $device->ip;
            }
            if($device->name)
            {
                $name = $device->name;
            }
            $sitename = strtoupper(substr($device->name, 0, 8));
            $serial = self::CiscoinventoryToSerial($device->inventory);
            $array = [
                0   =>  [
                    2   =>  $device->model,
                    3   =>  $serial,
                ],
            ];

            $reg = '/NAME:\s+"(\d)"\s*,.+\sPID:\s+(\S+).*SN:\s*(\S+)/';
            if(preg_match_all($reg, $device->inventory, $hits, PREG_SET_ORDER))
            {
                unset($array);
                $array = $hits;
            }
            foreach($array as $switch)
            {                
                unset($tmp);
                $serial = $switch[3];
                if($serial)
                {
                    $tmp['part']['manufacturer_id'] = $manufacturer->id;
                    $tmp['online'] = $online;
                    $tmp['name'] = $name;
                    $tmp['ip'] = $ip;
                    $tmp['location'] = $sitename;
                    $tmp['part']['part_number'] = $switch[2];
                    $this->devicearray[$serial] = $tmp;
                }
            }
        }
    }

    public function getArubaWlcs()
    {
        $manufacturer = Partner::where("name","Aruba")->first();
        $this->arubadevices = NetworkDeviceAruba::all();
        foreach($this->arubadevices as $device)
        {
            unset($tmp);
            $serial = self::ArubainventoryToSerial($device->inventory);
            if(!$serial)
            {
                continue;
            }
            if($device->protocol = "ssh2")
            {
                $online = 1;
            } else {
                $online = null;
            }
            if($device->ip)
            {
                $ip = $device->ip;
            }
            if($device->name)
            {
                $name = $device->name;
            }
            $sitename = strtoupper(substr($device->name, 0, 8));

            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['online'] = $online;
            $tmp['serial'] = $serial;
            $tmp['location'] = $sitename;
            $tmp['part']['part_number'] = $device->model;
            $tmp['ip'] = $device->ip;
            $tmp['name'] = $device->name;
            $this->devicearray[$serial] = $tmp;
        }
    }

    public function getArubaWaps()
    {
        $manufacturer = Partner::where("name","Aruba")->first();
        $url = env('NETMAN_BASE_URL') . env('NETMAN_AP_LIST');
        $cookiejar = new CookieJar(true);
        $params = [
            'cookies' => $cookiejar,
            'cert'    => env('NETMAN_CLIENT_CERT'),
            ];
        $client = new GuzzleHttpClient($params);
        $response = $client->request("GET", $url, $params);
        //get the body contents and decode json into an array.
        $array = json_decode($response->getBody()->getContents(), true);
        //print_r($array);
        //$array = $array["result"];
        foreach($array as $wap)
        {
            unset($tmp);
            $serial = $wap['serial'];
            if(!$serial)
            {
                continue;
            }

            if($wap['status'] = "Up")
            {
                $tmp['online'] = 1;
            } else {
                $tmp['online'] = null;
            }
            $tmp['location'] = strtoupper(substr($wap['name'], 0, 8));
            $tmp['ip'] = $wap['ip'];
            $tmp['name'] = $wap['name'];
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $tmp['part']['part_number'] = $wap['model'];
            $this->devicearray[$serial] = $tmp;
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
        foreach($this->devicearray as $serial => $device)
        {
            print "*****************************\n";
            if(!$serial)
            {
                print "No Serial found!  Skipping!\n";
                continue;
            }
            print "Device Serial : " . $serial . "\n";
            $asset = Asset::where("serial",$serial)->withTrashed()->first();
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
                    $asset->addLog($device['name'], $device['ip'], $device['location'], $message)
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
                if($location && $serial && $part)
                {
                    print "Location, Serial, and Part exist, creating a new Asset!\n";
                    $asset = new Asset;
                    $asset->serial = $serial;
                    $asset->part_id = $part->id;
                    $asset->location_id = $location->sys_id;
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

    public static function CiscoinventoryToSerial($show_inventory)
    {
        $serial = null;
        $invlines = explode("\r\n", $show_inventory);
        foreach ($invlines as $line) {
            // LEGACY PERL CODE: $x =~ /^\s*PID:\s(\S+).*SN:\s+(\S+)\s*$/;
            if (preg_match('/.*PID:\s(\S+).*SN:\s+(\S+)\s*$/', $line, $reg)) {
                $serial = $reg[2];

                return $serial;
            }
        }

        return $serial;
    }

    public static function ArubainventoryToSerial($show_inventory)
    {
        $serial = null;
        $reg = "/System Serial#\s+:\s+(\S+)/";
        if (preg_match($reg, $show_inventory, $hits))
        {
            //print_r($hits);
            $serial = $hits[1];
        }
        return $serial;
    }
}