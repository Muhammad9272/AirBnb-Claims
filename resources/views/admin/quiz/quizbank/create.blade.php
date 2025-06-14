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
              <a href="">  Quiz Management  </a>
        @endslot
        @slot('title')
            Create
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">


                    <div class="live-preview">
                        @include('admin.includes.alerts')
                        <form action="{{ route('admin.quizbank.store') }}" method="post" enctype="multipart/form-data" id="geniusform">
                            @csrf
                            @include('admin.includes.ajax-alerts')
                            @include('admin.quiz.quizbank.partials.inputs')
                            <div class="col-12 mb-4 ">
                                <button class="btn btn-primary submit-btn" type="submit">Save & Continue</button>
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


<script type='text/javascript' src="{{ URL::asset('assets/libs/choices.js/choices.js.min.js')}}"></script>
<script type='text/javascript' src="{{ URL::asset('assets/js/seperateplugins.min.js')}}"></script>


{{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}

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
        if ($(".quizdiv").length === 0) {
          // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.quizbank.partials.quizoptions')`;
           $(this).closest(".quizoptionsContainer").prepend(discountRowTemplate);
        } else {
            // clone the first row if there are some rows remaining
             $newRow = $(".quizdiv").first().clone();
             $newRow.find("input").val('').attr('placeholder','Enter Options');
             $(".quizdiv").last().after($newRow);
        }
    });
    $(document).on('click', '.removeQuizdiv', function(){
      $(this).closest('.quizdiv').remove();
    });

    $(document).on('change','#question_type', function(e) {
        var selectedValue = $(this).val();
        if (selectedValue == "Self-Recorded") {
            // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.quizbank.partials.quizbank', ['question_type' => "Self-Recorded"])`;    
        }else if (selectedValue == "Game-based") {
            // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.quizbank.partials.quizbank', ['question_type' => "Game-based"])`;    
        }else if (selectedValue == "Pdf-based") {
            // create a new row if no rows remaining
            var discountRowTemplate=`@include('admin.quiz.quizbank.partials.quizbank', ['question_type' => "Pdf-based"])`;

        }else{
            var discountRowTemplate=`@include('admin.quiz.quizbank.partials.quizbank', ['question_type' => ""])`;
        } 
        
        $("#quizbankContainer").empty().prepend(discountRowTemplate);
        // Initialize Summernote  
        // $("#quizbankContainer").find('.mlk-text-editor').summernote({
        //     height: 130
        // });
       initTextEditor();
       initializeDropzone($('#document-dropzone'));
       if(selectedValue == "Pdf-based"){
         attachPDFChangeEvent();
       }
       
    });

    
    
});


</script>

@endsection
