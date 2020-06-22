<?php

namespace App\Http\Controllers;


use App\File;
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
use App\Http\Requests\ApplicationEditRequest;

class MediatorController extends Controller
{
    public function edit(ApplicationEditRequest $request,$id)
    {

        $application = Application::findOrFail($id);
        $user = Auth::user();

        $model = new FileController();
        $method = 'edit';
        $data = $application->checkFile($request, $application, $model, $user,$method);
        return $this->sendResponse($application, $data, 200);
    }

    public function downloadFile(Request $request,$id)
    {

        $application = Application::findOrFail($id);
        $model = new FileController();
        $url = $model->fileSave($request, $application->user_id, $application->path_to);
        $application->path_to = $url;
        $application->save();

        return $this->sendResponse($application,'ok',200);
    }



}

