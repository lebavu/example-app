<?php

namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Http\Request;

class UserRepository
{
    public function paginate(&$errors, &$success)
    {
        $users = User::latest()->get();
        if(!empty($users->data)){
            $errors[] = 'Cannot Find out the user';
            return false;
        }
        $success = true;
        return $users;
    }

    public function store(Request $request)
    {
        $user = new User([
            'name' => $request->get('name'),
            'status' => $request->get('status')
        ]);
        $user->save();
        return $user;
    }

    public function show($id, &$errors, &$success){
        $user = User::find($id);
        if(empty($user)){
            $errors[] = 'Cannot Find out the user';
            return false;
        }
        $success = true;
        return $user;
    }

    public function update(Request $request, $id, &$errors, &$success){
        $user = User::find($id);
        if(empty($user)){
            $errors[] = 'Cannot Find out the user';
            return false;
        }
        $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);
        $user->update($request->all());
        $success = true;
        return $user;
    }

    public function delete($id, &$errors, &$success){
        $user = User::find($id);
        if(empty($user)){
            $errors[] = 'Cannot Find out the user';
            return false;
        }
        $user->delete();
        $success = true;
        return $user;
    }
}
