{{--<script>--}}

{{--    toastr.options =--}}
{{--        {--}}
{{--            "closeButton" : true,--}}
{{--            "progressBar" : true--}}
{{--        }--}}

{{--    @if(Session::has('success'))--}}
{{--    toastr.success("{{ session('success') }}");--}}
{{--    @endif--}}

{{--    @if(Session::has('error'))--}}
{{--    toastr.error("{{ session('error') }}");--}}
{{--    @endif--}}

{{--    @if(Session::has('info'))--}}
{{--    toastr.info("{{ session('info') }}");--}}
{{--    @endif--}}

{{--    @if(Session::has('warning'))--}}
{{--    toastr.warning("{{ session('warning') }}");--}}
{{--    @endif--}}

{{--    @if($errors->any())--}}
{{--        @foreach ($errors->all() as $message)--}}
{{--            toastr.error("{{ $message }}")--}}
{{--        @endforeach--}}
{{--    @endif--}}
{{--</script>--}}


    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{Session::get('success')}}
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-danger" role="alert">
            {{Session::get('error')}}
        </div>
    @endif

    @if(Session::has('warning'))
        <div class="alert alert-warning" role="alert">
            {{Session::get('warning')}}
        </div>
    @endif


    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul>
                @foreach ($errors->all() as $message)
                    <li>{{$message}}</li>
                @endforeach
            </ul>
        </div>
    @endif









