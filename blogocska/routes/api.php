<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request){
    $validated = $request -> validate([
        'email' => 'required|string|email',
        'password' => 'required|string'
    ]); // 422-es dob, ha elbukik
    if (Auth::attempt($validated)){
        $user = User::where('email', $validated['email']) -> first();
        $token = $user -> createToken('loginToken');
        return response()->json([ "token" => $token -> plainTextToken]);
    } else {
        return response()->json([ "message" => "Nope."], 401);
    }
});
