<?php

namespace App\Http\Controllers;

use App\Services\ParkingService;
use App\Services\TicketService;
use App\Ticket;
use App\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller {

    /**
     * Get all tickets.
     *
     * @param \App\Services\TicketService $ticketService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TicketService $ticketService) {
        $tickets = $ticketService->getAllTickets();
        $time    = Carbon::now();

        foreach ($tickets as $ticket) {
            $ticket->total_time = $ticketService->getTotalTime($ticket);
        }

        return response()->json($tickets);
    }

    /**
     * Get one ticket.
     *
     * @param \App\Services\TicketService $ticketService
     * @param                             $number
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TicketService $ticketService, $number) {
        if ($ticketService->getOneTicket($number)->exists()) {
            $ticket = $ticketService->getOneTicket($number)->first();

            $ticket->total_time = $ticketService->getTotalTime($ticket);
            $ticket->cost       = $ticketService->getCost($ticket);

            return response()->json($ticket);
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
     * @param \App\Services\ParkingService $parkingService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParkingService $parkingService) {
        $space = $parkingService->getEmptySpot()->first();

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
                ], 409
            );
        }
    }

    /**
     * Make a payment for a ticket.
     *
     * @param \App\Services\TicketService  $ticketService
     * @param \App\Services\ParkingService $parkingService
     * @param                              $number
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TicketService $ticketService, ParkingService $parkingService, $number) {
        $request       = app('request');
        $time          = Carbon::now();
        $ticket        = $ticketService->getOneTicket($number)->first();
        $parking_space = $parkingService->getSpace($ticket);

        if (Ticket::where('number', '=', $number)->exists()) {
            if ($ticket->paid == true) {
                return response()->json(
                    [
                        "message" => "Ticket already paid"
                    ], 409
                );
            } else {
                $card = $request->card;

                $validator = validator()->make(
                    $request->all(),
                    [
                        'card' => ['required', 'numeric']
                    ]
                );

                if ($validator->passes()) {
                    $ticket->paid       = true;
                    $ticket->total_time = $ticketService->getTotalTime($ticket);
                    $ticket->cost       = $ticketService->getCost($ticket);
                    $ticket->end_time   = $time;
                    $ticket->save();

                    $parking_space->occupied = false;
                    $parking_space->save();

                    return response()->json($ticket);
                } else {
                    return response()->json(
                        [
                            "message" => "Incorrect Payment Information"
                        ], 402
                    );
                }
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

