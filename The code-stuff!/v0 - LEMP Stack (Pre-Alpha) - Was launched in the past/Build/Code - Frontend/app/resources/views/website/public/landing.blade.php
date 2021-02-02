@extends('website.layouts.public')

@section('content')
	<section id='overview'>
		<div class='container'>
			<h1>Grow your business by turning static pages into dynamic conversations.</h1>
			<h4>We'll work with you to get you started!</h4>
			<a href='[openatomizenewchat]' data='I would love to grow my business using AtomizeCRM! My email address address is: '>Get Started</a>
		</div>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none'><polygon points='0,100 100,0 100,100'/></svg>
	</section>

	<section id='features'>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none'><polygon points='0,0 100,0 0,100'/></svg>
		<div class='container'>
			<h1>Our Features</h1>
			<div class='features'>
				<div class='text'>
					<h2>With easy on-demand support for your customers</h2>
					<p>You can provide on-demand support right from your website. Resolve issues faster. Keep customers informed and engaged at all times.</p>
				</div>
				<div class='showcase chat'></div>
			</div>

			<div class='features'>
				<div class='showcase portal'></div>
				<div class='text textRight'>
					<h2>And answer tickets from an intuitive interface</h2>
					<p>An intuitive agent interface to manage the queue, route support tickets, and monitor responses.</p>
				</div>
			</div>

			<div class='features'>
				<div class='text'>
					<h2>24/7 Support for your customers</h2>
					<p>AI-driven out of office</p>
				</div>
				<div class='showcase portal'></div>
			</div>

			<br/>
			<h2><i class='fad fa-user-hard-hat'></i></h2>
			<h2 class='xx'>Plus new features every week!</h2>
			<p>Need something made? Just ask</p>
			<br/>


		</div>
	</section>

	<section id='midCTA'>
		<div class='container'>
			<h1>Ready to transform your customer communications with AtomizeCRM?</h1>
			<p>We'll work with you to get you started!</p>
			<a href='[openatomizenewchat]' data='I would love to grow my business using AtomizeCRM! My email address address is: '>Get Started</a>
		</div>
	</section>

	<section id='integrations'>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none'><polygon points='0,0 100,0 0,100'/></svg>
		<div class='container'>
			<h1>It's Easy to Integrate</h1>
			<h5>It takes just 1 line of code and you're good to go!</h5>
			<div class='code_snippet'>
				<p class='comment'>
					<span>1</span>
					<span>&lt;!--HTML--&gt;</span>
				</p>
				<p class='nonimportant'>
					<span>2</span>
					<span>&lt;<i class='code_red'>head</i>&gt;</span>
				</p>
				<p class='nonimportant'>
					<span>3</span>
				</p>
				<p>
					<span>4</span>
					<span>&lt;<i class='code_red'>script</i>
					<i class='code_orange'>src</i>=<i class='code_green'>"https://atomizecrm.com/chat-widget/main.min.js"</i>
					<i class='code_orange'>defer</i>&gt;&lt;/<i class='code_red'>script</i>&gt;</span>
				</p>
				<p class='nonimportant'>
					<span>5</span>
				</p>
				<p class='nonimportant'>
					<span>6</span>
					<span>&lt;/<i class='code_red'>head</i>&gt;</span>
				</p>
			</div>
			<p>Or</p>
			<a href='https://wordpress.org/plugins/atomizecrm-chat/' target='_blank' class='integrate_wp'>
				<i class='fab fa-wordpress-simple'></i>
				<span>Install our plugin</span>
			</a>
		</div>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none'><polygon points='0,100 100,0 100,100'/></svg>
	</section>

	<section id='pricing'>
		<div class='container'>
			<h1>Pricing</h1>
			<h5>Simple, straight-forward pricing.</h5>
		</div>
		<div class='container pricing_options pricing_options_launch'>


			<div class='pricing_option popular'>

				<div class='pricing_option_popular'>Limited Offer</div>

				<h2>Launch Offer</h2>
				<p>Chat, dedicated-support, and basic customer management.</p>

				<h1>&pound;25<span>Per Agent, Billed Monthly</span></h1>

				<a href='[openatomizenewchat]' class='popular' data='I would love to grow my business using AtomizeCRM! My email address address is: '>Get Started</a>
				<small>7 days free trial, cancel any time</small>
			</div>


			{{-- <div class='pricing_option'>
				<h2>Get Started</h2>
				<p>Basic live chat and basic customer management.</p>

				<h1>&pound;29<span>Billed Monthly</span></h1>

				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4 disabled><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4 disabled><i class='far fa-check-circle'></i>1 seat included</h4>

				<a>Start My Trial</a>
				<small>14 days free trial, no credit-card required</small>
			</div>

			<div class='pricing_option popular'>

				<div class='pricing_option_popular'>Most Popular</div>

				<h2>Get Growing</h2>
				<p>Chat, self-support, and basic customer management.</p>

				<h1>&pound;129<span>Billed Monthly</span></h1>

				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>

				<a class='popular'>Start My Trial</a>
				<small>14 days free trial, no credit-card required</small>
			</div>

			<div class='pricing_option'>

				<h2>Get Started</h2>
				<p>Automated support, and advanced client management.</p>

				<h1>&pound;249<span>Billed Monthly</span></h1>

				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>

				<a>Contact Us</a>
			</div>

			<div class='pricing_option'>

				<h2>Get Started</h2>
				<p>Bespoke integrations and advanced workflows.</p>

				<h1>&pound;499<span>Billed Monthly</span></h1>

				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>
				<h4><i class='far fa-check-circle'></i>1 seat included</h4>

				<a>Contact Us</a>
			</div> --}}


		</div>
	</section>


	<section id='finalCTA'>
		<div class='container'>
			<h1>Interested?</h1>
			<p>We'll work with you to get you started!</p>
			<a href='[openatomizenewchat]' data='I would love to grow my business using AtomizeCRM! My email address address is: '>Get Started</a>
		</div>
		<svg viewBox='0 0 100 100' preserveAspectRatio='none' fill='#ffffff'><polygon points='0,100 100,0 100,100'/></svg>
	</section>
@endsection
