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
                                    {{-- <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="vendorcode">Kode Vendor</label>
                                            <input type="text" name="vendorcode" id="vendorcode" class="form-control" required>
                                        </div>
                                    </div> --}}
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendorname">Nama Vendor</label>
                                            <input type="text" name="vendorname" id="vendorname" class="form-control" autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label for="vendo_pt">Vendor PT</label>
                                            <input type="text" name="vendo_pt" id="vendo_pt" class="form-control" autocomplete="off" required>
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
                                            <label for="bank_holder">Bank Holder</label>
                                            <input type="text" name="bank_holder" id="bank_holder" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="nama_bank">Bank</label>
                                            <input type="text" name="nama_bank" id="nama_bank" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="no_rek">No. Rekening</label>
                                            <input type="text" name="no_rek" id="no_rek" class="form-control" autocomplete="off">
                                        </div>
                                    </div>  
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="vendor_profil">Vendor Profil</label>
                                    <textarea name="vendor_profil" cols="30" rows="5" class="form-control"></textarea>
                                </div>                                
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" cols="30" rows="5" class="form-control"></textarea>
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