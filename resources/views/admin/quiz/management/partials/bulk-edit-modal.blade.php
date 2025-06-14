                <!-- Bulk Model-->
                <div class="modal fade" id="BulkActionModal" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">Bulk Edit </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close" id="close-modal"></button>
                            </div>
                            
                            <form action="{{route('admin.quiz.management.bulk.action')}}" method="post" enctype="multipart/form-data" id="ItemsBulkActionForm" class="p-2">
                                @csrf
                                <h6><strong>Select an action to perform:</strong> (Only One action will be implemented at once)</h6>
                                @include('includes.alerts')
                                
                                <input type="hidden" id="selectedrowsIds" name="selectedrowsIds">
                                <div class="modal-body">
                                    <div id="bulkactionlist">
                                            <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="bulkactions" value="mb_change_status" id="mchangestatus">
                                                <label class="form-check-label" for="mchangestatus">
                                                   Change Status
                                                </label>
                                            </div>
                                            {{-- <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="bulkactions" id="mchangeprice" value="mb_change_price">
                                                <label class="form-check-label" for="mchangeprice">
                                                   Change Price
                                                </label>
                                            </div> --}}
                                            <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="bulkactions" id="mchangemembershiplevel" value="mb_membership_level">
                                                <label class="form-check-label" for="mchangemembershiplevel">
                                                   Change Membership level
                                                </label>
                                            </div>

                                            <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="bulkactions" id="mchangeassessmenttype" value="mb_assessment_type">
                                                <label class="form-check-label" for="mchangeassessmenttype">
                                                   Change AssessmentType
                                                </label>
                                            </div>

                                           {{--  <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="bulkactions" id="mdeleteitems" value="mb_delete_products">
                                                <label class="form-check-label" for="mdeleteitems">
                                                   Delete Seleted Items
                                                </label>
                                            </div> --}}

                                    </div>                                
                                    <div class="mbactionslist" >
                                        <div class="listtext" style="display:none;">
                                            <h6>Action Option</h6>
                                            <hr>
                                        </div>
                                        <div class="actionoptionss" data-id="mchangestatus">  
                                            <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="mb_status" id="mstatusactivated" value="1">
                                                <label class="form-check-label" for="mstatusactivated">
                                                   Activated
                                                </label>
                                            </div>
                                            <div class="form-check form-radio-secondary mb-3">
                                                <input class="form-check-input" type="radio" name="mb_status" id="mstatusdeactivated" value="0">
                                                <label class="form-check-label" for="mstatusdeactivated">
                                                   Deactivated
                                                </label>
                                            </div>
                                        </div>
                                    
                                       {{--  <div class="mb-3 actionoptionss" data-id="mchangeprice">
                                            <label for="orderId" class="form-label">Add Price</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">$</span>
                                                <input type="number" step="0.00000001"  class="form-control"  placeholder="Enter price" aria-label="Price" name="mb_price" >
                                            </div>
                                        </div> --}}

                                        <div class="actionoptionss" data-id="mchangemembershiplevel">
                                            <label for="deliveryTime" class="form-label">MemberShip Level</label>
                                           
                                            <select data-choices name="subplan_ids[]" multiple="multiple" >
                                                @foreach($subplans as $subplan)
                                                    <option value="{{$subplan->id}}" >{{$subplan->second_name}}</option>
                                                @endforeach
                                            </select>

                                        </div>  
                                        <div class="actionoptionss" data-id="mchangeassessmenttype">
                                            <label class="form-label">Assessment Type </label>
                                            <select class="form-control" name="assessment_type" >
                                                    <option selected disabled>Select Assessment Type</option>
                                                    @foreach(Helpers::getQuestionTypes() as $question_type)
                                                     <option value="{{$question_type}}" >{{$question_type}}</option>
                                                    @endforeach
                                            </select>

                                        </div>                                          
                                    </div>  
                                    
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Close</button>
                                        
                                        <button type="submit" class="btn btn-success submit-btn"
                                            id="edit-btn">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--Offer Bulk Model ends-->
@push('partial_script') 
  {{-- <script src="{{ URL::asset('/common_assets/js/bulkcheckbox.js') }}"></script> --}}
  <script type="text/javascript">

    $(document).ready(function() {
      $('.mbactionslist .actionoptionss').hide();
      $('#bulkactionlist input[name="bulkactions"]').change(function() {
        var selectedAction = $(this).attr('id');

        $('.mbactionslist .actionoptionss').hide();
        $('.listtext').show();
        $('.mbactionslist [data-id="' + selectedAction + '"]').show();

        // make the input field required for the selected radio button
        if(selectedAction!='mchangemembershiplevel'){
            $('.mbactionslist').find('div[data-id="' + selectedAction + '"] :input').prop('required', true);
        }
            
        // remove required attribute from other input fields
        $('.mbactionslist > div:not([data-id="' + selectedAction + '"]) :input').removeAttr('required');

        });
    });
    $(document).on('submit','#ItemsBulkActionForm',function(e){
        e.preventDefault();
        var allVals = [];  
        $(".sub_select:checked").each(function() {  
            allVals.push($(this).attr('data-id'));
        });

        if(allVals.length <=0)  
        {  
            alert("Please select row.");  
        }
        else {  
            var join_selected_values = allVals.join(","); 
            $('#selectedrowsIds').val(join_selected_values);
            var action = $('input[name="bulkactions"]:checked').val();
            if (action === 'mb_delete_products') {
                if (!confirm('Are you sure you want to delete the selected rows?')) {
                    return false;
                }
            }
            $.ajax({
               method:"POST",
               url:$(this).prop('action'),
               data:new FormData(this),
               contentType: false,
               cache: false,
               processData: false,
                 success:function(data)
                 {  
                    toastr.success(data.message);
                    $('#BulkActionModal').modal('hide');
                    location.reload();
                 }, 
                 error: function(xhr, status, error) {
                    // handle error response here
                    toastr.error("Something went wrong!");
                    console.log("Status: " + status);
                    console.log("Error: " + error);
                }
            });
        }
    });

  </script>
@endpush               