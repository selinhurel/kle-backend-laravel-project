<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'İlk önce giriş yapmalısınız.'
                ], 401);
            }

            $products = Product::all();

            return response()->json([
                'status' => 'success',
                'products' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
{
    try {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'İlk önce giriş yapmalısınız.'
            ], 401);
        }

        $request->validate([
            'name' => 'required|min:3',
            'price' => 'required|numeric',
            'description' => 'required'
        ], [
            'name.required' => 'Ürün adı alanı boş bırakılamaz.',
            'price.required' => 'Ürün fiyatı alanı boş bırakılamaz.',
            'price.numeric' => 'Lütfen ürün fiyatı alanına sayı giriniz.',
            'description.required' => 'Açıklama alanı boş bırakılamaz.',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Ürün başarıyla oluşturuldu.',
            'product' => $product
        ], 201);
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


       

    public function show($id)
    {
        try {
            $user = Auth::guard('api')->user();
    
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'İlk önce giriş yapmalısınız.'
                ], 401);
            }
    
            $product = Product::find($id);
    
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ürün bulunamadı.'
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'product' => $product,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ürün getirilirken bir hata oluştu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    

    public function update(Request $request, $id): JsonResponse
{
    try {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'İlk önce giriş yapmalısınız.'
            ], 401);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|min:3',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required'
        ], [
            'name.required' => 'Ürün adı alanı boş bırakılmamalıdır.',
            'price.required' => 'Ürün fiyatı alanı boş bırakılmamalıdır.',
            'price.numeric' => 'Ürün fiyatı sayı olmalıdır.',
            'description.required' => 'Açıklama alanı boş bırakılmamalıdır.',
        ]);

        $product->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Ürün başarıyla güncellendi.',
            'product' => $product
        ]);
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


    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'İlk önce giriş yapmalısınız.'
                ], 401);
            }

            $product = Product::find($id);

            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $product->delete();

            return response()->json(['message' => 'Ürün başarıyla kaldırıldı.']);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
