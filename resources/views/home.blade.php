@extends('layouts.app')

@section('content')
<div class="container">
<!-- Modal -->
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
                        <pre><span id="forAutoResize"></span></pre>
                        <textarea rows="10" id="postEdit" class="postEdit" name="content" onkeyup="synchronizeText(this)"></textarea>
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
 <!-- Modal End -->

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">建立貼文</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @if ($smallSource != null)
                        <div class="col-md-1">
                            <img id="smallSource" src="{{ asset($smallSource) }}" />
                        </div>
                        @endif
                        <div class="col-md">
                            <form action="{{ route("create") }}" method="post">
                                @csrf
                                <textarea id="post" name="content" class="form-control" rows="3" onclick="changePlaceholder(this)" onblur="changeBack(this)" placeholder="傳說古時候，心裡藏著祕密的人，會跑到樹林裡找一個樹洞，對著樹洞說出秘密，然後用泥土將樹洞填上。"></textarea>
                                <div class="row justify-content-end"><button type="submit" class="btn btn-primary">發佈</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($posts as $post)
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
                    </div>
                </div>
            @endforeach
        </div> 
    </div>
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
                        location.replace(getId.concat("/delete"));
                    });
                } else {
                    swal("它值得。");
                }
            });
        };

        function changePlaceholder(object) {
            $(object).attr('placeholder', '{{Auth::user()->name}}，你也有祕密嗎？');
        };
        function changeBack(object) {
            $(object).attr('placeholder', '傳說古時候，心裡藏著祕密的人，會跑到樹林裡找一個樹洞，對著樹洞說出秘密，然後用泥土將樹洞填上。');
        };

        $(function() {
            $('#modalForEditPost').on('shown.bs.modal', function (event){
                var button = $(event.relatedTarget);
                var recipient = button.data('postcontent');
                var idForPost = button.data('postid');
                $('#formForPostEdit').attr('action', function(){
                    var a = 'post';
                    var b = a.concat(idForPost);
                    var c = b.concat('/edit');
                    return c;
                });
                $('#forAutoResize').html(recipient);
                recipient = recipient.replace(/<br \/>/g, "\u200B");
                var modal = $(this);
                modal.find('.postEdit').val(recipient);
                autosize.update($('#postEdit'));
                $('#postEdit').focus();
            })
        });

        function synchronizeText(object) {
            var content = $(object).val();
            content = content.replace(/\u200B/g, "");
            content = content.replace(/\n/g, "<br \/>");
            $('#forAutoResize').html(content);
            autosize($('#postEdit'));
        }

        $(function(){
            $("#submitPostEdit").click(function(){
                $("#submitForPostEdit").trigger("click");
            })
        })

    </script>
</div>
@endsection