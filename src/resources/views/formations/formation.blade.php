@extends('layouts.app') <!-- 必要に応じてlayoutsファイルを利用 -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register Formation') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('teams.storeFormation') }}">
                        @csrf

                        <div class="form-group">
                            <label for="formation">{{ __('Formation') }}</label>
                            <input id="formation" type="text" class="form-control" name="formation" required>
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
