<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);
        $remember = $request->filled('remember');
        $user = User::where('usuario', $request->usuario)->first();
        if ($user != null) {
            if ($user->estado) {
                if (Auth::attempt($credentials, $remember)) {
                    $request->session()->regenerate();
                    switch ($user->rol) {
                        case 'ADMIN':
                            return redirect('inicio');
                            break;
                        case 'CAJERA':
                            return redirect('inicio');
                            break;
                        case 'MESERO':
                            return redirect('pedidos');
                            break;
                        case 'PARRILLA':
                            return redirect('my/platos');
                            break;
                        case 'COCINA':
                            return redirect('my/guarniciones');
                            break;
                    }
                }
            } else {
                throw ValidationException::withMessages([
                    'password' => 'Usuario dehabilitado'
                ]);
                return redirect('login');
            }
        }
        
        throw ValidationException::withMessages([
            'password' => __('auth.failed')
        ]);
        return redirect('login');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}
