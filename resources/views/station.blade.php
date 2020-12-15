@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row bg-white">
			<div class="col p-5">
				<h3>Station Details</h3>
				<div class="float-right pb-3">
					<a href="{{ route('station.edit', $station) }}" class="btn btn-success">
						Edit
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
						{{ __('Delete') }}
					</div>
				</div>
				<table class="table">
					<tr>
						<th>Code</th>
						<td>{{ $station->code }}</td>
					</tr>
					<tr>
						<th>Name</th>
						<td>{{ $station->name }}</td>
					</tr>
					<tr>
						<th>Latitude</th>
						<td>{{ $station->lat }}</td>
					</tr>
					<tr>
						<th>Longitude</th>
						<td>{{ $station->lng}}</td>
					</tr>
					<tr>
						<th>Status</th>
						<td>{{ $station->status}}</td>
					</tr>
				</table>
				<a href="{{ route('station.index') }}" class="btn btn-default">
					Back
				</a>
			</div>
		</div>
	</div>
@endsection