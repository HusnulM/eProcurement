@extends('layouts/App')

@section('title', 'Create Vendor Master')

@section('additional-css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .select2-container {
            display: block
        }

        .select2-container .select2-selection--single {
            height: 36px;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <form action="{{ url('/master/vendor/save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Vendor Master</h3>
                        <div class="card-tools">
                            <a href="{{ url('/master/vendor') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> SAVE
                            </button>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="vendorcode">Kode Vendor</label>
                                            <input type="text" name="vendorcode" id="vendorcode" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendorname">Nama Vendor</label>
                                            <input type="text" name="vendorname" id="vendorname" class="form-control" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="address">Alamat Vendor</label>
                                            <textarea name="address" cols="30" rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-lg-6 col-md-12 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="telp">No. Telp</label>
                                            <input type="text" name="telp" id="telp" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="contactperson">Contact Person</label>
                                            <input type="text" name="contactperson" id="contactperson" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('additional-js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection