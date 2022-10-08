@extends('layouts/App')

@section('title', 'Create Item Master')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Item Master</h3>
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
                    <form action="{{ url('/master/item/save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="itemcode">Item Code</label>
                                        <input type="text" name="itemcode" id="itemcode" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label for="itemname">Item Name</label>
                                        <input type="text" name="itemname" id="itemname" class="form-control" autocomplete="off" required>
                                    </div>
                                </div>  
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label for="itemtype">Item Type</label>
                                        <!-- <input type="text" name="itemtype" id="itemtype" class="form-control" autocomplete="off" required> -->
                                        <select name="itemtype" id="itemtype" class="form-control">
                                            <option value="">Pilih Item Type</option>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label for="itemunit">Item Unit</label>
                                        <input type="text" name="itemunit" id="itemunit" class="form-control" autocomplete="off" required>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <a href="{{ url('/master/item') }}" class="btn btn-default btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> SAVE
                                    </button>
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