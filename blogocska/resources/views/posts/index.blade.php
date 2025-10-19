@extends('default')

@section('title', 'Kezdőlap')

@section('content')

@if (Session::has('update-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('update-success') }} bejegyzés sikeresen szerkesztve.</div>
@elseif (Session::has('create-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('create-success') }} bejegyzés sikeresen létrehozva.</div>
@elseif (Session::has('delete-success'))
    <div class="rounded my-2 bg-green-300">A(z) {{ Session::get('delete-success') }} bejegyzés sikeresen törölve.</div>
@endif

@can('create', \App\Models\Post::class)
<a class="text-green-500" href="{{ route('posts.create') }}">Új bejegyzés írása</a>
<br><br>
@endcan

<ul>
    @foreach($posts as $post)
        <li><a href="
            {{ route('posts.show', ['post' => $post])}} ">{{ $post -> title }}</a> ({{ $post -> author -> name}})</li>
    @endforeach
</ul>

{{  $posts -> links() }}

@endsection
