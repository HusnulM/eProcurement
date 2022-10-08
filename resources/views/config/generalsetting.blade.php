@extends('layouts/App')

@section('title', 'General Setting')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Setting</h3>
                    <div class="card-tools">
                        <!-- <a href="{{ url('/') }}" class="btn btn-default btn-sm">
                            <i class="fa fa-arrow-left"></i> Back
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-content-above-home-tab" data-toggle="pill" href="#custom-content-above-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">Company Logo</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="custom-content-above-tabContent">
                                <div class="tab-pane fade show active" id="custom-content-above-home" role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                    <div class="col-lg-12">
                                        <br>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <form action="{{ url('/general/setting/save') }}" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="input-group mb-3">
                                                        <input type="file" class="form-control" name="companylogo" accept="image/png, image/gif, image/jpeg">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary btn-sm" type="submit">
                                                                <i class="fa fa-save"></i> SAVE COMPANY LOGO
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>                                        
                                            </div>
                                            <hr>
                                            <div class="col-lg-12">
                                                <img src="{{ asset($complogo->setting_value ?? '/assets/dist/img/no-image.png') }}" alt="Company-Logo" class="img-thumbnail" style="width:200px; height:200px;">
                                                @if(checkIsLocalhost() == 1)
                                                @else
                                                    <!-- <img src="/main/public/{{ $complogo->setting_value ?? '/assets/dist/img/no-image.png' }}" alt="Company-Logo" class="img-thumbnail" style="width:200px; height:200px;"> -->
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-modal')

@endsection

@section('additional-js')

@endsection