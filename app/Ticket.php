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
        return $this->belongsTo('App\ParkingSpace');
    }

    public function getCost($ticket) {
        $time       = Carbon::now();
        $total_time = $ticket->created_at->floatDiffInHours($time);
        $price      = 3.00;

        if ($total_time <= 1) {
            $price = 3.00;
        } else if ($total_time > 1 && $total_time <= 3) {
            $price = 4.50;
        } else if ($total_time > 3 && $total_time <= 6) {
            $price = 6.75;
        } else if ($total_time > 6 && $total_time <= 24) {
            $price = 10.15;
        } else if ($total_time > 24) {
            $days  = floor($total_time / 24);
            $price = 10.15 * $days;
        }

        return $price;
    }

}
