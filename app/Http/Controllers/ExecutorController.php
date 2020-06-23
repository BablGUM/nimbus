<?php


namespace App\Http\Controllers;


use App\Application;
use App\Executor;
use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ExecutorController extends Controller
{
    /**
     * @param $id
     * @return mixed
     */
    public function store($id)
    {

        $users = Auth::user();

        $model = Executor::where('user_id', '=', $users->id)->where('request_id', '=', $id)->get();

        if ($model->count() > 0) {
            return $this->sendError('Вы уже откликнулись', 403, ['Нельзя откликнутся несколько раз']);
        } else {
            $app = Application::where('id','=',$id)->get()->first();
            $user = User::where('id','=',$app->user_id)->get()->first();
            $link = 'https://aqueous-sea-49755.herokuapp.com/order-detail/'. $id . '/active/client';
            $notification = new Notification();
            $notification->requestResponded($user->email,$user->full_name,$link,$app->title);

            $data = [
                'request_id' => $id,
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
        $model = Executor::where('user_id', '=', $id)->where('request_id', '=', $request->request_id)->get();



        $model->first()->client_chose = 1;
        $model->first()->save();

        return response()->json(true, 200);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function consetExecutor(Request $request)
    {
        $user = Auth::user();
        $model = Executor::where('user_id', '=', $user->id)->where('request_id', '=', $request->request_id)->get();
        $model_delete = Executor::where('user_id', '!=', $user->id)->delete();
        $application = Application::where('id', '=', $request->request_id)->get()->first();
        $user_client = User::where('id','=',$application->user_id)->get()->first();
        $application->status = 2;
        $application->save();
        $model->first()->executor_chose = 1;
        $model->first()->save();
        $notification = new Notification();
        $link = "https://aqueous-sea-49755.herokuapp.com/order-detail/". $request->id ."/process";
        $notification->requestStart($user_client->email,$user_client->full_name,$link,$application->title);
        return response()->json(true, 200);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectionExecutor(Request $request)
    {
        $user = Auth::user();
        $model_delete = Executor::where('user_id', '=', $user->id)->where('request_id', '=', $request->request_id)->delete();

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
        foreach($request_id->pluck('request_id') as $item){

            $executors = Arr::prepend($executors,
                [
                    'id' => $item
                ]);

        }
        return response()->json($executors,200);

    }

}