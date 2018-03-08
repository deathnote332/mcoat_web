<select class="form-control" id="unit-value">
@foreach($data as $key=>$val)
    <option value="{{ $val }}">{{ $key }}</option>
@endforeach
</select>