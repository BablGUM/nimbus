<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskListController extends Controller
{
    /**
     * Метод создания нового списка , name string , $user->id int
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Post("/create-list")
     */

    public function createList(Request $request)
    {
        $user = Auth::user();
        $updateDate = [
            'name' => $request->name,
            'user_id' => $user->id
        ];
        $response = TaskList::create($updateDate);
        if ($response) {
            return response()->json($response, 201);
        } else {
            throw new \Exception('List not created');
        }
    }

    /**
     * Метод создания нового дела (элемента списка) ' , name_list string , $user->id int
     *
     * @param Request $request Request
     * @param Request $id_list integer
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Post("/create-list/{id_list}/item")
     */

    public function createItemTask(Request $request, $id_list)
    {
        $user = Auth::user();
        $response = Task::create(
            [
                'name_list' => $request->name_list,
                'user_id' => $user->id,
                'task_list_id' => $id_list,
                'status' => 0
            ]
        );
        if ($response) {
            return response()->json($response, 201);
        } else {
            throw new \Exception('Item task not created');
        }
    }

    /**
     * Метод просмотра всех списков
     *
     * @param Request $request Request
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Get("/show-list")
     */
    public function showList(Request $request)
    {
        if ($request->size > 100) {
            $request->size = 10;
        }
        $response = TaskList::with('task')
            ->orderBy('name', 'asc')
            ->limit($request->size)
            ->get();

        return response()->json([$response], 200);
    }


    /**
     * Метод просмотра конкретного списка '
     *
     * @param Request $id_list integer
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Get("/show-list/{id_list}")
     */
    public function showListByID($id_list)
    {
        $response = TaskList::with('task')->find($id_list);
        if ($response) {
            return response()->json([$response], 200);
        } else {
            return response()->json(
                [
                    'message' => 'Задача не найдена'
                ],
                404
            );
        }
    }

    /**
     * Метод удаления списка
     *
     * @param Request $id_list integer
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Delete("/delete-list/{id_list}")
     */
    public function listDelete($id_list)
    {
        $response = TaskList::find($id_list);

        if ($response) {
            $response->delete();

            return response()->json(['Список удален'], 200);
        } else {
            throw new \Exception('List not found');
        }
    }
    /**
     * Метод редактирования списка
     *
     * @param Request $request Request
     * @param Request $id_list integer
     *
     * @return mixed
     *
     * @throws \Exception
     *
     *
     * @Rest\Post("/update-list/{id_list}")
     */
    public function listUpdate(Request $request, $id_list)
    {
        $response = TaskList::find($id_list);

        if ($response) {
            $response->name = $request->name;
            $response->save();
            return response()->json(
                [
                    'attributes' => $response
                ],
                201
            );
        } else {
            throw new \Exception('List not found');
        }
    }
    /**
     * Метод редактирования списка
     *
     * @param Request $id_list integer
     * @param Request $item_id intger
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @Rest\Delete("/delete-list/{id_list}/item/{item_id}")
     */
    public function itemDelete($id_list, $item_id)
    {
        $item = Task::where('id', $item_id)->where('task_list_id', '=', $id_list)->first();

        if ($item) {
            $item->delete();
            return response()->json([], 204);
        } else {
            throw new \Exception('Task not found');
        }
    }


}
