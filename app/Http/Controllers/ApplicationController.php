<?php

namespace App\Http\Controllers;


use App\Mediator;
use App\User;
use Illuminate\Http\Request;
use App\Application;
use App\Http\Controllers\FileController;
use App\Http\Requests\ApplicationRequest;
use Illuminate\Support\Facades\Auth;
use App\Category;
use Illuminate\Support\Arr;
use App\Executor;

class ApplicationController extends Controller
{
    /**
     * Вывод заказов от заказчиков для исполнителе
     *
     *
     * @return mixed
     *
     * @Rest\Get("/request")
     */
    public function index()
    {
        return $this->sendResponse(Application::orderBy('id', 'DESC')
            ->where('status', '=', 0)
            ->get(), '200 OK', 200);
    }
    /**
     * Вывод заказа подробнее ( Выводятся не только заказы но и список заказчиков посредников и исполнителей на заказе)
     *
     * @param Request $id
     *
     * @return mixed
     *
     * @Rest\Get("/request/{$id}")
     */
    public function show($id)
    {
        if (Application::find($id)) {
            $application = new Application();
            $category_id = Application::find($id)->category_id;
            $category = Category::where('id', '=', $category_id)->get()->first()->name;
            $mediator_request = Mediator::where('request_id', '=', $id)->get()->first();
            $mediator = User::find($mediator_request->user_id);
            $user_client = User::find(Application::find($id)->first()->user_id);
            $request_executor = Executor::where('request_id', '=', $id)->get();
            $executors = $application->generateArrayToExecutors($request_executor,$id);

            return $this->sendResponse($application
                ->generateArrayToApplicationShow($id,$category,$mediator,$user_client,$executors),
                '200 OK', 200);
        }
        return $this->sendError('ERROR 404 NOT FOUND', 404, ['Данный заказ не существует']);
    }
    /**
     * Создание заказа
     *
     * @param ApplicationRequest $request
     *
     * @return mixed
     *
     * @Rest\Post("/request")
     */
    public function store(ApplicationRequest $request)
    {
        $user = Auth::user();
        $app = new Application();
        $model = new FileController();
        $mediator = new Application();
        $data = $app->checkFile($request, $app, $model, $user);
        $mediator->getMediatorsAndSetToApplication($data->id);

        return $this->sendResponse($data, '201 CREATED', 201);
    }
    /**
     * Вывод заказов в личный кабинет пользвателя
     *
     *
     * @return mixed
     *
     * @Rest\Get("/user/request")
     */
    public function showRequest()
    {
        $user = Auth::user();
        $app = new Application();

        return $this->sendResponse($app->requestGenerate($user->id), 'OK', 200);
    }
    /**
     * Удалить заказ от заказчика
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @Rest\Delete("/request")
     */
    public function deleteRequest(Request $request)
    {
        $user = Auth::user();
        $delete_request = Application::where('id', '=', $request->request_id)->where('user_id', '=',
            $user->id)->delete();

        return $this->sendResponse(true, 'delete request', 200);
    }
}
