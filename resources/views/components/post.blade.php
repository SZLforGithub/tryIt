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

            <div id="LikeCount{{$post->id}}">
                @if ($post->get_all_likes_count != 0)
                    <i class="fab fa-gratipay fa-lg" style="color:red; margin-right:6px;"></i><span>{{$post->get_all_likes_count}}</span>
                @endif
            </div>

        </div>
        <div class="card-footer bg-transparent">
            <div class="row justify-content-center">

                @if ($post->like(Auth::user()->id)->exists())
                    <div class="col text-center likeAndComment" id="like" onclick="unlike(this)" name="{{$post->id}}">
                        <i class="fas fa-heart" style="color:red; margin-right:6px;"></i>讚
                    </div>
                @else
                    <div class="col text-center likeAndComment" id="like" onclick="like(this)" name="{{$post->id}}">
                        <i class="far fa-heart" style="margin-right:6px;"></i>讚
                    </div>
                @endif

                <div class="col text-center likeAndComment">
                    <i class="far fa-comment-alt" style="margin-right:6px;"></i>留言
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function sureDelete(object) {
            swal({
                text: "刪除後將無法復原，確認要刪除此貼文？",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    swal("這則貼文就像所有你愛的一樣，永遠離你而去了。", {
                        icon: "success",
                    })
                    .then((value) => {
                        let getId = $(object).attr('id');
                        $.ajax({
                            type: "POST",
                            url: '{{ url("ajax/delete") }}',
                            data: { id: getId },
                            dataType: 'json',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                            success: function(json){
                                window.location.reload();
                            },
                            error: function(xhr, ajaxOptions, thrownError){
                                alert(xhr.responseText);
                            }
                        })
                    });
                } else {
                    swal("它值得。");
                }
            });
        };

    function like(object) {
        let getId = $(object).attr('name');

        let howManyLike = parseInt($("#LikeCount"+getId).children('i').siblings().html());
        if ($("#LikeCount"+getId).children('i').length == 0) {
            $("#LikeCount"+getId).append(function() {
                return "<i class='fab fa-gratipay fa-lg' style='color:red; margin-right:6px;'></i><span>1</span>"
            });
        }

        else {
            $("#LikeCount"+getId).children('i').siblings().html(howManyLike+1);
        }

        $.ajax({
            type: "POST",
            url: '{{ url("ajax/like") }}',
            data: { id: getId },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(json){},
        })
        $(object).children().attr("class", "fas fa-heart");
        $(object).unbind('click');
        $(object).attr("onclick", "").click(eval(function(){unlike(object)}));
        $(object).children().attr("style", "margin-right: 6px; color: red;");
    }

    function unlike(object) {
        let getId = $(object).attr('name');

        let howManyLike = parseInt($("#LikeCount"+getId).children('i').siblings().html());
        if (howManyLike == 1) {
            $("#LikeCount"+getId).children('i').siblings().remove();
            $("#LikeCount"+getId).children('i').remove();
        }
        else {
            $("#LikeCount"+getId).children('i').siblings().html(howManyLike-1);
        }
        $.ajax({
            type: "POST",
            url: '{{ url("ajax/unlike") }}',
            data: { id: getId },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(json){},
        })
        $(object).children().attr("class", "far fa-heart");
        $(object).unbind('click');
        $(object).attr("onclick", "").click(eval(function(){like(object)}));
        $(object).children().attr("style", "margin-right: 6px;");
    }
</script>