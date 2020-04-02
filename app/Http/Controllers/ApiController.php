<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller {

    /**
     * Get all tickets.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllTickets() {
        $tickets = Ticket::with('parking_space')->get();
        $time    = Carbon::now();

        foreach ($tickets as $ticket) {
            $ticket->total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
        }

        return response()->json($tickets);
    }

    /**
     * Get one ticket.
     *
     * @param $number
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Search for a ticket.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request) {
        $str = $request->get('str');

        $result = Ticket::where('number', 'like', "%$str%")->get();

        return response()->json($result);
    }

    /**
     * Save a new ticket.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
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

            return response()->json($ticket);
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
     *
     * @param $number
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay($number) {
        $request       = app('request');
        $ticket        = Ticket::where('number', '=', $number)->first();
        $time          = Carbon::now();
        $parking_space = ParkingSpace::find($ticket->space_id);

        if (Ticket::where('number', '=', $number)->exists()) {
            $card = $request->card;

            $validator = validator()->make(
                $request->all(),
                [
                    'card' => ['required', 'numeric']
                ]
            );

            if ($validator->passes()) {
                $ticket->paid       = true;
                $ticket->total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format(' % I');
                $ticket->cost       = (new Ticket())->getCost($ticket);
                $ticket->end_time   = $time;
                $ticket->save();

                $parking_space->occupied = false;
                $parking_space->save();

                return response()->json($ticket);
            } else {
                return response()->json(
                    [
                        "message" => "Incorrect Payment Information"
                    ], 404
                );
            }
        } else {
            return response()->json(
                [
                    "message" => "Ticket not found"
                ], 404
            );
        }
    }
}

