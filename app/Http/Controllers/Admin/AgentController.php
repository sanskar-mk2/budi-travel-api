<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function unapproved_agents(Request $request)
    {
        $unapproved_agents = \App\Models\User::role('agent')
            ->whereDoesntHave('agentStatus', function ($query) {
                $query->where('approved', true);
            })
            ->paginate(10);

        return response()->json([
            'unapproved_agents' => $unapproved_agents
        ]);
    }

    public function approve_agent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
        ]);

        $agent = \App\Models\User::role('agent')->find($request->agent_id);

        if (!$agent) {
            return response()->json([
                'message' => 'Agent not found',
            ], 404);
        }
        if (!$agent->agentStatus) {
            $agent_status = new \App\Models\AgentStatus([
                'approved' => true,
            ]);
            $agent->agentStatus()->save($agent_status);
        } else {
            $agent->agentStatus->approved = true;
            $agent->agentStatus->save();
        }

        return response()->json([
            'message' => 'Agent approved',
        ]);
    }
}
