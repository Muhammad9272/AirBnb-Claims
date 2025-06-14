<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NostalgiaItem;
use App\Models\NostalgiaCategory;
use Illuminate\Http\Request;

class NostalgiaController extends Controller
{
    public function index(Request $request)
    {
        $query = NostalgiaItem::with(['category', 'subcategory', 'childcategory'])->active();
        
        // Category filters using slugs
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        if ($request->subcategory) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }
        if ($request->childcategory) {
            $query->whereHas('childcategory', function($q) use ($request) {
                $q->where('slug', $request->childcategory);
            });
        }

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%')
                  ->orWhereRaw('tags LIKE ?', ['%"'.$request->search.'"%']);
            });
        }

        // Year filter
        if ($request->year) {
            $query->where('release_year', $request->year);
        }

        $items = $query
                ->orderBy('views', 'desc')
                ->paginate(12);
        $categories = NostalgiaCategory::main()->active()->get();
        $years = NostalgiaItem::active()
            ->selectRaw('DISTINCT release_year')
            ->whereNotNull('release_year')
            ->orderBy('release_year', 'desc')
            ->pluck('release_year');

        return view('front.nostalgia.index', compact('items', 'categories', 'years'));
    }

    public function show($slug)
    {
        $item = NostalgiaItem::with(['category', 'subcategory', 'childcategory'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();
        
        // Increment view count
        $item->increment('views');
        
        // Get related items
        $related = NostalgiaItem::active()
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(3)
            ->get();

        return view('front.nostalgia.show', compact('item', 'related'));
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = NostalgiaCategory::where('parent_id', $categoryId)
            ->where('level', 2)
            ->active()
            ->get(['id', 'name', 'slug']);
            
        return response()->json($subcategories);
    }

    public function getChildcategories($subcategoryId)
    {
        $childcategories = NostalgiaCategory::where('parent_id', $subcategoryId)
            ->where('level', 3)
            ->active()
            ->get(['id', 'name', 'slug']);
            
        return response()->json($childcategories);
    }
}
