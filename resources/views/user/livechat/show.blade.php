@extends('user.layouts.master')
@section('title')
    @lang('translation.home')
@endsection
@section('css')
<style type="text/css">
    .ap_messenger__card{
        width:97%;
    }
</style>
 {{-- @include('Chatify::layouts.headLinks') --}}
@endsection
@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"> Customer Support</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">Home</a></li> 
                                       
                    <li class="breadcrumb-item active"> Customer Support</li>
                    
                </ol>
            </div>

        </div>
    </div>
</div> 
 
<div class="row">
    <div class="col-12 m-3 ap_messenger__card userd__ashboard" >
        {{-- @include('user.livechat.includes.messenger') --}}

        @include('vendor.Chatify.pages.app')
    </div>
</div> 


@endsection

@section('script')
  <script type="text/javascript">
        // message form on submit.
  // $("#message-form").on("submit", (e) => {
  //   alert(34);
  //   e.preventDefault();
  //   sendMessage();
  // });

  </script>
  {{-- @include('Chatify::layouts.footerLinks') --}}
@endsection
