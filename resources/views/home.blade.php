@extends('layouts.app')
test
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-dark mb-3">
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
                            <img class="smallSource" src="{{ asset($smallSource) }}" />
                        </div>
                        @endif
                        <div class="col-md">
                            <form action="{{ route("create") }}" name="formForPost" method="post" enctype="multipart/form-data">
                                @csrf
                                <textarea id="post" name="content" class="form-control" rows="3" onclick="changePlaceholder(this)" onblur="changeBack(this)" placeholder="傳說古時候，心裡藏著秘密的人，會跑到樹林裡找一個樹洞，對著樹洞說出秘密，然後用泥土將樹洞填上。" required="required"></textarea>
                                <button id="buttonPhotoForPost" type="button" style="border-radius:10px; margin-bottom:10px;" class="btn btn-outline-secondary btn-sm"><img src="https://img.icons8.com/doodle/30/000000/picture.png"><!--<i class="fas fa-image"></i>-->上傳圖片</button>
                                <br>
                                <input id="photoForPost" name="photoForPost[]" style="display:none;" type="file" onchange="uploadPhoto(this)" onclick="clearPhoto()" accept="image/gif, image/jpeg, image/jpg, image/png" multiple/>
                                
                                <div class="row justify-content-end" id="containerOfSubmitPost"><button type="submit" class="btn btn-primary">發佈</button></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

			@include('components/post')
            
        </div> 
    </div>
    <script>

        $(function() {
            autosize($('#post'));
        });

        function changePlaceholder(object) {
            $(object).attr('placeholder', '{{Auth::user()->name}}，你也有秘密嗎？');
        };
        function changeBack(object) {
            $(object).attr('placeholder', '傳說古時候，心裡藏著秘密的人，會跑到樹林裡找一個樹洞，對著樹洞說出秘密，然後用泥土將樹洞填上。');
        };

        $(function(){
            $("#buttonPhotoForPost").click(function(){
                $("#photoForPost").trigger("click");
            })
        })

        function uploadPhoto(input) {
            if (input.files && input.files[0]) {
                for(var i=0; i<input.files.length; i++){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<div class='containerOfPhotoPreview'><div class='photoPreview' style='background:url(" + e.target.result + ")'></div></div>").insertBefore('#containerOfSubmitPost');
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };

        function clearPhoto() {
            $('.containerOfPhotoPreview').remove();
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