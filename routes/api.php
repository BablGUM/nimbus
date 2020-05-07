<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::post('login', 'UserController@loginUser'); // авторизация пользователя
    Route::post('registration', 'UserController@registerUser'); // регистрация пользователя
    Route::post('verification', 'UserController@verificationUser'); // подвтерждение email
    Route::post('unique','UserController@checkUnique'); // проверка уникальности
    Route::post('send','UserController@toSendMessageToEmail');  // отправка сообщения на почту
    Route::post('password','UserController@resetPassword');  // восстановление пароля
    Route::post('code','UserController@checkResetCode'); // проверка наличия кода восстановления

    Route::group(
        ['middleware' => 'auth:api'],
        function () {
            Route::get('logout', 'UserController@logoutUser'); // выход пользователя


//            Route::post('create-list', 'TaskListController@createList'); // создание задачи
//            Route::post('create-list/{id_list}/item', 'TaskListController@createItemTask'); // Создание подзадачи
//
//            Route::get('show-list', 'TaskListController@showList'); // посмотреть все задачи (список списков)
//            Route::get('show-list/{id_list}', 'TaskListController@showListByID'); // посмотреть конкретную задачу
//            Route::get('show-tasks', 'TaskController@showTask'); // посмотреть все подзадачи
//
//            Route::delete('delete-list/{id_list}', 'TaskListController@listDelete'); // удалить список
//            Route::delete('delete-list/{id_list}/item/{item_id}', 'TaskListController@itemDelete');//удалить подзадачу
//
//            Route::post('update-list/{id_list}', 'TaskListController@listUpdate'); // обновить список(задачу)
//            Route::post('update-task', 'TaskController@updateTask'); // обновить подзадачу
//
//            Route::post('mark-done/{id}', 'TaskController@markTask'); // пометить подзадачу как сделанную
        }
    );