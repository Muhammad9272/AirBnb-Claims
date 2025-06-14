<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\OptionType;
use Illuminate\Http\Request;
use Validator;
use DataTables;
class ProductOptionTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display the DataTables data for option types.
     */
    public function datatables(Request $request)
    {
        $optionTypes = OptionType::all();

        return DataTables::of($optionTypes)
           ->addIndexColumn()
            
            ->addColumn('status', function(OptionType $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.option-types.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.option-types.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
            })
            ->addColumn('action', function(OptionType $data) {
                return '
                    <button class="btn btn-info  edit-option" data-href="' . route('admin.option-types.edit', $data->id) . '">Edit</button>
                   
                ';
                 // <button class="btn btn-danger delete-option" data-href="' . route('admin.option-types.delete', $data->id) . '">Delete</button>
            })
            ->rawColumns([ 'status', 'action'])
            ->make(true);
    }

     public function index()
    {
       
        return view('admin.products.option_types.index');
    }

    /**
     * Show the form for creating a new option type.
     */
    public function create()
    {
        return view('admin.products.option_types.create');
    }

    /**
     * Store a newly created option type in storage.
     */
    public function store(Request $request)
    {
        $rules = [
              'name' => 'required|string|max:255|unique:option_types,name',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        OptionType::create([
            'name' => $request->name,
            'values' => $request->values,
        ]);

        return response()->json([
            'success' => true,
            'msg' => "Option Type has been added successfully!",
            'route' => route('admin.option-types.index'),
        ]);
    }

    /**
     * Show the form for editing an existing option type.
     */
    public function edit($id)
    {
        $optionType = OptionType::findOrFail($id);
        return view('admin.products.option_types.edit', compact('optionType'));
    }

    /**
     * Update the specified option type in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255|unique:option_types,name,' . $id,
            
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $optionType = OptionType::findOrFail($id);
        $optionType->update([
            'name' => $request->name,
            'values' => $request->values,
        ]);

        return response()->json([
            'success' => true,
            'msg' => "Option Type has been updated successfully!",
            'route' => route('admin.option-types.index'),
        ]);
    }

    /**
     * Remove the specified option type from storage.
     */
    public function destroy($id)
    {
        OptionType::destroy($id);
        return response()->json([
            'success' => true,
            'msg' => "Option Type has been deleted successfully!"
        ]);
    }

    /**
     * Update the status of an option type.
     */
    public function updateStatus($id1, $id2)
    {
        $optionType = OptionType::findOrFail($id1);
        $optionType->status = $id2;
        $optionType->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
