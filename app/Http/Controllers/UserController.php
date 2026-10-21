<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::latest()->paginate(20),
        ]);
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is_admin || $user->is($request->user())) {
            return back()->withErrors(['user' => 'Contas de administrador não podem ser removidas por esta tela.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário removido.');
    }
}
