@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Subscriptions Plan
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.subplan.index') }}"> Subscription Plan </a>
        @endslot
        @slot('title')
            Edit
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Edit Subscription Plan </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                         @include('admin.includes.alerts')
                        <form action="{{ route('admin.subplan.update',$data->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">
                                @include('admin.subplans.includes.inputs')
                                <!--end col-->
                                <div class="col-12">
                                    <button class="btn btn-primary" type="submit">Submit form</button>
                                </div>
                                <!--end col-->
                            </div>
                        </form>
                        <!--end row-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection
@section('script')
<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>
 <script>
    function toggleSelect(checkboxId, selectId) {

        $("#" + checkboxId).click(function() {
            var isChecked = $(this).is(":checked");
            $("#" + selectId).find('select').prop("required", isChecked);
            if(isChecked) {
                $("#" + selectId).show();
            } else {
                $("#" + selectId).hide();
            }
        });
    }

    $(document).ready(function() {
        toggleSelect("quizBankPermission", "quizBankSelect");
        toggleSelect("eventsPermission", "eventsSelect");
    });
</script>
@endsection
