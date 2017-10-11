@if (count($errors) > 0)

<div class="col s12 m12 text-center">
    <ul>
        @foreach ($errors->all() as $error)
        <li><div class="error">{{ $error }}</div></li>
        @endforeach
    </ul>
</div>
@endif

 