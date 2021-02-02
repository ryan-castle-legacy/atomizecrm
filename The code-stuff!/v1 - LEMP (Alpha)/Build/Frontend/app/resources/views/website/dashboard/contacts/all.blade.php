@extends('website.layouts.dashboard')

@section('content')
	<div id='body-container' class='container'>

        @include('website.layouts.dashboard.topbar')

		<div class='filter-block' filters='contact-filters'>
			asdasd
		</div>

		<div id='body-container-main' class='container'>

			<div class='body-container-main-card body-container-main-card-w100'>

				<div class='card-table'>

					<div class='card-table-header'>
						<label label-for='selection'></label>
						<label label-for='avatar'></label>
						<label label-for='combined-name'>Name</label>
						<label label-for='email'>Email address</label>
						<label label-for='date-created'>Date created</label>
						<label label-for='email'>Source</label>
						<span class='card-row-fader'></span>
					</div>


					<div class='card-table-main'>
					@if (gettype($contacts) != 'array' || @sizeof($contacts) == 0)
						{{-- Blank status --}}


					@else
						{{-- Loop through each contact that has been loaded --}}
						@foreach ($contacts as $contact)
							<div class='card-table-main-row' record-id='{{ $contact->contact_unique_id }}'>
								<div class='card-table-main-row-data' data-type='selection'>
									<div class='checkbox'>
										<input type='checkbox'/>
										<label><i class='fas fa-check'></i></label>
									</div>
								</div>
								<div class='card-table-main-row-data' data-type='avatar'>
								@if ($contact->avatar_url == null)
									<a class='contact-avatar contact-avatar-32 contact-avatar-empty'></a>
								@else
									<a class='contact-avatar contact-avatar-32' style='background-image: url("{{ $contact->avatar_url }}")'></a>
								@endif
								</div>
								<div class='card-table-main-row-data' data-type='text-medium'>
								{{-- <p>Ryan Castle<span class='contact-from-group'>from<strong>AtomizeCRM</strong></span></p> --}}
								@if ($contact->firstname == null && $contact->lastname == null)
									<p>Unknown Contact</p>
								@elseif ($contact->firstname != null && $contact->lastname == null)
									<p>{{ $contact->firstname }}</p>
								@else
									<p>{{ $contact->firstname.' '.$contact->lastname }}</p>
								@endif
								</div>
								<div class='card-table-main-row-data' data-type='email'>
								@if ($contact->email == null)
									<p>-</p>
								@else
									<p>{{ $contact->email }}</p>
								@endif
								</div>
								<div class='card-table-main-row-data' data-type='date'>
									<p class='data-show-tooltip-on-hover' tooltip-data='{{ date('jS F Y \a\t H:ia', strtotime($contact->created_at)) }} UTC'>{{ $contact->time_since_created }}</p>
								</div>
								<div class='card-table-main-row-data' data-type='email'>
									<p>{{ $contact->source_added }}</p>
								</div>
							</div>
						@endforeach
					@endif
					</div>

				</div>

			</div>

		</div>

	</div>
@endsection
