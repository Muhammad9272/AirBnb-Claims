@extends('admin.layouts.master')
@section('title')
   {{--  @lang('translation.basic-elements') --}}
   General Settings
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
              <a href="{{ route('admin.generalsettings') }}"> General  </a>
        @endslot
        @slot('title')
            Settings
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">General Settings </h4>
                    <div class="flex-shrink-0">
                       
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="live-preview">
                         @include('admin.includes.alerts')
                        <form action="{{ route('admin.generalsettings.update') }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            <div class="row gy-4">
 
                               {{--  <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Website Favicon</h5>                       
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                        <img src="{{$data->favicon?asset('/assets/images/'.$data->favicon):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input" type="file" class="profile-img-file-input" name="favicon" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Website Logo</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{{$data->logo?asset('/assets/images/logo/'.$data->logo):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class="rounded-circle avatar-xl img-thumbnail user-profile-image1"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input1" style="display: none;" type="file" class="profile-img-file-input1" name="logo" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input1" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Admin Panel Logo</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{{$data->admin_logo?asset('/assets/images/logo/'.$data->admin_logo):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class="rounded-circle avatar-xl img-thumbnail user-profile-image2"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input2" style="display: none;" type="file" class="profile-img-file-input2" name="admin_logo" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input2" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Landing Page Img</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{{$data->landing_page_img_1?asset('/assets/images/'.$data->landing_page_img_1):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class="rounded-circle avatar-xl img-thumbnail user-profile-image3"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input3" style="display: none;" type="file" class="profile-img-file-input3" name="landing_page_img_1" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input3" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4"> 
                                    <h5 class="fw-semibold mb-3">Intro Video Cover Img</h5>
                                    <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                                         <img src="{{$data->intro_video_cover?asset('/assets/images/'.$data->intro_video_cover):URL::asset('assets/images/users/user-dummy-img.jpg')}}"
                                            class="rounded-circle avatar-xl img-thumbnail user-profile-image4"
                                            alt="user-profile-image">
                                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                            <input id="profile-img-file-input4" style="display: none;" type="file" class="profile-img-file-input4" name="intro_video_cover" accept="image/png, image/gif, image/jpeg" />
                                            <label for="profile-img-file-input4" class="profile-photo-edit avatar-xs">
                                                <span class="avatar-title rounded-circle bg-light text-body">
                                                    <i class="ri-camera-fill"></i>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Intro Video</label>
                                        <input type="file" class="form-control" id="basiInput" name="intro_video"  >
                                    </div>
                                    @if($data->intro_video)
                                    <a href="{{$data->intro_video?asset('/assets/images/'.$data->intro_video):URL::asset('assets/images/users/user-dummy-img.jpg')}}">Click Here to Download</a>
                                    @endif
                                </div> --}}


                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Website Name</label>
                                        <input type="text" class="form-control" id="basiInput" name="name" value="{{$data->name}}" required>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Website Slogan</label>
                                        <input type="text" class="form-control" id="basiInput" value="{{$data->slogan}}"  name="slogan" required>
                                    </div>
                                </div>
                              

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Email</label>
                                        <input type="text" class="form-control" id="basiInput" value="{{$data->from_email}}"  name="from_email" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <h4 class="card-title mb-3">Referral & Influencer Settings</h4>
                                </div>
                                
                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="referralRewardInput" class="form-label">Referral Reward Percentage (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="referralRewardInput" value="{{$data->referral_reward_percentage}}" name="referral_reward_percentage" required>
                                        <small class="text-muted">Percentage of commission rewarded to users who refer others</small>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="influencerCommissionInput" class="form-label">Influencer Commission Percentage (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="influencerCommissionInput" value="{{$data->influencer_commission_percentage}}" name="influencer_commission_percentage" required>
                                        <small class="text-muted">Commission percentage earned by influencers</small>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="commissionDurationInput" class="form-label">Influencer Commission Duration (Days)</label>
                                        <input type="number" class="form-control" id="commissionDurationInput" value="{{$data->influencer_commission_duration_days}}" name="influencer_commission_duration_days" required>
                                        <small class="text-muted">Number of days an influencer can earn commission on their referrals</small>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="maxClaimsInput" class="form-label">Maximum Claims Per Influencer</label>
                                        <input type="number" class="form-control" id="maxClaimsInput" value="{{$data->influencer_max_claims}}" name="influencer_max_claims">
                                        <small class="text-muted">Maximum number of claims an influencer can make against each refferal (leave blank for unlimited)</small>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="discountCodeInput" class="form-label">Lead Discount Code</label>
                                        <input type="text" class="form-control" id="discountCodeInput" value="{{$data->lead_discount_code}}" name="lead_discount_code">
                                        <small class="text-muted">Discount code for leads (new potential customers)</small>
                                    </div>
                                </div>

                                <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="discountPercentageInput" class="form-label">Lead Discount Percentage (%)</label>
                                        <input type="number" step="0.01" class="form-control" id="discountPercentageInput" value="{{$data->lead_discount_percentage}}" name="lead_discount_percentage" required>
                                        <small class="text-muted">Discount percentage for new leads</small>
                                    </div>
                                </div>

                                {{-- <div class="col-xl-6 col-md-6">
                                    <div>
                                        <label for="basiInput" class="form-label"> Email</label>
                                        <input type="text" class="form-control" id="basiInput" value="{{$data->from_email}}"  name="from_email" required>
                                    </div>
                                </div> --}}

                               
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
    <script src="{{ URL::asset('assets/js/pages/profile-setting.init.js') }}"></script>
   {{--  <script src="{{ URL::asset('/assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script> --}}
@endsection
