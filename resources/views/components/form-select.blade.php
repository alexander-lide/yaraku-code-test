<div class="form-group row py-2">
    <label for="{{ $name }}" class="col-sm-2 col-form-label">{{ $label }}</label>
    <div class="col-sm-10">
        <select name="{{ $name }}" class="form-control" >
            @foreach ($options as $value => $title)
                <option value="{{ $value }}" {{ $value == $selected ? ' selected' : ''}}>{{ $title }}</option>
            @endforeach
        </select>
    </div>
    @if($errors->has($name))
        <div class="alert alert-danger mt-3">{{ $errors->first($name) }}</div>
    @endif
</div>
