<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
USE App\Models\User;

class UserController extends Controller {

    public function signup(Request $req)
    {
        try {
            // Validate the request data
            $validator = Validator::make($req->all(), [
                'fname' => 'required|string|max:255',
                'lname' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
                'confirmPassword' => 'required|same:password',
                'sex' => 'required|in:male,female',
            ]);
    
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Ошибка проверки данных.',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            // Create a new user
            $user = User::create([
                'fname' => $req->fname,
                'lname' => $req->lname,
                'email' => $req->email,
                'password' => bcrypt($req->password),
                'sex' => $req->sex,
            ]);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'msg' => 'Пользователь успешно зарегистрирован!',
                'usr' => $user,
            ], 201);
    
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'msg' => 'Произошла ошибка при обработке вашего запроса.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

}
