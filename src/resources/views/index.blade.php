@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">サッカーマイページ</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('matches.store') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="date" class="col-md-4 col-form-label text-md-right">試合日付</label>

                            <div class="col-md-6">
                                <input id="date" type="date" class="form-control" name="date" required autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="opponent" class="col-md-4 col-form-label text-md-right">対戦相手</label>

                            <div class="col-md-6">
                                <input id="opponent" type="text" class="form-control" name="opponent" required>
                            </div>
                        </div>

                        <!-- 試合結果とポジションの入力欄（最大4試合分） -->
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="form-group row">
                                <label for="result{{$i}}" class="col-md-4 col-form-label text-md-right">試合{{$i}}の結果</label>
                                <div class="col-md-6">
                                    <input id="result{{$i}}" type="text" class="form-control" name="results[]" required>
                                </div>
                                <label for="position{{$i}}" class="col-md-4 col-form-label text-md-right">試合{{$i}}のポジション</label>
                                <div class="col-md-6">
                                    <input id="position{{$i}}" type="text" class="form-control" name="positions[]" required>
                                </div>
                            </div>
                        @endfor

                        <!-- 他のフォーム要素を追加 -->

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    試合を記録する
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

