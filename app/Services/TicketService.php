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

    public function storeTicket($space) {
        $space->occupied = true;
        $space->save();

        $ticket = new Ticket(
            [
                'number'   => uniqid(),
                'space_id' => $space->id,
            ]
        );
        $ticket->save();

        return $ticket;
    }

    public function payTicket($ticket, $parking_space) {
        $time          = Carbon::now();

        $ticket->paid       = true;
        $ticket->total_time = $this->getTotalTime($ticket);
        $ticket->cost       = $this->getCost($ticket);
        $ticket->end_time   = $time;
        $ticket->save();

        $parking_space->occupied = false;
        $parking_space->save();

        return $ticket;
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
