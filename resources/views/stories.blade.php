@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center" style="margin:0px auto;" id="containerOfStoriesShot">

                @if ($photoPath != null)
                    <img src="{{ asset($photoPath->editSource) }}" style="width:100%; height:100%; border-radius:50%;"/>
                @endif

                <h1>{{Auth::user()->name}}</h1>
            </div>
            @foreach (array_reverse($posts) as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="media">
                            @if ($smallSource != null)
                                <img id="smallSource" src="{{ asset($smallSource) }}" />
                            @endif
                            <div class="media-body ml-1">
                                {{ Auth::user()->name }} <br>
                                <span style="font-size: .8rem; color: #616770">{{ date("Y年m月d日 h:ia", strtotime($post->updated_at)) }}</span>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-light dropdown-toggle" id="dropForSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropForSetting">
                                    <button id="editForPost" class="dropdown-item" data-toggle="modal" data-target="#modalForEditPost" data-postcontent="{{$post->content}}"  data-postid="{{$post->id}}">編輯貼文</button>
                                    <a class="dropdown-item" style="cursor:pointer" id="post{{$post->id}}" onclick="sureDelete(this)">刪除</a>
                                </div>
                            </div>
                        </div>
                        <p class="mt-1">{!! $post->content !!}</p>
                        @if ($post->path!=null)
                            <div style="height: 100%; width: 100%;"><img style="height: 100%; width: 100%;" src="{{asset($post->path)}}"></div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection