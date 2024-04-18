@extends('layouts.user_type.auth')

@section('content')
	<div>
		<div class="row">
			<div class="col-12">
				<div class="card mb-4 mx-4">
					<div class="card-body  pt-0 pb-2">
						<div class="table-responsive p-0">
							<table class="table align-items-center mb-0">
								<thead>
									<tr>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Name
										</th>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Email
										</th>

										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Course
										</th>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Front ID
										</th>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Back ID
										</th>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Creation Date
										</th>
										<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Status
										</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($students as $student)
										<tr>
											<td class="text-center">
												<p class="text-xs font-weight-bold mb-0">{{ $student->first_name . ' ' . $student->last_name }}</p>
											</td>
											<td class="text-center">
												<p class="text-xs font-weight-bold mb-0">{{$student->user->email  }}</p>
											</td>

											<td class="text-center">
												<p class="text-xs font-weight-bold mb-0">{{ $student->course }}</p>
											</td>
											<td class="text-center">
												<a data-target="#imageModal{{ $student->id }}" data-toggle="modal"
													href="{{ asset('storage/images/' . basename($student->front_id)) }}">
													<img alt="Front ID Image" src="{{ asset('storage/images/' . basename($student->front_id)) }}"
														width="100">
												</a>
											</td>
											<td class="text-center">
												<a data-target="#imageModal{{ $student->id }}" data-toggle="modal"
													href="{{ asset('storage/images/' . basename($student->back_id)) }}">
													<img alt="Front ID Image" src="{{ asset('storage/images/' . basename($student->back_id)) }}" width="100">
												</a>
											</td>
											<td class="text-center">
												<span class="text-secondary text-xs font-weight-bold">{{ $student->created_at->format('F j, Y') }}</span>
											</td>
											<td class="e text-center">
												<form action="{{ route('user-management.accept', $student->id) }}" id="accept-form" method="PoST" style="display: inline;">
													@csrf
													<button type="submit" class="badge badge-sm bg-gradient-success" onclick="return confirm('Are you sure you want to approve this user?')">Accept</button>
												</form>


											</td>

										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	@foreach ($students as $student)
		<div aria-hidden="true" aria-labelledby="imageModalLabel{{ $student->id }}" class="modal fade"
			id="imageModal{{ $student->id }}" role="dialog" tabindex="-1">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="imageModalLabel{{ $student->id }}">Image Modal</h5>
						<button aria-label="Close" class="btn-close btn-danger" data-dismiss="modal" type="button">asdasdasd</button>
					</div>
					<div class="modal-body">
						<!-- Image will be displayed here -->
						<img alt="Modal Image" class="img-fluid" id="modalImage{{ $student->id }}" src="">
					</div>
				</div>
			</div>
		</div>
	@endforeach
@endsection

<script>
	$(document).ready(function() {
		$('a[data-toggle="modal"]').click(function() {
			var imageSource = $(this).attr('href');
			console.log('click', imageSource)
			var modalId = $(this).data('target');
			$(modalId).find('.modal-body img').attr('src', imageSource);
		});
	});
</script>
