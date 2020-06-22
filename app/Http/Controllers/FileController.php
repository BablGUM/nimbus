<?php

namespace App\Http\Controllers;

use App\Document;
use App\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;

class FileController extends Controller
{
    /**
     * Скачивание файла по ссылке
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @Rest\Get("/file/download")
     */
    public function fileDownload(Request $request)
    {
        return response()->download(public_path($request->link), $request->fileName);
    }
    /**
     * Просмотр файла по ссылке
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @Rest\Get("/file/check")
     */
    public function fileCheck(Request $request)
    {
        return response()->file(public_path($request->link));
    }
    /**
     * Загрузка файла
     *
     * @param Request $request
     * @param Request $user
     *
     * @return mixed
     *
     * @Rest\Get("/file/download")
     */
    public function fileSave(Request $request,$user,$path_to)
    {
        if(!$path_to) {


            $file = new Filesystem();
            $fileName = $request->file->getClientOriginalName();
            $fileName = str_replace(" ", "", $fileName);
            $code = \Illuminate\Support\Str::random(10);

            $file->makeDirectory(public_path('/file/' . $code), 0777, true, true);

            $path = $request->file('file')->move(public_path('/file/' . $code), $fileName);
            $photoURL = '/file/' . $code . '/' . $fileName;

            return $photoURL;
        }
        else {
            $file = new Filesystem();
            $fileName = $request->file->getClientOriginalName();
            $fileName = str_replace(" ", "", $fileName);
            $code = substr($path_to,6,10);
            $file->delete(public_path($path_to));
            $path = $request->file('file')->move(public_path('/file/' . $code), $fileName);

            $photoURL = '/file/' . $code . '/' . $fileName;
            return $photoURL;
        }
    }


    public function uploadFileInRequest(Request $request, $id)
    {
        $file = new Filesystem();
        $fileMethod = new File();
        $fileContract = $request->fileContract;
        $fileReports = $request->fileReports;
        $fileOthers = $request->fileOthers;

        $fileName = $request->file->getClientOriginalName();
        $fileName = str_replace(" ", "", $fileName);
        $photoURL = '/request/' . $id . '/' . $fileName;
        $doc = \App\Document::where('path_to', '=', $photoURL)->count();
        if ($doc > 0) {
            return response()->json('Файл с таким названием уже существует', 401);
        } else {
            $file->makeDirectory(public_path('/request/' . $id), 0777, true, true);
            $file->makeDirectory(public_path('/request/' . $id . '/contract'), 0777, true, true);
            $file->makeDirectory(public_path('/request/' . $id . '/reports'), 0777, true, true);
            $file->makeDirectory(public_path('/request/' . $id . '/others'), 0777, true, true);
            $path = $request->file('file')->move(public_path('/request/' . $id), $fileName);
            $data = [
                'request_id' => $id,
                'path_to' => $photoURL,
                'name_file' => $fileName
            ];
            $data = \App\Document::create($data);
            return $this->sendResponse($data, 'ok', 200);
        }

    }

    public function index($id)
    {
        $doc = Document::where('request_id','=',$id)->get();

        return $this->sendResponse($doc,'OK',200);
    }





}
