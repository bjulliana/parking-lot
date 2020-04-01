<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParkingSpace extends Model
{

    protected $table = 'parking_space';
    public    $timestamps = true;
    protected $fillable = array('occupied');

    public function tickets()
    {
        return $this->hasMany('Ticket');
    }

}
