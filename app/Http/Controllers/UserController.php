<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.passwords.changePassword');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('home')->with('status', 'Password changed successfully!');
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->id === $user->id) {
            return redirect()->route('home.show', $id)->with('error', 'You cannot ban yourself.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        return redirect()->route('home.show', $id)->with('status', $user->is_banned ? 'User banned successfully!' : 'User unbanned successfully!');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $user->balance += $request->amount;
        $user->save();

        return redirect()->route('home')->with('status', 'Funds deposited successfully!');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($user->balance < $request->amount) {
            return redirect()->route('home')->with('error', 'Insufficient balance.');
        }

        $user->balance -= $request->amount;
        $user->save();

        return redirect()->route('home')->with('status', 'Funds withdrawn successfully!');
    }

    public function changeRole($id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'emprendedor') {
            $user->role = 'investor';
        } else {
            $user->role = 'emprendedor';
        }

        $user->save();

        return redirect()->route('home.show', $id)->with('status', 'User role updated successfully!');
    }

    public function updateIBAN(Request $request, $id)
    {
        $request->validate([
            'IBAN' => [
                'required',
                'string',
                'size:24',
                'regex:/^(AD|VG|MD|PK|RO|SA|SE|SK|ES|CZ|TN)[0-9]{22}$/'
            ],
        ]);

        $user = User::findOrFail($id);
        $user->IBAN = $request->input('IBAN');
        $user->save();

        return redirect()->route('home', $id)->with('status', 'IBAN updated successfully!');
    }

    public function adminBandUser(Request $request)
    {
        $status = $request->input('status');

        if ($status == 'banned') {
            $users = User::where('is_banned', true)->get();
        } elseif ($status == 'not_banned') {
            $users = User::where('is_banned', false)->get();
        } else {
            $users = User::all();
        }

        return view('adminBandUser', compact('users'));
    }

    public function show($id)
    {
        $user = User::with('projects')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Método para actualizar el perfil del usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('home', $user->id)->with('status', 'Profile updated successfully!');
    }
}
