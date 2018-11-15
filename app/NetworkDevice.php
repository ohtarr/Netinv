<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Inarticulate\InformationModel;

class NetworkDevice extends InformationModel
{
    protected $category = "Management";
    protected $type = "Device_Network_Cisco";
}
