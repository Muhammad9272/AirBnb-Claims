<form id="geniusformdata" action="{{ route('admin.option-types.store') }}" method="POST">
    @csrf
    @include('admin.includes.ajax-alerts')
    <div class="mb-3">
        <label for="name" class="form-label">Option Type Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
   
     <div class="col-12 mb-4 ">
        <button class="btn btn-primary submit-btn" type="submit">Submit form</button>
    </div>
</form>
