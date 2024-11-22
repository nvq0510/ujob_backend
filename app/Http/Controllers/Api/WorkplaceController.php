<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WorkplaceController extends Controller
{
    public function index(Request $request)
    {
        // Lấy số lượng phần tử trên mỗi trang từ request (mặc định là 10)
        $perPage = $request->input('per_page', 10);
    
        // Phân trang và load quan hệ 'rooms'
        $workplaces = Workplace::with('rooms')->paginate($perPage);
    
        // Trả về dữ liệu dạng JSON
        return response()->json([
            'success' => true,
            'message' => 'sussess',
            'data' => $workplaces,
        ], 200);
    }
    
    public function show($id)
    {
        $workplace = Workplace::with('rooms')->find($id);

        if (!$workplace) {
            return response()->json(['error_code' => '404', 'message' => '指定された職場が見つかりません。'], 404);
        }

        return response()->json($workplace);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error_code' => '403', 'message' => 'この操作を行う権限がありません。'], 403);
        }

        $validator = Validator::make($request->all(), [
            'workplace' => 'required|string|max:255',
            'address' => 'required|string',
            'zipcode' => 'nullable|string|max:255',
            'linen' => 'nullable|string|max:255',
            'nearest_laundry' => 'nullable|string',
        ], [
            'workplace.required' => '職場名は必須項目です。',
            'workplace.max' => '職場名は255文字以下である必要があります。',
            'address.required' => '住所は必須項目です。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $workplace = Workplace::create($request->all());
        return response()->json($workplace, 201);
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->role !== 'admin') {
            return response()->json(['error_code' => '403', 'message' => 'この操作を行う権限がありません。'], 403);
        }

        $workplace = Workplace::find($id);

        if (!$workplace) {
            return response()->json(['error_code' => '404', 'message' => '指定された職場が見つかりません。'], 404);
        }

        $validator = Validator::make($request->all(), [
            'workplace' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'zipcode' => 'nullable|string|max:255',
            'linen' => 'nullable|string|max:255',
            'nearest_laundry' => 'nullable|string',
        ], [
            'workplace.required' => '職場名は必須項目です。',
            'workplace.max' => '職場名は255文字以下である必要があります。',
            'address.required' => '住所は必須項目です。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => 'ERROR',
                'errors' => $validator->errors(),
            ], 422);
        }

        $workplace->update($request->all());
        return response()->json($workplace);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error_code' => '403', 'message' => 'この操作を行う権限がありません。'], 403);
        }

        $workplace = Workplace::find($id);

        if (!$workplace) {
            return response()->json(['error_code' => '404', 'message' => '指定された職場が見つかりません。'], 404);
        }

        $workplace->delete();
        return response()->json(['message' => '職場が正常に削除されました。']);
    }
}
