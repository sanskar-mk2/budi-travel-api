<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|integer|exists:offers,id',
            'price' => 'required|numeric|gt:0',
        ]);

        // make sure the user has user role
        if (! $request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'You are not allowed to create a project',
            ], 403);
        }

        // create the project
        $project = $request->user()->projects()->create([
            'offer_id' => $request->offer_id,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Successfully created project',
            'project' => ProjectResource::make($project),
        ], 200);
    }

    public function projects(Request $request)
    {
        // if user then get projects(), if agent, get agentProjects()
        if ($request->user()->hasRole('user')) {
            $projects = $request->user()->projects()->with('offer')->get();
        } elseif ($request->user()->hasRole('agent')) {
            $projects = $request->user()->agentProjects()->with('offer')->get();
        }

        return response()->json([
            'message' => 'Successfully fetched projects',
            'projects' => ProjectResource::collection($projects),
        ], 200);
    }

    public function project(Request $request, $id)
    {
        $project = $request->user()->projects()->where('id', $id)->with('offer')->first();

        if (! $project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched project',
            'project' => $project,
        ], 200);
    }

    public function mark_completed(Request $request, $id)
    {
        $project = $request->user()->projects()->where('id', $id)->first();

        if (! $project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }

        // if user has the role user, mark user_finished_at as now
        // if user has the role agent, mark agent_finished_at as now
        if ($request->user()->hasRole('user')) {
            $project->user_finished_at = now();
        } elseif ($request->user()->hasRole('agent')) {
            $project->agent_finished_at = now();
        }

        $project->save();

        return response()->json([
            'message' => 'Successfully marked project as completed',
            'project' => ProjectResource::make($project),
        ], 200);
    }

    public function index(Request $request)
    {
        // show all projects with pagination
        $projects = \App\Models\Project::with('offer')->paginate(10);
        ProjectResource::collection($projects);

        return response()->json([
            'message' => 'Successfully fetched projects',
            'projects' => $projects,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        $project = \App\Models\Project::where('id', $id)->with('offer')->first();

        if (! $project) {
            return response()->json([
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched project',
            'project' => ProjectResource::make($project),
        ], 200);
    }
}
