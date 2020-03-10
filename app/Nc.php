<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nc extends Model
{
    protected $primaryKey = 'nc_id';
    protected $table = 'pms_nc';
   
    protected $fillable = [
        'nc_nama', 'nc_dokumen', 'nc_keterangan', 'users_id', 'date_in', 'date_out',
    ];
}
