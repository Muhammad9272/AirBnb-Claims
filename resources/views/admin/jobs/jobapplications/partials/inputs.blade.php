
<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">My Applicaton Info</h5>
            </div>
            <div class="card-body mb-4">
                    <div class="row gy-4 ">
                            <div class="col-md-6">
                                    <label class="form-label">User (Svips)</label>
                                    <select class="form-control" name="user_id" required>
                                        <option selected disabled>Select User</option>
                                        @foreach($svipusers as $svipuser)
                                         <option value="{{$svipuser->id}}" {{(isset($data->user_id) && $data->user_id==$svipuser->id)?'selected':''}}>{{$svipuser->affiliate_code}}-{{$svipuser->email}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            
                            <div class="col-md-6 ">
                                    <label class="form-label">Jobs Applied</label>
                                    <input type="text" name="jobs_applied" class="form-control"  oninput="formatInput(this)" placeholder="e.g., 20/1000"   value="{{ isset($data->jobs_applied)?$data->jobs_applied:'' }}" required>
                            </div>

                            <div class="col-md-6">
                                    <label class="form-label">Company </label>
                                    <select class="form-control" id="company" name="company_id" required>
                                        <option selected disabled>Select Company</option>
                                        @foreach($companies as $company)
                                         <option value="{{$company->id}}" {{(isset($data->company_id) && $data->company_id==$company->id)?'selected':''}}>{{$company->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-md-6 ">
                                    <label class="form-label">Service Line</label>
                                    <select class="form-control" id="service_line" name="service_line" required {{isset($data->service_line)?'':'disabled'}}>
                                        <option selected disabled>Select Service Line</option>

                                        @if(isset($serviceLines))
                                        @foreach($serviceLines as $serviceLine)
                                            <option value="{{$serviceLine}}" {{(isset($data->service_line) && $data->service_line==$serviceLine)?'selected':''}}>{{$serviceLine}}</option>
                                        @endforeach
                                        @endif

                                    </select>
                            </div>
                            <div class="col-md-6 ">
                                    <label class="form-label">Location</label>
                                    <select class="form-control" id="location" name="location" required {{isset($data->location)?'':'disabled'}} >
                                        <option selected disabled value="">Select Location</option>
                                        @if(isset($locations))
                                        @foreach($locations as $location)
                                            <option value="{{$location}}" {{(isset($data->location) && $data->location==$location)?'selected':''}}>{{$location}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                            </div>

                            <div class="col-md-6 ">
                                    <label class="form-label">Job Title</label>
                                    <select class="form-control" id="job_title" name="job_id" required {{isset($data->job_id)?'':'disabled'}}>
                                        <option selected disabled>Select Job Title</option>
                                        @if(isset($jobtitles))
                                        @foreach($jobtitles as $job)
                                            <option value="{{$job->id}}" {{(isset($data->job_id) && $data->job_id==$job->id)?'selected':''}}>{{$job->title}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                            </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Documents</h5>
            </div>
            <div class="card-body">
                    <div class="row gy-4">
                            <div class="col-md-12">
                                    <label class="form-label">Application Instruction Form</label>
                                    <input type="file" name="instruction_form" class="form-control" >
                                    @if(isset($data->instruction_form))
                                     <div class="mt-1">
                                         <a download="true" href="{!! Helpers::image($data->instruction_form, 'job/applicationsfiles/') !!}">View File</a>
                                     </div>
                                     @endif
                            </div>
                            <div class="col-md-12">
                                    <label class="form-label">Application Resume (CV) </label>
                                    <input type="file" name="resume" class="form-control"   >
                                    @if(isset($data->resume))
                                     <div class="mt-1">
                                         <a download="true" href="{!! Helpers::image($data->resume, 'job/applicationsfiles/') !!}">View File</a>
                                     </div>
                                     @endif
                            </div>
                            <div class="col-md-12">
                                    <label class="form-label">Motivation Letter</label>
                                    <input type="file" name="motivation_letter" class="form-control"   >
                                    @if(isset($data->motivation_letter))
                                     <div class="mt-1">
                                         <a download="true" href="{!! Helpers::image($data->motivation_letter, 'job/applicationsfiles/') !!}">View File</a>
                                     </div>
                                     @endif
                            </div>
                    </div>
            </div>
        </div>
     

    </div>

    
</div>


<div class="col-lg-12">    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add Stages</h5>
        </div>
        <div class="card-body" >
            <div id="stages-container">
                @if(isset($stagesData))
                    @foreach($stagesData as $index => $stage)
                    <div class="stage-container">
                        <input type="hidden" name="stage_id[]" value="{{ isset($stage['id']) ? $stage['id'] : '' }}">
                        @include('admin.jobs.jobapplications.partials.stages')
                    </div>
                    @endforeach
                @else
                   @include('admin.jobs.jobapplications.partials.stages')
                @endif
            </div>
            <div class="col-lg-12 action-btn  mt-3">   
                <button type="button" class="btn btn-outline-success waves-effect waves-light addstage">Add More Stages</button>
            </div>

        </div>

       
    </div> 
</div> 



@push('partial_script') 
  <script>
    function formatInput(input) {
      // Remove non-numeric characters except '/'
      input.value = input.value.replace(/[^0-9/]/g, '');

      // Automatically add slash after the first two digits
      const parts = input.value.split('/');
      if (parts[0].length > 2) {
        input.value = parts[0].slice(0, 2) + '/' + parts.slice(1).join('/');
      }

      // Ensure the number after the slash is limited to 100
      if (parts.length === 2 && parseInt(parts[1]) > 100) {
        input.value = parts[0] + '/100';
      }
    }
  </script>



<script>
    $(document).ready(function() {
        function resetAndDisableDependents(targetDropdowns) {
            $(targetDropdowns.join(',')).each(function() {
                $(this).empty().prop('disabled', true).append('<option selected disabled value="">Select ' + this.id.replace('_', ' ') + '</option>');
            });
        }



        function updateDropdown(url, data, targetDropdown, placeholder) {
            $.ajax({
                type: 'GET',
                url: url,
                data: data,
                success: function(response) {
                    $(targetDropdown).empty().prop('disabled', false); // Enable the dropdown
                    $(targetDropdown).append('<option selected disabled value="">' + placeholder + '</option>');

                    $.each(response, function(key, value) {
                        // Check if targetDropdown is for job titles
                        if (targetDropdown === '#job_title') {
                            $(targetDropdown).append('<option value="' + key + '">' + value + '</option>');
                        } else {
                            $(targetDropdown).append('<option value="' + value + '">' + value + '</option>');
                        }
                    });
                }
            });
        }


        $('#company').on('change', function() {
            var company_id = $(this).val();
            resetAndDisableDependents(['#service_line', '#location', '#job_title']);
            if (company_id) {
                // Update service lines
                updateDropdown('{{ route("jobs.get-service-lines") }}', { company_id: company_id }, '#service_line', 'Select Service Line');
            }
        });

        $('#service_line').on('change', function() {
            var company_id = $('#company').val();
            var service_line = $(this).val();
            resetAndDisableDependents(['#location', '#job_title']);

            if (company_id && service_line) {
                // Update locations
                updateDropdown('{{ route("jobs.get-locations") }}', { company_id: company_id, service_line: service_line }, '#location', 'Select Location');
            } 
        });

        $('#location').on('change', function() {
            var company_id = $('#company').val();
            var service_line = $('#service_line').val();
            var location = $(this).val();
             // Clear and disable dependent dropdown
            resetAndDisableDependents(['#job_title']);
            
            if (company_id && service_line && location) {
                // Update job titles
                updateDropdown('{{ route("jobs.get-job-titles") }}', { company_id: company_id, service_line: service_line, location: location }, '#job_title', 'Select Job Title');
            } 
        });
    });
</script>

<script>

        // Add stage functionality
        $(document).on('click', '.addstage', function () {
            var clonedStage = $('#stages-container .stage:first').clone(true);
            clonedStage.find('input:not(.user_docs_re_checkbox), select, textarea').val(''); // Clear values in cloned stage
            clonedStage.find('.docs-to-download').remove();
            
            // Adjust input names for admin_docs by adding an index
            // clonedStage.find('[name^="admin_docs"]').attr('name', function (i, val) {
            //     return val.replace(/\[\d+\]/, '[' + $('.stage').length + ']');
            // });

            // Update index for new stage
            var newIndex = $('#stages-container .stage').length;
            clonedStage.find('[name^="admin_docs"]').attr('name', 'admin_docs[' + newIndex + '][]');
            clonedStage.find('[name^="status"]').attr('name', 'status[' + newIndex + ']');
            clonedStage.find('[name^="last_date"]').attr('name', 'last_date[' + newIndex + ']');
            clonedStage.find('[name^="user_docs_required"]').attr('name', 'user_docs_required[' + newIndex + ']');
           

            clonedStage.appendTo('#stages-container').find('.action-btn').html('<button type="button" class="btn btn-danger removestage">Remove</button>');

        });

        // Remove stage functionality
        $(document).on('click', '.removestage', function () {
            $(this).closest('.stage-container').find('input[name^="stage_id"]').remove(); // Remove the hidden input for stage ID
            $(this).closest('.stage').remove();
            // After removing a stage, reindex the remaining stages for admin_docs
            // Update indices after removal
            $('#stages-container .stage').each(function (index, element) {
                $(element).find('[name^="admin_docs["]').attr('name', 'admin_docs[' + index + '][]');
                $(element).find('[name^="status["]').attr('name', 'status[' + index + ']');
                $(element).find('[name^="last_date["]').attr('name', 'last_date[' + index + ']');
                $(element).find('[name^="user_docs_required["]').attr('name', 'user_docs_required[' + index + ']');
            });
            
        });
  
   
</script>





@endpush               