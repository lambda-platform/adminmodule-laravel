<?php

Route::namespace('Lambda\Puzzle\Controllers')
    ->prefix('lambda/puzzle')
    ->middleware(['api'])
//    ->middleware(['api'])
    ->group(function ($router) {
        $router->get('/', 'PuzzleController@index');
        $router->get('/upload', 'PuzzleController@UploadDBSCHEMA');
        $router->get('/dbschema/{table?}', 'PuzzleController@dbSchema');
        $router->delete('/delete/{table}/{type}/{id}', 'PuzzleController@deleteVB');

        //Puzzle
        $router->get('/schema/{type}/{id?}/{condition?}', 'PuzzleController@getVB');
        $router->post('/schema/{type}/{id?}', 'PuzzleController@saveVB');

        //Crud
        $router->any('/form/{action}/{schemaID}', 'PuzzleController@formVB');
        $router->any('/grid/{action}/{schemaID}', 'PuzzleController@gridVB');
        $router->any('/upload', 'PuzzleController@fileUpload');

        //Get From Options
        $router->post('/get_options', 'PuzzleController@getOptions');

        //Roles
        $router->get('roles-menus', 'RolesController@getRolesMenus');
//        $router->get('deletedroles', 'RolesController@getDeletedRoles');
        $router->get('get-krud-fields/{id}', 'RolesController@getKrudFields');
        $router->post('roles/create', 'RolesController@store');
        $router->post('roles/store/{id}', 'RolesController@update');
        $router->post('save-role', 'RolesController@saveRole');
        $router->delete('roles/destroy/{id}', 'RolesController@destroy');
        $router->get('roles/restore/{id}', 'RolesController@restore');
        $router->delete('roles/forceDestroy/{id}', 'RolesController@forceDelete');

        //Embed
        $router->get('embed', 'PuzzleController@embed');
    });

Route::namespace('Lambda\Puzzle\Controllers')
    ->prefix('lambda')
    ->middleware(['api'])
//    ->middleware(['api'])
    ->group(function ($router) {

        $router->get('/upload', 'PuzzleController@UploadDBSCHEMA');
        $router->get('/async', 'PuzzleController@ASyncFromCloud');


    });
