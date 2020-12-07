<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Station;
use App\Http\Requests;
//use SKAgarwal\GoogleApi\PlacesApi;

class StationController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

	public function index(){

        $stations = \App\Station::all();

        return view('station.index', compact('stations'));
		
    }
    
    public function create()
    {
        return view('station.create');
    }

   
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'status' => ['required', 'string', 'max:255'],

        ]);
        
        $data = $request->only('code','name', 'lat','lng','status');
        //dd($data['status']);
        $station = Station::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'status' => $data['status'],
        ]);

        alert()->success(__('Station has been added.'), __('Add Station'));

        return redirect()->route('station.index', $station);
    }

    public function show(Station $station)
    {
        return view('station.show', compact('station'));
    }


    public function edit(Station $station)
    {
        return view('station.edit', compact('station'));
    }
  
    public function update(Request $request, Station $station)
    {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'status' => ['required', 'string', 'max:255'],

        ]);
 
        $station->update([
            'code' => request('code'),
            'name' => request('name'),
            'lat' => request('lat'),
            'lng' => request('lng'),
            'status' => request('status'),

        ]);

        $station = \App\Station::updateOrCreate([
            'code' => $request->code,
            'name' => $request->name,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => $request->status,
        ]);

        //dd($station);
        alert()->success(__('Station has been updated.'), __('Station Address'));

        return redirect()->route('station.show', $station);
    }

    public function destroy(Station $station)
    {

        $station->delete();

        alert()->success(__('Station has been removed.'), __('Station Address'));

        return redirect()->route('station.index');
    }

    public function searchNearby(Station $station)
    {
        
    }

    public function dropDownShow(Request $request)

    {

        // $stations = \App\Station::pluck('name', 'id');
        $stations = \App\Station::where('status', 'active')->get();
        //dd($stations);
        $fstation = \App\Station::where('status', 'active')->first()->id;
       // $id = 2;

        // return view('front', compact('id', 'stations'));
        return view('front', compact('fstation','stations'));

    }

}

?>