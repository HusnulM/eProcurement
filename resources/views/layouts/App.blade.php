@if(getAppTheme() === 'TILE')
    @include('layouts.AppTile')
@else
    @include('layouts.AppSbar')
@endif