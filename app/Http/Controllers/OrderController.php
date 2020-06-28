<?php

namespace App\Http\Controllers;


use App\Models\Mediator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\FileController;
use App\Http\Requests\OrderRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use Illuminate\Support\Arr;
use App\Models\Executor;
use Illuminate\Filesystem\Filesystem;

class OrderController extends Controller
{
    /**
     * Вывод заказов от заказчиков для исполнителе
     * @return mixed
     *
     * @Rest\Get("/request")
     */
    public function index()
    {

        return $this->sendResponse(
            Order::select("id","title","description","budget","status","created_at")
                ->orderBy('id', 'DESC')
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
    public function show(Order $order)
    {
        return $this->sendResponse(array(
            [
                'order' => Order::find($order->id),
                'category_name' => Category::where('id', '=', $order->category_id)->first()->name,
                'mediator' => User::find(Mediator::where('order_id', '=', $order->id)->first()->user_id)->full_name,
                'client' => $user_client = User::find($order->first()->user_id)->full_name,
                'executors' => $order->generateArrayToExecutors($order->executors, $order->id)
            ])
        ,'OK',200);

    }
    /**
     * Создание заказа
     *
     * @param OrderRequest $request
     *
     * @return mixed
     *
     * @Rest\Post("/request")
     */
    public function store(OrderRequest $request)
    {
        $user = Auth::user();
        $order = new Order();
        $files = new FileController();
        $method = 'create';
        $data = $order->checkFile($request, $order, $files, $user,$method);
        $order->getMediatorsAndSetToApplication($data->id);

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
        $app = new Order();

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
        $order = Order::where('id', '=', $request->request_id)->where('user_id', '=', $user->id);
        $path = $order->first()->path_to;
        $delete_request = $order->delete();

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
    public function endRequest(Order $order)
    {
        $order->status = 3;
        $order->save();

        return response()->json(true, 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function startRequest(Order $order)
    {
        $order->status = 1;
        $order->save();

        return response()->json(true, 200);
    }


}
