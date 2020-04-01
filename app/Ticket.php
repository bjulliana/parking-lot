<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $table = 'ticket';
    public $timestamps = true;
    protected $fillable = array('number', 'cost', 'paid', 'space_id');

    public function space()
    {
        return $this->belongsTo('ParkingSpace');
    }

}
