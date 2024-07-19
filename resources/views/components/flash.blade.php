@if (count($errors) > 0)
    <div class="alert alert-dismissable alert-danger">
        <strong>Failed!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <br>
    </div>
@endif
