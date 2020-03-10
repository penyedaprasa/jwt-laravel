<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $primaryKey = 'menu_id';
    protected $table = 'pms_menu';
   
    protected $fillable = [
        'menu_name', 'menu_keterangan',
    ];
}
