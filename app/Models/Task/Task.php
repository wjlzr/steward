<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $table = 'st_sys_task';

    protected $primaryKey = 'task_id';

    public $incrementing = false;

    public $timestamps = false;

}
