@if ($type === 'hidden')
    <input type="{{ $type }}" name="{{ $name }}" value="{{ $value }}">
@else
    <div class="form-group row py-2">
        <label for="{{ $name }}" class="col-sm-2 col-form-label">{{ $label }}</label>
        <div class="col-sm-10">
            <input type="{{ $type }}" name="{{ $name }}" class="form-control" value="{{ $value }}">
        </div>
        @if($errors->has($name))
            <div class="alert alert-danger mt-3">{{ $errors->first($name) }}</div>
        @endif
    </div>
@endif