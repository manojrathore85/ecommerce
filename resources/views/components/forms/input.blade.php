
@if($type  == 'text' || $type  == 'email' || $type  == 'password'||$type == 'date' )
<div class="mb-3 row">
    <label for="name" class="col-sm-4 col-form-label">{{$label}}</label>
    <div class="col-sm-8">
        <input type="{{$type}}" id="{{$id}}" name="{{$name}}" value="{{$value}}" {{ $attributes->merge(['class' => 'form-control']) }}>
        <span class="text-danger">@error($errorname){{$message}}@enderror</span>
    </div>
</div>

@elseif('radio')
<input type="{{$type}}" class="{{$class}}" id="{{$id}}" value="{{$value}}" {{ $attributes->merge(['class' => 'form-control']) }}>
<label for="name" class="form-label">{{$label}}</label>
@endif
