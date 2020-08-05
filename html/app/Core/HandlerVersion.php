<?php

namespace App\Core;

use App\Core\Core;

class HandlerVersion
{
    private $version;
    private $api;

    public function __construct()
    {
        $this->version = Core::getVersion();
        switch($this->version){
            case "v1":
                $this->api = new APIv1Core();
                break;
  
            // case "v2":
            //     $this->api = new APIv2Core();
            //     break;

        }
        
        return $this->getApi();
    }

    public function getApi()
    {
        return $this->api;
    }

    public function getVersion()
    {
        return $this->version;
    }
}