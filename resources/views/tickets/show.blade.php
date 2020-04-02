@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="alert mt-4">
            @include('alerts')
        </div>
        <div class="row justify-content-center py-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h1 class="h3 mb-0">{{ $ticket->paid ? __('Ticket Receipt') : __('View Ticket') }}</h1></div>

                    <div class="card-body">
                        <h2 class="h5 card-title mb-5">{{ __('Ticket Number: ') }} {{ $ticket->number }}</h2>
                        <h2 class="h4 card-title">{{ __('Total Price') }}</h2>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <td>{{ __('Initial Time') }}</td>
                                            <td>{{ $ticket->created_at->format('d/m H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ $ticket->paid ? __('End Time') : __('Current Time') }}</td>
                                            <td>{{ $ticket->paid ? date('d/m H:i', strtotime($ticket->end_time)) : $time->format('d/m H:i')  }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Total Time') }}</td>
                                            <td>{{ $total_time }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Total Cost') }}</td>
                                            <td>{{ __('$') }}{{ number_format((float)$cost, 2, '.', '') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Payment Status') }}</td>
                                            <td>{{ $ticket->paid ? __('Paid') : __('Not Paid') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if (!$ticket->paid)
                            <h2 class="h5 card-title mb-5">{{ __('Make Payment') }}</h2>
                            <form method="POST" action="{{ route('tickets.pay', $ticket->number) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <label for="card"
                                           class="col-md-3 col-form-label text-md-left">{{ __('Credit Card Number') }}</label>
                                    <div class="col-md-9">
                                        <input id="card" type="text"
                                               class="form-control{{ $errors->has('card') ? ' is-invalid' : '' }}"
                                               name="card" value="{{ old('card') }}" required autofocus>

                                        @if($errors->has('card'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('card') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-lg btn-primary">
                                            {{ __('Make Payment') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
