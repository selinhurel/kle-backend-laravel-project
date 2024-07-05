<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Exception;

class UserController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $fields = $request->validate([
                'name' => 'required|string|regex:/^[\pL\s\-]+$/u|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|max:255|confirmed'
            ], [
                'name.required' => 'İsim alanı boş bırakılamaz.',
                'name.string' => 'İsim alanı sadece karakterlerden oluşmalıdır.',
                'name.regex' => 'İsim alanı sadece harfler ve boşluklar içerebilir.',
                'name.max' => 'İsim alanı 255 karakterden uzun olamaz.',
                'email.required' => 'Email alanı boş bırakılamaz.',
                'email.email' => 'Lütfen geçerli bir email adresi giriniz.',
                'email.unique' => 'Bu email adresi zaten kullanılıyor.',
                'email.max' => 'Email alanı 255 karakterden uzun olamaz.',
                'password.required' => 'Şifre alanı boş bırakılamaz.',
                'password.string' => 'Şifre sadece metin olarak kabul edilir.',
                'password.min' => 'Şifre en az 8 karakter uzunluğunda olmalıdır.',
                'password.max' => 'Şifre 255 karakterden uzun olamaz.',
                'password.confirmed' => 'Şifreler eşleşmiyor.'
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => Hash::make($fields['password'])
            ]);

            $response = [
                'status' => 'success',
                'user' => $user,
                'message' => 'Kayıt Başarılı. Lütfen giriş yapın.'
            ];

            return response()->json($response, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $fields = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string'
            ], [
                'email.required' => 'Email alanı boş bırakılamaz.',
                'email.email' => 'Lütfen geçerli bir e-posta adresi giriniz.',
                'password.required' => 'Şifre alanı boş bırakılamaz.',
                'password.string' => 'Şifre alanı bir metin olmalıdır.'
            ]);

            $user = User::where('email', $fields['email'])->first();

            if (!$user || !Hash::check($fields['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'E-posta adresi ya da şifre hatalı.'
                ], 401);
            }

            $token = $user->createToken('myapptoken')->plainTextToken;

            $response = [
                'status' => 'success',
                'user' => $user,
                'token' => $token,
                'message' => 'Giriş başarılı'
            ];

            return response()->json($response, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geçersiz giriş.',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Veritabanı hatası.',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Çıkış başarılı'], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Çıkış yapılırken bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
