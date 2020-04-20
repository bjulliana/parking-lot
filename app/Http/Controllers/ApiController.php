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
     * @param \App\Services\TicketService $ticketService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TicketService $ticketService) {
        //Get First Open Parking Space
        $space = (new ParkingSpace())->getEmptySpot()->first();

        if (isset($space)) {
            $ticket = $ticketService->storeTicket($space);

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
     * @param                              $number
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TicketService $ticketService, $number) {
        $request       = app('request');
        $time          = Carbon::now();
        $ticket        = $ticketService->getOneTicket($number)->first();
        $parking_space = (new ParkingSpace())->getSpace($ticket);

        if ($ticketService->getOneTicket($number)->exists()) {
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
                    $ticket = $ticketService->payTicket($ticket, $parking_space);

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

