<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Filesystem\Filesystem;
use function PHPSTORM_META\type;
use Spatie\Image\Image;

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

        if($request->type == 'doc')
        {
            $count = \App\Models\Document::where('request_id', '=', $id)->count();
            $file_s = new Filesystem();
            $size = count($_FILES['files']['name']);
            $value = $size + $count;
            if($request->hasfile('files')) {
                foreach ($request->files as $file) {
                    for($j = 0;$j < $size;$j++){
                        $fileName =  $value . '.' . $file[$j]->getClientOriginalExtension();
                        $value = $value + 1;
                        $originalName = str_replace(" ", "",$file[$j]->getClientOriginalName());
                        $photoURL = '/request/' . $id . '/documents' .'/' . $fileName;
                        $file_s->makeDirectory(public_path('/request/' . $id . '/documents/'),
                            0777, true, true);
                        $data = [
                            'request_id' => $id,
                            'path_to' => $photoURL,
                            'name_file' => $originalName,
                            'type' => 'doc',
                        ];
                        $file[$j]->move(public_path('/request/' . $id . '/documents/'), $fileName);
                        Document::create($data);

                    }
                }
            }
        }
        if ($request->type == 'reports')
        {
            $file_s = new Filesystem();
            $size = count($_FILES['files']['name']);
            $value = $size + \App\Models\Document::where('request_id', '=', $id)->count();
            if($request->hasfile('files')) {
                foreach ($request->files as $file) {
                    for($j = 0;$j < $size;$j++){
                        $fileName =  $value . '.' . $file[$j]->getClientOriginalExtension();
                        $value = $value + 1;
                        $originalName = str_replace(" ", "",$file[$j]->getClientOriginalName());
                        $photoURL = '/request/' . $id . '/reports' . '/' . $fileName;
                        $file_s->makeDirectory(public_path('/request/' . $id . '/reports/'),
                            0777, true, true);
                        $data = [
                            'request_id' => $id,
                            'path_to' => $photoURL,
                            'name_file' => $originalName,
                            'type' => 'reports',
                        ];
                        $file[$j]->move(public_path('/request/' . $id . '/reports/'), $fileName);
                        Document::create($data);
                    }
                }
            }
        }

    }

    public function index(Request $request,$id)
    {
        if ($request->type == 'doc'){
            $doc = Document::where('request_id','=',$id)->where('type','=',$request->type)->get();
        }

        if ($request->type == 'reports'){
            $doc = Document::where('request_id','=',$id)->where('type','=',$request->type)->get();
        }

        return $this->sendResponse($doc,'OK',200);
    }





}
