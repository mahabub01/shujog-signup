

@if(Session::has('success'))
    <script>
        swal("Success", "{{ Session::get('success') }}", "success");
    </script>
@endif

@if(Session::has('error'))
    <script>
        swal("Error", "{{ Session::get('error') }}", "error");
    </script>
@endif


@if(Session::has('warning'))
    <script>
        swal("Warning", "{{ Session::get('warning') }}", "warning");
    </script>
@endif

@if($errors->any())
    @php $msg = ""; @endphp
    @foreach ($errors->all() as $message)
        @php $msg .= $message.' '; @endphp
    @endforeach

    <script>
        swal("Error!", "{!! $msg !!}", "error");
    </script>
@endif


