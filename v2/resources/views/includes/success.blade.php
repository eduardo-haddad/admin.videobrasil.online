@if (session('success'))
    <div class="clearfix"></div>
    <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        {{ session('success') }}
    </div>
@endif
