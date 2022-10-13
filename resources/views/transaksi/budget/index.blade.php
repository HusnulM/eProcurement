@extends('layouts/App')

@section('title', 'Pengajuan Budget')

@section('additional-css')
@endsection

@section('content')        
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm btn-add-dept">
                            <i class="fas fa-save"></i> Buat Pengajuan
                        </button>
                        <!-- <a href="{{ url('/master/department/create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Create Department
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <div class="row">
                                <!-- <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="noaju">Nomor Pengajuan Budget</label>
                                        <input type="text" name="noaju" class="form-control">
                                    </div>
                                </div> -->
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="tglaju">Tanggal Pengajuan</label>
                                        <input type="date" name="tglaju" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="nominal">Nominal Budget yg diajukan</label>
                                        <input type="text" name="nominal" class="form-control">
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
<script>
    $(document).ready(function(){
        
    });
</script>
@endsection