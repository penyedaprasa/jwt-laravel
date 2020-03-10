<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'pms_part';
   
    protected $fillable = [
        'name', 'keterangan', 'nomor', 'date_in', 'date_out', 'keterangan',
    ];

    // public function users()
    // {
    //      return $this->belongsTo(User::class, 'group_id', 'group_id');
    // } 
    
}
