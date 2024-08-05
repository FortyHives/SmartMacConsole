<?php

namespace App\Http\Controllers\apps\planograms;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\OutletCategory;
use App\Models\Planogram;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Storage;


class ActivePlanograms extends Controller
{
  protected $storage;

  public function __construct(Storage $storage)
  {
    $this->storage = $storage;
  }
  /**
   * Redirect to planograms view.
   *
   */
  public function ActivePlanogramManagement()
  {
    // dd('Planograms');
    $planograms = Planogram::all();
    $totalPlanograms = $planograms->count();
    $activePlanograms = Planogram::where('active', 2)->get();
    $totalActivePlanograms = $activePlanograms->count();
    $activePercentage = 0;
    if ($totalActivePlanograms > 0 && $totalPlanograms > 0) {
      $activePercentage = ($totalActivePlanograms / $totalPlanograms) * 100;
    }
    $suspendedPlanograms = Planogram::where('suspended', 2)->where('active', 2)->get();
    $totalSuspendedPlanograms = $suspendedPlanograms->count();
    $suspendedPercentage = 0;
    if ($totalSuspendedPlanograms > 0 && $totalActivePlanograms > 0) {
      $suspendedPercentage = ($totalSuspendedPlanograms / $totalActivePlanograms) * 100;
    }

    // Fetch all roles from the database
    $products = Product::all();
    $categories = OutletCategory::all();

    return view('content.apps.planograms.active', [
      'totalActivePlanograms' => $totalActivePlanograms,
      'activePercentage' => number_format($activePercentage, 2) . "%",
      'totalSuspendedPlanograms' => $totalSuspendedPlanograms,
      'suspendedPercentage' => number_format($suspendedPercentage, 2) . "%",
      'products' => $products,
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
      3 => 'category_title',
      4 => 'description',
      5 => 'products_id',
      6 => 'suspended',
    ];

    $searchValue = $request->input('search.value');
    $totalData = Planogram::count();
    $limit = $request->input('length');
    $start = $request->input('start');

    // Default ordering by name in ascending order
    $orderColumnIndex = $request->input('order.0.column', 2); // Default to 'name' column (index 2)
    $order = $columns[$orderColumnIndex] ?? 'planograms.name';
    $dir = $request->input('order.0.dir', 'asc'); // Default to ascending order

    $query = Planogram::select(
      'planograms.id',
      'planograms.name',
      'outlet_categories.title as category_title',
      'planograms.description',
      'planograms.products_id',
      'planograms.category_id',
      'planograms.suspended',
      'planograms.active'
    )
      ->leftJoin('outlet_categories', 'planograms.category_id', '=', 'outlet_categories.id')
      ->where('planograms.active', 2);

    if (!empty($searchValue)) {
      $query->where(function($q) use ($searchValue) {
        $q->where('planograms.id', 'LIKE', "%{$searchValue}%")
          ->orWhere('planograms.name', 'LIKE', "%{$searchValue}%")
          ->orWhere('planograms.description', 'LIKE', "%{$searchValue}%");
      });

      $totalFiltered = $query->count();
    } else {
      $totalFiltered = $totalData;
    }

    $planograms = $query->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    $data = [];
    $fakeId = $start;

    foreach ($planograms as $planogram) {
      $data[] = [
        'id' => $planogram->id,
        'fake_id' => ++$fakeId,
        'name' => $planogram->name,
        'category_title' => $outlet->category_title ?? 'Unknown',
        'description' => $planogram->description,
        'products_id' => $planogram->products_id,
        'suspended' => $planogram->suspended,
        'active' => $planogram->active,
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
      "name" => "required|string|max:255",
      "description" => "required|string|max:255",
      //"products_id" => "required|numeric",
      "photo" => "image|mimes:jpeg,png,jpg,gif|max:2048" // Validate file type and size
    ]);
    try {
      $planogramID = $request->id;

      if ($planogramID) {
        // update the value
        $planogram = Planogram::where('id', $planogramID)->first();

        if ($planogram) {
          $planogram->name = $request->name;
          $planogram->description = $request->description;
          $planogram->products_id = $request->products_id;
          $planogram->category_id = $request->category_id;

          // Handle file upload
          if ($request->hasFile('photo')) {
            Log::channel('api')->info('Updating photo');
            try {
              $file = $request->file('photo');
              $extension = $file->getClientOriginalExtension();
              $fileName = $planogramID . '_0.' . $extension;
              $filePath = 'PlanogramPhotos/' . $fileName;

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
              $planogram->photo_url = $photoUrl;

              Log::channel('api')->info($photoUrl);

            } catch (\Exception $e) {
              Log::channel('api')->error($e);
            }

          }


          if ($planogram->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $planogram->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Planogram does not exist
          // OutletCategory does not exist, create a new category
          $planogram = new Planogram();
          $planogram->name = $request->name;
          $planogram->description = $request->description;
          $planogram->products_id = $request->products_id;
          $planogram->category_id = $request->category_id;
          $planogram->active = 2;
          $planogram->active_timestamp = now();
          $planogram->suspended = 1;
          $planogram->suspended_timestamp = now();

          if ($planogram->save()) {
            // Success
            $planogramID = $planogram ->id;
            // Handle file upload
            if ($request->hasFile('photo')) {
              Log::channel('api')->info('Updating photo');
              try {
                $file = $request->file('photo');
                $extension = $file->getClientOriginalExtension();
                $fileName = $planogramID . '_0.' . $extension;
                $filePath = 'PlanogramPhotos/' . $fileName;

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
                $planogram->photo_url = $photoUrl;

                Log::channel('api')->info($photoUrl);

                if ($planogram->save()) {
                  // Success
                  return response()->json('Created');
                } else {
                  // Handle error
                  $errors = $planogram->getErrors();
                  return response()->json(['message' => $errors], 422);
                }

              } catch (\Exception $e) {
                Log::channel('api')->error($e);
                return response()->json(['message' => error($e)], 422);
              }

            }
          } else {
            // Handle error
            $errors = $planogram->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // Planogram does not exist
        // OutletCategory does not exist, create a new category
        $planogram = new Planogram();
        $planogram->name = $request->name;
        $planogram->description = $request->description;
        $planogram->products_id = $request->products_id;
        $planogram->category_id = $request->category_id;
        $planogram->active = 2;
        $planogram->active_timestamp = now();
        $planogram->suspended = 1;
        $planogram->suspended_timestamp = now();

        if ($planogram->save()) {
          // Success
          $planogramID = $planogram ->id;
          // Handle file upload
          if ($request->hasFile('photo')) {
            Log::channel('api')->info('Updating photo');
            try {
              $file = $request->file('photo');
              $extension = $file->getClientOriginalExtension();
              $fileName = $planogramID . '_0.' . $extension;
              $filePath = 'PlanogramPhotos/' . $fileName;

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
              $planogram->photo_url = $photoUrl;

              Log::channel('api')->info($photoUrl);

              if ($planogram->save()) {
                // Success
                return response()->json('Created');
              } else {
                // Handle error
                $errors = $planogram->getErrors();
                return response()->json(['message' => $errors], 422);
              }

            } catch (\Exception $e) {
              Log::channel('api')->error($e);
              return response()->json(['message' => error($e)], 422);
            }

          }

        } else {
          // Handle error
          $errors = $planogram->getErrors();
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
    $planogram = Planogram::findOrFail($id);
    return response()->json($planogram);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  public function activation($id, Request $request)
  {
    $planogram = Planogram::where('id', $id)->first();
    if ($planogram) {
      $planogram->active = $request->input('status');
      $planogram->active_timestamp = now();

      if ($planogram->save()) {
        // Success
        return response()->json('Status updated successfully.');
      } else {
        // Handle error
        $errors = $planogram->getErrors();
        return response()->json(['message' => $errors], 422);
      }
    } else
    {
      // Agent does not exist, create a new agent
      return response()->json(['message' => 'Planogram does not exist'], 422);

    }
  }

  public function suspension($id, Request $request)
  {
    $planogram = Planogram::where('id', $id)->first();
    if ($planogram) {
      $planogram->suspended = $request->input('status');
      $planogram->suspended_timestamp = now();

      if ($planogram->save()) {
        // Success
        return response()->json('Status updated successfully.');
      } else {
        // Handle error
        $errors = $planogram->getErrors();
        return response()->json(['message' => $errors], 422);
      }
    } else
    {
      // Agent does not exist, create a new agent
      return response()->json(['message' => 'Planogram does not exist'], 422);

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
    $planograms = Planogram::where('id', $id)->delete();
  }
}
