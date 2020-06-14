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

class MediatorController extends Controller
{
    public function edit(Request $request,$id)
    {
        $application = Application::findOrFail($id);
        $user = Auth::user();

        $model = new FileController();
        $method = 'edit';
        $data = $application->checkFile($request, $application, $model, $user,$method);
//        $application->update($request->all());
//        $application->save();
        return $this->sendResponse($application, $data, 200);
    }




}

