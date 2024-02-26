@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-header">過去の試合結果</div>
                <div class="card-body">
                    <!-- 検索フォーム -->
                    <form action="{{ route('matches.search') }}" method="GET">
                        <div class="form-group row">
                            <label for="date" class="col-md-2 col-form-label text-md-right">日付:</label>
                            <div class="col-md-4">
                                <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                            </div>
                            <label for="opponent" class="col-md-2 col-form-label text-md-right">対戦相手:</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="opponent" name="opponent" value="{{ request('opponent') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">検索</button>
                    </form>
                    <!-- 検索フォームここまで -->
                    <hr>
                    @if ($matches->isEmpty())
                        <p>過去の試合結果はありません。</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">日付</th>
                                    <th scope="col">対戦相手</th>
                                    @for($i = 1; $i <= 4; $i++)
                                        <th scope="col">試合{{$i}}</th>
                                        <th scope="col">ポジション{{$i}}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($matches as $match)
                                    <tr>
                                        <td>{{ $match->date }}</td>
                                        <td>{{ $match->opponent }}</td>
                                        @for($i = 1; $i <= 4; $i++)
                                            <td>{{ $match["result{$i}"] ?? '' }}</td>
                                            <td>{{ $match["position{$i}"] ?? '' }}</td>
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
