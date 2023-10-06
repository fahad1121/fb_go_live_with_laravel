@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                        @if(auth::user()->facebook_provider_id)
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end"></label>
                                <div class="col-md-6">
                                    <a class="btn btn-primary" href="{{ url('upload/live-streaming/facebook-video') }}"> Upload video to facebook for live streaming</a>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('getRandomUserFromSheet') }}" method="POST">
                                @csrf
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="inputEmail4">Game Name</label>
                                                <input type="text" name="game_name" class="form-control" placeholder="Enter game name.">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="inputPassword4">Date</label>
                                                <input type="date" name="game_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group mt-4">
                                                <button type="submit" class="btn btn-primary">Get Winner</button>
                                            </div>
                                        </div>
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
