<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller {

    /**
     * Get all tickets.
     */
    public function getAllTickets() {
        $tickets = Ticket::with('parking_space')->get();
        $time    = Carbon::now();

        foreach ($tickets as $ticket) {
            $ticket->total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
        }

        return response($tickets, 200);
    }

    /**
     * Get one ticket.
     */
    public function getOneTicket($number) {
        if (Ticket::where('number', '=', $number)->exists()) {
            $time   = Carbon::now();
            $ticket = Ticket::where('number', '=', $number)->first();

            $ticket->total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
            $ticket->cost       = (new Ticket())->getCost($ticket);

            return response($ticket, 200);
        } else {
            return response()->json(
                [
                    "message" => "Ticket not found"
                ], 404
            );
        }
    }

    /**
     * Save a new ticket.
     */
    public function store(Request $request) {
        $space = ParkingSpace::where('occupied', false)->first();

        if (isset($space)) {
            $space->occupied = true;
            $space->save();

            $ticket           = new Ticket();
            $ticket->number   = uniqid();
            $ticket->space_id = $space->id;
            $ticket->save();

            return response()->json(
                [
                    "message" => "Your Ticket #$ticket->number is Printing"
                ], 201
            );
        } else {
            return response()->json(
                [
                    "message" => "Parking Lot Full"
                ], 404
            );
        }
    }

    /**
     * Make a payment for a ticket.
     */
    public function pay($number) {
        $request       = app('request');
        $ticket        = Ticket::where('number', '=', $number)->first();
        $time          = Carbon::now();
        $parking_space = ParkingSpace::find($ticket->space_id);

        if (Ticket::where('number', '=', $number)->exists()) {
            $card = $request->card;

            if ($card) {
                $ticket->paid       = true;
                $ticket->total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
                $ticket->cost       = (new Ticket())->getCost($ticket);
                $ticket->end_time   = $time;
                $ticket->save();

                $parking_space->occupied = false;
                $parking_space->save();
            } else {
                return response()->json(
                    [
                        "message" => "Payment Information Missing"
                    ], 404
                );
            }

            return response($ticket, 200);
        } else {
            return response()->json(
                [
                    "message" => "Ticket not found"
                ], 404
            );
        }
    }
}

