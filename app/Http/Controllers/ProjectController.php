<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'offer_id' => 'required|integer|exists:offers,id',
            'price' => 'required|numeric|gt:0',
            'paid_with_balance' => 'required|boolean',
        ]);

        // make sure the user has user role
        if (! $request->user()->hasRole('user')) {
            return response()->json([
                'message' => 'You are not allowed to create a project',
            ], 403);
        }

        \App\Models\Balance::firstOrCreate([
            'user_id' => $request->user()->id,
        ]);

        // make sure the user has enough balance
        if ($request->paid_with_balance && $request->user()->balance->amount < $request->price) {
            return response()->json([
                'message' => 'You do not have enough balance',
            ], 403);
        }

        // create the project
        $project = $request->user()->projects()->create([
            'offer_id' => $request->offer_id,
            'price' => $request->price,
            'paid_with_balance' => $request->paid_with_balance,
        ]);

        // if paid with balance, deduct the balance
        if ($request->paid_with_balance) {
            $request->user()->balance->amount -= $request->price;
            $request->user()->balance->save();
        }

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
        $project = Project::findOrFail($id);

        if ($project->user_id === $request->user()->id) {
            if (! $request->user()->hasRole('user')) {
                return response()->json([
                    'message' => 'You are not allowed to mark this project as completed',
                ], 403);
            } else {
                $project->user_finished_at = now();
            }
        } elseif ($project->offer->created_by === $request->user()->id) {
            if (! $request->user()->hasRole('agent')) {
                return response()->json([
                    'message' => 'You are not allowed to mark this project as completed',
                ], 403);
            } else {
                $project->agent_finished_at = now();
            }
        } else {
            return response()->json([
                'message' => 'You are not allowed to mark this project as completed',
            ], 403);
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
