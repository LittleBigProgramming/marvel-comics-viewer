@extends('layouts.default')

@section('content')
    <div id="content">
        <h2>Comics</h2>
        <form action="/comics">
            <p>
                <input type="text" name="query" id="query" value="{{ $query }}">
                <button>Search</button>
            </p>
        </form>

        <div id="comics" class="flex results">
            @foreach($comics as $com)
                <article class="card">
                    <a href="/comics/{{ $com['id'] }}"><img src="{{ $com['thumbnail']['path'] }}/portrait_incredible.jpg" alt="{{ $com['title'] }} thumbnail"></a>
                    <footer>
                        <h5>
                            <a href="/comics/{{ $com['id'] }}" class="card-title">{{ $com['title'] }}</a>
                        </h5>
                        <p>
                            {{ str_limit($com['description'], 160) }}
                        </p>
                    </footer>
                </article>
            @endforeach
        </div>
        <div><a href="http://marvel.com">Data provided by Marvel. © 2018 Marvel</a></div>
    </div>
@stop