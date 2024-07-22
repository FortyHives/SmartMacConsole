<?php

namespace App\Http\Controllers\apps\outlets;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\OutletCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class OutletCategories extends Controller
{

  /**
   * Redirect to categories view.
   *
   */
  public function OutletCategoryManagement()
  {
    // dd('Categories');
    $categories = OutletCategory::all();
    $categoryCount = $categories->count();
    $verified = 0;
    $notVerified = 0;
    $categoriesUnique = $categories->unique(['title']);
    $categoryDuplicates = $categories->diff($categoriesUnique)->count();

    return view('content.apps.outlets.categories', [
      'totalOutletCategory' => $categoryCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'categoryDuplicates' => $categoryDuplicates,
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
      2 => 'title',
      3 => 'proximity_radius',
      4 => 'description'
    ];

    $search = [];

    $totalData = OutletCategory::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $categories = OutletCategory::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $categories = OutletCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = OutletCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($categories)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($categories as $category) {
        $nestedData['id'] = $category->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['title'] = $category->title;
        $nestedData['proximity_radius'] = $category->proximity_radius;
        $nestedData['description'] = $category->description;

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
      "title" => "required|string|max:255",
      "description" => "required|string|max:255",
      "proximity_radius" => "required|numeric",
    ]);
    try {
      $categoryID = $request->id;

      if ($categoryID) {
        // update the value
        $category = OutletCategory::where('id', $categoryID)->first();

        if ($category) {
          $category->title = $request->title;
          $category->description = $request->description;
          $category->proximity_radius = $request->proximity_radius;

          if ($category->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $category->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // OutletCategory does not exist, create a new category
          $category = new OutletCategory();
          $category->title = $request->title;
          $category->description = $request->description;
          $category->proximity_radius = $request->proximity_radius;
          $category->active = 2;
          $category->active_timestamp = now();

          if ($category->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $category->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // OutletCategory does not exist, create a new category
        $category = new OutletCategory();
        $category->title = $request->title;
        $category->description = $request->description;
        $category->proximity_radius = $request->proximity_radius;
        $category->active = 2;
        $category->active_timestamp = now();

        if ($category->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $category->getErrors();
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
    $category = OutletCategory::findOrFail($id);
    return response()->json($category);
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
    $categories = OutletCategory::where('id', $id)->delete();
  }
}
