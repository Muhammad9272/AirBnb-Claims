<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use DataTables;
use Session;
class LocationController extends Controller
{

    public function __construct(Request $request){
        $this->middleware('auth:admin');
        $this->request = $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {   
        $datas=Location::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                             ->addIndexColumn()
                            ->addColumn('status', function(Location $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.location.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.location.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })

                            ->addColumn('action', function(Location $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.location.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                                <a data-href="' . route('admin.location.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>

                                </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {
        return view('admin.locations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        $locations=Location::where('status',1)->get();
        return view('admin.locations.create',compact('locations'));
    }

    public function createImport($value='')
    {   
        $locations=Location::where('status',1)->get();
        return view('admin.locations.import',compact('locations'));
    }

    public function importSubmit(Request $request)
    {   
        $log = "";
        //--- Validation Section
         $this->validate($request,[
            'csvfile'      => 'required|mimes:csv,txt',
        ]);

        $filename = '';
        if ($file = $request->file('csvfile')) {
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move('assets/temp_files', $filename);
        }

        $datas = "";

        $file = fopen(public_path('assets/temp_files/' . $filename), "r");
        $i = 1;
        while (($line = fgetcsv($file)) !== FALSE) {

            if ($i != 1) {

                if (!Location::where('name', $line[0])->exists()) {
                    //--- Logic Section
                    $data = new location;
                    $data->name= $line[0];
                    // Save Data
                    $data->save();

                    // Set SLug
                    $loc = Location::find($data->id);
                    $loc->slug = str_slug($data->name, '-');
                    $loc->update();

                     
                } else {
                    $log .= "<br>Row No: " . $i . " - Duplicate location name!<br>";
                }
            }

            $i++;
        }
        fclose($file);
        //--- Redirect Section
        $msg = 'Bulk Location File Imported Successfully.<a class="text-primary" href="' . route('admin.location.index') . '">View Locations.</a>' . $log;
        Session::flash('message', $msg);
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
       
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $this->validate($request,[
            'name' => 'required|unique:locations',
        ]);
        $data=new location();
        $data->name=$request->name;
        $data->save();

        // Set SLug
        $location = Location::find($data->id);
        $location->slug = str_slug($data->name, '-');
        $location->update();
        toastr()->success('Data has been saved successfully!');
        return redirect()->route('admin.location.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $locations=Location::where('status',1)->get();
        $data=Location::find($id);
        return view('admin.locations.edit',compact('data','locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request,[
            'name' => 'required|unique:locations,name,'.$id,
        ]);
        $data=Location::find($id);
        $data->name=$request->name;
        $data->update();

        // Set SLug
        $location = Location::find($data->id);
        $location->slug = str_slug($data->name, '-');
        $location->update();
        toastr('Data has been updated successfully!', 'success');
        return redirect()->route('admin.location.index');
    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Location::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $data = Location::findOrFail($id);
        $data->delete();
    }
}