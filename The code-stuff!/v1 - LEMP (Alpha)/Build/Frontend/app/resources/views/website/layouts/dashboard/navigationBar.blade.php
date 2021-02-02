@php

	// Check if $navigationCurrent is set
	if (!isset($navigationCurrent)) {
		// Set default of 'dashboard'
		$navigationCurrent = 'dashboard';
	}
	// Explode array for dropdown selections
	$navigationCurrentExploded = explode('.', $navigationCurrent);

@endphp
<nav id='navigation' class=''>

	<a id='navigation-logo'></a>

	<a href='{{ route('organisations-connectedWebsites') }}' id='navigation-organisation-active' class='navigation-organisation-active-empty'>
	{{-- <a id='navigation-organisation-active' class='navigation-organisation-active-empty'> --}}
		<i class='far fa-building'></i>
	</a>

	<div id='navigation-search' class='search-field'>
		<input type='search' placeholder='Search for everything' spellcheck='false'>
		<i class='far fa-search search-field-finder'></i>
		<i class='far fa-times-circle search-field-clear'></i>
	</div>

	<div class='navigation-links'>

		<a href='{{ route('dashboard-home') }}' class='navigation-link @php if (@$navigationCurrentExploded[0] == 'dashboard') { echo 'navigation-link-current'; } @endphp'>
			<i class='fas fa-tachometer'></i>
			<span class='label'>Dashboard</span>
		</a>



		{{-- <a href='{{ route('dashboard-chatInbox') }}' class='navigation-link @php if (@$navigationCurrentExploded[0] == 'chatInbox') { echo 'navigation-link-current'; } @endphp'>
			<i class='fas fa-comments-alt'></i>
			{{-- <span class='label'>Support Messenger</span> --}}
			{{-- <span class='label'>Chat Inbox</span>
		</a> --}}

		<a class='navigation-link' dropdown-target='contacts' @php if (@$navigationCurrentExploded[0] == 'chatInbox') { echo 'is-open=\'true\''; } else { echo 'is-open=\'false\''; } @endphp>
			<i class='fas fa-comments-alt'></i>
			<span class='label'>Chat Inbox</span>
			<span class='dropdown-toggler'>
				<i class='far fa-chevron-down'></i>
			</span>
			<span class='tip'>1</span>
		</a>

		@php $linkCount = 4; $linkHeight = 36; @endphp
		<div class='navigation-dropdown' dropdown-name='contacts' @php if (@$navigationCurrentExploded[0] == 'contacts') { echo 'is-open=\'true\' style=\'height: '.($linkCount * $linkHeight).'px;\''; } else { echo 'is-open=\'false\''; } @endphp>

			<a href='{{ route('dashboard-chatInbox') }}' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'all') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-list'></i>
				<span class='label'>All contacts</span>
			</a>

			<a class='navigation-dropdown-link' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'customers') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-user'></i>
				<span class='label'>Customers</span>
			</a>

		</div>






		<a class='navigation-link' dropdown-target='contacts' @php if (@$navigationCurrentExploded[0] == 'contacts') { echo 'is-open=\'true\''; } else { echo 'is-open=\'false\''; } @endphp>
			<i class='fas fa-address-book'></i>
			<span class='label'>Contacts</span>
			<span class='dropdown-toggler'>
				<i class='far fa-chevron-down'></i>
			</span>
			<span class='tip'>1</span>
		</a>

		@php $linkCount = 4; $linkHeight = 36; @endphp
		<div class='navigation-dropdown' dropdown-name='contacts' @php if (@$navigationCurrentExploded[0] == 'contacts') { echo 'is-open=\'true\' style=\'height: '.($linkCount * $linkHeight).'px;\''; } else { echo 'is-open=\'false\''; } @endphp>

			<a href='{{ route('dashboard-contacts-all') }}' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'all') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-list'></i>
				<span class='label'>All contacts</span>
			</a>

			<a class='navigation-dropdown-link' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'customers') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-user'></i>
				<span class='label'>Customers</span>
			</a>

			<a class='navigation-dropdown-link' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'new-enquiries') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-user-alien'></i>
				<span class='label'>New Enquiries</span>
				<span class='tip'>1</span>
			</a>

			<a class='navigation-dropdown-link' class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'contacts' && @$navigationCurrentExploded[1] == 'organisation') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-user-headset'></i>
				<span class='label'>Your Organisation</span>
			</a>

		</div>

		{{-- <a class='navigation-link' dropdown-target='tickets' @php if (@$navigationCurrentExploded[0] == 'tickets') { echo 'is-open=\'true\''; } else { echo 'is-open=\'false\''; } @endphp>
			<i class='fas fa-ticket'></i>
			<span class='label'>Tickets</span>
			<span class='dropdown-toggler'>
				<i class='far fa-chevron-down'></i>
			</span>
			<span class='tip'>3</span>
		</a>

		@php $linkCount = 4; $linkHeight = 36; @endphp
		<div class='navigation-dropdown' dropdown-name='tickets' @php if (@$navigationCurrentExploded[0] == 'tickets') { echo 'is-open=\'true\' style=\'height: '.($linkCount * $linkHeight).'px;\''; } else { echo 'is-open=\'false\''; } @endphp>

			<a class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'tickets' && @$navigationCurrentExploded[1] == 'all') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-list'></i>
				<span class='label'>All tickets</span>
			</a>

			<a class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'tickets' && @$navigationCurrentExploded[1] == 'new-tickets') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-sparkles'></i>
				<span class='label'>New tickets</span>
				<span class='tip'>2</span>
			</a>

			<a class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'tickets' && @$navigationCurrentExploded[1] == 'assigned-to-you') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-thumbtack'></i>
				<span class='label'>Assigned to you</span>
				<span class='tip'>1</span>
			</a>

			<a class='navigation-dropdown-link @php if (@$navigationCurrentExploded[0] == 'tickets' && @$navigationCurrentExploded[1] == 'resolved') { echo 'navigation-link-current'; } @endphp'>
				<i class='fas fa-badge-check'></i>
				<span class='label'>Resolved</span>
			</a>

		</div> --}}

	</div>

	<hr/>

	<div class='navigation-links'>

		<a class='navigation-link @php if (@$navigationCurrentExploded[0] == 'settings') { echo 'navigation-link-current'; } @endphp'>
			<i class='fas fa-cog'></i>
			<span class='label'>Settings</span>
		</a>

		<a href='{{ route('userAccounts-logout') }}' id='sign-out' class='navigation-link'>
			<i class='fas fa-sign-out-alt'></i>
			<span class='label'>Sign Out</span>
		</a>

	</div>

</nav>
