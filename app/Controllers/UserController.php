<?php

namespace App\Controllers;

use App\Models\User;


class UserController
{

    public function index()
    {
        $q = $_GET['q'] ?? null;
        $page = $_GET['page'] ?? 1;
        $limit = 10;
        if ($q) {
            $whereClause = "name LIKE :search OR email LIKE :search";
            $values = ['search' => "%$q%"];
            $users = User::where($whereClause, $values)->paginate($page, $limit);
        } else {
            $users = User::paginate($page, $limit);
        }

        return view('users/index', array_merge([
            'title' => 'User List',
            'q' => $q,
        ], $users));
    }

    public function create()
    {
        return view('users/create', [
            'title' => 'Create User'
        ]);
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_BCRYPT)
        ];

        if (User::findByEmail($data['email'])) {
            return back(['danger' => 'Email already exists']);
        }

        User::create($data);

        return redirect('/users', ['success' => 'User created successfully']);
    }

    public function delete($id)
    {
        User::where('id = :id', ['id' => $id])->delete();
        return redirect('/users', ['success' => 'User deleted successfully']);
    }

    public function edit($id)
    {
        return view('users/edit', [
            'title' => 'Edit User',
            'user' => User::find($id)
        ]);
    }

    public function update($id)
    {
        $data = [
            'id' => $id,
            'name' => $_POST['name'],
            'email' => $_POST['email']
        ];

        $existingUser = User::where("email = :email AND id != :id", ['email' => $data['email'], 'id' => $id])->first();
        if ($existingUser) {
            return back(['danger' => 'Email already exists']);
        }

        User::where('id = :id', ['id' => $id])->update($data);

        return redirect('/users', ['success' => 'User updated successfully']);
    }
}
