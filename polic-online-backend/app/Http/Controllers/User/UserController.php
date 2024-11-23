<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
USE App\Models\User;

class UserController extends Controller {

    /**
     * Signup method
     * @param Request $req
     * @return string JSON return
     */
    public function signup(Request $req): string{
        try {
            // Validate the request data
            $validator = Validator::make($req->all(), [
                'fnam1e' => 'required|string|max:255',
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
                ], 422)->getContent();
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
            ], 201)->getContent();
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'msg' => 'Произошла ошибка при обработке вашего запроса.',
                'error' => $e->getMessage(),
            ], 500)->getContent();
        }
    }
    /**
     * login method
     * @param Request $req
     * @return string JSON return
     */
    public function login(Request $req): string{
        try {
            // Validate incoming request
            $validator = Validator::make($req->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Ошибка проверки данных.',
                    'errors' => $validator->errors(),
                ], 422)->getContent();
            }

            // Attempt to authenticate the user
            if (!Auth::attempt($req->only('email', 'password'))) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Неверные учетные данные.',
                ], 401)->getContent(); // Unauthorized
            }

            // Get the authenticated user
            $user = Auth::user();

            // Generate a token for the user
            $token = $req->user()->createToken('API Token');



            // Return success response
            return response()->json([
                'status' => 'success',
                'msg' => 'Успешный вход!',
                'token' => $token,
                'user' => $user,
            ], 200)->getContent();
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Произошла ошибка при обработке вашего запроса.',
                'error' => $e->getMessage(), // For debugging
            ], 500)->getContent(); // Internal Server Error
        }
    }
    /**
     * User: to get the current user using a token
     * @param Request $req
     * @return string JSON return
    */    
    public function user(){
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

}
