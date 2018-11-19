<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\NetworkDevice;
use App\Asset;
use App\Partner;
use App\Part;
use App\ServiceNowLocation;

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
        //print_r($this->devicearray);
        $this->addAssets();
    }

    public function getCiscoDevices()
    {
        $manufacturer = Partner::where("name","Cisco")->first();//default to Cisco for Manufacturer.
        $devices = NetworkDevice::all();
        foreach($devices as $device)
        {
            print "Getting device " . $device-name . "\n";
            unset($tmp);
            $tmp['part']['manufacturer_id'] = $manufacturer->id;
            $serial = self::inventoryToSerial($device->inventory);

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
        $csv = array_map('str_getcsv', file(env("CSV_IMPORT_PATH")));
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

    public function addAssets()
    {
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
            $location = ServiceNowLocation::where("name",$device['location'])->first();

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
                    $asset->save();
                }
            }
        }
    }

    public static function inventoryToSerial($show_inventory)
    {
        $serial = 'Unknown';
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
}