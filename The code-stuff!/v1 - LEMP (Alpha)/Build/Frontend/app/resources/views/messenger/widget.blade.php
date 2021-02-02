<div id='AtomizeCRM-messenger-launcher'>

	<div id='AtomizeCRM-messenger-launcher-button' class='{{ $data['styles']->launcherType }}'>
	@switch($data['styles']->launcherType)
		@case('text-button-right-center')
			<div id='AtomizeCRM-messenger-launcher-button-cover'>
				<p>{{ $data['props']->launcherText }}</p>
			</div>
		@break
		@case('text-button-right-center')
		@default
			<div id='AtomizeCRM-messenger-launcher-button-cover'>
				@include('messenger.svg.launcher-button-cover-open-icon')
				@include('messenger.svg.launcher-button-cover-close-icon')
			</div>
		@break
	@endswitch
	</div>

</div>

<div id='AtomizeCRM-messenger-portal' class='launcher-style-{{ $data['styles']->launcherType }}'>

	<div id='AtomizeCRM-messenger-portal-header'>
		@include('messenger.svg.portal-header-close-icon')
		@include('messenger.svg.portal-header-back-icon')
	</div>
	<div id='AtomizeCRM-messenger-portal-background'></div>

	<div class='AtomizeCRM-messenger-portal-frame' frame='hero'>

		<div id='AtomizeCRM-messenger-portal-frame-hero-banner'>
			@if ($data['props']->portalLogo != false)
				<a id='AtomizeCRM-messenger-portal-frame-hero-banner-logo' style='background-image: url("{{ $data['props']->portalLogo }}");'></a>
			@endif
			<div id='AtomizeCRM-messenger-portal-frame-hero-banner-items'>
				<p class='AtomizeCRM-messenger-portal-frame-hero-banner-header'>{{ $data['props']->portalHeroBannerHeader }}</p>
				<p class='AtomizeCRM-messenger-portal-frame-hero-banner-desc'>{{ $data['props']->portalHeroBannerDesc }}</p>
			</div>
		</div>

		<div class='AtomizeCRM-messenger-portal-frame-cards'>

			<div class='AtomizeCRM-messenger-portal-frame-cards-card'>
				<p class='AtomizeCRM-messenger-portal-frame-cards-card-header'>{{ $data['props']->portalCardGetInTouch_header }}</p>
				<p>{{ $data['props']->portalCardGetInTouch_text }}</p>
				<button process-frame='chat'>
					@include('messenger.svg.contact-send-icon')
					{{ $data['props']->portalCardGetInTouch_button }}
				</button>
			</div>

		</div>

		@if ($data['props']->displayWhiteLabelCredit)
			<div id='AtomizeCRM-messenger-credit'>
				<a href='https://www.atomizecrm.com/referrer-link?instance-token=ADD-TOKEN-HERE' target='_blank'>
					Powered by
					@include('messenger.svg.credit')
				</a>
			</div>
		@endif

	</div>

	@if ($data['props']->chatFeature)
	<div class='AtomizeCRM-messenger-portal-frame' frame='chat'>

		<div class='AtomizeCRM-messenger-portal-frame-header'>
			<div class='AtomizeCRM-messenger-portal-chat-response-team'>
				<p style='background-image: url("http://xxx/img/ryan-avatar.jpg");'></p>
				<p style='background-image: url("http://xxx/img/AtomizeCRM%20Logo%20-%20Backing.png");'></p>
			</div>
			<h3>AtomizeCRM</h3>
			<p>Our team are here to help.</p>
			<p class='AtomizeCRM-messenger-portal-chat-response-time'>
				Our usual reply time:
				<span>
					@include('messenger.svg.portal-time-icon')
					A few minutes
				</span>
			</p>
		</div>

		<div class='AtomizeCRM-messenger-messages'></div>

		<div class='AtomizeCRM-messenger-composer'>
			<textarea rows='1' placeholder='Write a message...'></textarea>
			<div class='AtomizeCRM-messenger-composer-send' enabled='false'>
				@include('messenger.svg.send-message-icon')
			</div>
		</div>

	</div>
	@endif

</div>
