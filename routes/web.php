<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes();
Route::group(['middleware' => ['auth', 'user']], function() {
    Route::get('projects', 'FolderController@index')->name('projects.index');
    Route::get('shared', 'SharedController@index')->name('shared.index');
    Route::get('shared/{id}', 'SharedController@show')->name('shared.show');
    // Route::get('shared/{id}/download_zip', 'SharedController@download_zip')->name('shared.download_zip');

    Route::get('folders/upload', 'FolderController@upload')->name('folders.upload');
    Route::post('folders/media', 'FolderController@storeMedia')->name('folders.storeMedia');
    Route::post('folders/upload', 'FolderController@postUpload')->name('folders.postUpload');
    Route::post('folder/{id}/rename', 'FolderController@rename')->name('folders.rename');
    Route::post('folder/{id}/reload_screen', 'FolderController@reload_screen')->name('folders.reload_screen');
    Route::get('folder/{id}/download_zip', 'FolderController@download_zip')->name('folders.download_zip');
    Route::post('folder/{id}/display_in_modal', 'FolderController@display_in_modal')->name('folders.display_in_modal');
    Route::post('folder/{id}/move', 'FolderController@move_folder')->name('folders.move_folder');
    Route::post('folder/{id}/remove', 'FolderController@remove')->name('folders.remove');

    Route::post('file/{id}/rename', 'FileController@rename')->name('file.rename');
    Route::get('file/{id}/download', 'FileController@download')->name('file.download');
    Route::post('file/{id}/move', 'FileController@move')->name('file.move');
    Route::post('file/{id}/remove', 'FileController@remove')->name('file.remove');

    // Route::post('ajax/check_exist_email', 'AjaxController@check_exist_email')->name('ajax.check_exist_email');

    Route::post('invite/sent', 'InviteController@sent')->name('invite.sent');
    Route::post('invite/get_allawed_users', 'InviteController@get_allawed_users')->name('invite.get_allawed_users');
    Route::post('invite/delete_allowed', 'InviteController@delete_allowed')->name('invite.delete_allowed');

    Route::resource('folders', 'FolderController')->except(['index', 'destroy']);
});
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth','admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/media', 'UsersController@storeMedia')->name('users.storeMedia');
    Route::post('users/ckmedia', 'UsersController@storeCKEditorImages')->name('users.storeCKEditorImages');
    Route::resource('users', 'UsersController');

    // Projects
    // Route::delete('projects/destroy', 'ProjectsController@massDestroy')->name('projects.massDestroy');
    // Route::resource('projects', 'ProjectsController');

    // Folders
    Route::delete('folders/destroy', 'FoldersController@massDestroy')->name('folders.massDestroy');
    Route::post('folders/media', 'FoldersController@storeMedia')->name('folders.storeMedia');
    Route::post('folders/ckmedia', 'FoldersController@storeCKEditorImages')->name('folders.storeCKEditorImages');
    Route::resource('folders', 'FoldersController');

    // Invitables
    Route::delete('invitables/destroy', 'InvitablesController@massDestroy')->name('invitables.massDestroy');
    Route::resource('invitables', 'InvitablesController');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
