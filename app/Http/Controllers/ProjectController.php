<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Models\Investment;
use Carbon\Carbon;

class ProjectController extends Controller
{
    // Método para mostrar el formulario de creación de un nuevo proyecto
    public function create()
    {
        return view('newProject');
    }

    // Método para almacenar un nuevo proyecto en la base de datos
    public function store(Request $request)
    {
        // Verifica si el usuario tiene más de 2 proyectos activos
        $activeProjectsCount = Project::where('user_id', auth()->id())->where('status', 'active')->count();
        if ($activeProjectsCount >= 2) {
            return redirect()->route('home')->with('error', 'You can only have 2 active projects at a time.');
        }

        // Valida los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url_image' => 'nullable|string',
            'url_video' => 'nullable|string',
            'min_investment' => 'required|numeric|min:10',
            'max_investment' => 'nullable|numeric|max:9999999999.99',
            'limit_date' => 'required|date|after_or_equal:today',
        ]);

        // Crea un nuevo proyecto con los datos validados
        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->url_image = $request->url_image;
        $project->url_video = $request->url_video;
        $project->min_investment = $request->min_investment;
        $project->max_investment = $request->max_investment;
        $project->limit_date = $request->limit_date;
        $project->user_id = auth()->id(); // Aquí se usa el ID del usuario autenticado
        $project->status = 'pending'; // Establece el estado por defecto
        $project->save();

        return redirect()->route('projects.show', $project->id)->with('status', 'Project created successfully!');
    }

    // Método para mostrar una lista de todos los proyectos
    public function index()
    {
        return Project::all();
    }

    // Método para mostrar una lista de proyectos para usuarios no autenticados
    public function unlogged(Request $request)
    {
        $status = $request->input('status');
        if ($status) {
            $projects = Project::whereIn('status', ['active', 'inactive'])->where('status', $status)->paginate(9);
        } else {
            $projects = Project::whereIn('status', ['active', 'inactive'])->paginate(9);
        }
        return view('unlogged', compact('projects'));
    }

    // Método para mostrar un proyecto específico
    public function show($id)
    {
        // Obtener el proyecto
        $project = Project::findOrFail($id);

        // Verificar si la fecha límite ya ha pasado
        if (Carbon::parse($project->limit_date)->isPast() && $project->status != 'inactive') {
            $project->status = 'inactive';
            $project->save(); // Actualizar el estado del proyecto
        }

        // Retornar la vista con el proyecto
        return view('viewMore', compact('project'));
    }

    // Método para mostrar la página de aceptación del administrador
    public function adminAccept()
    {
        $projects = Project::where('status', 'pending')->get(); // Usa 'status' en lugar de 'estado'
        return view('adminAccept', compact('projects'));
    }

    // Método para actualizar el estado de un proyecto
    public function updateStatus(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Verifica si el usuario tiene más de 2 proyectos activos
        if ($request->input('status') == 'active') {
            $activeProjectsCount = Project::where('user_id', $project->user_id)->where('status', 'active')->count();
            if ($activeProjectsCount >= 2) {
                return redirect()->route('adminAccept')->with('error', 'This entrepreneur can only have 2 active projects at a time.');
            }
        }

        // Actualiza el estado del proyecto
        $project->status = $request->input('status');
        $project->save();

        return redirect()->route('adminAccept')->with('status', 'Project status updated successfully!');
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());

        return redirect()->route('projects.show', $project->id)->with('status', 'Project updated successfully!');
    }

    public function addComment(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Verificar si el proyecto está activo y si el usuario es el dueño del proyecto
        if ($project->status != 'active' || Auth::id() != $project->user_id) {
            return redirect()->route('projects.show', $id)->with('error', 'You can only add comments to your active projects.');
        }

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Añadir el nuevo comentario
        $comments[] = [
            'comment' => $request->input('comment'),
            'comment_image' => $request->input('comment_image'),
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return redirect()->route('projects.show', $id)->with('success', 'Comment added successfully.');
    }

    public function updateComment(Request $request, $projectId, $commentIndex)
    {
        $project = Project::findOrFail($projectId);

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Actualizar el comentario específico
        $comments[$commentIndex]['comment'] = $request->input('comment');
        $comments[$commentIndex]['comment_image'] = $request->input('comment_image');
        $comments[$commentIndex]['updated_at'] = now();

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return redirect()->route('projects.show', $projectId)->with('success', 'Comment updated successfully.');
    }

    public function deleteComment($projectId, $commentIndex)
    {
        $project = Project::findOrFail($projectId);

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Eliminar el comentario específico
        array_splice($comments, $commentIndex, 1);

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return redirect()->route('projects.show', $projectId)->with('success', 'Comment deleted successfully.');
    }

    // Método para manejar inversiones en un proyecto
    public function invest(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $user = Auth::user();

        $maxInvestmentAllowed = $project->max_investment - $project->current_investment;

        $request->validate([
            'investment_amount' => 'required|numeric|min:10|max:' . min($user->balance, $maxInvestmentAllowed),
        ]);

        $investmentAmount = $request->input('investment_amount');

        // Actualizar el balance del usuario
        $user->balance -= $investmentAmount;
        $user->save();

        // Actualizar la inversión actual del proyecto
        $project->current_investment += $investmentAmount;
        $project->save();

        // Guardar la inversión en la base de datos
        $investment = new Investment();
        $investment->user_id = $user->id;
        $investment->project_id = $project->id;
        $investment->amount = $investmentAmount;
        $investment->save();

        // Desactivar el proyecto si se alcanza el máximo de financiación
        if ($project->current_investment >= $project->max_investment) {
            $project->status = 'inactive';
            $project->save();
        }

        return redirect()->route('projects.show', $project->id)->with('status', 'Investment made successfully!');
    }

    // Método para manejar la retirada de inversiones dentro de 24 horas
    public function withdrawInvestment($id)
    {
        $investment = Investment::findOrFail($id);
        $project = $investment->project;
        $user = $investment->user;

        // Verificar si la inversión se realizó hace menos de 24 horas
        if (Carbon::now()->diffInHours($investment->created_at) < 24) {
            // Reembolsar al usuario
            $user->balance += $investment->amount;
            $user->save();

            // Descontar la inversión del proyecto
            $project->current_investment -= $investment->amount;
            $project->save();

            // Eliminar la inversión
            $investment->delete();

            return redirect()->route('projects.show', $project->id)->with('status', 'Investment withdrawn successfully!');
        }

        return redirect()->route('projects.show', $project->id)->with('error', 'Investment cannot be withdrawn after 24 hours.');
    }

    // Método para verificar y reembolsar inversiones si no se alcanza el objetivo mínimo
    public function checkAndRefundInvestments()
    {
        $projects = Project::where('status', 'active')->where('limit_date', '<', Carbon::now())->get();

        foreach ($projects as $project) {
            if ($project->current_investment < $project->min_investment) {
                // Reembolsar a los inversores
                foreach ($project->investments as $investment) {
                    $user = $investment->user;
                    $user->balance += $investment->amount;
                    $user->save();
                }

                // Cambiar el estado del proyecto a inactivo
                $project->status = 'inactive';
                $project->save();
            }
        }
    }

    public function createProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url_image' => 'nullable|url',
            'url_video' => 'nullable|url',
            'min_investment' => 'required|numeric|min:10',
            'max_investment' => 'nullable|numeric|max:9999999999.99',
            'limit_date' => 'required|date|after_or_equal:today',
            'comments' => 'nullable|string',
            'current_investment' => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = 2; // Establecer el user_id por defecto a 2

        $project = Project::create($validated);

        return response()->json($project, 201);
    }

    public function getInvestments($id)
    {
        try {
            $investments = Investment::where('project_id', $id)->with('user')->get();
            return response()->json($investments, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching investments data'], 500);
        }
    }

    public function deleteComment2($projectId, $commentIndex)
    {
        $project = Project::findOrFail($projectId);

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Verificar si el índice del comentario es válido
        if (!isset($comments[$commentIndex])) {
            return response()->json(['error' => 'Comment not found.'], 404);
        }

        // Eliminar el comentario específico
        array_splice($comments, $commentIndex, 1);

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return response()->json(['success' => 'Comment deleted successfully.'], 200);
    }

    public function updateComment2(Request $request, $projectId, $commentIndex)
    {
        $project = Project::findOrFail($projectId);

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Verificar si el índice del comentario es válido
        if (!isset($comments[$commentIndex])) {
            return response()->json(['error' => 'Comment not found.'], 404);
        }

        // Actualizar el comentario específico
        $comments[$commentIndex]['comment'] = $request->input('comment');
        $comments[$commentIndex]['comment_image'] = $request->input('comment_image');
        $comments[$commentIndex]['updated_at'] = now();

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return response()->json(['success' => 'Comment updated successfully.'], 200);
    }

    public function addComment2(Request $request, $id)
    {

        $project = Project::findOrFail($id);

        // Validar los datos del comentario
        $request->validate([
            'comment' => 'required|string',
            'comment_image' => 'nullable|url',
        ]);

        // Obtener los comentarios actuales
        $comments = $project->comments ?? [];

        // Añadir el nuevo comentario
        $comments[] = [
            'comment' => $request->input('comment'),
            'comment_image' => $request->input('comment_image'),
            'user_id' => "2",
            'user_name' => "luis",
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Actualizar el campo comments del proyecto
        $project->comments = $comments;
        $project->save();

        return response()->json(['success' => 'Comment added successfully.'], 201);
    }

    public function inactivateProject($id)
    {
        $project = Project::findOrFail($id);

        // Verificar si el proyecto está activo
        if ($project->status === 'active') {
            // Cambiar el estado a inactivo
            $project->status = 'inactive';
            $project->save();

            return response()->json(['success' => 'Project inactivated successfully.'], 200);
        }

        return response()->json(['error' => 'Project is not active.'], 400);
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Validar los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url_image' => 'nullable|string',
            'url_video' => 'nullable|string',
        ]);

        // Actualizar el proyecto con los datos validados
        $project->update($request->all());

        return response()->json(['success' => 'Project updated successfully.'], 200);
    }
}