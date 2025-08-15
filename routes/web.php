<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Plant Routes
    Volt::route('plants', 'pages.plants.index')->name('plants.index');
    Volt::route('plants/create-test', 'pages.plants.create-test')->name('plants.create-test');
    Volt::route('plants/minimal-test', 'pages.plants.minimal-test')->name('plants.minimal-test');
    Volt::route('plants/create-fixed', 'pages.plants.create-fixed')->name('plants.create-fixed');
    Volt::route('plants/create', 'pages.plants.create')->name('plants.create');
    Volt::route('plants/request', 'pages.plants.request')->name('plants.request');
    Volt::route('plants/{plant}', 'pages.plants.show')->name('plants.show');
    Volt::route('plants/{plant}/edit', 'pages.plants.edit')->name('plants.edit');

    // Admin Routes
    Volt::route('admin/dashboard', 'pages.admin.dashboard')->name('admin.dashboard');
});

require __DIR__.'/auth.php';
