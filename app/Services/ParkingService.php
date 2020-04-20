<?php

namespace App\Services;

use App\ParkingSpace;
use App\Ticket;
use Carbon\Carbon;

class ParkingService {

    public function getEmptySpot() {
        return ParkingSpace::where('occupied', false);
    }

    public function getSpace($ticket) {
        return ParkingSpace::find($ticket->space_id);
    }
}
