<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\PostStoreOrUpdateRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(){
        // $posts = Post::all(); // n + 1 probléma
        $posts = Post::with('author') -> where('is_public', true) -> paginate(10);
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post]);
    }

    public function create(){
        Gate::authorize('create', Post::class);
        return view('posts.create', [
            'users' => User::all(),
            'categories' => Category::all()
        ]);
    }

    public function store(PostStoreOrUpdateRequest $request){
        Gate::authorize('create', Post::class);
        $validated = $request -> validated();
        $validated['is_public'] = $request -> has('is_public');
        $validated['author_id'] = Auth::id();
        if ($request -> hasFile('image_file')){
            $file = $request -> file('image_file');
            $fname = Str::uuid() . "." . $file -> getClientOriginalExtension();
            Storage::disk('public') -> put('images/' . $fname, $file -> getContent());
            $validated['image'] = $fname;
        }
        $post = Post::create($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        Session::flash('create-success', $post -> title);
        return redirect() -> route('posts.index');
    }

    public function edit(Post $post){
        Gate::authorize('update', $post);
        return view('posts.edit', [
            'users' => User::all(),
            'categories' => Category::all(),
            'post' => $post
        ]);
    }

    public function update(PostStoreOrUpdateRequest $request, Post $post){
        Gate::authorize('update', $post);
        $validated = $request -> validated();
        $validated['is_public'] = ($request -> input('is_public') ?? 0) == "on";
        $validated['author_id'] = Auth::id();
        $post -> update($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        Session::flash('update-success', $post -> title);
        return redirect() -> route('posts.index');
    }

    public function destroy(Post $post){
        Gate::authorize('delete', $post);
        $tmp = $post -> title;
        // $post -> categories() -> sync([]);
        $post -> delete();
        Session::flash('delete-success', $tmp);
        return redirect() -> route('posts.index');
    }
}
