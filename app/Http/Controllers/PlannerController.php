<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use GooglePlaces;
use SKAgarwal\GoogleApi\PlacesApi;
use GoogleMaps\ServiceProvider;
use Illuminate\Support\Collection;

//require 'vendor/autoload.php';

class PlannerController extends Controller
{   
    

    public function index()
    {   
        
        $stations = \App\Station::where('status', 'active')->get();

        //     if ($result->isNotEmpty()) {
        //         $locations  = $locations->concat(collect($result));  //use merge or concat
        //         }

        $fstation = \App\Station::where('status', 'active')->first()->id;        
      
        return view('planner', compact('fstation','stations'));
               
    }

    public function search(Request $request)
    {    

        $this->validate($request, [
            'place1' => 'required',
            'start' => 'required',
            'option' =>'required_with:place2',
            // 'longitude' =>'required',
        ]);


        //get stations
        $stations = \App\Station::where('status', 'active')->get();
        $fstation = \App\Station::where('status', 'active')->first()->id;
        $radius = 10;
        $locations = new Collection;

        $place = $request['place1'];
            $keyword = array('keyword' => $place);
        $place2 = $request['place2'];
            if (($place2 == '') ? $place2 =null : ($keyword2 = array('keyword' => $place2)));
        $start = $request['start'];
            $stIndex = $start - 1;
        $end = $request['end'];
            if (($end == '') ? $end =null : ($enIndex = $end - 1));
        $option = $request['option'];
            if (($option == '') ? $option ==null : $option = $request['option']);

        if($end == null)
        {
            if ($place2 == null)
            {
                //search forward
                for($i=$stIndex; $i< count($stations); $i++)
                {
                    $lat = $stations[$i]->lat;
                    $lng = $stations[$i]->lng;
                    $location = $lat. "," .$lng;
                    //dd($location);
                    $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                    //dd($response["results"]);
                    if(!empty($response["results"]) && count($response["results"]) > 0 )
                    {
                        $data = $response["results"];
                        //dd($response["results"]);

                        $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                            
                            // Add the new property in every places
                            $tempLat = $data['geometry']['location']['lat'];
                            $tempLng = $data['geometry']['location']['lng'];
                            $tempLocation = $tempLat. "," .$tempLng;
                            //dd($tempLocation);
                            $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                            $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                            $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                            // Add the new property
                            $data['index'] = $i;
                            $data['diff'] = $i-$stIndex;
                            $data['distance'] = $distance;
                            $data['duration'] = $duration;
                        
                            // Return the new object
                            return $data;
                        
                        });
                    break;
                    }
                }

                //search backward
                for($i=$stIndex-1; $i >= 0; $i--)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];

                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $stIndex-$i;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });

                            $locations = $locations->merge($unmerge);

                            //$locations->put('index', $i);
                            //$locations[1] = $i;

                            //$index = $index->merge($i); 
                        break;
                        }

                    }
                
                return view('planner', compact('fstation','stations','locations'));

            }
            else //has place2
            {
                if( $option == 'single')
                {
                    //forward
                    for($i=$stIndex; $i< count($stations); $i++)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        
                        if( count($response["results"]) > 0 && count($response2["results"]) > 0)
                        {
                            $data = $response["results"]->merge($response2["results"]);
                            // dd($data);
                            //$data = $response["results"];

                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $i-$stIndex;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });
                            $locations = $locations->merge($unmerge);
                        break;
                        }
                            
                    } 

                    //backward
                    for($i=$stIndex-1; $i >= 0; $i--)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        
                        if( count($response["results"]) > 0 && count($response2["results"]) > 0)
                        {
                            $data = $response["results"]->merge($response2["results"]);
                            // dd($data);
                            //$data = $response["results"];

                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $stIndex-$i;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });
                            $locations = $locations->merge($unmerge);
                        break;
                        }
                            
                    }

                    return view('planner', compact('fstation','stations','locations'));
                } 
                else // multiple option
                {
                    //place 1 forward
                    for($i=$stIndex; $i< count($stations); $i++)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];
                            //dd($response["results"]);
    
                            $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                                
                                // Add the new property in every places
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $i-$stIndex;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });
                        break;
                        }

                    }

                    //place 1 backward
                    for($i=$stIndex-1; $i >= 0; $i--)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];

                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $stIndex-$i;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });

                            $locations = $locations->merge($unmerge);

                            //$locations->put('index', $i);
                            //$locations[1] = $i;

                            //$index = $index->merge($i); 
                        break;
                        }

                    }

                    //place 2 forward
                    for($i=$stIndex; $i< count($stations); $i++)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];
                            
                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
    
                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $i-$stIndex;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });

                            $locations = $locations->merge($unmerge);
                            //$locations->put('index', $i);
                            //$locations[1] = $i;

                            //$index = $index->merge($i); 
                        break;
                        }
                    }

                    //place 2 backward
                    for($i=$stIndex-1; $i >= 0; $i--)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];

                            $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
    
                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $stIndex-$i;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });
                            $locations = $locations->merge($unmerge);
                        break;
                        }
                    } 
                 
                    return view('planner', compact('fstation','stations','locations'));

                }
            }
        }
        else //has ending
        {
            if( $place2 == null)
            {   
                //forward
                if($stIndex < $enIndex)
                {
                    for($i=$stIndex; $i <= $enIndex; $i++)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];
                            //dd($response["results"]);
    
                            $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                                
                                // Add the new property in every places
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $i-$stIndex;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });
                        break;
                        }
                       
                        //$locations =null;

                    }
                    
                    return view('planner', compact('fstation','stations','locations'));
                }
                else //backward
                {
                    for($i=$stIndex; $i >= $enIndex; $i--)
                    {
                        $lat = $stations[$i]->lat;
                        $lng = $stations[$i]->lng;
                        $location = $lat. "," .$lng;
                        //dd($location);
                        $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                        //dd($response["results"]);
                        if(!empty($response["results"]) && count($response["results"]) > 0 )
                        {
                            $data = $response["results"];

                            $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                //Find distance & duration
                                $tempLat = $data['geometry']['location']['lat'];
                                $tempLng = $data['geometry']['location']['lng'];
                                $tempLocation = $tempLat. "," .$tempLng;
                                //dd($tempLocation);
                                $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                // Add the new property
                                $data['index'] = $i;
                                $data['diff'] = $stIndex-$i;
                                $data['distance'] = $distance;
                                $data['duration'] = $duration;
                            
                                // Return the new object
                                return $data;
                            
                            });

                        break;
                        }
                        //$locations =null;

                    }
                    
                    return view('planner', compact('fstation','stations','locations'));
                }
            }
            else  // has place 2
            {
                if( $option == 'single')
                {
                    //forward
                    if($stIndex < $enIndex)
                    {
                        for($i=$stIndex; $i<= $enIndex; $i++)
                        {
                            $lat = $stations[$i]->lat;
                            $lng = $stations[$i]->lng;
                            $location = $lat. "," .$lng;
                            //dd($location);
                            $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                            $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                            
                            if( count($response["results"]) > 0 && count($response2["results"]) > 0)
                            {
                                $data = $response["results"]->merge($response2["results"]);
                                // dd($data);
                                //$data = $response["results"];

                                $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                    //Find distance & duration
                                    $tempLat = $data['geometry']['location']['lat'];
                                    $tempLng = $data['geometry']['location']['lng'];
                                    $tempLocation = $tempLat. "," .$tempLng;
                                    //dd($tempLocation);
                                    $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                    $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                    $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                    // Add the new property
                                    $data['index'] = $i;
                                    $data['diff'] = $i-$stIndex;
                                    $data['distance'] = $distance;
                                    $data['duration'] = $duration;
                                
                                    // Return the new object
                                    return $data;
                                
                                });
                                $locations = $locations->merge($unmerge);

                            break;
                            }
                            //$locations = null;
                                
                        }                       
                        
                        return view('planner', compact('fstation','stations','locations'));
                    }
                    else //backward
                    {
                        for($i=$stIndex; $i >= $enIndex; $i--)
                        {
                            $lat = $stations[$i]->lat;
                            $lng = $stations[$i]->lng;
                            $location = $lat. "," .$lng;
                            //dd($location);
                            $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                            $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                            
                            if( count($response["results"]) > 0 && count($response2["results"]) > 0)
                            {
                                $data = $response["results"]->merge($response2["results"]);
                                // dd($data);
                                //$data = $response["results"];

                                $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                    //Find distance & duration
                                    $tempLat = $data['geometry']['location']['lat'];
                                    $tempLng = $data['geometry']['location']['lng'];
                                    $tempLocation = $tempLat. "," .$tempLng;
                                    //dd($tempLocation);
                                    $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                    $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                    $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                    // Add the new property
                                    $data['index'] = $i;
                                    $data['diff'] = $stIndex-$i;
                                    $data['distance'] = $distance;
                                    $data['duration'] = $duration;
                                
                                    // Return the new object
                                    return $data;
                                
                                });
                                $locations = $locations->merge($unmerge);

                            break;
                            }
                            //$locations = null;
                        }
                       
                        return view('planner', compact('fstation','stations','locations'));
                    }
                } 
                else // multiple option
                {
                    //forward
                    if($stIndex < $enIndex)
                    {
                        //place 1
                        for($i=$stIndex; $i <= $enIndex; $i++)
                        {
                            $lat = $stations[$i]->lat;
                            $lng = $stations[$i]->lng;
                            $location = $lat. "," .$lng;
                            //dd($location);
                            $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                            //dd($response["results"]);
                            if(!empty($response["results"]) && count($response["results"]) > 0 )
                            {
                                $data = $response["results"];
                                //dd($response["results"]);
        
                                $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                                    
                                    // Add the new property in every places
                                    $tempLat = $data['geometry']['location']['lat'];
                                    $tempLng = $data['geometry']['location']['lng'];
                                    $tempLocation = $tempLat. "," .$tempLng;
                                    //dd($tempLocation);
                                    $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                    $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                    $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                    // Add the new property
                                    $data['index'] = $i;
                                    $data['diff'] = $i-$stIndex;
                                    $data['distance'] = $distance;
                                    $data['duration'] = $duration;
                                
                                    // Return the new object
                                    return $data;
                                
                                });
                            break;
                            }
                           
                            $locations =null;

                        }

                        //place 2
                        for($i=$stIndex; $i<= $enIndex; $i++)
                            {
                                $lat = $stations[$i]->lat;
                                $lng = $stations[$i]->lng;
                                $location = $lat. "," .$lng;
                                //dd($location);
                                $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                                //dd($response["results"]);
                                if(!empty($response["results"]) && count($response["results"]) > 0 )
                                {
                                    $data = $response["results"];
                                    
                                    $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                        //Find distance & duration
                                        $tempLat = $data['geometry']['location']['lat'];
                                        $tempLng = $data['geometry']['location']['lng'];
                                        $tempLocation = $tempLat. "," .$tempLng;
                                        //dd($tempLocation);
                                        $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                        $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                        $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
            
                                        // Add the new property
                                        $data['index'] = $i;
                                        $data['diff'] = $i-$stIndex;
                                        $data['distance'] = $distance;
                                        $data['duration'] = $duration;
                                    
                                        // Return the new object
                                        return $data;
                                    
                                    });

                                    $locations = $locations->merge($unmerge);

                                    //$locations->put('index', $i);
                                    //$locations[1] = $i;

                                    //$index = $index->merge($i); 
                                break;
                                }
                                $locations =null;
                            }
                        
                        return view('planner', compact('fstation','stations','locations'));

                    }
                    else //backward
                    {
                        //place1 
                        for($i=$stIndex; $i >= $enIndex; $i--)
                        {
                            $lat = $stations[$i]->lat;
                            $lng = $stations[$i]->lng;
                            $location = $lat. "," .$lng;
                            //dd($location);
                            $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
                            //dd($response["results"]);
                            if(!empty($response["results"]) && count($response["results"]) > 0 )
                            {
                                $data = $response["results"];

                                $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                    //Find distance & duration
                                    $tempLat = $data['geometry']['location']['lat'];
                                    $tempLng = $data['geometry']['location']['lng'];
                                    $tempLocation = $tempLat. "," .$tempLng;
                                    //dd($tempLocation);
                                    $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                    $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                    $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

                                    // Add the new property
                                    $data['index'] = $i;
                                    $data['diff'] = $stIndex-$i;
                                    $data['distance'] = $distance;
                                    $data['duration'] = $duration;
                                
                                    // Return the new object
                                    return $data;
                                
                                });

                            break;
                            }
                            //$locations =null;

                        }

                        //place 2
                        for($i=$stIndex; $i >= $enIndex; $i--)
                        {
                            $lat = $stations[$i]->lat;
                            $lng = $stations[$i]->lng;
                            $location = $lat. "," .$lng;
                            //dd($location);
                            $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                            //dd($response["results"]);
                            if(!empty($response["results"]) && count($response["results"]) > 0 )
                            {
                                $data = $response["results"];

                                $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

                                    //Find distance & duration
                                    $tempLat = $data['geometry']['location']['lat'];
                                    $tempLng = $data['geometry']['location']['lng'];
                                    $tempLocation = $tempLat. "," .$tempLng;
                                    //dd($tempLocation);
                                    $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
                                    $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
                                    $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
        
                                    // Add the new property
                                    $data['index'] = $i;
                                    $data['diff'] = $stIndex-$i;
                                    $data['distance'] = $distance;
                                    $data['duration'] = $duration;
                                
                                    // Return the new object
                                    return $data;
                                
                                });
                                $locations = $locations->merge($unmerge);

                            break;
                            }
                            //$locations=null;
                        }
                       
                        return view('planner', compact('fstation','stations','locations'));
                    }

                }

            }
        }
//////////////////////////////////////
        //making sure all the request has input
        // if($request->has('place1') && $request->has('start') && $request->has('option'))
        // {
        //     //define all the requests
            
            
        //     if ( $end == null)
        //     {
        //         if( $option == 'multiple')
        //         {
        //             //SEARCH FORWARD
        //             //place1

        //             for($i=$stIndex; $i< count($stations); $i++)
        //             {
        //                 $lat = $stations[$i]->lat;
        //                 $lng = $stations[$i]->lng;
        //                 $location = $lat. "," .$lng;
        //                 //dd($location);
        //                 $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                 //dd($response["results"]);
        //                 if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                 {
        //                     $data = $response["results"];
        //                     //dd($response["results"]);
    
        //                     $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                                
        //                         // Add the new property in every places
        //                         $tempLat = $data['geometry']['location']['lat'];
        //                         $tempLng = $data['geometry']['location']['lng'];
        //                         $tempLocation = $tempLat. "," .$tempLng;
        //                         //dd($tempLocation);
        //                         $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                         $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                         $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                         // Add the new property
        //                         $data['index'] = $i;
        //                         $data['diff'] = $i-$stIndex;
        //                         $data['distance'] = $distance;
        //                         $data['duration'] = $duration;
                            
        //                         // Return the new object
        //                         return $data;
                            
        //                     });
        //                 break;
        //                 }

        //             }

        //             //place2
        //             if($place2 != null)
        //             {
        //                 for($i=$stIndex; $i< count($stations); $i++)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
        //                     //dd($response["results"]);
        //                     if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                     {
        //                         $data = $response["results"];
                                
        //                         $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                             //Find distance & duration
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
        
        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $i-$stIndex;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });

        //                         $locations = $locations->merge($unmerge);
        //                         //$locations->put('index', $i);
        //                         //$locations[1] = $i;

        //                         //$index = $index->merge($i); 
        //                     break;
        //                     }
        //                 }

        //             }
                    
                    
        //             //SEARCH BACKWARDS
        //             //place1

        //             for($i=$stIndex-1; $i >= 0; $i--)
        //             {
        //                 $lat = $stations[$i]->lat;
        //                 $lng = $stations[$i]->lng;
        //                 $location = $lat. "," .$lng;
        //                 //dd($location);
        //                 $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                 //dd($response["results"]);
        //                 if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                 {
        //                     $data = $response["results"];

        //                     $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                         //Find distance & duration
        //                         $tempLat = $data['geometry']['location']['lat'];
        //                         $tempLng = $data['geometry']['location']['lng'];
        //                         $tempLocation = $tempLat. "," .$tempLng;
        //                         //dd($tempLocation);
        //                         $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                         $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                         $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                         // Add the new property
        //                         $data['index'] = $i;
        //                         $data['diff'] = $stIndex-$i;
        //                         $data['distance'] = $distance;
        //                         $data['duration'] = $duration;
                            
        //                         // Return the new object
        //                         return $data;
                            
        //                     });

        //                     $locations = $locations->merge($unmerge);

        //                     //$locations->put('index', $i);
        //                     //$locations[1] = $i;

        //                     //$index = $index->merge($i); 
        //                 break;
        //                 }

        //             }

        //             //place2
        //             if($place2 != null)
        //             {
        //                 for($i=$stIndex-1; $i >= 0; $i--)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
        //                     //dd($response["results"]);
        //                     if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                     {
        //                         $data = $response["results"];

        //                         $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                             //Find distance & duration
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
        
        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $stIndex-$i;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });
        //                         $locations = $locations->merge($unmerge);
        //                     break;
        //                     }
        //                 }           
        //             }
        //             //dd($locations);
        //             return view('planner', compact('fstation','stations','locations'));
        //         }
        //         else
        //         {
        //             //BOTH PLACES IN SAME STATION
        //             // place1 & place2 where response == station lat lng

        //             //FORWARD
        //             for($i=$stIndex; $i< count($stations); $i++)
        //             {
        //                 $lat = $stations[$i]->lat;
        //                 $lng = $stations[$i]->lng;
        //                 $location = $lat. "," .$lng;
        //                 //dd($location);
        //                 $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                 $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        
        //                 if( count($response["results"]) > 0 && count($response2["results"]) > 0)
        //                 {
        //                     $data = $response["results"]->merge($response2["results"]);
        //                     // dd($data);
        //                     //$data = $response["results"];

        //                     $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                         //Find distance & duration
        //                         $tempLat = $data['geometry']['location']['lat'];
        //                         $tempLng = $data['geometry']['location']['lng'];
        //                         $tempLocation = $tempLat. "," .$tempLng;
        //                         //dd($tempLocation);
        //                         $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                         $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                         $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                         // Add the new property
        //                         $data['index'] = $i;
        //                         $data['diff'] = $i-$stIndex;
        //                         $data['distance'] = $distance;
        //                         $data['duration'] = $duration;
                            
        //                         // Return the new object
        //                         return $data;
                            
        //                     });
        //                     $locations = $locations->merge($unmerge);
        //                 break;
        //                 }
                            
        //             } 

        //             //BACKWARD
        //             for($i=$stIndex-1; $i >= 0; $i--)
        //             {
        //                 $lat = $stations[$i]->lat;
        //                 $lng = $stations[$i]->lng;
        //                 $location = $lat. "," .$lng;
        //                 //dd($location);
        //                 $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                 $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                        
        //                 if( count($response["results"]) > 0 && count($response2["results"]) > 0)
        //                 {
        //                     $data = $response["results"]->merge($response2["results"]);
        //                     // dd($data);
        //                     //$data = $response["results"];

        //                     $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                         //Find distance & duration
        //                         $tempLat = $data['geometry']['location']['lat'];
        //                         $tempLng = $data['geometry']['location']['lng'];
        //                         $tempLocation = $tempLat. "," .$tempLng;
        //                         //dd($tempLocation);
        //                         $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                         $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                         $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                         // Add the new property
        //                         $data['index'] = $i;
        //                         $data['diff'] = $stIndex-$i;
        //                         $data['distance'] = $distance;
        //                         $data['duration'] = $duration;
                            
        //                         // Return the new object
        //                         return $data;
                            
        //                     });
        //                     $locations = $locations->merge($unmerge);
        //                 break;
        //                 }
                            
        //             }

        //             return view('planner', compact('fstation','stations','locations'));
                    
        //         }
        //     }
        //     else if ($request->has('place1') && $request->has('start') && $request->has('option') && $request->has('end'))
        //     {
        //         if( $option == 'multiple')
        //         {
        //             if($stIndex < $enIndex) //FORWARD
        //             {
        //                  //place1

        //                 for($i=$stIndex; $i <= $enIndex; $i++)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                     //dd($response["results"]);
        //                     if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                     {
        //                         $data = $response["results"];
        //                         //dd($response["results"]);
        
        //                         $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {
                                    
        //                             // Add the new property in every places
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $i-$stIndex;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });
        //                     break;
        //                     }
                           
        //                     $locations =null;

        //                 }

        //                 //place2
        //                 if($place2 != null)
        //                 {
        //                     for($i=$stIndex; $i<= $enIndex; $i++)
        //                     {
        //                         $lat = $stations[$i]->lat;
        //                         $lng = $stations[$i]->lng;
        //                         $location = $lat. "," .$lng;
        //                         //dd($location);
        //                         $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
        //                         //dd($response["results"]);
        //                         if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                         {
        //                             $data = $response["results"];
                                    
        //                             $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                                 //Find distance & duration
        //                                 $tempLat = $data['geometry']['location']['lat'];
        //                                 $tempLng = $data['geometry']['location']['lng'];
        //                                 $tempLocation = $tempLat. "," .$tempLng;
        //                                 //dd($tempLocation);
        //                                 $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                                 $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                                 $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
            
        //                                 // Add the new property
        //                                 $data['index'] = $i;
        //                                 $data['diff'] = $i-$stIndex;
        //                                 $data['distance'] = $distance;
        //                                 $data['duration'] = $duration;
                                    
        //                                 // Return the new object
        //                                 return $data;
                                    
        //                             });

        //                             $locations = $locations->merge($unmerge);

        //                             //$locations->put('index', $i);
        //                             //$locations[1] = $i;

        //                             //$index = $index->merge($i); 
        //                         break;
        //                         }
        //                         $locations =null;
        //                     }

        //                 }
                    
        //                 return view('planner', compact('fstation','stations','locations'));
        //             }
        //             else //SEARCH BACKWARDS
        //             {
        //                 //place1

        //                 for($i=$stIndex; $i >= $enIndex; $i--)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                     //dd($response["results"]);
        //                     if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                     {
        //                         $data = $response["results"];

        //                         $locations = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                             //Find distance & duration
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $stIndex-$i;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });

        //                     break;
        //                     }
        //                     $locations =null;

        //                 }

        //                 //place2
        //                 if($place2 != null)
        //                 {
        //                     for($i=$stIndex; $i >= $enIndex; $i--)
        //                     {
        //                         $lat = $stations[$i]->lat;
        //                         $lng = $stations[$i]->lng;
        //                         $location = $lat. "," .$lng;
        //                         //dd($location);
        //                         $response = GooglePlaces::nearbySearch($location, $radius,$keyword2);
        //                         //dd($response["results"]);
        //                         if(!empty($response["results"]) && count($response["results"]) > 0 )
        //                         {
        //                             $data = $response["results"];

        //                             $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                                 //Find distance & duration
        //                                 $tempLat = $data['geometry']['location']['lat'];
        //                                 $tempLng = $data['geometry']['location']['lng'];
        //                                 $tempLocation = $tempLat. "," .$tempLng;
        //                                 //dd($tempLocation);
        //                                 $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                                 $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                                 $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];
            
        //                                 // Add the new property
        //                                 $data['index'] = $i;
        //                                 $data['diff'] = $stIndex-$i;
        //                                 $data['distance'] = $distance;
        //                                 $data['duration'] = $duration;
                                    
        //                                 // Return the new object
        //                                 return $data;
                                    
        //                             });
        //                             $locations = $locations->merge($unmerge);

        //                         break;
        //                         }
        //                         $locations=null;
        //                     }           
        //                 }

        //                 return view('planner', compact('fstation','stations','locations'));
        //             }
                    
        //         }
        //         else  //BOTH PLACES IN SAME STATION
        //         {     // place1 & place2 where response == station lat lng
                
        //             if($stIndex < $enIndex)
        //             {
        //                 //FORWARD
        //                 for($i=$stIndex; $i<= $enIndex; $i++)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                     $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                            
        //                     if( count($response["results"]) > 0 && count($response2["results"]) > 0)
        //                     {
        //                         $data = $response["results"]->merge($response2["results"]);
        //                         // dd($data);
        //                         //$data = $response["results"];

        //                         $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                             //Find distance & duration
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $i-$stIndex;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });
        //                         $locations = $locations->merge($unmerge);

        //                     break;
        //                     }
        //                     $locations = null;
                                
        //                 } 
                        
        //                 return view('planner', compact('fstation','stations','locations'));

        //             }
        //             else
        //             {   //BACKWARD
        //                 for($i=$stIndex; $i >= $enIndex; $i--)
        //                 {
        //                     $lat = $stations[$i]->lat;
        //                     $lng = $stations[$i]->lng;
        //                     $location = $lat. "," .$lng;
        //                     //dd($location);
        //                     $response = GooglePlaces::nearbySearch($location, $radius,$keyword);
        //                     $response2 = GooglePlaces::nearbySearch($location, $radius,$keyword2);
                            
        //                     if( count($response["results"]) > 0 && count($response2["results"]) > 0)
        //                     {
        //                         $data = $response["results"]->merge($response2["results"]);
        //                         // dd($data);
        //                         //$data = $response["results"];

        //                         $unmerge = collect($data)->map(function ($data) use ($i,$stIndex,$location) {

        //                             //Find distance & duration
        //                             $tempLat = $data['geometry']['location']['lat'];
        //                             $tempLng = $data['geometry']['location']['lng'];
        //                             $tempLocation = $tempLat. "," .$tempLng;
        //                             //dd($tempLocation);
        //                             $distancematrix = json_decode(\GoogleMaps::load('distancematrix')->setParam (['origins' => $location, 'destinations' => $tempLocation, 'mode' => 'walking'])->get(),true);
        //                             $distance = $distancematrix['rows'][0]['elements'][0]['distance']['text'];
        //                             $duration = $distancematrix['rows'][0]['elements'][0]['duration']['text'];

        //                             // Add the new property
        //                             $data['index'] = $i;
        //                             $data['diff'] = $stIndex-$i;
        //                             $data['distance'] = $distance;
        //                             $data['duration'] = $duration;
                                
        //                             // Return the new object
        //                             return $data;
                                
        //                         });
        //                         $locations = $locations->merge($unmerge);

        //                     break;
        //                     }
        //                     $locations = null;
        //                 }
                       
        //                 return view('planner', compact('fstation','stations','locations'));         
        //             } 
        //         }
        //     }       
        // }
        echo "Nothibg";
    }           
}
?>