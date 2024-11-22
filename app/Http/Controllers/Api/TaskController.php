<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'workplace_id' => 'required|exists:workplaces,id',
            'task_date' => 'required|date',
            'priority' => 'required|in:Normal,High',
            'status' => 'required|in:Pending,In Progress,Completed',
        ], [
            'user_id.required' => 'ユーザーIDは必須項目です。',
            'user_id.exists' => '指定されたユーザーIDが存在しません。',
            'workplace_id.required' => '職場IDは必須項目です。',
            'workplace_id.exists' => '指定された職場IDが存在しません。',
            'task_date.required' => 'タスク日付は必須項目です。',
            'task_date.date' => 'タスク日付の形式が正しくありません。',
            'priority.required' => '優先度は必須項目です。',
            'priority.in' => '優先度は「Normal」または「High」である必要があります。',
            'status.required' => 'ステータスは必須項目です。',
            'status.in' => 'ステータスは「Pending」、「In Progress」または「Completed」である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = Task::create($request->all());
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error_code' => '404', 'message' => '指定されたタスクが見つかりません。'], 404);
        }

        $validator = Validator::make($request->all(), [
            'priority' => 'sometimes|required|in:Normal,High',
            'status' => 'sometimes|required|in:Pending,In Progress,Completed',
        ], [
            'priority.in' => '優先度は「Normal」または「High」である必要があります。',
            'status.in' => 'ステータスは「Pending」、「In Progress」または「Completed」である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task->update($request->all());
        return response()->json($task);
    }
}
