@extends('layouts.app') <!-- 必要に応じてlayoutsファイルを利用 -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register Team') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teams.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">{{ __('Team Name') }}</label>
                            <input id="name" type="text" class="form-control" name="name" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ __('Register') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection