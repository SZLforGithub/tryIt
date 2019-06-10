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

                <h1>{{$user->name}}</h1>
            </div>

            <div class="row text-center">
                @if ($user->name != Auth::user()->name && $areYouFriend == 'N')
                    <div class="col-md">
                        <button id="buttonOfAddFriend" type="button" onclick="addFriend(this)" name="{{$user->id}}" style="border-radius:10%; margin-bottom:10px;" class="btn btn-outline-secondary btn-sm"><i id="imgOfAddFriend" style="margin: 5px" class="fas fa-user-plus fa-2x"></i></button>
                    </div>
                @endif

                @if ($user->name != Auth::user()->name && $areYouFriend == 'add')
                    <div class="col-md">
                        <button type="button" onclick="" name="{{$user->id}}" style="border-radius:10%; margin-bottom:10px;" class="btn btn-outline-primary btn-sm"><i id="imgOfSend" style="margin: 5px" class="fas fa-sign-out-alt fa-2x"></i></button>
                    </div>
                @endif
                
                @if ($user->name != Auth::user()->name)
                    <div class="col-md">
                        <button type="button" style="border-radius:25%; margin-bottom:10px;" class="btn btn-outline-secondary btn-sm"><i style="margin: 5px" class="fas fa-comment-dots fa-2x"></i></button>
                    </div>
                @endif
            </div>
            
            @if ($user->name == Auth::user()->name)
                <!-- TODO answer add friend -->
                @foreach ($anyAddFriend as $someoneAddYou)
                    <div class="alert {{ $someoneAddYou->name }} alert-success fade show" role="alert">
                        <div class="row">
                            <div class="col">
                                @if ($someoneAddYou->smallSource != null)
                                    <a href="{{ route('stories', ['whoYouAre' => $someoneAddYou->name]) }}"><img class="smallSource" src="{{ asset($someoneAddYou->smallSource) }}" /></a>
                                @endif
                                <b><a href="{{ route('stories', ['whoYouAre' => $someoneAddYou->name]) }}">{{ $someoneAddYou->name }}</a></b>向您提出了好友邀請
                            </div>
                            <div class="ml-auto">
                                <button type="button" onclick="agreeAddFriend(this)" class="btn btn-primary {{ $someoneAddYou->name }}" name="{{ $someoneAddYou->userId1 }}">好啊</button>
                                <button type="button" onclick="rejectAddFriend(this)" class="btn btn-danger {{ $someoneAddYou->name }}" name="{{ $someoneAddYou->userId1 }}">滾</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @include('components/post')

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
        
        

        function agreeAddFriend(object) {
            $(object).alert('close');
            let getId = $(object).attr('name');

            $.ajax({
                type: "POST",
                url: '{{ url("ajax/agreeAddFriend") }}',
                data: { id: getId },
                dataType: 'text',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(json){
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText);
                }
            })

            swal({
                title: "世間所有的相遇，都是久別重逢", 
                icon: "success"
            });
        }

        function rejectAddFriend(object) {
            $(object).alert('close');
            let getId = $(object).attr('name');

            $.ajax({
                type: "POST",
                url: '{{ url("ajax/rejectAddFriend") }}',
                data: { id: getId },
                dataType: 'text',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(json){
                },
                error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.responseText);
                }
            })

            swal({
                title: "囂張屁",
                icon: "error"
            })
        }

        function synchronizeText(object) {
            var content = $(object).val();
            content = content.replace(/\u200B/g, "");
            content = content.replace(/\n/g, "<br \/>");
            $('#forAutoResize').html(content);
            autosize($('#postEdit'));
        }

        function addFriend(object) {
            $.ajax({
                type: "POST",
                url: '{{ url("ajax/addFriend") }}',
                data: { userId1: "{{Auth::user()->id}}", userId2: $(object).attr("name") },
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(json) {

                },

                error: function(xhr, ajaxOptions, thrownError){
                                alert(xhr.responseText);
                }
            })
            $("#imgOfAddFriend").attr("class", "fas fa-sign-out-alt fa-2x");
            $("#buttonOfAddFriend").attr("class", "btn btn-outline-primary btn-sm");
            $("#buttonOfAddFriend").removeAttr("onclick");
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