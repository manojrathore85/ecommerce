
@if($type  == 'text' || $type  == 'email' || $type  == 'password' )
<div class="mb-3 row">
    <label for="name" class="col-sm-4 col-form-label">{{$label}}</label>
    <div class="col-sm-8">
        <input type="{{$type}}" class="{{$class}}" id="{{$id}}" name="{{$name}}" value="{{$value}}">
        <span class="text-danger">@error($errorname){{$message}}@enderror</span>
    </div>
</div>

@elseif('radio')
<input type="{{$type}}" class="{{$class}}" id="{{$id}}" name="{{$name}}" value="{{$value}}">
<label for="name" class="form-label">{{$label}}</label>
@endif
