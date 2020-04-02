@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="alert mt-4">
            @include('alerts')
        </div>
        <div class="row justify-content-center py-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h1 class="h3 mb-0">{{ __('New Parking Spot') }}</h1></div>

                    <div class="card-body">
                        <h2 class="h4 card-title">{{ __('Parking Price') }}</h2>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('Time') }}</th>
                                            <th scope="col">{{ __('Price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('1hr') }}</td>
                                            <td>{{ __('$3') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('3hr') }}</td>
                                            <td>{{ __('$4.5') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('6hr') }}</td>
                                            <td>{{ __('$6.75') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('All Day (24h)') }}</td>
                                            <td>{{ __('$10.15') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <h2 class="h5 card-title mb-5">{{ __('Current Time: ') }} {{ $time->format('H:i') }}</h2>
                        <h2 class="h5 card-title mb-5">{{ __('Parking Spots Available: ') }} {{ $count }}</h2>
                        <form method="POST" action="{{ route('tickets.add') }}"
                              enctype="multipart/form-data">
                            @csrf

                            {{--                            <div class="form-group row">--}}
                            {{--                                <label for="plate"--}}
                            {{--                                       class="col-md-3 col-form-label text-md-left">{{ __('License Plate') }}</label>--}}
                            {{--                                <div class="col-md-9">--}}
                            {{--                                    <input id="plate" type="text"--}}
                            {{--                                           class="form-control{{ $errors->has('plate') ? ' is-invalid' : '' }}"--}}
                            {{--                                           name="plate" value="{{ old('plate') }}" required autofocus>--}}

                            {{--                                    @if($errors->has('plate'))--}}
                            {{--                                        <span class="invalid-feedback" role="alert">--}}
                            {{--                                            <strong>{{ $errors->first('plate') }}</strong>--}}
                            {{--                                        </span>--}}
                            {{--                                    @endif--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}

                            <div class="form-group row mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-lg btn-primary" {{ $count === 0 ? 'disabled' : '' }}>
                                        {{ $count === 0 ? __('Lot Full') : __('Issue Ticket') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
