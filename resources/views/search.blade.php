@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card text-center">
        <div class="card-header">
            搜尋結果
        </div>
        <ul class="list-group list-group-flush">
            @forelse ($searchs as $search)
                <li class="list-group-item">
                    @if ($search->smallSource != null)
                        <a href="{{ route('stories', ['whoYouAre' => $search->name]) }}"><img class="smallSource" src="{{ asset($search->smallSource) }}" /></a><br>
                    @endif
                    <a href="{{ route('stories', ['whoYouAre' => $search->name]) }}">{{ $search->name }}</a>
                </li>
                @empty
                <li class="list-group-item">
                    <span style="color: #AAAAAA;">這裡跟你的錢包一樣，什麼也沒有</span>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection