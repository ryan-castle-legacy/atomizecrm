<div class='popup' popup-name='add-new-connected-website'>

	<div class='popup-main popup-main-slim'>
		<div class='popup-main-header'>
			<p>Add a website</p>
		</div>
		<div class='popup-main-body'>

            <div class='text-field w100'>

				<div class='error-note' error-note-for='newConnectedWebsite'>
					<i class='fas fa-exclamation-triangle'></i>
					The website's domain name that you have input is invalid.
				</div>

				<input type='text' name='website' id='website' value='{{ old('website') }}' placeholder='Enter your website&apos;s domain name' on-change='handleConnectedWebsiteInput' valid-check='valid-domain-name' error-notice-by='newConnectedWebsite' required/>
			</div>

			<p><small>Examples: example.com, sub.example.com</small></p>

			<div class='container w100 row'>
				<button class='button-link button-link-close'>Cancel</button>
				<button id='add-new-domain' class='button-link' success-only-if='valid-domain-name' disabled>Continue and verify website ownership</button>
			</div>
		</div>
		<div class='processing-bar'><span></span></div>
	</div>

</div>
