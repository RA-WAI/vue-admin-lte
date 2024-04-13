<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate();

        return $users;
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|min:8',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);

        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('passsword')),
        ]);
    }

    public function update(User $user) {

        request()->validate([
            'email' => 'required|unique:users,email,' . $user->id
        ]);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('passsword') ? bcrypt(request('passsword')) : $user->password,
        ]);

        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function changeRole(User $user) {
        $user->update([
            'role' => request('role')
        ]);

        return response()->json(['success'  => true]);
    }

    public function search()
    {
        $query = request('query');
        $users = User::where('name', 'like', "%$query%")
                    ->orWhere('email', 'like', "%$query%")
                    ->latest()->paginate();

        return response()->json($users);
    }
}
