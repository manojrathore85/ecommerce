<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable){
        return $dataTable->render('auth.users');
    }
    public function getUsers(){
        return User::all();
    }
}
