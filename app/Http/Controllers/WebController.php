<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\ParkingSpace;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebController extends Controller {

    /**
     * Display all tickets.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllTickets() {
        $tickets = Ticket::with('parking_space')->get();

        return view('tickets.all', ['tickets' => $tickets]);
    }

    /**
     * Find ticket and display owning amount.
     *
     * @param $number
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getOneTicket($number) {
        $ticket     = Ticket::where('number', '=', $number)->first();
        $time       = Carbon::now();
        $cost       = (new Ticket())->getCost($ticket);
        $total_time = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');

        return view('tickets.show', ['ticket' => $ticket, 'time' => $time, 'total_time' => $total_time, 'cost' => $cost]);
    }

    /**
     * Show the form for creating a new ticket.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        $time   = Carbon::now();
        $spaces = ParkingSpace::where('occupied', false)->get();
        $count  = count($spaces);

        return view('tickets.create', ['time' => $time, 'count' => $count]);
    }

    /**
     * Store a new ticket.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {

        //Get First Open Parking Space
        $space = ParkingSpace::where('occupied', false)->first();

        if (isset($space)) {
            $space->occupied = true;
            $space->save();

            $ticket = new Ticket(
                [
                    'number'   => uniqid(),
                    'space_id' => $space->id,
                ]
            );
            $ticket->save();

            return back()->with('success', 'The Ticket #' . $ticket->number . ' is Printing');
        } else {
            return back()->with('error', 'Parking Lot Full');
        }
    }

    /**
     * Store the payment information and clear the parking spot.
     *
     * @param $number
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay($number) {

        $time          = Carbon::now();
        $ticket        = Ticket::where('number', '=', $number)->first();
        $total_time    = $ticket->created_at->diffInHours($time) . ':' . $ticket->created_at->diff($time)->format('%I');
        $parking_space = ParkingSpace::find($ticket->space_id);

        $data      = request()->input();
        $validator = validator()->make(
            $data, [
                     'card' => ['required', 'numeric'],
                 ]
        );

        if ($validator->passes()) {
            $ticket->paid       = true;
            $ticket->total_time = $total_time;
            $ticket->cost       = (new Ticket())->getCost($ticket);
            $ticket->end_time   = $time;
            $ticket->save();

            $parking_space->occupied = false;
            $parking_space->save();

            return back()->with('success', 'The Ticket #' . $ticket->number . ' is Paid');
        }

        return redirect()->back()->withErrors($validator->errors())->withInput()->with('error', 'Please Verify Card Information!');
    }
}
