@extends('default')

@section('title', 'Kezdőlap')

@section('content')
<ul>
    @foreach($posts as $post)
        <li><a href="
            {{ route('posts.show', ['post' => $post])}} ">{{ $post -> title }}</a> ({{ $post -> author -> name}})</li>
    @endforeach
</ul>
@endsection
