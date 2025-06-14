@if(isset($data->options))
@foreach(json_decode($data->options) as $option)
<div class="row quizdiv">
    <div class="col-md-10">
        <input type="text" name="options[]" class="form-control mb-3"   placeholder="Enter Options" value="{{$option}}" >
    </div> 
    <div class="col-md-2">
         <div style="">
             <a href="javascript:;" class="btn btn-danger removeQuizdiv btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></a>
         </div> 
    </div>
</div>
@endforeach
@else
<div class="row quizdiv">
    <div class="col-md-10">
        <input type="text" name="options[]" class="form-control mb-3"   placeholder="Enter Options" value="" >
    </div> 
    <div class="col-md-2">
         <div style="">
             <a href="javascript:;" class="btn btn-danger removeQuizdiv btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></a>
         </div> 
    </div>
</div>
@endif