<div class="mb-3">
    <label class="form-label">{{ $inputLabel }}</label>
    <input type="text"
           name="{{$name}}"
           @if($value)
           value="{{ old($name,$value) }}"
           @else
           value="{{ old($name) }}"
           @endif
           class="form-control @error($name) is-invalid @enderror">
    @error($name)
    <p class="text-danger small mt-2">{{ $message }}</p>
    @enderror
</div>
