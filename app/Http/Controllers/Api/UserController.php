<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Chỉ admin được phép xem danh sách user
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'error_code' => '403',
                'message' => 'この操作を行う権限がありません。',
            ], 403);
        }

        $users = User::paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $users,
        ], 200);
    }

    public function show($id)
    {
        $currentUser = Auth::user();

        // User chỉ có thể xem thông tin của chính họ
        if ($currentUser->role === 'user' && $currentUser->id != $id) {
            return response()->json([
                'error_code' => '403',
                'message' => '他のユーザーの情報を閲覧する権限がありません。',
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error_code' => '404',
                'message' => '指定されたユーザーが見つかりません。',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'ユーザー情報を取得しました。',
            'data' => $user,
        ], 200);
    }

    public function store(Request $request)
    {
        // Chỉ admin được phép tạo user mới
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'error_code' => '403',
                'message' => 'この操作を行う権限がありません。',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
            'zipcode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => '名前は必須項目です。',
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは6文字以上である必要があります。',
            'role.required' => '役割は必須項目です。',
            'role.in' => '役割は「user」または「admin」である必要があります。',
            'status.required' => 'ステータスは必須項目です。',
            'status.in' => 'ステータスは「active」または「inactive」である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => '入力にエラーがあります。',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'zipcode' => $request->zipcode,
            'address' => $request->address,
            'role' => $request->role ?? 'user',
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ユーザーが正常に作成されました。',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $currentUser = Auth::user();

        // User chỉ được cập nhật thông tin của chính họ
        if ($currentUser->role === 'user' && $currentUser->id != $id) {
            return response()->json([
                'error_code' => '403',
                'message' => '他のユーザーの情報を更新する権限がありません。',
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error_code' => '404',
                'message' => '指定されたユーザーが見つかりません。',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:6',
            'phone' => 'nullable|string|max:20',
            'zipcode' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'role' => 'sometimes|required|in:user,admin',
            'status' => 'sometimes|required|in:active,inactive',
        ], [
            'name.required' => '名前は必須項目です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.min' => 'パスワードは6文字以上である必要があります。',
            'role.in' => '役割は「user」または「admin」である必要があります。',
            'status.in' => 'ステータスは「active」または「inactive」である必要があります。',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => '入力にエラーがあります。',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'ユーザー情報が正常に更新されました。',
            'data' => $user,
        ], 200);
    }

    public function destroy($id)
    {
        // Chỉ admin được phép xóa user
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'error_code' => '403',
                'message' => 'この操作を行う権限がありません。',
            ], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'error_code' => '404',
                'message' => '指定されたユーザーが見つかりません。',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'ユーザーが正常に削除されました。',
        ], 200);
    }
}
