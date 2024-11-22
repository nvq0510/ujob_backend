<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:tasks,id',
            'path' => 'required|string|max:255',
        ], [
            'task_id.required' => 'タスクIDは必須項目です。',
            'task_id.exists' => '指定されたタスクIDが存在しません。',
            'path.required' => '画像パスは必須項目です。',
            'path.string' => '画像パスは文字列である必要があります。',
            'path.max' => '画像パスは255文字以下である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $image = TaskImage::create($request->all());
        return response()->json($image, 201);
    }
}
