@extends('default')

@section('title', 'Kezdőlap')

@section('content')

@if (Session::has('update-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('update-success') }} bejegyzés sikeresen szerkesztve.</div>
@elseif (Session::has('create-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('create-success') }} bejegyzés sikeresen létrehozva.</div>
@endif


<ul>
    @foreach($posts as $post)
        <li><a href="
            {{ route('posts.show', ['post' => $post])}} ">{{ $post -> title }}</a> ({{ $post -> author -> name}})</li>
    @endforeach
</ul>
@endsection
