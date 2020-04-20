<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;

class ParkingSpace extends Model {

    protected $table      = 'parking_space';

    public    $timestamps = true;

    protected $fillable   = ['occupied'];

    public function tickets() {
        return $this->hasMany('App\Ticket');
    }

    public function getEmptySpot() {
        return $this->where('occupied', false);
    }

    public function getSpace($ticket) {
        return $this->find($ticket->space_id);
    }

}
