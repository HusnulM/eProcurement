@extends('layouts/App')

@section('title', 'Upload Item Master')

@section('additional-css')
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col-lg-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Material</h3>
                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i> /excel/Template Upload Material.xlsx
                    </button> -->
                    <a href="{{ url('master/item/exporttemplate') }}" target="_blank" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-download"></i> Download Template
                    </a>

                    <a href="/master/player" class="btn btn-primary btn-sm"> 
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        @if(count($errors) > 0)
                            @foreach( $errors->all() as $message )
                            <div class="alert alert-danger alert-block msgAlert">
                                <button type="button" class="close closeAlert" data-dismiss="alert"></button> 
                                <strong>{{ $message }}</strong>
                            </div>
                            @endforeach            
                        @endif
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block msgAlert">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                            <strong>{{ $message }}</strong>
                        </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger msgAlert">
                                {{ session()->get('error') }}
                            </div>
                        @endif
                    </div>
                </div>
                    
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{ url('/master/item/upload/save') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="browse-file">File</label>
                                    <input type="file" name="file" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary" style="margin-top:27px; width:100%;">
                                        <i class="fa fa-folder-open"></i> Import Data
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                
            </div>
        </div>
    </div>
</div>
</div>    
@endsection

@section('additional-js')
<script>
    
</script>
@endsection