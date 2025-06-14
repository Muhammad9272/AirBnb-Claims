@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Email Campiagn
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href=""> Email campaign  </a>
        @endslot
        @slot('title')
            Send
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

                <div class="card p-4">
                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.users.email.campaign.submit') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label">User Type </label>
                                    <select class="form-select" name="user_type"   >
                                        <option value="1">All Users</option>
                                        <option value="2">Non Subscribed</option>
                                        <option value="3">Subscribed Users</option>
                                    </select>
                                </div>
                                <div class="mb-3 d-done for_subscribed">
                                    <label class="form-label">MemberShip Level </label>
                                    <select data-choices name="subplan_ids[]" multiple="multiple"  >
                                        @foreach($subplans as $subplan)
                                            <option value="{{$subplan->id}}" @if(isset($data->subplan_ids)){{ in_array($subplan->id, json_decode($data->subplan_ids)) ? 'selected' : '' }}@endif>{{$subplan->name }} ({{$subplan->interval}})</option>
                                        @endforeach

                                    </select>
                                </div>
                                <div class="mb-3 d-done for_subscribed">
                                    <label class="form-label">MemberShip Status </label>
                                    <select data-choices name="membership_status[]" multiple="multiple"  >
                                       <option value="active">Active</option>
                                       <option value="expired">Expired</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Program Pref. </label>
                                    <select data-choices name="program_pref[]"  multiple="multiple" >
                                       <option value="internship">Internship</option>
                                       <option value="graduate">Graduate</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Registration Time </label>
                                    <select data-choices name="registration_time[]" multiple="multiple"  >
                                       <option value="one_week"> One Week</option>
                                       <option value="one_month"> One Month</option>
                                       <option value="six_month"> Six Month</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="meta-description-input">  Subject</label>
                                    <input class="form-control" name="subject" required></input>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label" for="meta-description-input">  Message</label>
                                    <textarea class="mlk-text-editor form-control" name="details"></textarea>
                                     @include('includes.inputs.texteditor')
                                </div>


                            </div>
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </div>
                        </form>
                        <!--end row-->
                    </div>
                </div>
                
            
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')


<script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>



<script>
$(document).ready(function() {

      // Listen for changes in the "User Type" select field
      $("select[name='user_type']").change(function() {
        // Get the selected option value
        var userType = $(this).val();
        // Show or hide the "forsubscrbed" divs based on the selected option
        if (userType === "3") {
          $(".for_subscribed").show();
        } else {
          $(".for_subscribed").hide();
        }
      });
    
});


</script>

@endsection
