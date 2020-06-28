<?php


namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Executor;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ExecutorController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function store(Order $order)
    {

        $users = Auth::user();
        $model = Executor::where('user_id', '=', $users->id)->where('order_id', '=', $order->id)->get();

        if ($model->count() > 0) {
            return $this->sendError('Вы уже откликнулись', 403, ['Нельзя откликнутся несколько раз']);
        } else {
            $user = User::where('id','=',$order->user_id)->first();
            $link = 'https://aqueous-sea-49755.herokuapp.com/order-detail/'. $order->id . '/active/client';
            $notification = new Notification();
            $notification->requestResponded($user->email,$user->full_name,$link,$order->title);

            $data = [
                'order_id' => $order->id,
                'user_id' => $users->id,

            ];
            $executor = Executor::create($data);
            return $this->sendResponse($executor, 'Create', 201);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function consetClient(Request $request, $id)
    {

        $model = Executor::where('user_id', '=', $id)->where('order_id', '=', $request->request_id)->first();
        $model->client_chose = 1;
        $model->save();

        return response()->json(true, 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consetExecutor(Request $request)
    {
        $user = Auth::user();
        $model = Executor::where('user_id', '=', $user->id)->where('order_id', '=', $request->request_id)->first();
        $model_delete = Executor::where('user_id', '!=', $user->id)->delete();
        $order = Order::where('id', '=', $request->request_id)->first();
        $user_client = User::where('id','=',$order->user_id)->first();
        $order->status = 2;
        $order->save();
        $model->executor_chose = 1;
        $model->save();
        $notification = new Notification();
        $link = "https://aqueous-sea-49755.herokuapp.com/order-detail/". $request->request_id ."/process";
        $notification->requestStart($user_client->email,$user_client->full_name,$link,$order->title);
        return response()->json(true, 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectionExecutor(Request $request)
    {
        $user = Auth::user();
        $model_delete = Executor::where('user_id', '=', $user->id)
            ->where('order_id', '=', $request->request_id)->delete();

        return response()->json(true, 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countChose()
    {
        $user = Auth::user();
        $request_id = Executor::where('user_id','=',$user->id)->where('client_chose','=','1')->get();
        $executors = [];
        foreach($request_id->pluck('order_id') as $item){

            $executors = Arr::prepend($executors,
                [
                    'id' => $item
                ]);

        }
        return response()->json($executors,200);

    }

}