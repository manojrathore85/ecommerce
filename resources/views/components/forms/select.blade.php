<div class="mb-3 row">
    <label for="name" class="col-sm-4 col-form-label">{{$label}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="{{$id}}" name="{{$name}}">
            <?php //print_r($selected); exit?>
            @foreach ($options as $optionkey => $optionvalue)
            <option value="{{ $optionkey }}"  @selected($selected == $optionkey) >{{$optionvalue}}</opttion>      
            @endforeach
        </select>   
        <span class="text-danger">@error($errorname){{$message}}@enderror</span>
        <!-- <span class="text-danger">{{$errormessage}}</span> -->
    </div>
</div>