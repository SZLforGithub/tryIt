@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Modal For Name -->
    <div class="modal fade" id="modalForEditName" tabindex="-1" role="dialog" aria-labelledby="modalForEditNameTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForEditNameTitle">修改姓名</h5>
                </div>
                <div class="modal-body">
                    <form class="was-validated"  action="{{ route("edit") }}" method="post" required>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">姓名</label>
                            <input type="text" class="form-control" name="name" required>
                            <input type="hidden" name="whatThisInput" value="name">
                        </div>
                        <div style="display:none"><button id="submitForEditName" type="submit"></button></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="button" id="submitName" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Modal For Email -->
    <div class="modal fade" id="modalForEditEmail" tabindex="-1" role="dialog" aria-labelledby="modalForEditEmailTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForEditEmailTitle">修改電子信箱</h5>
                </div>
                <div class="modal-body">
                    <form class="was-validated"  action="{{ route("edit") }}" method="post" required>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">電子信箱</label>
                            <input type="text" class="form-control" name="email" required>
                            <input type="hidden" name="whatThisInput" value="email">
                        </div>
                        <div style="display:none"><button id="submitForEditEmail" type="submit"></button></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="button" id="submitEmail" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Modal For Shot -->
    <div class="modal fade" id="modalForUpdateShot" tabindex="-1" role="dialog" aria-labelledby="modalForUpdateShotTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" sytle="overflow: hidden;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForUpdateShotTitle">上傳大頭貼</h5>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route("uploadShot") }}" id="formForShotUpload" name="formForShotUpload">
                        {{ csrf_field() }}
                        <input id="shot" name="shot" type="file" onchange="uploadShot(this)" accept="image/gif, image/jpeg, image/jpg, image/png" />
                        <div style="display:none"><button id="submitForShot" type="submit"></button></div> 
                    </form>
                    <div id="containerOfShotPreview"><img id="shotPreview" src="" style="margin:auto;" /></div>
                    <!--<label>X: <input type="text" size="4" id="x" name="width"></label>
                    <label>Y: <input type="text" size="4" id="y" name="width"></label>
                    <label>Width: <input type="text" size="4" id="w" name="width"></label>
                    <label>Height: <input type="text" size="4" id="h" name="height"></label>-->   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="button" id="submitShot" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">關於你</div>
                <div class="card-body">
                    <div class="row hover">
                        <div class="col-4">姓名</div>
                        <div class="col-5">{{ Auth::user()->name }}</div>
                        <div class="col-3 edit">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalForEditName"><i class="fas fa-pencil-alt"></i>編輯</button>
                        </div>
                    </div>

                    <hr/>

                    <div class="row hover">
                        <div class="col-4">電子信箱</div>
                        <div class="col-5">{{ Auth::user()->email }}</div>
                        <div class="col-3 edit">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalForEditEmail"><i class="fas fa-pencil-alt"></i>編輯</button>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <label class="col-4 ">{{ __('上傳大頭貼') }}</label>
                        <div class="col">
                            <button type="button" class="btn btn-outline-secondary btn-block" data-toggle="modal" data-target="#modalForUpdateShot"><i class="fas fa-plus"></i></button>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <label for="shot_cut" class="col-4">{{ __('編輯大頭貼') }}</label>

                        <div class="col text-center" id="containerOfShotOutSide">
                            <img id="cutShotPreview" src="shot_default.jpeg" style="margin:auto;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $("#submitName").click(function(){
                $("#submitForEditName").trigger('click');
            });
        })

        $(function(){
            $("#submitEmail").click(function(){
                $("#submitForEditEmail").trigger('click');
            });
        })
        
        //preview for shot
        var jcropApi;
        function uploadShot(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#containerOfShotPreview #shotPreview').remove();
                    $('#containerOfShotPreview').html('<img id="shotPreview"></img>')
                    $("#shotPreview").attr('src', e.target.result);
                
                   /* $('#shotPreview').Jcrop({
                        onChange: showCoords,
                        onSelect: showCoords
                    }, function() {
                        jcropApi = this;
                        jcropApi.animateTo([100, 100 ,400, 300]);
                    });*/
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        /*function showCoords(c){
            $('#x').val(c.x);
            $('#y').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
        }*/

        $(function(){
            $("#submitShot").click(function(){
                $("#submitForShot").trigger("click");
                /*var shot = $('#shot').get(0).files[0];
                var x = $('#x').val();
                var y = $('#y').val();
                var width = $('#w').val();
                var height = $('#h').val();
                $.ajax({
                    type: "POST",
                    url: "uploadShot",
                    data: {
                        shot: $('#shot').val(),
                        x: $('#x').val(),
                        y: $('#y').val(),
                        width: $('#width').val(),
                        height: $('#height').val(),
                    },
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(json){
                        $('#containerOfShotOutSide #cutShotPreview').remove();
                        $('#containerOfShotOutSide').html('<img id="cutShotPreview"></img>')
                        $("#cutShotPreview").attr('src', json.url);
                    },
                    error: function(){
                        alert('WTF');
                    }
                })*/
            });
        })
    </script>
</div>
@endsection
