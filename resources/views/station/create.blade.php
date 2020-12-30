@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row bg-light">
			<div class="col p-5">
				<h3>{{ __('Station Details') }}</h3>
				<form id="location-form" action="{{ route('station.store') }}" method="POST">
					@csrf 
					<table class="table">
                    <tr>
							<th>{{ __('Code') }}</th>
							<td>
								<input class="form-control @error('code') border border-danger @enderror" 
									type="text" name="code" 
									value="{{ old('code') }}">

								@error('code')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</td>
						</tr>
                        <tr>
							<th>{{ __('Name') }}</th>
							<td>
								<input class="form-control @error('name') border border-danger @enderror" 
									type="text" name="name" 
									value="{{ old('name') }}">

								@error('name')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</td>
						</tr>
						<tr>
							<th>{{ __('Latitude') }}</th>
							<td>
								<input class="form-control" 
									type="decimal" name="lat" id="lat"
									value="{{ old('lat') }}">
							</td>
						</tr>
						<tr>
							<th>{{ __('Longitude') }}</th>
							<td>
								<input class="form-control" 
									type="decimal" name="lng" id="lng"
									value="{{ old('lng') }}">
							</td>
						</tr>
						<tr>
							<th></th>
							<td>
							</td>
						</tr>
						<tr>
							<th>Status</th>
							<td>
								<select class="form-control" id="status" name="status">
									<option  value="active"> 
										Active
									</option>
									<option value="inactive"> 
										Inactive 
									</option>   
								</select>
							</td>
						</tr>	
					</table>				
					<div class="float-right">
						<a href="{{ route('station.index') }}" class="btn btn-default">
							{{ __('Back') }}
						</a>
						<button type="submit" class="btn btn-primary">
                            {{ __('Create') }}
                        </button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<center><input id="submit" type="button" value="Geocode" onclick="geocode()"/></center>

	<div id="geocode"> </div>
<br>
@endsection