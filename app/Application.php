<?php

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class Application extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'path_to',
        'budget',
        'start_date',
        'end_date',
        'status',
        'percentage_of_completion',
        'created_at',
        'user_id',
        'category_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Метод создание массива для заказа
     *
     * @param $request
     * @param $id
     * @param $fileUrl
     *
     * @return array
     *
     */
    public function generateArrayRequestApplication($request, $id, $fileUrl,$app)
    {

        if($request->name_category){
            $category_id = Category::where('name', '=', $request->name_category)->get()->first()->id;

        }
        else{
           $category_id = $app->category_id;
        }



        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'path_to' => $fileUrl,
            'budget' => $request->budget,
            'created_at' => Carbon::today()->format('Y-m-d'),
            'user_id' => $id,
            'start_date' => Carbon::createFromFormat('d.m.Y', $request->start_date)->format('Y-m-d'),
            'end_date' =>  Carbon::createFromFormat('d.m.Y', $request->end_date)->format('Y-m-d'),
            'category_id' => $category_id
        ];

        return $data;
    }
    /**
     * Метод для проверки загрузки файла
     *
     * @param $request
     * @param $app
     * @param $model
     *  @param $user
     *
     *
     */
    public function checkFile($request, $app, $model, $user,$method)
    {
        if ($method == 'create'){
            if ($request->file) {
                $data = Application::create($app->generateArrayRequestApplication($request,
                    $user->id,
                    $model->fileSave($request, $user,null),null));
            } else {
                $fileUrl = null;
                $data = Application::create($app->generateArrayRequestApplication($request, $user->id, $fileUrl,null));
            }
            return $data;
        }
        if ($method == 'edit' ){

            if ($request->file) {

                $data = Application::update($app->generateArrayRequestApplication($request,
                    $app->user_id,
                    $model->fileSave($request, $user,$app->path_to),$app));

            } else {
                $data = Application::update($app->generateArrayRequestApplication($request,   $app->user_id, $app->path_to,$app));

            }
            return $data;
        }
    }
    /**
     * Метод добавление случайного посредника из всех посредников
     *
     * @param $id_request

     * @return void
     *
     */
    public function getMediatorsAndSetToApplication($id_request)
    {
        $mediator_id = User::where('role_id', '=', 3)->get()->random()->id;
        $dataInput = [
            'request_id' => $id_request,
            'user_id' => $mediator_id

        ];
        $data = Mediator::create($dataInput);
    }
    /**
     * Метод создания массива всех заказов в личный кабинет в зависимости от роли
     *
     * @param $id

     * @return array
     *
     */
    public function checkRolesByRequest($id)
    {
        $user = Auth::user()->role_id;
        if ($user == 1) {
            $request = Application::where('user_id', '=', $id)->get();
        }
        if ($user == 2) {
            $request = Executor::where('user_id', '=', $id)->with('request')->get()
                ->pluck('request');

        }
        if ($user == 3) {
            $request = Mediator::where('user_id', '=', $id)->with('request')->get()
                ->pluck('request');
        }

        return $request;
    }
    /**
     * Метод который возращает результат метода создания массива заказов в личный кабинет
     *
     * @param $id
     *
     * @return array
     *
     */
    public function requestGenerate($id)
    {
        return $this->checkRolesByRequest($id);
    }
    /**
     * Метод создания массива для вывода всех заказчиков , посредников и исполнителей
     *
     * @param $id
     * @param $category
     * @param $mediator
     * @param $user_client
     * @param $executors
     *
     * @return array
     *
     */

    public function generateArrayToApplicationShow($id, $category, $mediator, $user_client, $executors)
    {
        return array(
            [
                'order' => Application::find($id),
                'category_name' => $category,
                'mediator' => $mediator->full_name,
                'client' => $user_client->first()->full_name,
                'executors' => $executors
            ]
        );
    }
    /**
     * Метод создания массива исполнителей
     *
     * @param $id
     * @param $request_executor
     *
     * @return array
     *
     */
    public function generateArrayToExecutors($request_executor, $id)
    {
        $executors = [];
        foreach ($request_executor->pluck('user_id') as $item) {

            $executors = Arr::prepend($executors,
                [
                    'id' => User::where('id', '=', $item)->get()->first()->id,
                    'full_name' => User::where('id', '=', $item)->get()->first()->full_name,
                    'first_name' => User::where('id', '=', $item)->get()->first()->first_name,
                    'client_chose' => Executor::where('request_id', '=', $id)
                        ->where('user_id', '=', $item)->get()->first()->client_chose,
                    'executor_chose' => Executor::where('request_id', '=', $id)
                        ->where('user_id', '=', $item)->get()->first()->executor_chose,

                ]);
        }
        return $executors;
    }


}
