<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $primaryKey = 'group_id';
    protected $table = 'pms_group';
   
    protected $fillable = [
        'group_name', 'group_keterangan', 'group_menu',
    ];

    public function users()
    {
         return $this->belongsTo(User::class, 'group_id', 'group_id');
    } 
    
}
