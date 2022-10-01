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
    