@extends('layouts.app')

@section('content')

<div class="container text-center">            
	<div class="form-group">
		<form id="planner-form" action="{{ route('search') }}" method="GET">
			 <div class="row justify-content-center">
				<div class="col-12 col-md-10 col-lg-8">
					<!--Accordion-->					
					<div class="input-group-append">
						<select class="form-control @error('start') border border-danger @enderror" id="station_id" name="start">
							<option value=''>Starting point</option>
							@foreach ($stations as $station)
								<option value="{{ json_decode($station)->id }}" {{ ( Request()->start == json_decode($station)->id ) ? 'selected' : ( $fstation == 0) }}> 
									{{ $station->name }} 
								</option>
							@endforeach    
						</select>
						@error('start')
							<span class="text-danger">
								<strong> </strong>
							</span>
						@enderror
						<a href="#panel-1" data-target="#panel-1" class="accordion-panel-header" data-toggle="collapse" aria-expanded="false" aria-controls="panel-1">
						&nbsp;&nbsp;&nbsp;<span class="icon"><span class="fas fa-plus"></span></span>
						</a>             
					</div>
					<div class="collapse" id="panel-1">
						<div class="pt-3">
							<select class="form-control" id="station_id2" name="end">
							<option value=''>Ending point</option>
							@foreach ($stations as $station)
								<option value="{{ json_decode($station)->id }}" {{ ( Request()->end == json_decode($station)->id ) ? 'selected' : ( $fstation == 0) }}> 
									{{ $station->name }} 
								</option>
							@endforeach    
							</select>
						</div>
					</div>
					<br>
					<div class="input-group-append">
						<input type="text" class="form-control @error('place1') border border-danger @enderror" name="place1" placeholder="Find a place" value="{{ Request()->place1 ?? '' }}" >
						@error('place1')
							<span class="text-danger">
								<strong> </strong>
							</span>
						@enderror
						<a href="#panel-2" data-target="#panel-2" class="accordion-panel-header" data-toggle="collapse" aria-expanded="false" aria-controls="panel-1">
						&nbsp;&nbsp;&nbsp;<span class="icon"><span class="fas fa-plus"></span></span>
						</a>
					</div>	
						<div class="collapse" id="panel-2">
							<div class="pt-3">
								<input type="text" class="form-control" name="place2" placeholder="Find another place" value="{{ Request()->place2 ?? '' }}" >
							</div>
							<div class="pt-3">
								<select class="form-control" id="station_option" name="option">
									<option value=''>Station option</option>
									<option value="single" {{ ( Request()->option == "single" ) ? 'selected' : '' }}> 
										Single Station 
									</option>   
									<option value="multiple" {{ ( Request()->option == "multiple" ) ? 'selected' : '' }}> 
										Multiple Stations 
									</option>
								</select>
							</div>
						</div>
					</div>					
					<!--End of Accordion-->
				</div>
			</div>                     
        
		<button onclick="loading()" class="btn btn-primary text-secondary mr-3" type="submit"><span class="ml-1"><span class="fas fa-search-location"></span></span></button>
	<br>
	</form>
	<br>
	<section class="min-vh-20 d-flex bg-primary align-items-center">
    	<div class="container">
			<div id='loading'></div>
			@if (!empty($locations) && count($locations) > 0)			
				@for ($i = 0; $i < count($locations); $i++) 
				<div class="row justify-content-center no-gutters"> 
					<!-- <div class="col-12 col-md-4 col-lg-6 "> -->
						<div class="row justify-content-center no-gutters card bg-primary shadow-soft border-light p-4 mb-3 col-12 col-md-4 col-lg-6" style="max-width: 540px;">
							<div class="row justify-content-center no-gutters">
								<div class="col-md-4">
								@if (!empty($locations[$i]["photos"][0]["photo_reference"]) )
									<img class="card-img-top" src="https://maps.googleapis.com/maps/api/place/photo?maxwidth=100&photoreference={{$locations[$i]["photos"][0]["photo_reference"] }}&key={{env('GOOGLE_PLACES_API_KEY', null)}}" alt="Card image cap">
								@else
									<img class="card-img-top" src="{{ $locations[$i]["icon"] }}" alt="Card image cap">
								@endif
								</div>
								<div class="col-md-8">
									<div class="card-body">
										<h5 class="card-title">{{ $locations[$i]["name"] }}</h5>
										<p class="card-text">{{ $locations[$i]["distance"] }} ({{ $locations[$i]["duration"] }})</p>
										<p class="card-text">Station : {{ $stations[$locations[$i]["index"]]->name }}
											@if ($locations[$i]["diff"] == 0 )
												(Current Station)
											@else
												({{$locations[$i]["diff"] }} stations) 
											@endif</p>
										<p class="card-text"><a href="http://www.google.com/maps/dir/?api=1&origin=LRT+{{$stations[$locations[$i]["index"]]->name}}&destination={{$locations[$i]["name"]}}&travelmode=walking" class="btn btn-primary" target="_blank">Google Map</a></p>
										<!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
									</div>
								</div>
						</div>
					</div>
				<!-- </div> -->
			</div>
			<br>
			@endfor
		@else
			<center>No results</center>
		@endif				
		</div>
	</section>    
</div>
@endsection				


				<!-- <table class="table">
					<tr>
						<th>Picture</th>
						<th>Name</th>
						<th>Distance</th>
						<th>Duration</th>
						<th>Station</th>
						<th>Intermediate Station</th>
					</tr>

					@if (!empty($locations) && count($locations) > 0)
						@for ($i = 0; $i < count($locations); $i++)  
					        <tr> 
                                @if (!empty($locations[$i]["photos"][0]["photo_reference"]) )
								<td> <img src ="https://maps.googleapis.com/maps/api/place/photo?maxwidth=100&photoreference={{$locations[$i]["photos"][0]["photo_reference"] }}&key={{ config('googlemaps.key', null) }}"></td>
								@else
								<td><img src="{{ $locations[$i]["icon"] }}"></td>
								@endif
								<td>{{ $locations[$i]["name"] }}</td>
								<td>{{ $locations[$i]["distance"] }}</td>
								<td>{{ $locations[$i]["duration"] }}</td>
								<td></td>
								<td>{{ $stations[$locations[$i]["index"]]->name }}</td>
								@if ($locations[$i]["diff"] == 0 )
								<td>Current Station</td>
								@else
								<td>{{$locations[$i]["diff"] }} </td>
								@endif
								<td><img src="{{ $locations[$i]["icon"] }}"></td>
                                <td>{{ $locations[$i]["place_id"] }}</td>

                            </tr>
						@endfor	
					@else
							<tr> 
								<td colspan="6"><center>No results</center></td>
							</tr>
					@endif		
				</table>
				<div class="float-right">

				</div> -->
			
		
