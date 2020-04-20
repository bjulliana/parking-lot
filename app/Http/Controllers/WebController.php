<?php

namespace App\Http\Controllers;

use App\Services\ParkingService;
use App\Ticket;
use App\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\TicketService;

class WebController extends Controller {

    /**
     * Display all tickets.
     *
     * @param \App\Services\TicketService $ticketService
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TicketService $ticketService) {
        $tickets = $ticketService->getAllTickets();

        return view('tickets.all', ['tickets' => $tickets]);
    }

    /**
     * Find ticket and display owning amount.
     *
     * @param \App\Services\TicketService $ticketService
     * @param                             $number
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(TicketService $ticketService, $number) {
        $time       = Carbon::now();
        $ticket     = $ticketService->getOneTicket($number)->first();
        $cost       = $ticketService->getCost($ticket);
        $total_time = $ticketService->getTotalTime($ticket);

        return view('tickets.show', ['ticket' => $ticket, 'time' => $time, 'total_time' => $total_time, 'cost' => $cost]);
    }

    /**
     * Show the form for creating a new ticket.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        $time   = Carbon::now();
        $spaces = (new ParkingSpace())->getEmptySpot()->get();
        $count  = count($spaces);

        return view('tickets.create', ['time' => $time, 'count' => $count]);
    }

    /**
     * Store a new ticket.
     *
     * @param \App\Services\TicketService $ticketService
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TicketService $ticketService) {

        //Get First Open Parking Space
        $space = (new ParkingSpace())->getEmptySpot()->first();

        if (isset($space)) {
            $ticket = $ticketService->storeTicket($space);

            return back()->with('success', 'The Ticket #' . $ticket->number . ' is Printing');
        } else {
            return back()->with('error', 'Parking Lot Full');
        }
    }

    /**
     * Store the payment information and clear the parking spot.
     *
     * @param \App\Services\TicketService  $ticketService
     * @param                              $number
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TicketService $ticketService, $number) {
        $ticket        = $ticketService->getOneTicket($number)->first();
        $parking_space = (new ParkingSpace())->getSpace($ticket);

        $data      = request()->input();
        $validator = validator()->make(
            $data, [
                     'card' => ['required', 'numeric'],
                 ]
        );

        if ($validator->passes()) {
            $ticket = $ticketService->payTicket($ticket, $parking_space);

            return back()->with('success', 'The Ticket #' . $ticket->number . ' is Paid');
        }

        return redirect()->back()->withErrors($validator->errors())->withInput()->with('error', 'Please Verify Card Information!');
    }
}
