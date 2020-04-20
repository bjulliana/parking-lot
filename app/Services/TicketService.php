<?php

namespace App\Services;

use App\Ticket;
use Carbon\Carbon;

class TicketService {

    public function getAllTickets() {
        return Ticket::with('parking_space')->get();
    }

    public function getOneTicket($number) {
        return $ticket = Ticket::where('number', '=', $number);
    }

    public function getTotalTime($ticket) {
        $time = Carbon::now();

        return $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
    }

    public function getCost($ticket) {
        $time           = Carbon::now();
        $total_time     = $ticket->created_at->floatDiffInHours($time);
        $starting_price = 3.00;
        $increase       = 0.5;
        $tier           = 0;
        $days           = 1;

        if ($total_time <= 1) {
            $tier = 0;
        } else if ($total_time > 1 && $total_time <= 3) {
            $tier = 1;
        } else if ($total_time > 3 && $total_time <= 6) {
            $tier = 2;
        } else if ($total_time > 6 && $total_time <= 24) {
            $tier = 3;
        } else if ($total_time > 24) {
            $tier = 3;
            $days = floor($total_time / 24);
        }

        return ($starting_price * pow(1 + $increase, $tier)) * $days;
    }

}
