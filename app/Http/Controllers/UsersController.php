<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    public function index(){
        return view('users.index');
    }

    public function create(){
        return view('users.create');
    }

    public function store(Request $request){
        // Normaliza espaços acidentais nas senhas
        $request->merge([
            'password' => trim((string)$request->input('password')),
            'password_confirmation' => trim((string)$request->input('password_confirmation')),
        ]);

        $validated = $request->validate([
            'first_name' => ['required','string','max:150'],
            'last_name' => ['required','string','max:150'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed','min:6'],
            'birth_date' => ['required','date'],
        ]);

        $name = trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));

        $user = new \App\Models\User();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->name = $name ?: $validated['first_name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->birth_date = $validated['birth_date'];
        $user->save();

        return redirect('/login')->with('status', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    public function authenticate(Request $request){
        $request->merge([
            'email' => trim((string)$request->input('email')),
            'password' => trim((string)$request->input('password')),
        ]);

        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Credenciais inválidas.'])->withInput(['email' => $credentials['email']]);
        }

        // Simples sessão de usuário (placeholder; não é auth completo do Laravel)
        $request->session()->put('user_id', $user->id);

        return redirect('/home')->with('status', 'Bem-vindo, '.$user->name.'!');
    }

    public function profile(Request $request){
        $userId = $request->session()->get('user_id');
        if (!$userId) {
            return redirect('/login')->with('status', 'Faça login para acessar o perfil.');
        }

        $user = User::find($userId);
        if (!$user) {
            $request->session()->forget('user_id');
            return redirect('/login')->withErrors(['email' => 'Sessão inválida. Faça login novamente.']);
        }

        return view('profile', ['user' => $user]);
    }
}

