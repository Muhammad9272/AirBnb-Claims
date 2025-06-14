@extends('admin.layouts.master')

@section('title')
    Review Submission
@endsection

@section('css')
<style>
    .aspect-w-4 {
        position: relative;
        padding-bottom: 75%; /* 4:3 Aspect Ratio */
    }
    .aspect-w-4 img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    /* Lightbox effect on image click */
    .proof-image-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1050;
    }
    .proof-image-overlay img {
        max-width: 90%;
        max-height: 90vh;
        object-fit: contain;
    }
</style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Review Submission</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bit-submissions.index') }}">Bit Submissions</a></li>
                        <li class="breadcrumb-item active">Review</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Submission Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5>Submission Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" width="30%">Submission ID</th>
                                            <td>{{ $data->id }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Submitted On</th>
                                            <td>{{ $data->created_at->format('M d, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $data->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Bit Balance</th>
                                            <td>{{ $data->user->bit_balance }} bits</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Task Information</h5>
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row" width="20%">Task ID</th>
                                        <td>{{ $data->task->id }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Title</th>
                                        <td>{{ $data->task->title }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bit Value</th>
                                        <td>{{ $data->task->bit_value }} bits</td>
                                    </tr>
                                    @if($data->task->max_submissions)
                                    <tr>
                                        <th scope="row">User Submission Limit</th>
                                        <td>{{ $userApprovedCount }} / {{ $data->task->max_submissions }} 
                                            <span class="badge bg-info">
                                                {{ $data->task->max_submissions - $userApprovedCount }} remaining
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">User's Monthly Submissions</th>
                                        <td>{{ $userRecentSubmissionsCount }} / {{ $data->task->max_submissions }} 
                                            <span class="badge bg-info">
                                                {{ $data->task->max_submissions - $userRecentSubmissionsCount }} remaining
                                            </span>
                                            <small class="text-muted d-block">Resets 30 days after first submission</small>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Submission Content</h5>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($data->submission_content)) !!}
                        </div>
                    </div>
                    
                    @if($data->proof)
    <div class="card mb-4">
        <div class="card-header bg-light d-flex align-items-center">
            <i class="ri-attachment-2 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Proof Submissions</h5>
            <span class="badge bg-primary rounded-pill ms-auto">
                {{ count( json_decode($data->proof, true) ?? [] ) }} file(s)
            </span>
            
        </div>
        
        <div class="card-body p-3">
            <div class="row g-3">
                @php
                    $proofFiles = json_decode($data->proof, true) ?? [];
                @endphp
                
                <div class="row11">
                    @foreach($proofFiles as $proof)
                        @php
                            $extension = pathinfo($proof, PATHINFO_EXTENSION);
                            $isImage   = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']);
                            $isPdf     = strtolower($extension) === 'pdf';
                            $fileName  = basename($proof);
                            $fileColor = $isImage ? 'success' : ($isPdf ? 'danger' : 'info');
                            $fileIcon  = $isImage ? 'image' : ($isPdf ? 'file-pdf' : 'file-text');
                        @endphp
                
                        <div class="col-6 col-sm-4 col-lg-3 mb-4">
                            <a href="{!! Helpers::image($proof, 'bit-submissions/') !!}"
                            target="_blank"
                            class="text-decoration-none">
                                <div class="border rounded position-relative overflow-hidden">
                                    <div class="p-3 d-flex align-items-center">
                                        <div class="me-3 fs-4">
                                            <i class="ri-{{ $fileIcon }}-line text-{{ $fileColor }}"></i>
                                        </div>
                                        <div class="text-truncate">
                                            <p class="mb-0 text-truncate small">{{ $fileName }}</p>
                                            <small class="text-muted text-uppercase">{{ $extension }}</small>
                                        </div>
                                    </div>
                                    <div class="border-start border-5 border-{{ $fileColor }} position-absolute top-0 start-0 h-100"></div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            
            </div>
        </div>
    </div>
@endif
                    
                    @if($data->status != 'pending')
                    <div class="mb-4">
                        <h5>Admin Notes</h5>
                        <div class="border rounded p-3 bg-light">
                            {{ $data->admin_notes ?: 'No notes provided' }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            @if($data->status == 'pending')
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Review Action</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.bit-submissions.review', $data->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Decision <span class="text-danger">*</span></label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="status" id="approve" value="approved" checked>
                                <label class="form-check-label" for="approve">
                                    <span class="text-success">Approve</span> - Award {{ $data->task->bit_value }} bits to user
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="reject" value="rejected">
                                <label class="form-check-label" for="reject">
                                    <span class="text-danger">Reject</span> - No bits will be awarded
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="admin_notes" class="form-label">Admin Notes</label>
                            <textarea id="admin_notes" name="admin_notes" class="form-control" rows="4" 
                                      placeholder="Optional notes about your decision"></textarea>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.bit-submissions.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Task Description</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        {!! $data->task->description !!}
                    </div>
                    
                    <div class="mb-0 mt-4">
                        <h6>Requirements</h6>
                        @if($data->task->required_proof)
                            <div class="alert alert-info mb-0">
                                <i class="ri-information-line me-2"></i> {{ $data->task->required_proof }}
                            </div>
                        @else
                            <div class="alert alert-light mb-0">
                                No specific proof requirements
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
