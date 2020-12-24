@extends('layouts.app')

@section('content')

<div class="container text-center">           
    <div class="form-group">
    <h3><b>Nearby</b></h3>
    You're on a station and looking for a specific place? Find it here.
    <br><br>
        <form>   
            <select class="form-control" id="station_id">
                <option>Select Station</option>
                @foreach ($stations as $station)
                    <option value="{{ $station }}" {{ ( $fstation == 0) ? 'selected' : '' }}> 
                        {{ $station->name }} 
                    </option>
                @endforeach    
            </select>
            <br>
            <div class="input-group-append"><input type="text" class="form-control" id="keyword" placeholder="Find place Eg: KFC, Gym" aria-label="Find places" aria-describedby="basic-addon2">
        </form>
            <button class="btn btn-primary text-secondary mr-3" type="button"  onclick="myFunction()"><span class="ml-1"><span class="fas fa-search-location"></span></span></button>               
            </div>
    </div>
</div>
       
        <br>
        

        <div id="map"> </div>
        
        <br>

        <p id="demo"></p>
 
 
        <div id="panel"><center>Click marker for place details</center></div>

        <div id="myTable"></div>

    <br>
    
@endsection