<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskRoomController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'room_id' => 'required|exists:rooms,id',
            'status' => 'required|in:Vacant,IN',
        ], [
            'task_id.required' => 'タスクIDは必須項目です。',
            'task_id.exists' => '指定されたタスクIDが存在しません。',
            'room_id.required' => '部屋IDは必須項目です。',
            'room_id.exists' => '指定された部屋IDが存在しません。',
            'status.required' => 'ステータスは必須項目です。',
            'status.in' => 'ステータスは「Vacant」または「IN」である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $taskRoom = TaskRoom::create($request->all());
        return response()->json($taskRoom, 201);
    }
}
