<?php

namespace App\Http\Controllers\apps\outlets;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ActiveOutlets extends Controller
{
  /**
   * Redirect to outlets view.
   *
   */
  public function OutletManagement()
  {
    // dd('Outlets');
    $outlets = Outlet::all();
    $outletCount = $outlets->count();
    $verified = Outlet::whereNotNull('draft')->get()->count();
    $notVerified = Outlet::whereNull('verified')->get()->count();
    $outletsUnique = $outlets->unique(['name']);
    $outletDuplicates = $outlets->diff($outletsUnique)->count();

    return view('content.apps.outlets.active', [
      'totalOutlet' => $outletCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'outletDuplicates' => $outletDuplicates,
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

    $totalData = Outlet::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $outlets = Outlet::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $outlets = Outlet::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Outlet::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($outlets)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($outlets as $outlet) {
        $nestedData['id'] = $outlet->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $outlet->name[0]  ." ".  $outlet->name[1]  ." ". $outlet->name[2];
        $nestedData['email'] = $outlet->email;
        $nestedData['phone_number'] = $outlet->phone_number;
        $nestedData['id_number'] = $outlet->id_number;
        $nestedData['role'] = $outlet->role;
        $nestedData['country'] = $outlet->country;
        $nestedData['active'] = $outlet->active;

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
      $outletID = $request->id;

      if ($outletID) {
        // update the value
        $outlet = Outlet::where('id', $outletID)->first();

        if ($outlet) {
          $outlet->name = [$request->first_name, $request->middle_name, $request->last_name];
          $outlet->country = $request->country;
          //$outlet->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $outlet->email = $request->email;
          $outlet->phone_number = $request->phone_number;
          $outlet->id_number = $request->id_number;
          $outlet->role = $request->role;

          if ($outlet->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $outlet->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Outlet does not exist, create a new outlet
          $outlet = new Outlet();
          $outlet->name = [$request->first_name, $request->middle_name, $request->last_name];
          $outlet->country = $request->country;
          //$outlet->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $outlet->email = $request->email;
          $outlet->phone_number = $request->phone_number;
          $outlet->id_number = $request->id_number;
          $outlet->role = $request->role;
          $outlet->active = 2;
          $outlet->active_timestamp = now();
          $outlet->suspended = 1;
          $outlet->suspended_timestamp = now();

          if ($outlet->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $outlet->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // Outlet does not exist, create a new outlet
        $outlet = new Outlet();
        $outlet->name = [$request->first_name, $request->middle_name, $request->last_name];
        $outlet->country = $request->country;
        //$outlet->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
        $outlet->email = $request->email;
        $outlet->phone_number = $request->phone_number;
        $outlet->id_number = $request->id_number;
        $outlet->role = $request->role;
        $outlet->active = 2;
        $outlet->active_timestamp = now();
        $outlet->suspended = 1;
        $outlet->suspended_timestamp = now();

        if ($outlet->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $outlet->getErrors();
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
  public function outlet($id)
  {
    // Fetch the outlet record using the provided ID
    $outlet = Outlet::find($id);
    return view('content.apps.outlets.outlet', ['outlet' => $outlet]);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id): JsonResponse
  {
    $outlet = Outlet::findOrFail($id);
    return response()->json($outlet);
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
    //$outlet = Outlet::find($id);
    $outlet = Outlet::where('id', $id)->first();
    if ($outlet) {
      $outlet->active = $request->input('status');
      $outlet->active_timestamp = now();

      if ($outlet->save()) {
        // Success
        return response()->json('Status updated successfully.');
      } else {
        // Handle error
        $errors = $outlet->getErrors();
        return response()->json(['message' => $errors], 422);
      }
    } else
    {
      // Outlet does not exist, create a new outlet
      return response()->json(['message' => 'Outlet does not exist'], 422);

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
    $outlets = Outlet::where('id', $id)->delete();
  }
}
