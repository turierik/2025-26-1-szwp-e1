<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;

class PostController extends Controller
{
    public function index(){
        // $posts = Post::all(); // n + 1 probléma
        $posts = Post::with('author') -> get();
        return view('posts.index', ['posts' => $posts]);
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post]);
    }

    public function create(){
        return view('posts.create', [
            'users' => User::all(),
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request){
        $validated = $request -> validate([
            'title' => 'required|string',
            'content' => 'required|string|min:10',
            'author_id' => 'required|integer|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'distinct|integer|exists:categories,id'
        ], [
            'content.min' => 'Legalább 10 karakter kellene'
        ]);
        $validated['is_public'] = $request -> has('is_public');
        $post = Post::create($validated);
        $post -> categories() -> sync($validated['categories'] ?? []);
        return redirect() -> route('posts.index');
    }
}
