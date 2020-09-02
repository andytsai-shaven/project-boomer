<?php

$controller = ProjectChecklistsController::class;

Route::group(['prefix' => 'api/v1'], function () use ($controller) {
    Route::get('projects/{projects}/checklists',  "$controller@index")->middleware('role:*');
    Route::post('projects/{projects}/checklists', "$controller@store")->middleware('role:project_manager|quality_manager');
});

Route::group(['middleware' => ['csrftoken']], function () use ($controller) {
    Route::put('projects/{projects}/checklists/{checklists}/checkresults', "$controller@updateCheckitemsResults")
        ->name('projects.checklists.checkresults.update')
        ->middleware('role:project_manager|quality_manager');

    Route::get('projects/{projects}/checklists', "$controller@index")->name('projects.checklists.index')->middleware('role:*');
    Route::get('projects/{projects}/checklists/create', "$controller@create")->name('projects.checklists.create')->middleware('role:project_manager|quality_manager');
    Route::post('projects/{projects}/checklists', "$controller@store")->name('projects.checklists.store')->middleware('role:project_manager|quality_manager');
    Route::get('projects/{projects}/checklists/{checklists}', "$controller@show")->name('projects.checklists.show')->middleware('role:*');
});
