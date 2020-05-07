<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception->getMessage() == 'User not login') {
            return response()->json(
                [
                    'message' => 'Неправильный логин или пароль'
                ],
                401
            );
        }

        if ($exception->getMessage() == 'Task not found') {
            return response()->json(
                [
                    'message' => 'Подзадача не найдена'
                ],
                404
            );
        }

        if ($exception->getMessage() == 'List not found') {
            return response()->json(
                [
                    'message' => 'Список  не найден'
                ],
                404
            );
        }

        if ($exception instanceof RouteNotFoundException) {
            return response()->json(
                [
                    'message' => 'Для соверешения данного действия необходимо авторизоваться'
                ],
                403
            );
        }

        if ($exception->getMessage() == 'Subtask not updated') {
            return response()->json(
                [
                    'message' => 'Проверьте правильность ввода полей'
                ],
                400
            );
        }

        if($exception instanceof MethodNotAllowedHttpException){
            return response()->json(
                [
                    'message' => 'Данный метод не поддерживается в текущем запросе'
                ],
                404
            );
        }
        return parent::render($request, $exception);
    }
}
