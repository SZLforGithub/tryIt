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
                    <form method="post" action="{{ route("uploadShot") }}" enctype="multipart/form-data" id="formForShotUpload" name="formForShotUpload">
                        {{ csrf_field() }}
                        <input id="shot" name="shot" type="file" onchange="uploadShot(this)" accept="image/gif, image/jpeg, image/jpg, image/png" />
                        <div style="display:none"><button id="submitForShot" type="submit"></button></div> 
                    </form>
                    <div class="containerOfShot" id="containerOfShotPreview"><img class="sizeOfShot" id="shotPreview" src="" /></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="button" id="submitShot" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Modal For Shot Edit -->
    <div class="modal fade" id="modalForEditShot" tabindex="-1" role="dialog" aria-labelledby="modalForEditEmailTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForEditShotTitle">編輯大頭貼</h5>
                </div>
                <div class="modal-body">
                    <div style="float:left;">
                        <img id="jcropShot" src="{{ asset(Auth::user()->shot_path) }}" />
                    </div>
                    <form method="post" action="{{ route("editShot") }}" id="coords" name="coords">
                        {{ csrf_field() }}
                        <input type="hidden" id="x1" name="x1"/>
                        <input type="hidden" id="y1" name="y1"/>
                        <input type="hidden" id="w" name="w"/>
                        <input type="hidden" id="h" name="h"/>
                        <div style="display:none"><button id="submitForEditShot" type="submit"></button></div>
                    </form>
                    <div id="preview-pane" style="float:left;">
                        <div class="preview-container">
                            <img src="{{ asset(Auth::user()->shot_path) }}" class="jcrop-preview" alt="Preview" id="Preview"/>
                        </div>
                    </div>
                    <div style="clear:both;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                    <button type="button" id="submitEditShot" class="btn btn-primary">Ok</button>
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
                        @if ( Auth::user()->shot_path != null )
                            <div class="col hover" id="containerOfShotOutSide">
                                <button type="button" class="edit btn btn-primary btn-sm" id="editForShot" data-toggle="modal" data-target="#modalForEditShot"><i class="fas fa-pencil-alt"></i>點選編輯</button>
                                <img id="cutShotPreview" src="{{ asset(Auth::user()->shot_path) }}" onerror="this.src='shot_default.jpeg'" />
                            </div>
                        @endif
                        
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
        });

        $(function(){
            $("#submitEmail").click(function(){
                $("#submitForEditEmail").trigger('click');
            });
        });
        $(function(){
            $("#cutShotPreview").click(function(){
                $("#editForShot").trigger('click');
            });
        });
        $(function(){
            $("#submitShot").click(function(){
                $("#submitForShot").trigger('click');
            });
        });
        $(function(){
            $("#submitEditShot").click(function(){
                $("#submitForEditShot").trigger("click");
            });
        });
        //preview for shot
        function uploadShot(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#containerOfShotPreview #shotPreview').remove();
                    $('#containerOfShotPreview').html('<img class="sizeOfShot" id="shotPreview"/>')
                    $("#shotPreview").attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        };

        

        //shot edit (Jcrop)
        var jcrop_api;
        var boundx;
        var boundy;

        var $preview = $('#preview-pane');
        var $pcnt = $('#preview-pane .preview-container');

        var xsize = $pcnt.width();
        var ysize = $pcnt.height();


        console.log('init', [xsize, ysize]);

        function initJcrop(){
            $('#jcropShot').Jcrop({
                boxWidth: 600,
                boxHeight: 600,
                onChange: updatePreview,
                onSelect: updatePreview,
                aspectRatio: xsize / ysize
            }, function(){
                var bounds = this.getBounds();
                boundx = bounds[0];
                boundy = bounds[1];
               
                jcrop_api = this;
                jcrop_api.animateTo([0, 0, 700, 700]);

                

            });
        };

        function updatePreview(c){
            $('#x1').val(c.x);
            $('#y1').val(c.y);
            $('#w').val(c.w);
            $('#h').val(c.h);
            if(parseInt(c.w) > 0){
                
                var rx = xsize / c.w;
                var ry = ysize / c.h;


                $('#preview-pane .preview-container img').css({
                    width: Math.round(rx * boundx) + 'px',
                    height: Math.round(ry * boundy) + 'px',
                    marginLeft: '-' + Math.round(rx * c.x) + 'px',
                    marginTop: '-' + Math.round(ry * c.y) + 'px'
                });
            }
        }

        window.onload = function () {
            initJcrop();
        };
    </script>
</div>
@endsection
