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
use Illuminate\Filesystem\Filesystem;

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
            ->where('status', '=', 1)
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
        $method = 'create';
        $data = $app->checkFile($request, $app, $model, $user,$method);
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
        $file = new Filesystem();
        $path = Application::where('id', '=', $request->request_id)->where('user_id', '=',
            $user->id)->get()->first()->path_to;
        $delete_request = Application::where('id', '=', $request->request_id)->where('user_id', '=',
            $user->id)->delete();

        if($delete_request > 0){
            $delete_request_mediators = Mediator::where('request_id','=',$request->request_id)->delete();
            $delete_request_executors = Executor::where('request_id','=',$request->request_id)->delete();
            if(!is_null($path)){
                $path_delete = mb_substr($path,0,16);
                $file->deleteDirectory(public_path($path_delete));
            }
            return $this->sendResponse(true, 'delete request', 200);
        }
        return $this->sendResponse(false, 'not delete request', 404);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function endRequest(Request $request,$id)
    {
        $application = Application::where('id', '=', $id)->get()->first();
        $application->status = 3;
        $application->save();
        return response()->json(true, 200);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function startRequest(Request $request,$id)
    {
        $application = Application::where('id', '=', $id)->get()->first();
        $application->status = 1;
        $application->save();
        return response()->json(true, 200);

    }


}
