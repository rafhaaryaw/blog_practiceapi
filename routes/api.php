<?php

use App\Http\Controllers\api\C_crudblog;
use App\Http\Controllers\api\C_crudcategory;
use App\Http\Controllers\C_admin;
use App\Http\Controllers\C_auth;
use App\Http\Controllers\C_updateprofile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [C_auth::class, 'login']);
Route::post('register', [C_auth::class, 'register']);

Route::apiResource('/post', C_crudblog::class);

Route::get('showall', [C_crudblog::class, 'index']);
Route::apiResource('/categories',   C_crudcategory::class);

Route::middleware('auth:sanctum')->group(function () {

    //penulis
    Route::post('create', [C_crudblog::class, 'Post_blog'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::get('/show/{id}', [C_crudblog::class, 'show'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::put('/update/{id}', [C_crudblog::class, 'update'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::delete('/delete/{id}', [C_crudblog::class, 'destroy'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::get('/updateget/{id}', [C_crudblog::class, 'updateget'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');


    Route::post('createcategory', [C_crudcategory::class, 'create'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::put('/updatecat/{id}', [C_crudcategory::class, 'update'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::get('/showcat/{id}', [C_crudcategory::class, 'show'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::get('/cat', [C_crudblog::class, 'category'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::get('/showall', [C_crudblog::class, 'index'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::delete('/deletecat/{id}', [C_crudcategory::class, 'destroy'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');

    Route::post('/updateprofile/{id}', [C_updateprofile::class, 'update'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');
    Route::post('/updatepass/{id}', [C_updateprofile::class, 'updatepass'])->middleware('restrictrole:penulis', 'restrictstatus:aktif');


    //admin
    Route::get('showuser', [C_admin::class, 'index'])->middleware('restrictrole:admin');
    Route::get('showblog', [C_admin::class, 'blog'])->middleware('restrictrole:admin');
    Route::post('/activation/{id}', [C_admin::class, 'activation'])->middleware('restrictrole:admin');
    Route::post('/nonactivation/{id}', [C_admin::class, 'nonactivation'])->middleware('restrictrole:admin');
});
