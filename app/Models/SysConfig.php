<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysConfig extends Model
{

    protected $table = 'st_sys_config';

    protected $primaryKey = 's_id';

    public $timestamps = false;

    public $incrementing = false;

}