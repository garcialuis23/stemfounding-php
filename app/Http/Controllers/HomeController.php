<?php
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    // Constructor del controlador, aplica el middleware de autenticación
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Método para mostrar el dashboard del usuario autenticado o un usuario específico
    public function index($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id); // Encuentra el usuario por ID o lanza un error 404 si no existe
        } else {
            $user = auth()->user(); // Obtiene el usuario autenticado
        }
        return view('home', compact('user')); // Retorna la vista 'home' con los datos del usuario
    }

    // Método para mostrar el perfil de un usuario específico
    public function show($id)
    {
        $user = User::findOrFail($id); // Encuentra el usuario por ID o lanza un error 404 si no existe
        return view('home', compact('user')); // Retorna la vista 'home' con los datos del usuario
    }
}
