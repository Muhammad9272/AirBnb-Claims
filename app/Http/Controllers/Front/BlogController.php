<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('category')->active();
        
        // Category filter
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search filter
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('content', 'like', '%'.$request->search.'%');
            });
        }

        $posts = $query->latest()->paginate(12);
        $categories = BlogCategory::active()->withCount('blogs')->get();
        $featured = Blog::active()->featured()->latest()->take(5)->get();

        return view('front.blog.index', compact('posts', 'categories', 'featured'));
    }

    public function show($slug)
    {
        $post = Blog::with('category')->active()->where('slug', $slug)->firstOrFail();
        
        // Increment view count
        $post->increment('views');
        
        // Get related posts from same category
        $related = Blog::active()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

        return view('front.blog.show', compact('post', 'related'));
    }
}
