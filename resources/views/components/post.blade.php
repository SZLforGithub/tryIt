@foreach ($posts as $post)
    <div class="card mb-3">
        <div class="card-body">
            <div class="media">
                @if ($post->shot_path != null)
                    <a href="{{ route('stories', ['whoYouAre' => $post->name]) }}"><img class="smallSource" src="{{ asset($post->smallSource) }}" /></a>
                @endif
                <div class="media-body ml-1">
                    <a href="{{ route('stories', ['whoYouAre' => $post->name]) }}">{{ $post->name }}</a> <br>
                    <span style="font-size: .8rem; color: #616770">{{ date("Y年m月d日 h:ia", strtotime($post->created_at)) }}</span>
                </div>
                @if ($post->name == Auth::user()->name)
                    <div class="dropdown">
                        <button type="button" class="btn btn-light dropdown-toggle" id="dropForSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                        <div class="dropdown-menu" aria-labelledby="dropForSetting">
                            <button id="editForPost" class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalForEditPost" data-postcontent="{{$post->content}}"  data-postid="{{$post->id}}">編輯貼文</button>
                            
                                <a class="dropdown-item" style="cursor:pointer" id="{{$post->id}}" onclick="sureDelete(this)">刪除</a>
                        </div>
                    </div>
                @endif
            </div>

            <p class="mt-1">{!! $post->content !!}</p>
            @for ($i=0; $i<count($photos); $i++)
                @if ($post->id === ($photos[$i]->postId))
                    <!-- Slider main container -->
                    <div class="swiper-container">
                        <div class="swiper-pagination swiper-pagination-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-wrapper">
                            <!-- Slides -->
                            @foreach ($photos as $photo)
                                @if ($post->id === ($photo->postId))
                                    <div class="swiper-slide"><img class="ImgInSwiper" src="{{asset($photo->path)}}"/></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @break
                @endif
            @endfor
        </div>
    </div>
@endforeach