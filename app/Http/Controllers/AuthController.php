<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

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

    // função que registra a conta do usuario
    public function register(Request $req){
        // pega as credenciais do usuario e valida se sao válidas
        $credentials = $req->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // se forem validas, cria o usuario no banco de dados e faz login automatico
        $user = User::create([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ]);

        // faz login automatico do usuario apos criar a conta
        Auth::login($user);
        $req->session()->regenerate();

        // redireciona para a rota de produtos apos criar a conta
        return redirect()->intended('/products');
    }

    // função que envia o email de reset de senha
    public function reset(Request $req){
        // valida se a senha esta valida
        $req->validate([
            'email' => ['required', 'email'],
        ]);

        // envia apenas o email para o laravel, que vai gerar o token e enviar o email com o link de reset de senha
        $status = Password::sendResetLink(
            $req->only('email')
        );

        // se o email for enviado, volta pra pagina anterior e exibe o status
        if($status == Password::RESET_LINK_SENT){
            return back()->with(['status' => __($status)]);
        };

        // se o email nao for enviado, volta pra pagina anterior e exibe o erro
        return back()->withErrors(['email' =>'We could not find a user with that email address.',]);
    }

    // função que reseta a senha e salva no banco
    public function resetPassword(Request $req){
        // pega a requisição enviada e valida se o token, email e senha são válidos
        $data = $req->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // se forem validos, ele chama a função reset do laravel, que vai verificar se o token é válido e se o email existe, se sim, ele vai atualizar a senha do usuário no banco de dados
        $status = Password::reset($data, function($user, $password){
            $user->password = $password;
            $user->save();
        });

        // se a senha foi resetada, redireciona para o email e exibe o status de sucesso
        if($status == Password::PASSWORD_RESET){
            return redirect()->route('login')->with('status', 'Password reset successfully!');
        };

        // se nao, volta pra pagina anterior e exibe o erro
        return back()->withErrors(['email' => 'The password reset link is invalid or has expired.',]);
    }
}