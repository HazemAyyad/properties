@if ($message = Session::get('success'))
    <script>
        swal.fire({
            icon: 'success',
            title: '{!! $message !!}'
        })
    </script>
@endif


@if ($message = Session::get('error'))
<script>
    swal.fire({
        icon: 'error',
        title: '{!! $message !!}'
    })
</script>
@endif

@if(session()->has('message'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">�</button>
        <strong></strong>
</div>

<script>
    swal.fire({
        icon: 'error',
        title: '{!! session()->get('message') !!}'
    })
</script>
@endif

@if ($errors->any())

<script>
    @foreach ($errors->all() as $key => $error)
    swal.fire({
        icon: 'error',
        title: '{!! $error !!}'
    })
    @endforeach

</script>
@endif




@if ($message = Session::get('warning'))
    <script>
        swal.fire({
            icon: 'warning',
            title: '{!! $message !!}'
        })
    </script>
@endif


@if ($message = Session::get('info'))
<script>
    swal.fire({
        icon: 'info',
        title: '{!! $message !!}'
    })
</script>
@endif


