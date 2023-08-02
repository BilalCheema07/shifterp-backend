<?php
namespace App\Models\Legacy;

use App\Models\BaseFacilityModel;

class EdiCustomer extends BaseFacilityModel
{
    public $table = 'edicustomer';

    public function getEdiStorageOutboundDir()
    {
        return $this->ftpDirOut;
    }

    public function getEdiStorageInboundDir()
    {
        return $this->ftpDirIn;
    }

    public function getEdiStorageHost()
    {
        return $this->ftpHostOut;
    }

    public function getEdiStorageUsername()
    {
        return $this->ftpUsernameOut;
    }

    public function getEdiStoragePassword()
    {
        return $this->ftpPasswordOut;
    }

    public function getEdiStorageRoot()
    {
        return '/';
    }
}
