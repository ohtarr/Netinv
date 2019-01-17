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
        $this->getArubaDevices();
        $this->addAssets();
        //print_r($this->devicearray);
    }

    public function getCiscoDevices()
    {
        $manufacturer = Partner::where("name","Cisco")->first();//default to Cisco for Manufacturer.
        $this->ciscodevices = NetworkDeviceCisco::all();
        foreach($this->ciscodevices as $device)
        {
            unset($tmp);
            print "Getting device " . $device->name . "\n";
            if($device->protocol = "ssh2")
            {
                $tmp['online'] = 1;
            } else {
                $tmp['online'] = null;
            }
            $reg = '/NAME:\s+"(\d)"\s*,.+\sPID:\s+(\S+).*SN:\s*(\S+)/';
            if(preg_match_all($reg, $device->inventory, $hits, PREG_SET_ORDER))
            {
                //print_r($hits);
                foreach($hits as $switch)
                {
                    unset($tmp2);
                    $tmp2['part']['manufacturer_id'] = $manufacturer->id;
                    $tmp2['online'] = $tmp['online'];
                    $serial = $switch[3];

                    if($serial)
                    {
                        $sitename = strtoupper(substr($device->name, 0, 8));
                        $tmp2['location'] = $sitename;
                        $tmp2['part']['part_number'] = $device->model;
                        $this->devicearray[$serial] = $tmp2;
                    }
                }
            } else {
                unset($tmp2);
                $tmp2['part']['manufacturer_id'] = $manufacturer->id;
                $tmp2['online'] = $tmp['online'];
                $serial = self::CiscoinventoryToSerial($device->inventory);
    
                if($serial)
                {
                    $sitename = strtoupper(substr($device->name, 0, 8));
                    $tmp2['location'] = $sitename;
                    $tmp2['part']['part_number'] = $device->model;
                    $this->devicearray[$serial] = $tmp2;
                }
            }

        }
    }

    public function getArubaDevices()
    {
        $manufacturer = Partner::where("name","Aruba")->first();//default to Cisco for Manufacturer.
        $this->arubadevices = NetworkDeviceAruba::all();
        foreach($this->arubadevices as $device)
        {
            print "Getting device " . $device->name . "\n";
            unset($tmp);
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            if($device->protocol)
            {
                $tmp['online'] = 1;
            } else {
                $tmp['online'] = null;
            }
            $serial = self::ArubainventoryToSerial($device->inventory);

            if($serial)
            {
                $sitename = strtoupper(substr($device->name, 0, 8));
                $tmp['location'] = $sitename;
                $tmp['part']['part_number'] = $device->model;
                $this->devicearray[$serial] = $tmp;
            }
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
                if($device['online'] == 1)
                {
                    $asset->last_online = Carbon::now();
                }
                if($part)
                {
                    print "Updating Asset with part : " . $part->part_number . "\n";
                    $asset->part_id = $part->id;
                }
                if($location)
                {
                    print "Updating Asset with Location : " . $location->name . "\n";
                    $asset->location_id = $location->sys_id;
                }
                $asset->save();

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