<?php

namespace App\Http\Controllers\Admin\Services;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use DataTables;
use App\CentralLogics\Helpers;
use Validator;

class ServiceItemController extends Controller
{
    protected function validateService(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                'max:255',
                $id ? 'unique:services,title,'.$id : 'unique:services,title'
            ],
            'category_id' => 'required|exists:service_categories,id',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'gallery.*' => 'nullable|image',
            'package_labels' => 'required|array|min:1',
            'package_labels.*' => 'required|string|max:255',
            'package_prices' => 'required|array|min:1',
            'package_prices.*' => 'required|numeric|min:0'
        ]);
    }

    protected function processPackages($request)
    {
        $packages = [];
        
        if ($request->has('package_labels') && $request->has('package_prices')) {
            foreach ($request->package_labels as $key => $label) {
                if (!empty($label) && isset($request->package_prices[$key])) {
                    $packages[] = [
                        'label' => $label,
                        'price' => floatval($request->package_prices[$key])
                    ];
                }
            }
        }
        
        return !empty($packages) ? json_encode($packages) : null;
    }

    public function index()
    {
        return view('admin.services.items.index');
    }

    public function datatables()
    {
        $services = Service::with('category')->get();

        return DataTables::of($services)
            ->addIndexColumn()
            ->addColumn('photo', function($service) {
                $photo = json_decode($service->gallery)[0] ?? null;
                return $photo ? '<img src="'.Helpers::image($photo, 'services/').'" class="img-thumbnail" style="width:50px">' : 'No Image';
            })
            ->addColumn('category', function($service) {
                return $service->category ? $service->category->name : 'N/A';
            })
            ->addColumn('price', function($service) {
                return $service->price ? '$'.number_format($service->price, 2) : 'Quote Only';
            })
            ->addColumn('status', function($service) {
                $class = $service->status ? 'drop-success' : 'drop-danger';
                $s = $service->status ? 'selected' : '';
                $ns = !$service->status ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'">
                    <option data-val="1" value="'.route('admin.service.item.status',['id1' => $service->id, 'id2' => 1]).'" '.$s.'>Active</option>
                    <option data-val="0" value="'.route('admin.service.item.status',['id1' => $service->id, 'id2' => 0]).'" '.$ns.'>Inactive</option>
                    </select></div>';
            })
            ->addColumn('action', function($service) {
                return '<div class="action-list">
                    <a href="'.route('admin.service.item.edit', $service->id).'" class="btn btn-info btn-sm">
                        <i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit
                    </a>
                    <button class="btn btn-danger btn-sm delete-item" data-id="'.$service->id.'">
                        <i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete
                    </button>
                </div>';
            })
            ->rawColumns(['photo', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        $categories = ServiceCategory::active()->get();
        return view('admin.services.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateService($request);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $service = new Service();
            $input = $request->except(['gallery', 'package_labels', 'package_prices']);
            
            // Handle gallery upload
            if ($request->hasFile('gallery')) {
                $gallery = [];
                foreach ($request->file('gallery') as $file) {
                    $gallery[] = Helpers::upload('services/', config('fileformats.image'), $file);
                }
                $service->gallery = json_encode($gallery);
            }
            
            // Process packages
            $service->items = $this->processPackages($request);
            
            $service->slug = Helpers::slug($request->title);
            $service->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => 'Service created successfully!',
                'route' => route('admin.service.item.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $data = Service::findOrFail($id);
        $categories = ServiceCategory::active()->get();
        return view('admin.services.items.edit', compact('data', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $validator = $this->validateService($request, $id);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $input = $request->except(['gallery', 'package_labels', 'package_prices']);

            // Handle gallery upload
            if ($request->hasFile('gallery')) {
                // Delete old gallery images
                if ($service->gallery) {
                    foreach (json_decode($service->gallery) as $photo) {
                        Helpers::unlink('services/', $photo);
                    }
                }

                $gallery = [];
                foreach ($request->file('gallery') as $file) {
                    $gallery[] = Helpers::upload('services/', config('fileformats.image'), $file);
                }
                $service->gallery = json_encode($gallery);
            }

            // Process packages
            $service->items = $this->processPackages($request);

            $service->slug = Helpers::slug($request->title);
            $service->fill($input)->save();

            return response()->json([
                'success' => true,
                'msg' => 'Service updated successfully!',
                'route' => route('admin.service.item.edit', $id)
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Delete gallery images
            if ($service->gallery) {
                foreach (json_decode($service->gallery) as $photo) {
                    Helpers::unlink('services/', $photo);
                }
            }
            
            $service->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Service deleted successfully.'
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
        $service = Service::findOrFail($id1);
        $service->status = $id2;
        $service->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
