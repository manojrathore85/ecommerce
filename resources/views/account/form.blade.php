<?php $groups = $groups->put('','PleaseSelect');
 $cities = $cities->put('','PleaseSelect');
// echo "<pre>";
// print_r($groups);
// echo "</pre>";

?>
<form method="{{$account ? 'PUT' : 'POST' }}" action="{{ url($account ? '/account/'.$account->id : '/account') }}" id="formAccount" name="formAccount">
    @csrf
    <x-input type="text" name="name" id="name" label="Name" value="{{$account ? $account->name : old('name')}}" />
    <x-input type="text" name="phone" id="phone" label="Phone" value="{{$account ? $account->phone : old('phone')}}" />
    <x-input type="email" name="email" id="email" label="email" value="{{$account ? $account->email : old('email')}}" />
    <x-select name="group_id" id="group_id" label="Group" :options="$groups" :selected=" $account ? $account->group_id : old('group_id') " />
    <x-select name="category" id="category" label="Category" :options="$categories" :selected=" $account ? $account->category : old('category') " />
    <x-select name="city_id" id="city_id" label="City" :options="$cities" :selected=" $account ? $account->city_id : old('city_id') " />

    <button type="submit" id="submit" name="submit" class="btn btn-primary float-end">Save</button>
    <button type="button" id="cancel" name="cancel" class="btn btn-secondy float-end" data-bs-dismiss="modal">Cancel</button>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        form = $("#formAccount");       
        form.submit(function(e) {           
            e.preventDefault();    
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize(),
                success: function(result) {
                    try {
                        console.log(result);
                        if (result.status = 'success') {
                            Swal.fire(result.message, '', 'success');
                            $('#modaladd').modal('hide');
                            accountTable.ajax.reload();
                        } else {
                            handleValidationErrorsControlsToast(result.errors);
                        }

                    } catch (error) {
                        Swal.fire(error.message, '', 'fail');
                    }
                },
                error: function(error) {
                    handleValidationErrorsControlsToast(error.responseJSON.errors);
                }

            });
        });
    });
</script>
