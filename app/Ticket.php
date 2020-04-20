<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\ParkingSpace;

class Ticket extends Model {

    protected $table      = 'ticket';

    public    $timestamps = true;

    protected $fillable   = ['number', 'cost', 'total_time', 'paid', 'space_id'];

    public function parking_space() {
        return $this->belongsTo('App\ParkingSpace', 'space_id');
    }

}
