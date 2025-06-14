@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   Quiz Management
@endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.product.index') }}">  Quiz Management  </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">


                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.quiz.group.create') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @include('admin.quiz.groups.partials.inputs')
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary" type="submit">Submit form</button>
                            </div>
                        </form>
                        <!--end row-->
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


<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function() {
    // $(document).on('focusin', '.mlk-text-editor', function() {
    //   $(this).summernote({
    //     height: 130
    //   });
    // });

    // var currentOptionLetter = 'B'; 
    $(document).on('click', '.addQuizdiv', function(e) {     
        e.preventDefault()
        if ($(this).closest(".quizdiv").length === 0) {
          // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.groups.partials.quizoptions')`;
           $(this).closest(".quizoptionsContainer").prepend(discountRowTemplate);
        } else {
          // clone the first row if there are some rows remaining
            $newRow = $(this).closest(".quizdiv").first().clone();
            $newRow.find("input").val('').attr('placeholder', 'Enter Options ');
            $(this).closest(".quizdiv").last().after($newRow);
        }
        // currentOptionLetter = String.fromCharCode(currentOptionLetter.charCodeAt(0) + 1); // increment the option letter
    });
    $(document).on('click', '.removeQuizdiv', function(){
      $(this).closest('.quizdiv').remove();
      // currentOptionLetter = String.fromCharCode(currentOptionLetter.charCodeAt(0) - 1); // increment the option letter
    });

    $(document).on('change','#question_type', function(e) { 
        var selectedValue = $(this).val();
        if (selectedValue == "Self-Recorded") {
            // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.groups.partials.quizbank', ['question_type' => "Self-Recorded"])`;    
        }else{
            var discountRowTemplate=`@include('admin.quiz.groups.partials.quizbank', ['question_type' => ""])`;
        } 

        $("#quizbankContainer").empty().prepend(discountRowTemplate);
        // Initialize Summernote
        $("#quizbankContainer").find('.mlk-text-editor').summernote({
            height: 130
        });
    });
    
});


</script>

{{-- <script type="text/javascript">
        $(document).on('click', '.addQuizBankdiv', function(e) {       
        e.preventDefault()
        if ($(".quizbankdiv").length === 0) {
          // create a new row if no rows remaining
            var discountRowTemplate=``;
           $("#quizbankContainer").prepend(discountRowTemplate);
        } else {
          // clone the first row if there are some rows remaining
            $newRow = $(".quizbankdiv").first().clone();
            $(".quizbankdiv").last().after($newRow);
        }
    });
    $(document).on('click', '.removeQuizBankdiv', function(){
        if ($(".quizbankdiv").length <= 1) {
          alert("You can't delete that one");
          return;
        }
        $(this).closest('.quizbankdiv').remove();
    });
</script> --}}
@endsection
