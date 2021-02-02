@extends('website.layouts.dashboard')

@section('content')
	<div id='body-container' class='container'>

        @include('website.layouts.dashboard.topbar')

		<div id='body-container-main' class='container'>

			<p class='note w100'><i class='fas fa-info-circle'></i>Adding a website to your organisation enables AtomizeCRM to authenticate any widgets that you use, such as Support Messengers.</p>

			<div class='body-container-main-card body-container-main-card-w100'>

				<div class='card-table'>

					<div class='card-table-header'>
						<label label-for='combined-name'>Website domain</label>
						<label label-for='date-created'>Date added</label>
						{{-- <label label-for='button'></label> --}}
					</div>

					<div class='card-table-main'>
					@if (gettype($connectedWebsites) != 'array' || @sizeof($connectedWebsites) == 0)
						{{-- Blank status --}}


					@else
						{{-- Loop through each items added to the organisation --}}
						@foreach ($connectedWebsites as $website)
							<div class='card-table-main-row' record-id='{{ $website->domain_unique_id }}'>
								<div class='card-table-main-row-data' data-type='text-medium'>
									<p>{{ $website->domain_name }}</p>
								</div>
								<div class='card-table-main-row-data' data-type='date'>
									<p class='data-show-tooltip-on-hover' tooltip-data='{{ date('jS F Y \a\t H:ia', strtotime($website->created_at)) }} UTC'>{{ $website->time_since_created }}</p>
								</div>
								{{-- <div class='card-table-main-row-data card-table-main-row-data-hidden-until-hover' data-type='button'>
									<button class='button-link button-link-remove'>Remove this domain</button>
								</div> --}}
							</div>
						@endforeach
					@endif
					</div>

				</div>

				<div class='row-in-card'>
					<button class='button-link-opener' popup-target='add-new-connected-website'><i class='fas fa-plus'></i>Add a website</button>
				</div>

			</div>

		</div>

	</div>

	@include('website.organisations.popups.newConnectedWebsite')

@endsection
