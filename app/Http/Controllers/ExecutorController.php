<?php


namespace App\Http\Controllers;


use App\Application;
use App\Executor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class ExecutorController extends Controller
{
    public function store($id)
    {

        $user = Auth::user();
        $model = Executor::where('user_id', '=', $user->id)->where('request_id', '=', $id)->get();
        if ($model->count() > 0) {
            return $this->sendError('Вы уже откликнулись', 401, ['Нельзя откликнутся несколько раз']);
        } else {
            $data = [
                'request_id' => $id,
                'user_id' => $user->id,

            ];
            $executor = Executor::create($data);
            return $this->sendResponse($executor, 'Create', 201);
        }
    }

    public function consetClient(Request $request, $id)
    {
        $model = Executor::where('user_id', '=', $id)->where('request_id', '=', $request->request_id)->get();



        $model->first()->client_chose = 1;
        $model->first()->save();

        return response()->json(true, 200);
    }


    public function consetExecutor(Request $request)
    {
        $user = Auth::user();
        $model = Executor::where('user_id', '=', $user->id)->where('request_id', '=', $request->request_id)->get();
        $model_delete = Executor::where('user_id', '!=', $user->id)->delete();
        $application = Application::where('id', '=', $request->request_id)->get()->first();
        $application->status = 2;
        $application->save();
        $model->first()->executor_chose = 1;
        $model->first()->save();

        return response()->json(true, 200);

    }


    public function rejectionExecutor(Request $request)
    {
        $user = Auth::user();
        $model_delete = Executor::where('user_id', '=', $user->id)->where('request_id', '=', $request->request_id)->delete();

        return response()->json(true, 200);
    }

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