
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\WebAuthController;


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::post('/login', [WebAuthController::class, 'login'])->name('auth.login');

Route::post('/logout', [WebAuthController::class, 'logout'])->name('auth.logout');
