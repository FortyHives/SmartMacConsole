<?php

namespace App\Http\Controllers\apps\products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class Products extends Controller
{
  /**
   * Redirect to products view.
   *
   */
  public function ProductManagement()
  {
    // dd('Products');
    $products = Product::all();
    $productCount = $products->count();
    $verified = Product::whereNotNull('active')->get()->count();
    $notVerified = Product::whereNull('draft')->get()->count();
    $productsUnique = $products->unique(['name']);
    $productDuplicates = $products->diff($productsUnique)->count();

    return view('content.apps.products.listing', [
      'totalProduct' => $productCount,
      'verified' => $verified,
      'notVerified' => $notVerified,
      'productDuplicates' => $productDuplicates,
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

    $totalData = Product::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $products = Product::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $products = Product::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Product::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($products)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($products as $product) {
        $nestedData['id'] = $product->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $product->name[0]  ." ".  $product->name[1]  ." ". $product->name[2];
        $nestedData['email'] = $product->email;
        $nestedData['phone_number'] = $product->phone_number;
        $nestedData['id_number'] = $product->id_number;
        $nestedData['role'] = $product->role;
        $nestedData['country'] = $product->country;
        $nestedData['active'] = $product->active;

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
      $productID = $request->id;

      if ($productID) {
        // update the value
        $product = Product::where('id', $productID)->first();

        if ($product) {
          $product->name = [$request->first_name, $request->middle_name, $request->last_name];
          $product->country = $request->country;
          //$product->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $product->email = $request->email;
          $product->phone_number = $request->phone_number;
          $product->id_number = $request->id_number;
          $product->role = $request->role;

          if ($product->save()) {
            // Success
            return response()->json('Updated');
          } else {
            // Handle error
            $errors = $product->getErrors();
            return response()->json(['message' => $errors], 422);
          }
        } else
        {
          // Product does not exist, create a new product
          $product = new Product();
          $product->name = [$request->first_name, $request->middle_name, $request->last_name];
          $product->country = $request->country;
          //$product->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
          $product->email = $request->email;
          $product->phone_number = $request->phone_number;
          $product->id_number = $request->id_number;
          $product->role = $request->role;
          $product->active = 2;
          $product->active_timestamp = now();
          $product->suspended = 1;
          $product->suspended_timestamp = now();

          if ($product->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $product->getErrors();
            return response()->json(['message' => $errors], 422);
          }

        }
      } else {
        // create new one if email is unique
        // Product does not exist, create a new product
        $product = new Product();
        $product->name = [$request->first_name, $request->middle_name, $request->last_name];
        $product->country = $request->country;
        //$product->search_keywords = generateKeywords($request->first_name ." ". $request->middle_name ." ". $request->last_name);
        $product->email = $request->email;
        $product->phone_number = $request->phone_number;
        $product->id_number = $request->id_number;
        $product->role = $request->role;
        $product->active = 2;
        $product->active_timestamp = now();
        $product->suspended = 1;
        $product->suspended_timestamp = now();

        if ($product->save()) {
          // Success
          return response()->json('Created');
        } else {
          // Handle error
          $errors = $product->getErrors();
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
  public function product($id)
  {
    // Fetch the product record using the provided ID
    $product = Product::find($id);
    return view('content.apps.products.product', ['product' => $product]);
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id): JsonResponse
  {
    $product = Product::findOrFail($id);
    return response()->json($product);
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
    //$product = Product::find($id);
    $product = Product::where('id', $id)->first();
    if ($product) {
      $product->active = $request->input('status');
      $product->active_timestamp = now();

      if ($product->save()) {
        // Success
        return response()->json('Status updated successfully.');
      } else {
        // Handle error
        $errors = $product->getErrors();
        return response()->json(['message' => $errors], 422);
      }
    } else
    {
      // Product does not exist, create a new product
      return response()->json(['message' => 'Product does not exist'], 422);

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
    $products = Product::where('id', $id)->delete();
  }
}
