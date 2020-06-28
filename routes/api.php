<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::post('login', 'UserController@loginUser'); // авторизация пользователя
    Route::post('registration', 'UserController@registerUser'); // регистрация пользователя
    Route::post('verification', 'UserController@verificationUser'); // подвтерждение email
    Route::post('unique', 'UserController@checkUnique'); // проверка уникальности
    Route::post('send', 'UserController@toSendMessageToEmail');  // отправка сообщения на почту
    Route::post('password', 'UserController@resetPassword');  // восстановление пароля
    Route::post('code', 'UserController@checkResetCode'); // проверка наличия кода восстановления
    Route::get('request', 'OrderController@index'); // все заявки
    Route::get('request/{order}', 'OrderController@show'); // посмотреть одну
    Route::get('file/download', 'FileController@fileDownload'); // скачать файл
    Route::get('file/check', 'FileController@fileCheck'); // посмотреть файл
    Route::get('status','Controller@check'); // для деплоя на хероку




    Route::group(
        ['middleware' => 'auth:api'],
        function () {
            Route::get('logout', 'UserController@logoutUser'); // выход пользователя
            Route::post('request', 'OrderController@store'); // создать заявку
            Route::get('user', 'UserController@index'); // личный кабинет ( вывод информации о пользоват)
            Route::get('users/{id}', 'UserController@show'); // анкета пользователя
            Route::get('user/request', 'OrderController@showRequest'); // вывод заказов в личный кабинет
            Route::post('user', 'UserController@edit'); // редактирование личных данных
            Route::get('executor/add/{order}', 'ExecutorController@store'); //  исполнитель к заказу
            Route::post('user', 'UserController@edit'); // редактирование профиля
            Route::get('consent/client/{id}', 'ExecutorController@consetClient'); // выбор заказчиком исполнителя
            Route::get('consent/executor', 'ExecutorController@consetExecutor'); // согласие исполнителя на заказ
            Route::delete('rejection', 'ExecutorController@rejectionExecutor'); // отказ исполнителя
            Route::delete('request', 'OrderController@deleteRequest'); // удаление заявки
            Route::get('id/client-chose','ExecutorController@countChose'); // id заказов где исполнитель выбран
            Route::get('request/end/{order}','OrderController@endRequest'); // завершение заказа
            Route::post('mediator/request/{order}', 'MediatorController@edit'); // редактирование заказа от посредника
            Route::get('request/start/{order}','OrderController@startRequest'); // статус с нового в активный
            Route::post('request/{id}/file','FileController@uploadFileInRequest'); // загрузка документов в заказ
            Route::get('request/{id}/documents','FileController@index'); // просмотр всех доков ( кроме тз )
            Route::post('mediator/file/{order}','MediatorController@downloadFile'); // редактирование файла тз посредником


        }
    );