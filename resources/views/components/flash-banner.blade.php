{{-- DELETE error --}}
@if ($errors->has('delete'))
    <div class="mb-4 p-3 bg-red-700 text-red-100 rounded overflow-hidden animate-fade-out-collapse">
        {{ $errors->first('delete') }}
    </div>
@endif

{{-- SUCCESS banner --}}
@if (session('success'))
    <div class="mb-4 p-3 bg-green-700 text-green-100 rounded overflow-hidden animate-fade-out-collapse">
        {{ session('success') }}
    </div>
@endif
