@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row bg-light">
			<div class="col p-5">
				<h3>{{ __('Station Details') }}</h3>
				<form action="{{ route('station.update', $station) }}" method="POST">
					{{-- enctype="multipart/form-data" --}}
					@csrf 
					{{-- <input type="hidden" name="_token" value="3412345ysdf"> --}}
					@method('PUT')
					{{-- <input type="hidden" name="_method" value="PUT"> --}}
					<table class="table">
					<tr>
							<th>Code</th>
							<td>
								<input class="form-control @error('code') border border-danger @enderror" 
									type="text" name="code" 
									value="{{ old('code',$station->code) }}">

								@error('code')
                                    <span class="text-danger">
                                        <strong>{{ $code }}</strong>
                                    </span>
                                @enderror
							</td>
						</tr>
						<tr>
							<th>Name</th>
							<td>
								<input class="form-control @error('name') border border-danger @enderror" 
									type="text" name="name" 
									value="{{ old('name',$station->name) }}">

								@error('name')
                                    <span class="text-danger">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
							</td>
						</tr>
						<tr>
							<th>Latitude</th>
							<td>
								<input class="form-control" 
									type="decimal" name="lat" value="{{ old('lat',$station->lat) }}">
							</td>
						</tr>
						<tr>
							<th>Station</th>
							<td>
								<input class="form-control" 
									type="decimal" name="lng" value="{{ old('lng',$station->lng) }}">
							</td>
						</tr>
						<tr>
							<th>Status</th>
							<td>
								<select class="form-control" id="status" name="status">
										<option {{ old('status',$station->status) == 'active' ? "selected" : "" }} value="active"> 
											Active
										</option>
										<option {{ old('status',$station->status) == 'inactive' ? "selected" : "" }} value="inactive"> 
											Inactive 
										</option>   
								</select>
							</td>
						</tr>
					</table>
					<div class="float-right">
						<a href="{{ route('station.show', $station) }}" class="btn btn-default">
							{{ __('Back') }}
						</a>
						<button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection