<!-- Modal For Edit Post-->
<div class="modal fade" id="modalForEditPost" tabindex="-1" role="dialog" aria-labelledby="modalForEditPostLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalForEditPostLabel">編輯貼文</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="formForPostEdit" method="post" action="" class="was-validated">
                @csrf
                <div class="containerOfTextarea">
                    <pre><span id="forAutoResize" style="word-break: break-word;"></span></pre>
                    <textarea id="postEdit" class="postEdit" name="content" onkeyup="synchronizeText(this)"></textarea>
                </div>
                <div style="display:none"><button id="submitForPostEdit" type="submit"></button></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="submitPostEdit">Save changes</button>
        </div>
        </div>
    </div>
</div>

<!-- Modal For WhoLikes-->
<div class="modal fade" id="modalForWhoLikes" tabindex="-1" role="dialog" aria-labelledby="modalForWhoLikesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">按讚的人</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush listForWhoLikes">
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal For updateComment-->
<div class="modal fade" id="modalForUpdateComment" tabindex="-1" role="dialog" aria-labelledby="modalForUpdateCommentLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">留言</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formForUpdateComment" method="post" action="" class="was-validated">
                    @csrf
                    <div class="containerOfTextarea">
                        <pre><span id="forUpdateCommentAutoResize" style="word-break: break-word;">thisForPresetHeight</span></pre>
                        <textarea id="updateComment" class="postEdit" name="updateComment" onkeyup="autosizeForUpdateComment(this)" placeholder="尊重、包容、友善。" required="required"></textarea>
                    </div>
                    <div style="display:none"><button id="submitForUpdateComment" type="submit"></button></div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                <button type="button" class="btn btn-primary" id="submitUpdateComment">送出</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal For Comments-->
<div class="modal fade" id="modalForComments" tabindex="-1" role="dialog" aria-labelledby="modalForWhoLikesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush listForComments">
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal End -->

@if (isset($posts))
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
                                <button id="editForPost" class="dropdown-item" style="cursor:pointer" data-toggle="modal" data-target="#modalForEditPost" data-postcontent="{{$post->content}}" data-postid="{{$post->id}}">編輯貼文</button>
                                
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

                <div class="LikeCount" id="LikeCount{{$post->id}}" name="{{$post->id}}" style="display:inline;">
                    @if ($post->get_all_likes_count != 0)
                        <button id="whoLikes" type="button" class="btn btn-light" data-toggle="modal" data-target="#modalForWhoLikes" data-postid="{{$post->id}}"><i class="fab fa-gratipay fa-lg" style="color:red; margin-right:6px;"></i><span>{{$post->get_all_likes_count}}</span></button>
                    @endif
                </div>

                <div class="CommentCount" id="CommentCount{{$post->id}}" name="{{$post->id}}" style="display:inline;">
                    @if ($post->get_all_comments_count != 0)
                        <button id="comments" type="button" class="btn btn-light" onclick="forWhoComments(this)" name="{{$post->id}}"><i class="fas fa-comment-alt" style="color: #00BBFF   ;margin-right:6px;"></i><span>{{$post->get_all_comments_count}}</span></button>
                    @endif
                </div>

            </div>
            <div class="card-footer bg-transparent">
                <div class="row justify-content-center">

                    @if ($post->isThisUserLike(Auth::user()->id)->exists())
                        <div class="col text-center likeAndComment" id="like" onclick="unlike(this)" name="{{$post->id}}">
                            <i class="fas fa-heart" style="color:red; margin-right:6px;"></i>讚
                        </div>
                    @else
                        <div class="col text-center likeAndComment" id="like" onclick="like(this)" name="{{$post->id}}">
                            <i class="far fa-heart" style="margin-right:6px;"></i>讚
                        </div>
                    @endif

                    <div class="col text-center likeAndComment" id="comment" onclick="comment(this)" name="{{$post->id}}">
                        <i class="far fa-comment-alt" style="margin-right:6px;"></i>留言
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{ $posts->links() }}
@endif

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
    }

    $(function() {
        $('#modalForEditPost').on('shown.bs.modal', function (event){
            var button = $(event.relatedTarget);
            var recipient = button.data('postcontent');
            var idForPost = button.data('postid');
            $('#formForPostEdit').attr('action', function(){
                if (location.pathname == '/tryIt/public/home') {
                    var a = 'post';
                    var b = a.concat(idForPost);
                    var c = b.concat('/edit');
                    return c;
                }
                else {
                    var a = '../post';
                    var b = a.concat(idForPost);
                    var c = b.concat('/edit');
                    return c;
                }
            });
            $('#forAutoResize').html(recipient);
            recipient = recipient.replace(/<br \/>/g, "\u200B");
            var modal = $(this);
            modal.find('.postEdit').val(recipient);
            autosize.update($('#postEdit'));
            $('#postEdit').focus();
        })

        $('#modalForWhoLikes').on('shown.bs.modal', function (event){
            $(".listForWhoLikes").empty();
            var button = $(event.relatedTarget);
            var getId = button.data('postid');
            $.ajax({
                type: "POST",
                url: '{{ url("ajax/whoLikes") }}',
                data: { id: getId },
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(json){
                    for (var index in json.peopleWhoLikeThisPost) {                     
                        var tempForName = (json.peopleWhoLikeThisPost[index].name);
                        var tempForShot = (json.peopleWhoLikeThisPost[index].smallSource);

                        $(".listForWhoLikes").append(function (index) {
                            if (tempForShot != null) {
                                return "<li id='" + tempForName + "' class='list-group-item WhoLikesOrComment' onclick='gotoThisPersonStory(this)'>" + "<img src='" + tempForShot + "' class='smallSource' style='margin-right:6px;'>" + tempForName + "</li>";
                            }
                            else {
                                return "<li id='" + tempForName + "' class='list-group-item WhoLikesOrComment' onclick='gotoThisPersonStory(this)'>" + tempForName + "</li>";
                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText);
                }
            })
        })
    });

    function forWhoComments(object) {
        $(".listForComments").empty();
        var getId = $(object).attr('name');
        $.ajax({
            type: "POST",
            url: '{{ url("ajax/comment/get") }}',
            data: { id: getId },
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(json) {
                for (var index in json.comments) {
                    var tempForCommentName = (json.comments[index].name);
                    var tempForCommentShot = (json.comments[index].smallSource);
                    var tempForCommentContent = (json.comments[index].content);
                    $(".listForComments").append(function (index) {
                        if (tempForCommentShot != null) {
                            return "<li class='list-group-item'>" + "<div class='row'><div id='" + tempForCommentName + "'class='col-4 WhoLikesOrComment' onclick='gotoThisPersonStory(this)'><img src='" + tempForCommentShot + "' class='smallSource' style='margin-right:6px;'>" + tempForCommentName + "</div><div class='col-8' style='word-wrap: break-word; word-break: break-word;'>" + tempForCommentContent + "</div></div></li>";
                        }
                        else {
                            return "<li class='list-group-item'>" + "<div class='row'><div id='" + tempForCommentName + "'class='col-4 WhoLikesOrComment' onclick='gotoThisPersonStory(this)'>" + tempForCommentName + "</div><div class='col-8' style='word-wrap: break-word; word-break: break-word;'>" + tempForCommentContent + "</div></div></li>";
                        }
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.responseText);
            }
        })
        $('#modalForComments').modal('show');
    }

    function synchronizeText(object) {
        var content = $(object).val();
        content = content.replace(/\u200B/g, "");
        content = content.replace(/\n/g, "<br \/>");
        $('#forAutoResize').html(content);
        autosize($('#postEdit'));
    }

    function autosizeForUpdateComment(object) {
        var content = $(object).val();
        content = content.replace(/\u200B/g, "");
        content = content.replace(/\n/g, "<br \/>");
        $('#forUpdateCommentAutoResize').html(content);
        autosize($('#updateComment'));
    }

    function gotoThisPersonStory(object) {
        if (location.pathname == '/tryIt/public/home') {
            var URL1 = 'stories/';
            var URL2 = $(object).attr('id');
            var URL = URL1.concat(URL2);
            location.href = URL;
        }
        else {
            location.href = $(object).attr('id');
        }
    }

    $(function() {
        $("#submitPostEdit").click(function(){
            $("#submitForPostEdit").trigger("click");
        })

        $("#submitUpdateComment").click(function(){
            $("#submitForUpdateComment").trigger("click");
        })
    });

    function like(object) {
        let getId = $(object).attr('name');

        let howManyLike = parseInt($("#LikeCount"+getId).children('button').children('i').siblings().html());
        if ($("#LikeCount"+getId).children('button').children('i').length == 0) {
            $("#LikeCount"+getId).append(function() {
                return "<button id='whoLikes' type='button' class='btn btn-light' data-toggle='modal' data-target='#modalForWhoLikes' data-postid='" + getId + "'><i class='fab fa-gratipay fa-lg' style='color:red; margin-right:6px;'></i><span>1</span>"
            });
        }

        else {
            $("#LikeCount"+getId).children('button').children('i').siblings().html(howManyLike+1);
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

        let howManyLike = parseInt($("#LikeCount"+getId).children('button').children('i').siblings().html());
        if (howManyLike == 1) {
            $("#LikeCount"+getId).children('button').remove();
        }
        else {
            $("#LikeCount"+getId).children('button').children('i').siblings().html(howManyLike-1);
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

    function comment(object) {
        var getId = $(object).attr('name');
        $('#modalForUpdateComment').modal('show');
        $('#modalForUpdateComment').on('shown.bs.modal', function (event) {
            $('#formForUpdateComment').attr('action', function () {
                if (location.pathname == '/tryIt/public/home') {
                    var a = 'comment';
                    var b = a.concat('/post');
                    var c = b.concat(getId);
                    return c;
                }
                else {
                    var a = '../comment';
                    var b = a.concat('/post');
                    var c = b.concat(getId);
                    return c;
                }
            });
            autosize.update($('#updateComment'));
            $('#updateComment').focus();
        })
    }
</script>