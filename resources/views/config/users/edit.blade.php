@extends('layouts/App')

@section('title', 'Edit User')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                    <div class="card-tools">
                        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                        </button> -->
                        
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('/config/users/update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="name">Name of User</label>
                                            <input type="text" name="name" id="name" class="form-control" value="{{ $datauser->name }}" required>
                                            <input type="hidden" name="iduser" id="iduser" class="form-control" value="{{ $datauser->id }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="username">User ID</label>
                                            <input type="text" name="username" id="username" class="form-control" value="{{ $datauser->username }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" value="{{ $datauser->email }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="esignfile">e-signature</label>
                                            <input type="file" name="esignfile" id="esignfile" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <a href="{{ url('/config/users') }}" class="btn btn-default btn-sm">
                                            <i class="fa fa-arrow-left"></i> Back
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save"></i> SAVE
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="col-lg-12 col-md-12 text-center">
                                    @if(checkIsLocalhost() == 1)
                                    <!-- <img src="{{ $datauser->s_signfile ?? '/assets/dist/img/no-image.png' }}" class="img-thumbnail" alt="E-sign" style="width:200px; height:200px;"> -->
                                    @else
                                    @endif
                                    <img src="{{ asset($datauser->s_signfile ?? '/assets/dist/img/no-image.png') }}" class="img-thumbnail" alt="E-sign" style="width:200px; height:200px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-js')
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection