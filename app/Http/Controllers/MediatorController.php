<?php

namespace App\Http\Controllers;


use App\Models\File;
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
use App\Http\Requests\OrderEditRequest;

class MediatorController extends Controller
{
    public function edit(OrderEditRequest $request,Order $order)
    {
        $user = Auth::user();
        $model = new FileController();
        $method = 'edit';
        $data = $order->checkFile($request, $order, $model, $user,$method);

        return $this->sendResponse($order, $data, 200);
    }

    public function downloadFile(Request $request,Order $order)
    {
        $model = new FileController();
        $url = $model->fileSave($request, $order->user_id, $order->path_to);
        $order->path_to = $url;
        $order->save();

        return $this->sendResponse($order,'ok',200);
    }



}

