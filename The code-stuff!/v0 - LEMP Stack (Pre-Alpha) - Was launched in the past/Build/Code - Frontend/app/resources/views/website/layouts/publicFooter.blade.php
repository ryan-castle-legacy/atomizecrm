<footer>
	<div class='container'>
		<div class='footer_details'>
			<a class='footer_details_logo'></a>
			<p class='footer_details_desc'>Powering business growth through meaningful customer experiences.</p>
			<p class='footer_details_sprint'>{{ date('Y', time()) }} &copy; AtomizeCRM</p>
		</div>
		<div class='footer_links'>
			<div class='footer_links_group'>
				<label>Customer Support</label>
				<a href='{{ route('public-chatLanding') }}#overview'>Overview<i></i></a>
				<a href='{{ route('public-chatLanding') }}#features'>Features<i></i></a>
				<a href='{{ route('public-chatLanding') }}#integrations'>Integrations<i></i></a>
				{{-- <a href='{{ route('public-chatLanding') }}#pricing'>Pricing<i></i></a> --}}
			</div>
			<div class='footer_links_group'>
				<label>Connect</label>
				<a href='[openatomize]'>Talk to us<i></i></a>
				<a href='https://www.facebook.com/atomizecrm' target='_blank'>Facebook<i></i></a>
				<a href='https://www.instagram.com/atomizecrm' target='_blank'>Instagram<i></i></a>
				<a href='https://twitter.com/atomizecrm' target='_blank'>Twitter<i></i></a>
			</div>
			<div class='footer_links_group'>
				<label>Other</label>
				<a href='' disabled>Intercom alternative<i></i><span>(Coming soon)</span></a>
				<a href='' disabled>Legal stuff<i></i><span>(Coming soon)</span></a>
				<a href='' disabled>Atomize blog<i></i><span>(Coming soon)</span></a>
			</div>
		</div>
	</div>
</footer>
