<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Kiểm tra đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'メールアドレスは必須項目です。',
            'email.email' => '正しいメールアドレス形式で入力してください。',
            'password.required' => 'パスワードは必須項目です。',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error_code' => '422',
                'message' => '入力にエラーがあります。',
                'errors' => $validator->errors(),
            ], 422);
        }
    
        // Xác thực người dùng
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
    
            // Trả về phản hồi JSON kèm vai trò
            return response()->json([
                'message' => 'ログインに成功しました。',
                'token' => $token,
                'role' => $user->role, // Thêm vai trò của người dùng
                'user' => $user,
            ], 200);
        }
    
        // Thông báo lỗi nếu xác thực thất bại
        return response()->json([
            'error_code' => '401',
            'message' => 'メールアドレスまたはパスワードが正しくありません。',
        ], 401);
    }
    

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'ログアウトに成功しました。',
        ], 200);
    }
}
