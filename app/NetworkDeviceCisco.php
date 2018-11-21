<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inarticulate\InformationModel;

class NetworkDeviceCisco extends InformationModel
{
    protected $category = "Management";
    protected $type = "Device_Network_Cisco";
}
