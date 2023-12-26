<form method="{{$group ? 'PUT' : 'POST' }}" action="{{url($group ? '/group/'.$group->id : '/group')}}" id="formGroup" name="formGroup">

    @csrf
    <x-input type="text" name="name" id="name" label="Name" value="{{ $group ? $group->name : old('name')}}" />
    <?php
    $groups->put('0', 'Primary');
    ?>
    <x-select name="parentGroup" id="parentGroup" label="Parent Group" :options="$groups" :selected=" $group ? $group->parent_id : old('patent_id') " />
    <x-select name="nature" id="nature" label="Group Nature" :options="$nature" :selected=" $group ? $group->nature : old('nature') " />
    <x-input type="text" name="order" id="order" label="Order No" value="{{ $group ? $group->order : old('order')}}" />

    <button type="submit" id="submit" name="submit" class="btn btn-primary m-2 float-end">Save</button>
    <button type="button" id="cancel" name="cancel" class="btn btn-secondary m-2 float-end" data-bs-dismiss="modal">Cancel</button>
</form>
<script>
    // this function is js based ajax request no jquery is used
    // formGroup.onsubmit = (e) => {
    //     e.preventDefault(); // prevent to default form submit
    //     // Send a POST request to your Laravel backend
    //     fetch(formGroup.action, {
    //             method: formGroup.method,
    //             headers: {
    //                 'X-Requested-With': 'XMLHttpRequest', // this tax is required to treat request as ajax request
    //                 'Accept': 'application/json', // this is required to return validation data return in json not redirecting 
    //                 //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

    //             },
    //             body: new FormData(formGroup),
    //         })
    //         .then(response => {
    //             return response.json()
    //         })
    //         .then(data => {
    //             // Handle the response from the server
    //             if (data.status == 'success') {
    //                 showToast('Success', data.message)
    //                 document.querySelector('#formGroup').reset();
    //                 let modal = bootstrap.Modal.getInstance(document.querySelector('#modaladd'));
    //                 modal.hide();
    //                 groupsTable.ajax.reload();
    //             } else {
    //                 handleValidationErrorsControlsToast(data.errors);
    //             }
    //         })
    //         .catch(error => {
    //             showToast('gettingerror', error.message);
    //         });
    // };
    $(document).ready(function() {
        form = $("#formGroup");
        //console.log(form);
        form.submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                
                data: form.serialize(),
                
                success: function(result) {
                    try {
                        //var result = JSON.parse(responce);
                        console.log(result);
                        if (result.status == 'success') {
                            //showToast('Success', result.message);
                            Swal.fire(result.message, '', 'success')
                            $('#modaladd').modal('hide');
                            groupsTable.ajax.reload();
                        } else {
                            handleValidationErrorsControlsToast(result.errors);
                            
                        }

                    } catch (error) {
                       // showToast('gettingerror', error.message);
                        Swal.fire(error.message, '', 'fail')
                    }
                },
                error: function(error) {
                    //console.log(error.responseJSON.errors);
                    handleValidationErrorsControlsToast(error.responseJSON.errors);
                }
            });           
        });
    });
</script>