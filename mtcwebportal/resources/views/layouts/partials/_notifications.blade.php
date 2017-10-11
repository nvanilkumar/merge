@if (session('message'))
    <div class="col offset-m3 m6 alert alert-success">
        <div id="card-alert" class="card blue">
            <div class="card-content white-text">
                {{ session('message') }}
            </div>
            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close" style="top:7px;">
                <span aria-hidden="true">×</span>
            </button>
        </div>

    </div>
@endif