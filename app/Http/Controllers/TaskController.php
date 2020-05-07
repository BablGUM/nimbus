<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * @var int $success_status Status
     */
    private $success_status = 200;
    /**
     * Метод редактирования дела
     *
     * @param Request $request Request
     *
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @Rest\Post("/update-task")
     */
    public function updateTask(Request $request)
    {
        $user = Auth::user();
        $task = Task::where('id', $request->id)
            ->where('user_id', $user->id)
            ->update($request->all());

        if ($task) {
            $success = 'Подзадача успешно обновлена';
        } else {
            throw new \Exception('Subtask not updated');
        }
        return response()->json(['success' => $success], $this->success_status);
    }

    /**
     * Метод отметить задачу как сделанную
     *
     * @param Request $request Request
     * @param Request $id integer
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @Rest\Delete("/mark-done/{id}")
     */
    public function markTask(Request $request, $id)
    {
        $task = Task::find($id);
        $status = [
            'status' => 'true'
        ];
        $response = Task::where('id', $request->id)->update($status);
        if ($task && $response) {
            $success = 'Подзадача отмечена как сделанная';
        } else {
            throw new \Exception('Task not found');
        }
        return response()->json(['success' => $success], $this->success_status);
    }

    public function showTask(Request $request)
    {
        return Task::all();
    }

}
