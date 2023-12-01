<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Events\SayHiToAll;
use Illuminate\Http\Request;
use App\Events\ChatRoomJoin;
use App\Events\ChatRoomSendPublicMessage;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])
->prefix('chat')->group(function(){

    Route::get('/',function(){
        return Inertia::render('Chat');
    })->name('chat.room');

    Route::prefix('events')->group(function(){
        Route::get('joined',function(Request $request){
            broadcast(new ChatRoomJoin($request->user()))->toOthers();
        })->name('event.join');

        Route::post('message',function(Request $request){
            broadcast(new ChatRoomJoin($request->user()));
            broadcast(new ChatRoomSendPublicMessage(
                $request->user(),
                $request->input('message')
            ))->toOthers();
        })->name('event.message');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('event/greet-everyone',function(Request $request){
        broadcast(new SayHiToAll($request->user()))->toOthers();
    })->name('event.greet-everyone');
});

require __DIR__.'/auth.php';
