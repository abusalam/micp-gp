<?= $this->extend($config->viewLayout) ?>
<?= $this->section('main') ?>

	<div class="container">
		<div class="row">
			<div class="col-lg-10 offset-lg-1">
				<div class="card">
					<h1 class="card-title"><?=lang('app.home.welcome')?></h1>
					<div class="card-body">
						<?= view('Myth\Auth\Views\_message_block') ?>
						<h4>Location Map:</h4>
						<div>
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4450.881492634591!2d87.97706421539529!3d24.95847774746772!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39faf7191c7db497%3A0xe28e82ef7444110b!2sPanchanandapur%20Ferry%20Ghat!5e1!3m2!1sen!2sin!4v1654086475481!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
						</div>
						<h4>Jetty(boarding & deboarding point):</h4>
						<p>Panchanandapur Ferry Ghat, Block: Kaliachak-II, PS: Mothabari, Distric: Malda, West Bengal</p>
						<h4>Terms &amp; Conditions</h4>
						<ul>
							<li><strong>Travelling Hours:</strong> 8AM to 12PM and 4PM to 8PM. Booking can be done for minimum 2 hours.</li>
							<li>Booked Hours are including boarding and deboarding time</li>
							<li>No. of Passengers should not exceed <?=env('MAX_PASSENGER')?> </li>
						</ul>
						<h4>Do's &amp; Don'ts for Passenger/Travelers</h4>
						<ul>
							<li><strong>Do's</strong>
								<ul>
									<li>Board the vessel in proper manner.</li>
									<li>Listen to the crew.</li>
									<li>Maintain cleanliness of the boat.</li>
									<li>Maintain personal safety.</li>
								</ul>
							</li>
							<li><strong>Don'ts</strong>
								<ul>
									<li>Don't rush into the boat.</li>
									<li>Don’t stand/lean dangerously near railing.</li>
									<li>Don't cross the designated zone on the boat.</li>
									<li>Don't disturb the crew while they are operating.</li>
									<li>Don’t consume alcohol or any such substance onboard.</li>
									<li>Don’t board the vessel on drunken condition.</li>
									<li>Don’t throw garbage into the river.</li>
									<li>Don’t carry any kind of inflammable item.</li>
								</ul>
							</li>
						</ul>
						<h4>Cancel &amp; refund Policy:</h4>
						<ul>
							<li><strong>Cancellation procedure:</strong>
								<ul>
									<li>Send mail to the Co-ordinator at <?=env('CONTACT_EMAIL')?>.</li>
									<li>State the reason for cancellation</li>
								</ul>
							</li>
							<li><strong>Refund Policy:</strong>
								<ul>
									<li>48 hours before scheduled Journey time => Cancellation charges of Rs. 500.00(Rupees Five hundred) will be applicable.</li>
									<li>Between 48 hours and 24 hours before scheduled journey time => 70% refund of the fare</li>
									<li>No refund request will be entertained/accepted further.</li>
								</ul>
							</li>
						</ul>
						<h4>Contacts</h4>
						<p><strong>Coordinator Mobile No:</strong> <?=env('CONTACT_MOBILE')?>  </p>
						<p><strong>E-Mail ID: </strong><?=env('CONTACT_EMAIL')?></p>
						<p><strong>Boat Driver Mobile No: </strong><?=env('CONTACT_DRIVER')?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

<?= $this->endSection() ?>
