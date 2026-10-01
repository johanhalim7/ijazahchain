<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IjazahController;
use App\Http\Controllers\PublicVerificationController;
use App\Http\Controllers\RectorDecisionController;
use App\Http\Controllers\RevokeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationLogController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicVerificationController::class, 'landing'])->name('home');
Route::get('/verifikasi', [PublicVerificationController::class, 'form'])->name('verify.form');
Route::post('/verifikasi', [PublicVerificationController::class, 'verify'])->name('verify.submit');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('workflows', WorkflowController::class)->except(['show', 'edit', 'update']);
        Route::post('workflows/{workflow}/activate', [WorkflowController::class, 'activate'])->name('workflows.activate');
        Route::get('workflows/{workflow}/signers', [WorkflowController::class, 'getSigners'])->name('workflows.signers');
    });
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::post('/wallet/link', [WalletController::class, 'link'])->name('wallet.link');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/verification-logs', [VerificationLogController::class, 'index'])->name('verification-logs.index');
    Route::get('/rektor/keputusan', [RectorDecisionController::class, 'index'])->middleware('role:rektor')->name('rector-decisions.index');
    Route::get('/rektor/keputusan/{ijazah}', [RectorDecisionController::class, 'show'])->middleware('role:rektor')->name('rector-decisions.show');
    Route::get('/ijazah', [IjazahController::class, 'index'])->name('ijazahs.index');
    Route::get('/ijazah/create', [IjazahController::class, 'create'])->middleware('role:akademik')->name('ijazahs.create');
    Route::post('/ijazah', [IjazahController::class, 'store'])->middleware('role:akademik')->name('ijazahs.store');
    Route::get('/ijazah/{ijazah}', [IjazahController::class, 'show'])->name('ijazahs.show');
    Route::get('/ijazah/{ijazah}/edit', [IjazahController::class, 'edit'])->middleware('role:akademik')->name('ijazahs.edit');
    Route::put('/ijazah/{ijazah}', [IjazahController::class, 'update'])->middleware('role:akademik')->name('ijazahs.update');

    Route::get('/approval/{ijazah}', [ApprovalController::class, 'show'])->name('approvals.show');
    Route::post('/approval/{ijazah}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('/approval/{ijazah}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');
    Route::post('/approval/{ijazah}/upload', [ApprovalController::class, 'upload'])->middleware('role:admin')->name('approvals.upload');

    Route::get('/revoke', [RevokeController::class, 'index'])->name('revoke.index');
    Route::post('/revoke', [RevokeController::class, 'store'])->middleware('role:admin')->name('revoke.store');
    Route::get('/revoke/{revoke}', [RevokeController::class, 'show'])->name('revoke.show');
    Route::post('/revoke/{revoke}/approve', [RevokeController::class, 'approve'])->name('revoke.approve');
    Route::post('/revoke/{revoke}/execute', [RevokeController::class, 'execute'])->middleware('role:admin')->name('revoke.execute');
    Route::post('/revoke/{revoke}/reject', [RevokeController::class, 'reject'])->name('revoke.reject');
});
