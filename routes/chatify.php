<?php

use App\Http\Controllers\Vendor\Chatify\MessagesController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => config('chatify.routes.prefix'),
    // 'middleware' => ['auth:admin'],
], function () {
   
    Route::post('/idInfo', [MessagesController::class, 'idFetchData']);
    Route::post('/sendMessage', [MessagesController::class, 'send'])->name('send.message');
    Route::post('/fetchMessages', [MessagesController::class, 'fetch'])->name('fetch.messages');
    Route::get('/download/{fileName}', [MessagesController::class, 'download'])->name(config('chatify.attachments.download_route_name'));
    Route::post('/chat/auth', [MessagesController::class, 'pusherAuth'])->name('pusher.auth');
    Route::post('/makeSeen', [MessagesController::class, 'seen'])->name('messages.seen');
    Route::get('/getContacts', [MessagesController::class, 'getContacts'])->name('contacts.get');
    Route::post('/updateContacts', [MessagesController::class, 'updateContactItem'])->name('contacts.update');
    
    Route::post('/updateLayoutMode', [MessagesController::class, 'updateLayoutMode'])->name('layout.mode.update');

    Route::post('/star', [MessagesController::class, 'favorite'])->name('star');
    Route::post('/favorites', [MessagesController::class, 'getFavorites'])->name('favorites');
    Route::get('/search', [MessagesController::class, 'search'])->name('search');
    Route::post('/shared', [MessagesController::class, 'sharedPhotos'])->name('shared');
    Route::post('/deleteConversation', [MessagesController::class, 'deleteConversation'])->name('conversation.delete');
    Route::post('/deleteMessage', [MessagesController::class, 'deleteMessage'])->name('message.delete');
    Route::post('/updateSettings', [MessagesController::class, 'updateSettings'])->name('avatar.update');
    Route::post('/setActiveStatus', [MessagesController::class, 'setActiveStatus'])->name('activeStatus.set');
    Route::get('/group/{id}', [MessagesController::class, 'index'])->name('group');
    Route::get('/{id}', [MessagesController::class, 'index'])->name('user');
});
