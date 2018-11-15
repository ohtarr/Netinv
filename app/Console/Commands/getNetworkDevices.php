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
        $this->getCiscoDevices();
    }

    public function getCiscoDevices()
    {
        $manufacturer = Partner::where("name","Cisco")->first();//default to Cisco for Manufacturer.
        print "MANUFACTURER ID: " . $manufacturer->id . "\n";
        $devices = NetworkDevice::all();
        foreach($devices as $device)
        {
            print "***********************************************\n";
            print "DEVICE : " . $device->name . "\n";
            $serial = self::inventoryToSerial($device->inventory);
            print "SERIAL : " . $serial . "\n";
            $sitename = strtoupper(substr($device->name, 0, 8));
            print "SITENAME : " . $sitename . "\n";
            $location = ServiceNowLocation::where("name",$sitename)->first();
            if($location)
            {
                print "LOCATION NAME: " . $location->name . "\n";
            } else {
                print "NO VALID LOCATION, SKIPPING!!\n";
                continue;
            }
            if($serial)
            {
                print "SERIAL FOUND! CONTINUING \n";
                $asset = Asset::where("serial",$serial)->withTrashed()->first();
                $part = Part::where("part_number",$device->model)->withTrashed()->first();
                if(!$part)
                {
                    print "NO EXISTING PART FOUND! CREATING ONE\n";
                    if($device->model)
                    {
                        print "DEVICE MODEL EXISTS!\n";
                        $newpart = new Part;
                        $newpart->manufacturer_id  = $manufacturer->id;
                        $newpart->part_number = $device->model;
                        $newpart->save();
                        $part = $newpart;
                    } else {
                        print "DEVIC MODEL DOES NOT EXIST, SKIPPING!!\n";
                        continue;
                    }
                } else {
                    print "EXISTING PART FOUND! PART ID: " . $part->id . "\n";
                    if($part->deleted_at)
                    {
                        print "PART FOUND BUT DELETED!  UPDATING IT!!\n";
                        $part->manufacturer_id  = $manufacturer->id;
                        $part->save();
                        $part->restore();
                    }
                }


                if(!$asset)
                {
                    print "NO EXISTING ASSET FOUND!\n";

                    print "CREATING NEW ASSET\n";
                    $newasset = new Asset;
                    $newasset->serial = $serial;
                    $newasset->part_id = $part->id;
                    $newasset->vendor_id = $manufacturer->id;
                    $newasset->location_id = $location->sys_id;
                    $newasset->save();
                } else {
                    print "EXISTING ASSET FOUND! ASSET ID: " . $asset->id . "\n";
                    if($asset->deleted_at)
                    {
                        print "ASSET FOUND, BUT DELETED!  UPDATING IT!!\n";
                        $asset->part_id = $part->id;
                        $asset->vendor_id = $manufacturer->id;
                        $asset->location_id = $location->sys_id;
                        $asset->save();
                        $asset->restore();
                    }
                }
            }
            //break;
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