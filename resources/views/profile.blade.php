@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Modal -->
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

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">關於你</div>
                <div class="card-body">
                    <div class="row hover">
                        <div class="col-5">姓名</div>
                        <div class="col-5">{{ Auth::user()->name }}</div>
                        <div class="col-2 edit">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalForEditName"><i class="fas fa-pencil-alt"></i>編輯</button>
                            
                        </div>
                    <hr/>
                    <div class="row">
                        <div class="col">電子信箱</div>
                        <div class="col">{{ Auth::user()->email }}</div>
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
        
    </script>
</div>
@endsection
