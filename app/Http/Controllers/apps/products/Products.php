<?php

namespace App\Http\Controllers\apps\products;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Storage;


class Products extends Controller
{

  protected $storage;

  public function __construct(Storage $storage)
  {
    $this->storage = $storage;
  }

  /**
   * Redirect to products view.
   *
   */
  public function ProductManagement()
  {
    // dd('Categories');
    $products = Product::all();
    $productCount = $products->count();
    $verified = 0;
    $notVerified = 0;
    $productsUnique = $products->unique(['name']);
    $productDuplicates = $products->diff($productsUnique)->count();

    return view('content.apps.products.listing', [
      'totalProducts' => $productCount,
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
      4 => 'description'
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
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Product::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($products)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($products as $product) {
        $nestedData['id'] = $product->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $product->name;
        $nestedData['description'] = $product->description;

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
      "description" => "required|string|max:255",
      //"photos" => "image|mimes:jpeg,png,jpg,gif|max:2048" // Validate file type and size
    ]);
    try {
      $productID = $request->id;



      if ($productID) {
        // update the value
        $product = Product::where('id', $productID)->first();

        if ($product) {
          $product->name = $request->name;
          $product->description = $request->description;
          $product->photo_urls = ["", "", "", "", "", ""];

          // Check if the request has files
          if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $photosUrls = [];

            // Loop through each file and upload to Firebase Storage
            foreach ($files as $index => $file) {
              $extension = $file->getClientOriginalExtension();
              $fileName = $productID . '_' . $index . '.' . $extension;
              $filePath = 'ProductPhotos/' . $fileName;

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
              $photosUrl = "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";

              // Add URL to the array
              $photosUrls[] = $photosUrl;
            }

            // Convert the URLs array to a JSON string
            $photosUrlsJson = json_encode($photosUrls);

            // Update photos_url field with the JSON string
            $product->photo_urls = $photosUrls;
          }

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
          $product->name = $request->name;
          $product->description = $request->description;
          $product->photo_urls = ["", "", "", "", "", ""];
          $product->active = 2;
          $product->active_timestamp = now();

          if ($product->save()) {
            // Success
            $productID = $product->id;
            // Check if the request has files
            if ($request->hasFile('photos')) {
              $files = $request->file('photos');
              $photosUrls = [];

              // Loop through each file and upload to Firebase Storage
              foreach ($files as $index => $file) {
                $extension = $file->getClientOriginalExtension();
                $fileName = $productID . '_' . $index . '.' . $extension;
                $filePath = 'ProductPhotos/' . $fileName;

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
                $photosUrl = "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";

                // Add URL to the array
                $photosUrls[] = $photosUrl;
              }

              // Convert the URLs array to a JSON string
              $photosUrlsJson = json_encode($photosUrls);

              // Update photos_url field with the JSON string
              $product->photo_urls = $photosUrls;
            }
            if ($product->save()) {
              // Success
              return response()->json('Created');
            } else {
              // Handle error
              $errors = $product->getErrors();
              return response()->json(['message' => $errors], 422);
            }
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
        $product->name = $request->name;
        $product->description = $request->description;
        $product->photo_urls = ["", "", "", "", "", ""];
        $product->active = 2;
        $product->active_timestamp = now();

        if ($product->save()) {
          // Success
          $productID = $product->id;
          // Check if the request has files
          if ($request->hasFile('photos')) {
            $files = $request->file('photos');
            $photosUrls = [];

            // Loop through each file and upload to Firebase Storage
            foreach ($files as $index => $file) {
              $extension = $file->getClientOriginalExtension();
              $fileName = $productID . '_' . $index . '.' . $extension;
              $filePath = 'ProductPhotos/' . $fileName;

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
              $photosUrl = "https://storage.googleapis.com/{$bucket->name()}/{$filePath}";

              // Add URL to the array
              $photosUrls[] = $photosUrl;
            }

            // Convert the URLs array to a JSON string
            $photosUrlsJson = json_encode($photosUrls);

            // Update photos_url field with the JSON string
            $product->photo_urls = $photosUrls;
          }
          if ($product->save()) {
            // Success
            return response()->json('Created');
          } else {
            // Handle error
            $errors = $product->getErrors();
            return response()->json(['message' => $errors], 422);
          }
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
