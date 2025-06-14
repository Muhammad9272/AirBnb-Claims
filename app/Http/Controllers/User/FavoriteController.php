<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
     public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }


    public function favorite(Request $request, $type, $id)
    {
        $user = $request->user();
        $favoriteable_type = "App\\Models\\" . ucfirst($type);
        try {
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'favoriteable_type' => $favoriteable_type,
                'favoriteable_id' => $id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Item favorited successfully',
            ]);
        } catch (\Exception $e) {
            // Log the exception or display an error message
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function unfavorite(Request $request, $type, $id)
    {
        $user = $request->user();
        $favoriteable_type = "App\\Models\\" . ucfirst($type);
        try {
            $favorite = Favorite::where([
                'user_id' => $user->id,
                'favoriteable_type' => $favoriteable_type,
                'favoriteable_id' => $id,
            ])->first();

            if ($favorite) {
                $favorite->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Item unfavorited successfully',
            ]);
        } catch (\Exception $e) {
            // Log the exception or display an error message
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}



