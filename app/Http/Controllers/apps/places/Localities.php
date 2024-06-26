<?php

namespace App\Http\Controllers\apps\places;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

class Localities extends Controller
{
  /**
   * Redirect to localities view.
   *
   */
  public function LocalityManagement()
  {
    // dd('Localities');
    $localities = Locality::all();
    $localityCount = $localities->count();
    $verified = 0;
    $notVerified = 0;
    $localitiesUnique = $localities->unique(['name']);
    $localityDuplicates = $localities->diff($localitiesUnique)->count();

    return view('content.apps.places.localities.listing', [
      'totalLocality' => $localityCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'localityDuplicates' => $localityDuplicates,
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
      2 => 'localities.name',
      3 => 'region_name',
      4 => 'country',
      5 => 'latitude',
      6 => 'longitude',
      7 => 'proximity_radius',
    ];

    $searchValue = $request->input('search.value');
    $totalData = Locality::count();
    $limit = $request->input('length');
    $start = $request->input('start');

    // Default ordering by name in ascending order
    $orderColumnIndex = $request->input('order.0.column', 2); // Default to 'name' column (index 2)
    $order = $columns[$orderColumnIndex] ?? 'localities.name';
    $dir = $request->input('order.0.dir', 'asc'); // Default to ascending order

    $query = Locality::select(
      'localities.id',
      'localities.name',
      'regions.name as region_name',
      'localities.country',
      'localities.latitude',
      'localities.longitude',
      'localities.attitude',
      'localities.proximity_radius',
      'localities.population'
    )
      ->leftJoin('regions', 'localities.region_id', '=', 'regions.id');

    if (!empty($searchValue)) {
      $query->where(function($q) use ($searchValue) {
        $q->where('localities.id', 'LIKE', "%{$searchValue}%")
          ->orWhere('localities.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('regions.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('localities.country', 'LIKE', "%{$searchValue}%");
      });

      $totalFiltered = $query->count();
    } else {
      $totalFiltered = $totalData;
    }

    $localities = $query->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    $data = [];
    $fakeId = $start;

    foreach ($localities as $locality) {
      $data[] = [
        'id' => $locality->id,
        'fake_id' => ++$fakeId,
        'name' => $locality->name,
        'region' => $locality->region_name ?? 'Unknown', // Handle case where region might not exist
        'country' => $locality->country,
        'latitude' => $locality->latitude,
        'longitude' => $locality->longitude,
        'attitude' => $locality->attitude,
        'proximity_radius' => $locality->proximity_radius,
        'population' => $locality->population,

      ];
    }

    return response()->json([
      'draw' => intval($request->input('draw')),
      'recordsTotal' => intval($totalData),
      'recordsFiltered' => intval($totalFiltered),
      'code' => 200,
      'data' => $data,
    ]);
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
      "region_id" => "required|numeric",
      "name" => "required|string|max:255",
      "country" => "required|string|max:255",
      "latitude" => "required|numeric",
      "longitude" => "required|numeric",
      "attitude" => "required|numeric",
      "proximity_radius" => "required|numeric",
      "population" => "required|numeric",
    ]);
    try {
      $localityID = $request->id;

      if ($localityID) {
        // update the value
        $locality = Locality::where('id', $localityID)->first();

        if ($locality) {
          $locality->region_id = $request->region_id;
          $locality->name = $request->name;
          $locality->country = $request->country;
          $locality->latitude = $request->latitude;
          $locality->longitude = $request->longitude;
          $locality->proximity_radius = $request->proximity_radius;
          $locality->population = $request->population;
          $locality->attitude = $request->attitude;
          $locality->verified = 2;
          $locality->verified_timestamp = now();
          $locality->search_keywords = generateKeywords($request->name);
          $locality->timestamp = now();

          if ($locality->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $locality->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Region does not exist, create a new region
          $locality = new Locality();
          $locality->region_id = $request->region_id;
          $locality->name = $request->name;
          $locality->country = $request->country;
          $locality->latitude = $request->latitude;
          $locality->longitude = $request->longitude;
          $locality->proximity_radius = $request->proximity_radius;
          $locality->population = $request->population;
          $locality->attitude = $request->attitude;
          $locality->verified = 2;
          $locality->verified_timestamp = now();
          $locality->search_keywords = generateKeywords($request->name);
          $locality->timestamp = now();

          if ($locality->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $locality->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // Region does not exist, create a new region
        $locality = new Locality();
        $locality->region_id = $request->region_id;
        $locality->name = $request->name;
        $locality->country = $request->country;
        $locality->latitude = $request->latitude;
        $locality->longitude = $request->longitude;
        $locality->proximity_radius = $request->proximity_radius;
        $locality->population = $request->population;
        $locality->attitude = $request->attitude;
        $locality->verified = 2;
        $locality->verified_timestamp = now();
        $locality->search_keywords = generateKeywords($request->name);
        $locality->timestamp = now();

        if ($locality->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $locality->getErrors();
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
    $locality = Locality::findOrFail($id);
    return response()->json($locality);
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
    Log::info($request);
    $locality = Locality::findOrFail($id);
    if ($locality)
    {
      Log::info($locality);
      $locality->name = $request->name;
      $locality->latitude = $request->latitude;
      $locality->longitude = $request->longitude;
      $locality->proximity_radius = $request->proximity_radius;
      $locality->country = $request->country;

      if ($locality->save()) {
        // Success
        Log::info('Locality updated');
      } else {
        // Handle error
        $errors = $locality->getErrors();
        Log::info($errors);
      }
    }else
    {
      Log::info('Locality not found');
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
    $localities = Locality::where('id', $id)->delete();
  }
}
