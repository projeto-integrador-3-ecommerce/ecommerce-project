<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // validação login
    public function login(Request $req){
        // validate = verifica se as credenciais são válidas
        $credentials = $req->validate([
            // pra ser válido o email deve seguir as regras = ser obrigatório e ser um email válido
            'email' => ['required', 'email'],
            // pra ser válido a senha deve seguir as regras = ser obrigatório
            'password' => ['required'],
        ]);

        // o laravel recebe as credenciais, verifica se o email existe e se a senha está correta,
        // se sim, ele retorna true, se não, retorna false
        // Auth::attemp = tenta autenticar o usuário com as credenciais fornecidas, ele cuida da senha hashada
        if(Auth::attempt($credentials)){
            // regenerate = cria um novo token de sessão para o usuário autenticado, isso é importante para evitar ataques de sessão
            $req->session()->regenerate();
            // redirect = redireciona para a rota de produtos
            return redirect()->intended('/products');
        }

        // caso as credenciais não sejam válidas, ele retorna um erro de login
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    // validação logout
    public function logout(Request $req){
        // diz ao laravel que o usuário não está mais autenticado, ele limpa a sessão do usuário
        Auth::logout();

        // invalidate = invalida a sessão atual do usuário, isso é importante para evitar ataques de sessão
        $req->session()->invalidate();
        // regenerateToken = cria um novo token de sessão para o usuário, isso é importante para evitar ataques de sessão
        $req->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register(Request $req){
        $credentials = $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        Auth::login($user);
        $req->session()->regenerate();

        return redirect()->intended('/products');
    }
}