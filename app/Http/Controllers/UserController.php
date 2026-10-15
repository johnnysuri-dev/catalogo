<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('buscar', ''));

        $users = User::query()
            ->select(['id_usuario', 'nombre', 'correo', 'rol', 'estado', 'created_at'])
            ->when($termino !== '', function ($query) use ($termino) {
                $query->where(function ($query) use ($termino) {
                    $query->where('nombre', 'like', "%{$termino}%")
                        ->orWhere('correo', 'like', "%{$termino}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', compact('users', 'termino'));
    }

    public function create(): View
    {
        return view('users.create', ['user' => new User()]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password_hash'] = $data['password'];
        unset($data['password']);

        $user = User::create($data);

        return redirect()->route('users.show', $user)
            ->with('success', 'Usuario creado correctamente.');
    }

    public function show(User $user): View
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        if (! empty($data['password'])) {
            $data['password_hash'] = $data['password'];
        }
        unset($data['password']);

        $user->update($data);

        return redirect()->route('users.show', $user)
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(Auth::user())) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}