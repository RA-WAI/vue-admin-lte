<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
                ->when(request('query'), function($query, $searchQuery){
                    $query->where('name', 'like', "%{$searchQuery}%");
                })
                ->latest()->paginate();
        return $users;
    }

    public function store()
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8',
        ]);

        return User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password'))
        ]);
    }

    public function update(User $user) {

        request()->validate([
            'email' => 'required|unique:users,email,' . $user->id
        ]);

        $user->update([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('passsword') ? Hash::make(request('password')) : $user->password,
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

    public function bulkDelete()
    {
        User::whereIn('id', request('ids'))->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
