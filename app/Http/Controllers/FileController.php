<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
     * @Rest\Get("/file/check")
     */
    public function fileSave(Request $request,$user)
    {

        $file = new Filesystem();
        $fileName = $request->file->getClientOriginalName();
        $code = \Illuminate\Support\Str::random(10);

        $file->makeDirectory(public_path('/file/'.$code),0777,true,true);

        $path = $request->file('file')->move(public_path('/file/'.$code), $fileName);
        $photoURL  = '/file/'.$code.'/'.$fileName;
        return $photoURL;
    }


}
