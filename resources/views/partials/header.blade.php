 <header>
    <ul class="navbar">
        <li class="{{ Route::currentRouteNamed('comics') ? 'active' : '' }}"><a href="{{ route('comics') }}">Comics</a></li>
        <li class="{{ Route::currentRouteNamed('characters') ? 'active' : '' }}"><a href="{{ route('characters') }}">Characters</a></li>
        <li class="{{ Route::currentRouteNamed('about') ? 'active' : '' }}"><a href="{{ route('characters') }}">About</a></li>
    </ul>
</header>