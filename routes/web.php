<?php

use Illuminate\Support\Facades\Route;
use App\Models\Types;
use App\Models\Words;
use App\Models\Languages;
use App\Http\Controllers\WordsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/create_wordlist','App\Http\Controllers\WordsController@create');
Route::post('/update_wordList','App\Http\Controllers\WordsController@update');
Route::post('/delete_wordList','App\Http\Controllers\WordsController@destroy');
Route::get('/search_wordList','App\Http\Controllers\WordsController@index');

Route::get('/', function () {
    return view('welcome',[
        'types' => Types::all(),
    ]);
});

Route::get('/{type:id}', function ($id) {
    return view('/components/table',[
        'types' => Types::all(),
        'languages' => Languages::all(),
        'typed' => Types::findOrfail($id),
        'list' => Words::where('type',$id)->where('parent_id', 0)->with('children')->paginate(20)
    ]);
});


