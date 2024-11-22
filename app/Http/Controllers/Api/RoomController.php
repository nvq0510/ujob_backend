<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with('workplace')->get();
    
        if ($rooms->isEmpty()) {
            return response()->json([
                'error_code' => '404',
                'message' => '部屋が見つかりません。',
            ], 404);
        }
    
        return response()->json($rooms, 200);
    }
    
    public function show($id)
    {
        $room = Room::with('workplace')->find($id);
        if (!$room) {
            return response()->json([
                'error_code' => '404',
                'message' => '指定された部屋が見つかりません。',
            ], 404);
        }
        return response()->json($room, 200);
    }
    

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error_code' => '403', 'message' => 'この操作を行う権限がありません。'], 403);
        }

        $validator = Validator::make($request->all(), [
            'workplace_id' => 'required|exists:workplaces,id',
            'room_number' => 'required|string|max:50|unique:rooms,room_number,NULL,id,workplace_id,' . $request->workplace_id,
            'room_type' => 'required|string|max:50',
        ], [
            'workplace_id.required' => '職場IDは必須項目です。',
            'workplace_id.exists' => '指定された職場IDが存在しません。',
            'room_number.required' => '部屋番号は必須項目です。',
            'room_number.unique' => '同じ職場内で部屋番号が重複しています。',
            'room_type.required' => '部屋タイプは必須項目です。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors()
            ], 422);
        }

        $room = Room::create($request->all());
        return response()->json($room, 201);
    }
    
    

    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error_code' => '403', 'message' => 'この操作を行う権限がありません。'], 403);
        }
    
        $room = Room::find($id);
    
        if (!$room) {
            return response()->json(['error_code' => '404', 'message' => '指定された部屋が見つかりません。'], 404);
        }
    
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $id . ',id,workplace_id,' . $room->workplace_id,
            'room_type' => 'required|string|max:50',
        ], [
            'room_number.required' => '部屋番号は必須項目です。',
            'room_number.unique' => '同じ職場内で部屋番号が重複しています。',
            'room_type.required' => '部屋タイプは必須項目です。',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $room->update($request->all());
        return response()->json($room);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'error_code' => '403',
                'message' => 'この操作を行う権限がありません。',
            ], 403);
        }
    
        $room = Room::find($id);
    
        if (!$room) {
            return response()->json([
                'error_code' => '404',
                'message' => '指定された部屋が見つかりません。',
            ], 404);
        }
    
        $room->delete();
    
        return response()->json([
            'message' => '部屋が正常に削除されました。',
        ], 200);
    }
    
}
