<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class BlogController extends Controller
{
    protected function validateBlog(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:blogs,title,'.$id : 'unique:blogs,title'
            ],
            'category_id' => 'required|exists:blog_categories,id',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'photo' => 'nullable|image',
            'tags' => 'nullable|string',
            'featured' => 'nullable|boolean'
        ]);
    }

    public function index()
    {
        return view('admin.blog.posts.index');
    }

    public function datatables()
    {
        $posts = Blog::with('category')->get();

        return DataTables::of($posts)
            ->addIndexColumn()
            ->addColumn('photo', function($post) {
                return $post->photo ? '<img src="'.Helpers::image($post->photo, 'blog/').'" class="img-thumbnail" style="width:50px">' : 'No Image';
            })
            ->addColumn('category', function($post) {
                return $post->category ? $post->category->name : 'N/A';
            })
            ->addColumn('status', function($post) {
                $class = $post->status ? 'drop-success' : 'drop-danger';
                $s = $post->status ? 'selected' : '';
                $ns = !$post->status ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'.route('admin.blog.status',['id1' => $post->id, 'id2' => 1]).'" '.$s.'>Activated</option>
                    <option data-val="0" value="'.route('admin.blog.status',['id1' => $post->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option>
                    </select></div>';
            })
            ->addColumn('featured', function($post) {
                return $post->featured ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>';
            })
            ->addColumn('action', function($post) {
                return '<div class="action-list">
                    <a href="'.route('admin.blog.edit', $post->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                    <button class="btn btn-danger btn-sm delete-item" data-href="'.route('admin.blog.delete', $post->id).'">
                        <i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete
                    </button>
                </div>';
            })
            ->rawColumns(['photo', 'status', 'featured', 'action'])
            ->make(true);
    }

    public function create()
    {
        $categories = BlogCategory::active()->get();
        return view('admin.blog.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateBlog($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $blog = new Blog();
            $input = $request->except(['tags']);
            
            if ($request->hasFile('photo')) {
                $blog->photo = Helpers::upload('blog/', config('fileformats.image'), $request->file('photo'));
            }
            
            // Process tags
            if ($request->has('tags')) {
                $tags = array_filter(array_map('trim', explode(',', $request->tags)));
                $blog->tags = !empty($tags) ? $tags : null;
            }
            
            $blog->slug = Helpers::slug($request->title);
            $blog->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Blog post created successfully!",
                'route' => route('admin.blog.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function edit($id)
    {
        $data = Blog::findOrFail($id);
        $categories = BlogCategory::active()->get();
        return view('admin.blog.posts.edit', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $validator = $this->validateBlog($request, $id);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        try {
            $input = $request->except(['tags']);

            if ($request->hasFile('photo')) {
                $blog->photo = Helpers::update('blog/', $blog->photo, 
                    config('fileformats.image'), $request->file('photo'));
            }

            // Process tags
            if ($request->has('tags')) {
                $tags = array_filter(array_map('trim', explode(',', $request->tags)));
                $blog->tags = !empty($tags) ? $tags : null;
            }

            $blog->slug = Helpers::slug($request->title);
            $blog->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => "Blog post updated successfully!",
                'route' => route('admin.blog.edit', $id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => "Error: " . $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            
            if ($blog->photo) {
                Helpers::unlink('blog/', $blog->photo);
            }
            
            $blog->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Blog post deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function status($id1, $id2)
    {
        $blog = Blog::findOrFail($id1);
        $blog->status = $id2;
        $blog->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
