<?php

namespace App\Http\Controllers\apps\places;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


function generateKeywords($inputString)
{
  $inputStringLower = strtolower($inputString);
  $keywords = array();
  $words = explode(" ", $inputStringLower);

  foreach ($words as $word) {
    $appendString = "";
    for ($i = 0; $i < strlen($word); $i++) {
      $appendString .= $word[$i];
      $keywords[] = $appendString;
    }
  }

  return $keywords;
}
class Regions extends Controller
{

  /**
   * Redirect to regions view.
   *
   */
  public function RegionManagement()
  {
    // dd('Regions');
    $regions = Region::all();
    $regionCount = $regions->count();
    $verified = 0;
    $notVerified = 0;
    $regionsUnique = $regions->unique(['name']);
    $regionDuplicates = $regions->diff($regionsUnique)->count();

    return view('content.apps..places.regions.listing', [
      'totalRegion' => $regionCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'regionDuplicates' => $regionDuplicates,
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
      3 => 'country',
      4 => 'latitude',
      5 => 'longitude',
      6 => 'proximity_radius',
    ];

    $search = [];

    $totalData = Region::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $regions = Region::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $regions = Region::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('country', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Region::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('country', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($regions)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($regions as $region) {
        $nestedData['id'] = $region->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $region->name;
        $nestedData['country'] = $region->country;
        $nestedData['latitude'] = $region->latitude;
        $nestedData['longitude'] = $region->longitude;
        $nestedData['proximity_radius'] = $region->proximity_radius;

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
      "name" => "required|string|max:255",
      "country" => "required|string|max:255",
      "latitude" => "required|numeric",
      "longitude" => "required|numeric",
      "proximity_radius" => "required|numeric",
    ]);
    try {
      $regionID = $request->id;

      if ($regionID) {
        // update the value
        $region = Region::where('id', $regionID)->first();

        if ($region) {
          $region->name = $request->name;
          $region->country = $request->country;
          $region->search_keywords = generateKeywords($request->name);
          $region->latitude = $request->latitude;
          $region->longitude = $request->longitude;
          $region->proximity_radius = $request->proximity_radius;
          $region->timestamp = now();

          if ($region->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $region->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Region does not exist, create a new region
          $region = new Region();
          $region->name = $request->name;
          $region->country = $request->country;
          $region->search_keywords = generateKeywords($request->name);
          $region->latitude = $request->latitude;
          $region->longitude = $request->longitude;
          $region->proximity_radius = $request->proximity_radius;
          $region->timestamp = now();

          if ($region->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $region->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // Region does not exist, create a new region
        $region = new Region();
        $region->name = $request->name;
        $region->country = $request->country;
        $region->search_keywords = generateKeywords($request->name);
        $region->latitude = $request->latitude;
        $region->longitude = $request->longitude;
        $region->proximity_radius = $request->proximity_radius;
        $region->timestamp = now();

        if ($region->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $region->getErrors();
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
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id): JsonResponse
  {
    $region = Region::findOrFail($id);
    return response()->json($region);
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

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $regions = Region::where('id', $id)->delete();
  }
}
