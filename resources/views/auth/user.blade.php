<form method="POST" action="{{url($user ? '/user/'.$user->id : '/user')}}" id="formUser" name="formUser">
    <?php 
    $options = ['1' => 'admin', '2' => 'manager', '3' => 'user']; ?>
    @csrf
    <x-input type="text" name="name" id="name" label="Name" value="{{ $user ? $user->name : old('name')}}" />
    <x-input type="email" name="email" id="email" label="Email"  value="{{ $user ? $user->email : old('email')}}" />
    <x-input type="password" name="password" id="password" label="Password" />
    <x-input type="password" name="confirm_password" id="confirm_password" label="Confirm Password" />
    <div class="mb-3 row">
        <label for="gender" class="col-sm-4 col-form-label">Gender</label>
        <div class="col-sm-8">
            <input type="radio" id="gender_m" name="gender" value="M" {{ $user ? ($user->gender == 'M' ? 'checked' : ''): ''}}>
            <label for="gender" class="col-2 col-form-label">Male</label>
            <input type="radio" id="gender_f" name="gender" value="F" {{  $user ? ($user->gender == 'F' ? 'checked' : ''): ''}} > 
            <label for="gender" class="col-2 col-form-label">Fimale</label>
        </div>
        <span class="text-danger">@error('$errorname'){{$errormessage}}@enderror</span>
    </div>
    <x-select name="role" id="role" label="User Role" :options="$options" :selected=" $user ? $user->role : old('role') " />
    <button type="submit" id="submit" name="submit" class="btn btn-primary m-2 float-end">Save</button>
    <button type="button" id="cancel" name="cancel" class="btn btn-secondary m-2 float-end" data-bs-dismiss="modal">Cancel</button>
</form>
<script>
    // this function is js based ajax request no jquery is used
    formUser.onsubmit = (e) => {
        e.preventDefault(); // prevent to default form submit
        // Send a POST request to your Laravel backend
        fetch(formUser.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // this tax is required to treat request as ajax request
                    'Accept': 'application/json', // this is required to return validation data return in json not redirecting 
                    //'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    
                },
                body: new FormData(formUser),
            })
            .then(response => { 
                return response.json()} )
            .then(data => {
                // Handle the response from the server
                if (data.status == 'success') {
                    showToast('Success', data.message)
                    document.querySelector('#formUser').reset();
                    let modal = bootstrap.Modal.getInstance(document.querySelector('#modaluseradd'));
                    modal.hide();
                    userTable.ajax.reload();
                } else {
                    handleValidationErrorsControlsToast(data.errors);
                }
            })
            .catch(error => {
                showToast('gettingerror', error.message);
            });
    };
</script>