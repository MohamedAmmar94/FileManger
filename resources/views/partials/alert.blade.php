@if (session()->has("true"))

    <script>
    //console.log('notify');
      new Noty({
                text: "{{ session()->get("true") }}",
                type: 'success',
                timeout: 6000,
            }).show();
    </script>

@endif
@if (session()->has("info"))
      <script>
      new Noty({
                text: "{{ session()->get("info") }}",
                type: 'info',
                timeout: 6000,
            }).show();
    </script>
@endif
@if(count($errors) > 0)

    <script>
      @foreach($errors->all() as $error)
            new Noty({
                text: "{{ $error }}",
                type: 'error',
                timeout: 6000,
            }).show();
      @endforeach
    </script>

@endif
@if (session()->has("false"))
    <script>
      new Noty({
                text: "{{ session()->get("false") }}",
                type: 'error',
                timeout: 6000,
            }).show();
    </script>

@endif
