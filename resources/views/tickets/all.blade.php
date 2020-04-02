@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="col-md-10">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">{{ __('Ticket Number') }}</th>
                            <th scope="col">{{ __('Initial Time') }}</th>
                            <th scope="col">{{ __('End Time') }}</th>
                            <th scope="col">{{ __('Paid') }}</th>
                            <th scope="col">{{ __('Price') }}</th>
                            <th scope="col">{{ __('Parking Spot No') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr class="text-center">
                                <td class="align-middle">{{ $ticket->number }}</td>
                                <td class="align-middle">{{ $ticket->created_at->format('d/m H:i') }}</td>
                                <td class="align-middle">{{ $ticket->paid ?  date('d/m H:i', strtotime($ticket->end_time)) : ''  }}</td>
                                <td class="align-middle">
                                    <h5 class="mb-0"><span class="badge badge-{{ $ticket->paid ? 'success' : 'secondary' }}">{{ $ticket->paid ? __('Paid') : __('Not Paid') }}</span></h5>
                                </td>
                                <td class="align-middle">{{ $ticket->paid ? '$' . number_format((float)$ticket->cost, 2, '.', '') : '' }}</td>
                                <td class="align-middle">{{ $ticket->parking_space->id }}</td>
                                <td class="align-middle">
                                    <a class="btn btn-primary" href="{{ route('tickets.show', $ticket->number) }}" role="button">{{ $ticket->paid ? __('View Receipt') : __('Make Payment') }}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
