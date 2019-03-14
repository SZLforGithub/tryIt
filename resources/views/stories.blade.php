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
            <div class="text-center">

                @if ($photoPath != null)
                    <img src="{{ asset($photoPath->editSource) }}" id="StoriesShot"/>
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
                                <span style="font-size: .8rem; color: #616770">{{ date("Y年m月d日 h:ia", strtotime($post['updated_at'])) }}</span>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-light dropdown-toggle" id="dropForSetting" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropForSetting">
                                    <button id="editForPost" class="dropdown-item" data-toggle="modal" data-target="#modalForEditPost" data-postcontent="{{$post['content']}}"  data-postid="{{$post['id']}}">編輯貼文</button>
                                    <a class="dropdown-item" style="cursor:pointer" id="post{{$post['id']}}" onclick="sureDelete(this)">刪除</a>
                                </div>
                            </div>
                        </div>

                        <p class="mt-1">{!! $post['content'] !!}</p>
                        @for ($i=0; $i<count($photos); $i++)
                            @if ($post['id'] === ($photos[$i]->postId))
                                <!-- Slider main container -->
                                <div class="swiper-container">
                                    <div class="swiper-pagination swiper-pagination-white"></div>
                                    <div class="swiper-button-prev swiper-button-white"></div>
                                    <div class="swiper-button-next swiper-button-white"></div>
                                    <div class="swiper-wrapper">
                                        <!-- Slides -->
                                        @foreach ($photos as $photo)
                                            @if ($post['id'] === ($photo->postId))
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
        </div>
    </div>
    <script>

        $(function() {
            autosize($('#post'));
        }); 

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

        function synchronizeText(object) {
            var content = $(object).val();
            content = content.replace(/\u200B/g, "");
            content = content.replace(/\n/g, "<br \/>");
            $('#forAutoResize').html(content);
            autosize($('#postEdit'));
        }

        $(document).ready(function() {
            var mySwiper = new Swiper ('.swiper-container', {
                autoHeight: true,
                effect: 'coverflow',
                grabCursor: true,
                coverflowEffect: {
                    rotate: 50,
                    stretch: 0,
                    depth: 100,
                    modifier: 1,
                    slideShadows : true,
                },

                pagination: {
                    el: '.swiper-pagination',
                },

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            })
        })
    </script>
</div>
@endsection