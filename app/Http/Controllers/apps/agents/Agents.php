<?php

namespace App\Http\Controllers\apps\agents;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Agents extends Controller
{
  /**
   * Redirect to agents view.
   *
   */
  public function AgentManagement()
  {
    // dd('Agents');
    $agents = Agent::all();
    $agentCount = $agents->count();
    $verified = Agent::whereNotNull('email_verified')->get()->count();
    $notVerified = Agent::whereNull('email_verified')->get()->count();
    $agentsUnique = $agents->unique(['email']);
    $agentDuplicates = $agents->diff($agentsUnique)->count();

    return view('content.apps.agents.listing', [
      'totalAgent' => $agentCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'agentDuplicates' => $agentDuplicates,
    ]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'name',
      3 => 'email',
      4 => 'phone_number',
      5 => 'id_number',
      6 => 'country',
      7 => 'active',
    ];

    $search = [];

    $totalData = Agent::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $agents = Agent::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $agents = Agent::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Agent::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($agents)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($agents as $agent) {
        $nestedData['id'] = $agent->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $agent->name[0]  ." ".  $agent->name[1]  ." ". $agent->name[2];
        $nestedData['email'] = $agent->email;
        $nestedData['phone_number'] = $agent->phone_number;
        $nestedData['id_number'] = $agent->id_number;
        $nestedData['role'] = $agent->role;
        $nestedData['country'] = $agent->country;
        $nestedData['active'] = $agent->active;

        $data[] = $nestedData;
      }
    }

    if ($data) {
      return response()->json([
        'draw' => intval($request->input('draw')),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'code' => 200,
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $request->validate([
      "first_name" => "required|string|max:255",
      "middle_name" => "required|string|max:255",
      "last_name" => "required|string|max:255",
      "country" => "required|string|max:255",
      "email" => "required|string|max:255",
      "phone_number" => "required|numeric",
      "id_number" => "required|numeric",
      "role" => "required|string|max:255",
    ]);
    try {
      $agentID = $request->id;

      if ($agentID) {
        // update the value
        $agent = Agent::where('id', $agentID)->first();

        if ($agent) {
          $agent->name = [$request->first_name, $request->middle_name, $request->last_name];
          $agent->country = $request->country;
          $agent->photo_url = ["", ""];
          //$agent->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $agent->email = $request->email;
          $agent->phone_number = $request->phone_number;
          $agent->id_number = $request->id_number;
          $agent->role = $request->role;

          $agent->pin = "";
          $agent->pin_activated = 2;
          $agent->pin_activated_timestamp = now();
          $agent->current_locality_id = "";
          $agent->current_region_id = "";
          $agent->selected_locality_id = "";
          $agent->selected_region_id = "";
          $agent->selected_category_id = "";

          if ($agent->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $agent->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Agent does not exist, create a new agent
          $agent = new Agent();
          $agent->name = [$request->first_name, $request->middle_name, $request->last_name];
          $agent->country = $request->country;
          $agent->photo_url = ["", ""];
          //$agent->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $agent->email = $request->email;
          $agent->phone_number = $request->phone_number;
          $agent->id_number = $request->id_number;
          $agent->role = $request->role;
          $agent->active = 2;
          $agent->active_timestamp = now();

          $agent->pin = "";
          $agent->pin_activated = 2;
          $agent->pin_activated_timestamp = now();
          $agent->current_locality_id = "";
          $agent->current_region_id = "";
          $agent->selected_locality_id = "";
          $agent->selected_region_id = "";
          $agent->selected_category_id = "";

          $agent->suspended = 1;
          $agent->suspended_timestamp = now();

          if ($agent->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $agent->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // Agent does not exist, create a new agent
        $agent = new Agent();
        $agent->name = [$request->first_name, $request->middle_name, $request->last_name];
        $agent->country = $request->country;
        $agent->photo_url = ["", ""];
        //$agent->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
        $agent->email = $request->email;
        $agent->phone_number = $request->phone_number;
        $agent->id_number = $request->id_number;
        $agent->role = $request->role;

        $agent->pin = "";
        $agent->pin_activated = 2;
        $agent->pin_activated_timestamp = now();
        $agent->current_locality_id = "";
        $agent->current_region_id = "";
        $agent->selected_locality_id = "";
        $agent->selected_region_id = "";
        $agent->selected_category_id = "";

        $agent->active = 2;
        $agent->active_timestamp = now();
        $agent->suspended = 1;
        $agent->suspended_timestamp = now();

        if ($agent->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $agent->getErrors();
          return response()->json(['message' => $errors], 422);
        }
      }

    } catch (\Exception $e) {
      // Token is invalid
      return response()->json(['message' => $e->getMessage()], 422);
    }

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   */
  public function agent($id)
  {
    // Fetch the agent record using the provided ID
    $agent = Agent::find($id);
    return view('content.apps.agents.agent', ['agent' => $agent]);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id): JsonResponse
  {
    $agent = Agent::findOrFail($id);
    return response()->json($agent);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
  }

  public function status($id, Request $request)
  {
    //$agent = Agent::find($id);
    $agent = Agent::where('id', $id)->first();
    if ($agent) {
      $agent->active = $request->input('status');
      $agent->active_timestamp = now();

      if ($agent->save()) {
        // Success
        return response()->json('Status updated successfully.');
      } else {
        // Handle error
        $errors = $agent->getErrors();
        return response()->json(['message' => $errors], 422);
      }
    } else
    {
      // Agent does not exist, create a new agent
      return response()->json(['message' => 'Agent does not exist'], 422);

    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $agents = Agent::where('id', $id)->delete();
  }
}
