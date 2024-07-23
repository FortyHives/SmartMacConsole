<?php

namespace App\Http\Controllers\apps\outlets;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\OutletCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Storage;


class ActiveOutlets extends Controller
{
  protected $storage;

  public function __construct(Storage $storage)
  {
    $this->storage = $storage;
  }
  /**
   * Redirect to outlets view.
   *
   */
  public function ActiveOutletManagement()
  {
    // dd('Outlets');
    $outlets = Outlet::all();
    $totalOutlets = $outlets->count();
    $activeOutlets = Outlet::where('active', 2)->get();
    $totalActiveOutlets = $activeOutlets->count();
    $activePercentage = 0;
    if ($totalActiveOutlets > 0 && $totalOutlets > 0) {
      $activePercentage = ($totalActiveOutlets / $totalOutlets) * 100;
    }
    $verifiedOutlets = Outlet::where('verified', 2)->where('active', 2)->get();
    $totalVerifiedOutlets = $verifiedOutlets->count();
    $verifiedPercentage = 0;
    if ($totalVerifiedOutlets > 0 && $totalActiveOutlets > 0) {
      $verifiedPercentage = ($totalVerifiedOutlets / $totalActiveOutlets) * 100;
    }

    // Fetch all roles from the database
    $categories = OutletCategory::all();

    return view('content.apps.outlets.active', [
      'totalActiveOutlets' => $totalActiveOutlets,
      'activePercentage' => number_format($activePercentage, 2) . "%",
      'totalVerifiedOutlets' => $totalVerifiedOutlets,
      'verifiedPercentage' => number_format($verifiedPercentage, 2) . "%",
      'categories' => $categories
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
      3 => 'contact_name',
      4 => 'contact_phone_number',
      5 => 'category_title',
      6 => 'region_name',
      7 => 'locality_name',
      8 => 'country',
      9 => 'verified',
    ];

    $searchValue = $request->input('search.value');
    $totalData = Outlet::count();
    $limit = $request->input('length');
    $start = $request->input('start');

    // Default ordering by name in ascending order
    $orderColumnIndex = $request->input('order.0.column', 2); // Default to 'name' column (index 2)
    $order = $columns[$orderColumnIndex] ?? 'outlets.name';
    $dir = $request->input('order.0.dir', 'asc'); // Default to ascending order

    $query = Outlet::select(
      'outlets.id',
      'outlets.name',
      'outlets.contact_name',
      'outlets.contact_phone_number',
      'outlet_categories.title as category_title',
      'regions.name as region_name',
      'localities.name as locality_name',
      'outlets.country',
      'outlets.verified',
      'outlets.active',
      'outlets.remarks',
      'outlets.category_id'
    )
      ->leftJoin('outlet_categories', 'outlets.category_id', '=', 'outlet_categories.id')
      ->leftJoin('regions', 'outlets.region_id', '=', 'regions.id')
      ->leftJoin('localities', 'outlets.locality_id', '=', 'localities.id')
      ->where('outlets.active', 2);

    if (!empty($searchValue)) {
      $query->where(function($q) use ($searchValue) {
        $q->where('outlets.id', 'LIKE', "%{$searchValue}%")
          ->orWhere('outlets.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('outlets.contact_name', 'LIKE', "%{$searchValue}%")
          ->orWhere('outlets.contact_phone_number', 'LIKE', "%{$searchValue}%")
          ->orWhere('regions.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('localities.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('outlets.country', 'LIKE', "%{$searchValue}%");
      });

      $totalFiltered = $query->count();
    } else {
      $totalFiltered = $totalData;
    }

    $outlets = $query->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    $data = [];
    $fakeId = $start;

    foreach ($outlets as $outlet) {
      $data[] = [
        'id' => $outlet->id,
        'fake_id' => ++$fakeId,
        'name' => $outlet->name,
        'contact_name' => $outlet->contact_name,
        'contact_phone_number' => $outlet->contact_phone_number,
        'region_name' => $outlet->region_name ?? 'Unknown', // Handle case where region might not exist
        'locality_name' => $outlet->locality_name ?? 'Unknown', // Handle case where locality might not exist
        'category_title' => $outlet->category_title ?? 'Unknown', // Handle case where category might not exist
        'country' => $outlet->country,
        'verified' => $outlet->verified,
        'active' => $outlet->active,
        'remarks' => $outlet->remarks,
        'category_id' => $outlet->category_id,
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
      "outlet_id" => "required|numeric",
      "name" => "required|string|max:255",
      "contact_name" => "required|string|max:255",
      "contact_phone_number" => "required|numeric",
      "category_id" => "required|numeric",
      "photo" => "image|mimes:jpeg,png,jpg,gif|max:2048" // Validate file type and size
    ]);
    try {
      $outletID = $request->id;

      if ($outletID) {
        // update the value
        $outlet = Outlet::where('id', $outletID)->first();

        if ($outlet) {
          $outlet->name = $request->name;
          $outlet->contact_name = $request->contact_name;
          $outlet->category_id = $request->category_id;
          $outlet->remarks = $request->remarks;
          $outlet->verified = 2;
          $outlet->verified_timestamp = now();
          $outlet->draft = 1;
          $outlet->draft_timestamp = now();
          $outlet->search_keywords = Helpers::generateKeywords($request->name);
          $outlet->timestamp = now();

          // Handle file upload
          if ($request->hasFile('photo')) {
            Log::channel('api')->info('Updating photo');
            try {
              $file = $request->file('photo');
              $extension = $file->getClientOriginalExtension();
              $fileName = $outletID . '_0.' . $extension;
              $filePath = 'OutletPhotos/' . $fileName;

              // Upload to Firebase Storage
              $bucket = $this->storage->getBucket();
              $bucket->upload(
                file_get_contents($file->getPathname()),
                [
                  'name' => $filePath,
                  'predefinedAcl' => 'publicRead'  // Make the file publicly accessible
                ]
              );

              // Generate public URL
              $photoUrl = "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";

              // Update photo_url field
              $outlet->photo_urls = [$photoUrl,""];

              Log::channel('api')->info([$photoUrl,""]);

            } catch (\Exception $e) {
              Log::channel('api')->error($e);
            }

          }


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
          // Outlet does not exist
          return response()->json(['message' => 'Outlet does not exixt'], 422);

        }
      } else {
        // Outlet does not exist
        return response()->json(['message' => 'Outlet does not exixt'], 422);
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
    Log::info($request);
    $outlet = Outlet::findOrFail($id);
    if ($outlet)
    {
      Log::info($outlet);
      $outlet->name = $request->name;
      $outlet->latitude = $request->latitude;
      $outlet->longitude = $request->longitude;
      $outlet->proximity_radius = $request->proximity_radius;
      $outlet->country = $request->country;

      if ($outlet->save()) {
        // Success
        Log::info('Outlet updated');
      } else {
        // Handle error
        $errors = $outlet->getErrors();
        Log::info($errors);
      }
    }else
    {
      Log::info('Outlet not found');
    }
  }

  public function activation($id, Request $request)
  {
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
      // Agent does not exist, create a new agent
      return response()->json(['message' => 'Outlet does not exist'], 422);

    }
  }

  public function verification($id, Request $request)
  {
    $outlet = Outlet::where('id', $id)->first();
    if ($outlet) {
      $outlet->verified = $request->input('status');
      $outlet->verified_timestamp = now();

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
      // Agent does not exist, create a new agent
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
