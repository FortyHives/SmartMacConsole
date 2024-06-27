<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Agent;
use App\Models\OutletCategory;
use App\Models\Locality;
use App\Models\Outlet;
use App\Models\Region;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function checkAgent(Request $request)
    {
        Log::channel('api')->info('Check account initiated');
        if ($request != null)
        {
            $request->validate([
                "email" => "required"
            ]);
            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $email = $decoded->sub;
                if ($email === $request->email)
                {
                    try {
                        $agent = Agent::where('email', $request->email)->first();

                        if ($agent) {
                          Log::channel('api')->info('Agent found');
                            return response()->json([
                                "status" => 0,
                                "message" => "Agent exists",
                                "agent" => $agent
                            ]);
                        } else {
                            // Account does not exist
                          Log::channel('api')->info('Agent does not exist');
                          return response()->json([
                            "status" => 1,
                            "message" => "Agent file  not available",
                            "account" => $agent
                          ]);

                        }
                    } catch (\Exception $e) {
                        Log::channel('api')->info('Check account: Database error');
                        Log::channel('api')->error($e);
                        return response()->json([
                            "status" => 6,
                            "message" => "Database error",
                            "data_0" => $e->getMessage()
                        ]);
                    }

                }else
                {
                    Log::channel('api')->info('Check account: JWT Verification failed');
                    Log::channel('api')->error($email.' '.$request->email);
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $email,
                        "data_1" => $request,
                        "data_2" => $request->email
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                Log::channel('api')->info('Check account: Invalid token');
                Log::channel('api')->error($e);
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $request,
                    "data_1" => $request->email,
                    "data_2" => $e
                ]);
            }
        }else
        {
            Log::channel('api')->info('Check agent: Invalid request');
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }

    }

    public function getAgent(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                            $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                                return response()->json([
                                    "status" => 0,
                                    "message" => "Agent exists",
                                    "agent" => $agent
                                ]);
                            } else {
                                // Account does not exist
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent not found"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage(),
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }

    }

    public function updateAgentPIN(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
                "pin" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                            $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                              $agent->pin = $request->pin;
                                if ($agent->save()) {
                                    // Success
                                  $agent->refresh();
                                    return response()->json([
                                        "status" => 0,
                                        "message" => "Agent pin updated",
                                        "agent" => $agent
                                    ]);
                                } else {
                                    // Handle error
                                    $errors = $agent->getErrors();
                                    return response()->json([
                                        "status" => 7,
                                        "message" => "Database error",
                                        "data_0" => $errors
                                    ]);
                                }

                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent does not exist"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage(),
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function updateAgentCurrentLocality(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
                "current_region_id" => "required",
                "current_locality_id" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id) && is_numeric($request->current_region_id) && is_numeric($request->current_locality_id)) {
                        $id = intval($request->id);
                        $current_locality_id = intval($request->current_locality_id);
                        $current_region_id = intval($request->current_region_id);
                        try {
                            $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                              $agent->current_region_id = $current_region_id;
                              $agent->current_locality_id = $current_locality_id;
                                if ($agent->save()) {
                                    // Success
                                  $agent->refresh();
                                    return response()->json([
                                        "status" => 0,
                                        "message" => "Agent locality updated",
                                        "agent" => $agent
                                    ]);
                                } else {
                                    // Handle error
                                    $errors = $agent->getErrors();
                                    return response()->json([
                                        "status" => 7,
                                        "message" => "Database error",
                                        "data_0" => $errors
                                    ]);
                                }

                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent does not exist"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage(),
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function updateAgentSelectedLocality(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
                "selected_region_id" => "required",
                "selected_locality_id" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id) && is_numeric($request->selected_region_id) && is_numeric($request->selected_locality_id)) {
                        $id = intval($request->id);
                        $selected_locality_id = intval($request->selected_locality_id);
                        $selected_region_id = intval($request->selected_region_id);
                        try {
                          $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                              $agent->selected_region_id = $selected_region_id;
                              $agent->selected_locality_id = $selected_locality_id;
                                if ($agent->save()) {
                                    // Success
                                  $agent->refresh();
                                    return response()->json([
                                        "status" => 0,
                                        "message" => "Agent locality updated",
                                        "agent" => $agent
                                    ]);
                                } else {
                                    // Handle error
                                    $errors = $agent->getErrors();
                                    return response()->json([
                                        "status" => 7,
                                        "message" => "Database error",
                                        "data_0" => $errors
                                    ]);
                                }

                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent does not exist"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage(),
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function updateAgentPhotoUrl(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
                "photo_url" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                          $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                              $agent->photo_url = json_decode($request->photo_url, 2);
                                if ($agent->save()) {
                                    // Success
                                  $agent->refresh();

                                    return response()->json([
                                        "status" => 0,
                                        "message" => "Agent photo url updated",
                                        "agent" => $agent
                                    ]);
                                } else {
                                    // Handle error
                                    $errors = $agent->getErrors();
                                    return response()->json([
                                        "status" => 7,
                                        "message" => "Database error",
                                        "data_0" => $errors
                                    ]);
                                }

                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent does not exist"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function getRegions(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    try {
                        $regions = Region::get();

                        if ($regions) {
                            return response()->json([
                                "status" => 0,
                                "message" => $regions->count()." regions found",
                                "regions" => $regions
                            ]);
                        } else {
                            return response()->json([
                                "status" => 1,
                                "message" => "Regions not found"
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Token is invalid
                        return response()->json([
                            "status" => 6,
                            "message" => "Database error",
                            "data_0" => $e->getMessage()
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function createRegion(Request $request){
        if ($request != null)
        {
            if ($request->access_key != null AND $request->access_key == env('ACCESS_KEY'))
            {
                $request->validate([
                    "name" => "required",
                    "country" => "required",
                    "latitude" => "required",
                    "longitude" => "required",
                    "proximity_radius" => "required",
                ]);
                try {
                    $region = Region::where('name', $request->name)->first();

                    if ($region) {
                        return response()->json([
                            "status" => 1,
                            "message" => "Region exists",
                        ]);
                    } else {
                        // Region does not exist, create a new region
                        $region = new Region();
                        $region->name = $request->name;
                        $region->country = $request->country;
                        $region->search_keywords = json_decode($request->search_keywords, 2);
                        $region->latitude = $request->latitude;
                        $region->longitude = $request->longitude;
                        $region->proximity_radius = $request->proximity_radius;
                        $region->timestamp = now();

                        if ($region->save()) {
                            // Success
                            return response()->json([
                                "status" => 0,
                                "message" => "Region created"
                            ]);
                        } else {
                            // Handle error
                            $errors = $region->getErrors();
                            return response()->json([
                                "status" => 7,
                                "message" => "Database error",
                                "data_0" => $errors
                            ]);
                        }


                    }
                } catch (\Exception $e) {
                    // Token is invalid
                    return response()->json([
                        "status" => 6,
                        "message" => "Database error",
                        "data_0" => $e->getMessage()
                    ]);
                }

            }else
            {
                return response()->json([
                    "status" => 2,
                    "message" => "Invalid Access Key"
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 3,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function getLocalities(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    try {
                        $localities = Locality::get();

                        if ($localities) {
                            return response()->json([
                                "status" => 0,
                                "message" => $localities->count()." localities found",
                                "localities" => $localities
                            ]);
                        } else {
                            return response()->json([
                                "status" => 1,
                                "message" => "Localities not found"
                            ]);
                        }
                    } catch (\Exception $e) {
                        // Token is invalid
                        return response()->json([
                            "status" => 6,
                            "message" => "Database error",
                            "data_0" => $e->getMessage()
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function getLocality(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                            $locality = Locality::where('id', $id)->first();

                            if ($locality) {
                                return response()->json([
                                    "status" => 0,
                                    "message" => "Locality exists",
                                    "locality" => $locality
                                ]);
                            } else {
                                // Tenant does not exist
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Locality not found"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function getRegionLocalities(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                            //$localities = Locality::get();
                            $localities = Locality::where('region_id', $id)
                                ->get();

                            if ($localities) {
                                return response()->json([
                                    "status" => 0,
                                    "message" => $localities->count()." region localities found",
                                    "localities" => $localities
                                ]);
                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Region localities not found"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function getOutletCategories(Request $request){
      if ($request != null)
      {
        $request->validate([
          "id" => "required"
        ]);

        try {
          $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
          //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
          $id = $decoded->sub;
          if ($id === $request->id)
          {
            try {
              $categories = OutletCategory::get();

              if ($categories) {
                return response()->json([
                  "status" => 0,
                  "message" => $categories->count()." categories found",
                  "categories" => $categories
                ]);
              } else {
                return response()->json([
                  "status" => 1,
                  "message" => "Categories not found"
                ]);
              }
            } catch (\Exception $e) {
              // Token is invalid
              return response()->json([
                "status" => 6,
                "message" => "Database error",
                "data_0" => $e->getMessage()
              ]);
            }

          }else
          {
            return response()->json([
              "status" => 2,
              "message" => "Invalid token",
              "data_0" => $id,
              "data_1" => $request,
              "data_2" => $request->id
            ]);
          }
        } catch (\Exception $e) {
          // Token is invalid
          return response()->json([
            "status" => 3,
            "message" => "Invalid token",
            "data_0" => $e->getMessage()
          ]);
        }
      }else
      {
        return response()->json([
          "status" => 4,
          "message" => "Invalid Request"
        ]);
      }
    }

    public function createLocality(Request $request){
        if ($request != null)
        {
            if ($request->access_key != null AND $request->access_key == env('ACCESS_KEY'))
            {
                $request->validate([
                  "id" => "required",
                    "region_id" => "required",
                    "name" => "required",
                    "country" => "required",
                    "latitude" => "required",
                    "longitude" => "required",
                    "proximity_radius" => "required",
                  "attitude" => "required",
                  "population" => "required",
                ]);

              try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                  if (is_numeric($request->id) && is_numeric($request->region_id) && is_numeric($request->latitude) && is_numeric($request->longitude) && is_numeric($request->proximity_radius) && is_numeric($request->attitude) && is_numeric($request->population)) {
                    $id = intval($request->id);
                    $region_id = intval($request->region_id);
                    $latitude = intval($request->latitude);
                    $longitude = intval($request->longitude);
                    $proximity_radius = intval($request->proximity_radius);
                    $attitude = intval($request->attitude);
                    $population = intval($request->population);

                    try {
                      $locality = Locality::where('name', $request->name)->first();

                      if ($locality) {
                        return response()->json([
                          "status" => 1,
                          "message" => "Locality exists",
                        ]);
                      } else {
                        // Region does not exist, create a new region
                        $locality = new Locality();
                        $locality->region_id = $region_id;
                        $locality->mapped_by_id = $id;
                        $locality->name = $request->name;
                        $locality->country = $request->country;
                        $locality->search_keywords = Helpers::generateKeywords($request->name ." ". $request->country);
                        $locality->latitude = $latitude;
                        $locality->longitude = $longitude;
                        $locality->proximity_radius = $proximity_radius;
                        $locality->population = $population;
                        $locality->attitude = $attitude;
                        $locality->verified = 2;
                        $locality->verified_timestamp = now();
                        $locality->timestamp = now();

                        if ($locality->save()) {
                          // Success
                          $locality->refresh();

                          return response()->json([
                            "status" => 0,
                            "message" => "Locality created",
                            "locality" => $locality
                          ]);
                        } else {
                          // Handle error
                          $errors = $locality->getErrors();
                          return response()->json([
                            "status" => 7,
                            "message" => "Database error",
                            "data_0" => $errors
                          ]);
                        }

                      }
                    } catch (\Exception $e) {
                      // Token is invalid
                      return response()->json([
                        "status" => 6,
                        "message" => "Database error",
                        "data_0" => $e->getMessage()
                      ]);
                    }

                  } else {
                    return response()->json([
                      "status" => 5,
                      "message" => "Invalid Data Request"
                    ]);
                  }

                }else
                {
                  return response()->json([
                    "status" => 2,
                    "message" => "Invalid token",
                    "data_0" => $id,
                    "data_1" => $request,
                    "data_2" => $request->id
                  ]);
                }
              } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                  "status" => 3,
                  "message" => "Invalid token",
                  "data_0" => $e->getMessage()
                ]);
              }



            }else
            {
                return response()->json([
                    "status" => 2,
                    "message" => "Invalid Access Key"
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 3,
                "message" => "Invalid Request"
            ]);
        }
    }

    public function updateAgentDetails(Request $request){
        if ($request != null)
        {
            $request->validate([
                "id" => "required",
                "name" => "required"
            ]);

            try {
                $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
                //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
                $id = $decoded->sub;
                if ($id === $request->id)
                {
                    if (is_numeric($request->id)) {
                        $id = intval($request->id);
                        try {
                            $agent = Agent::where('id', $id)->first();

                            if ($agent) {
                              $agent->name = json_decode($request->name, 2);
                              $agent->email = $request->email;
                                if ($agent->save()) {
                                    // Success
                                  $agent->refresh();

                                    return response()->json([
                                        "status" => 0,
                                        "message" => "Agent details updated",
                                        "account" => $agent
                                    ]);
                                } else {
                                    // Handle error
                                    $errors = $agent->getErrors();
                                    return response()->json([
                                        "status" => 7,
                                        "message" => "Database error",
                                        "data_0" => $errors
                                    ]);
                                }

                            } else {
                                return response()->json([
                                    "status" => 1,
                                    "message" => "Agent does not exist"
                                ]);
                            }
                        } catch (\Exception $e) {
                            // Token is invalid
                            return response()->json([
                                "status" => 6,
                                "message" => "Database error",
                                "data_0" => $e->getMessage()
                            ]);
                        }

                    } else {
                        return response()->json([
                            "status" => 5,
                            "message" => "Invalid Data Request"
                        ]);
                    }

                }else
                {
                    return response()->json([
                        "status" => 2,
                        "message" => "Invalid token",
                        "data_0" => $id,
                        "data_1" => $request,
                        "data_2" => $request->id
                    ]);
                }
            } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                    "status" => 3,
                    "message" => "Invalid token",
                    "data_0" => $e->getMessage()
                ]);
            }
        }else
        {
            return response()->json([
                "status" => 4,
                "message" => "Invalid Request"
            ]);
        }
    }

  public function getOutlets(Request $request){
    if ($request != null)
    {
      $request->validate([
        "id" => "required",
        "locality_id" => "required",
        "region_id" => "required",
      ]);

      try {
        $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
        //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
        $id = $decoded->sub;
        if ($id === $request->id)
        {
          if (is_numeric($request->id) && is_numeric($request->locality_id) && is_numeric($request->region_id)) {
            $id = intval($request->id);
            $locality_id = intval($request->locality_id);
            $region_id = intval($request->region_id);
            try {
              $outlets = Outlet::where('active', 2)
                ->where('region_id', $region_id)
                ->where('locality_id', $locality_id)
                ->get();

              if ($outlets) {
                return response()->json([
                  "status" => 0,
                  "message" => $outlets->count()." outlets found",
                  "outlets" => $outlets
                ]);
              } else {
                return response()->json([
                  "status" => 1,
                  "message" => "Outlets not found"
                ]);
              }
            } catch (\Exception $e) {
              // Token is invalid
              return response()->json([
                "status" => 6,
                "message" => "Database error",
                "data_0" => $e->getMessage()
              ]);
            }

          } else {
            return response()->json([
              "status" => 5,
              "message" => "Invalid Data Request"
            ]);
          }

        }else
        {
          return response()->json([
            "status" => 2,
            "message" => "Invalid token",
            "data_0" => $id,
            "data_1" => $request,
            "data_2" => $request->id
          ]);
        }
      } catch (\Exception $e) {
        // Token is invalid
        return response()->json([
          "status" => 3,
          "message" => "Invalid token",
          "data_0" => $e->getMessage()
        ]);
      }
    }else
    {
      return response()->json([
        "status" => 4,
        "message" => "Invalid Request"
      ]);
    }
  }

  public function createAnOutlet(Request $request){
    if ($request != null)
    {
      if ($request->access_key != null AND $request->access_key == env('ACCESS_KEY'))
      {
        $request->validate([
          "id" => "required",
          "country" => "required",
          "region_id" => "required",
          "locality_id" => "required",
          "latitude" => "required",
          "longitude" => "required",
          "category_id" => "required",
          "sales_value" => "required",
          "name" => "required",
          "contact_name" => "required",
          "contact_phone_number" => "required",
          //"contact_email_address" => "required",
          //"remarks" => "required",
          "mapped_by_id" => "required",
        ]);

        try {
          $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
          //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
          $id = $decoded->sub;
          if ($id === $request->id)
          {
            if (is_numeric($request->id) && is_numeric($request->region_id) && is_numeric($request->locality_id) && is_numeric($request->latitude) && is_numeric($request->longitude) && is_numeric($request->category_id) && is_numeric($request->mapped_by_id)) {
              $id = intval($request->id);
              $region_id = intval($request->region_id);
              $latitude = intval($request->latitude);
              $longitude = intval($request->longitude);
              $locality_id = intval($request->locality_id);
              $category_id = intval($request->category_id);
              $mapped_by_id = intval($request->mapped_by_id);

              try {
                $outlet = Outlet::where('name', $request->name)->first();

                if ($outlet) {
                  return response()->json([
                    "status" => 1,
                    "message" => "Outlet exists",
                  ]);
                } else {
                  // Outlet does not exist, create a new outlet
                  $outlet = new Locality();
                  $outlet->name = $request->name;
                  $outlet->country = $request->country;
                  $outlet->latitude = $latitude;
                  $outlet->longitude = $longitude;
                  $outlet->category_id = $category_id;
                  $outlet->region_id = $region_id;
                  $outlet->mapped_by_id = $id;
                  $outlet->contact_name = $request->contact_name;
                  $outlet->contact_email = $request->contact_email;
                  $outlet->contact_photo_url = $request->contact_photo_url;
                  $outlet->contact_phone_number = $request->contact_phone_number;

                  $outlet->photo_urls = ["", ""];
                  $outlet->search_keywords = Helpers::generateKeywords($request->name ." ". $request->contact_name);
                  $outlet->active = 2;
                  $outlet->active_timestamp = now();
                  $outlet->verified = 2;
                  $outlet->verified_timestamp = now();
                  $outlet->draft = 1;
                  $outlet->draft_timestamp = now();
                  $outlet->timestamp = now();

                  if ($outlet->save()) {
                    // Success
                    $outlet->refresh();

                    return response()->json([
                      "status" => 0,
                      "message" => "Outlet created",
                      "outlet" => $outlet
                    ]);
                  } else {
                    // Handle error
                    $errors = $outlet->getErrors();
                    return response()->json([
                      "status" => 7,
                      "message" => "Database error",
                      "data_0" => $errors
                    ]);
                  }

                }
              } catch (\Exception $e) {
                // Token is invalid
                return response()->json([
                  "status" => 6,
                  "message" => "Database error",
                  "data_0" => $e->getMessage()
                ]);
              }

            } else {
              return response()->json([
                "status" => 5,
                "message" => "Invalid Data Request"
              ]);
            }

          }else
          {
            return response()->json([
              "status" => 2,
              "message" => "Invalid token",
              "data_0" => $id,
              "data_1" => $request,
              "data_2" => $request->id
            ]);
          }
        } catch (\Exception $e) {
          // Token is invalid
          return response()->json([
            "status" => 3,
            "message" => "Invalid token",
            "data_0" => $e->getMessage()
          ]);
        }



      }else
      {
        return response()->json([
          "status" => 2,
          "message" => "Invalid Access Key"
        ]);
      }
    }else
    {
      return response()->json([
        "status" => 3,
        "message" => "Invalid Request"
      ]);
    }
  }

  public function updateAgentSelectedCategory(Request $request){
    if ($request != null)
    {
      $request->validate([
        "id" => "required",
        "selected_category_id" => "required"
      ]);

      try {
        $decoded = JWT::decode(str_replace('Bearer ', '', $request->header('Authorization')), env('JWT_SECRET'), array('HS256'));
        //$decoded = JWT::decode($request->token, env('JWT_SECRET'), array('HS256'));
        $id = $decoded->sub;
        if ($id === $request->id)
        {
          if (is_numeric($request->id) && is_numeric($request->selected_category_id)) {
            $id = intval($request->id);
            $selected_category_id = intval($request->selected_category_id);
            try {
              $agent = Agent::where('id', $id)->first();

              if ($agent) {
                $agent->selected_category_id = $selected_category_id;
                if ($agent->save()) {
                  // Success
                  $agent->refresh();
                  return response()->json([
                    "status" => 0,
                    "message" => "Agent category updated",
                    "agent" => $agent
                  ]);
                } else {
                  // Handle error
                  $errors = $agent->getErrors();
                  return response()->json([
                    "status" => 7,
                    "message" => "Database error",
                    "data_0" => $errors
                  ]);
                }

              } else {
                return response()->json([
                  "status" => 1,
                  "message" => "Agent does not exist"
                ]);
              }
            } catch (\Exception $e) {
              // Token is invalid
              return response()->json([
                "status" => 6,
                "message" => "Database error",
                "data_0" => $e->getMessage()
              ]);
            }

          } else {
            return response()->json([
              "status" => 5,
              "message" => "Invalid Data Request"
            ]);
          }

        }else
        {
          return response()->json([
            "status" => 2,
            "message" => "Invalid token",
            "data_0" => $id,
            "data_1" => $request,
            "data_2" => $request->id
          ]);
        }
      } catch (\Exception $e) {
        // Token is invalid
        return response()->json([
          "status" => 3,
          "message" => "Invalid token",
          "data_0" => $e->getMessage(),
        ]);
      }
    }else
    {
      return response()->json([
        "status" => 4,
        "message" => "Invalid Request"
      ]);
    }
  }

    function getOption($option_key, $default = '')
    {
        $system_settings = config('marima_settings');
        if ($option_key && isset($system_settings[$option_key])) {
            return $system_settings[$option_key];
        } else {
            return $default;
        }
    }



}
