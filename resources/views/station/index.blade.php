@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row bg-light">
			<div class="col p-5">
				<h3 class="pb-4">Station
					<a href="{{ route('station.create') }}" 
						class="float-right btn btn-success">
						{{ __('Create New Station') }}
					</a>
				</h3>
				
				<table class="table">
					<tr>
						<th>Code</th>
						<th>Name</th>
						<th colspan="3">Details</th>
						<!-- <th>Latitude</th>
						<th>Longitude</th> -->
					</tr>

					@foreach($stations as $station)
						<tr>
							<td>{{ $station->code }}</td>
							<td>{{ $station->name }}</td>
							<!-- <td>{{ $station->lat }}</td>
							<td>{{ $station->lng }}</td> -->
							<td>
								<div class="btn-group">
									<a href="{{ route('station.show',$station) }}" class="btn btn-primary">
									<span class="ml-1"><span class="fas fa-info"></span></span>
									</a>
									<a href="{{ route('station.edit', $station) }}" class="btn btn-success">
									<span class="ml-1"><span class="fas fa-edit"></span></span>
									</a>
									<div class="btn btn-danger" onclick="
										if(confirm('Are you sure want to delete this record?')) {
											document.getElementById('station-{{ $station->id }}').submit();
										}
									">
										<form id="station-{{ $station->id }}" 
											action="{{ route('station.destroy', $station) }}" method="POST">
											@csrf @method('DELETE')
										</form>
										<span class="ml-1"><span class="fas fa-trash"></span></span>
									</div>
								</div>
							</td>
						</tr>
					@endforeach
				</table>
				
			</div>
		</div>
	</div>
@endsection