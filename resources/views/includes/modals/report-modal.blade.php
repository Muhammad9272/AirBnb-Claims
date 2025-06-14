<!-- Report Error modal content -->
<div class="modal fade" id="reportErrorModal" tabindex="-1" aria-labelledby="reportcontentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyingcontentModalLabel">Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('error.report')}}" method="POST" id="reportErrorForm">
                @csrf
                
                <div class="modal-body">
                     @include('includes.alerts')
                    <p>Any questions about the suggested answers? Leave your comment here.</p>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message-text" rows="4" name="error_message"></textarea>
                        </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-primary submit-btn">Send message</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('partial_script')
<script type="text/javascript">
    $(function() {
        // Get the current page URL
        var pageUrl = window.location.href;  
        // Submit the form
        $("#reportErrorForm").submit(function(e) {
            e.preventDefault();

            var $form = $(this);
            var $alert = $form.find('.alert');
            var $btn = $form.find('button.submit-btn');

            var formData = new FormData(this);
            formData.append('page_url', pageUrl);
            $btn.prop('disabled', true);
            $alert.hide().filter('.alert-info').show().find('p').html("Processing...");

            $.ajax({
                method: "POST",
                url: $form.prop('action'),
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if (data.success) {
                        // window.location.href = data.route;
                        toastr.success(data.message);
                        window.location.reload();
                    } else if (data.error) {
                         $alert.hide().filter('.alert-danger').show().find('p').html(data.error);
                    }else if (data.errors) {
                        var errorHtml = '';
                        for(var error in data.errors){
                          errorHtml += '<li>' + data.errors[error] + '</li>';
                        }
                        $alert.hide().filter('.alert-danger').show().find('p').html(errorHtml);
                    }
                    $btn.prop('disabled', false);
                    
                }
            });
        });
    
    });

</script>
@endpush