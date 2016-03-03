<div class="container">
	<div id = "profile_type_select" class="dropdown profile_type">
		<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
			Use Timeline As
			<span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li>
				<a href = "javascript:void(0)" onclick = "changeProfileType('user', <?php echo $this->session->userdata('user_id')?>)" >User Profile</a>
				<!-- <a href="javascript:void(0)" onclick="changeProfileType('<?php //echo $this->session->userdata('user_id'); ?>',1,'<?php //echo urlencode($this->session->userdata('user_account_name')); ?>','<?php //echo $this->session->userdata('user_account_image'); ?>')">User Profile</a> -->
			</li>
			<li>
				<a href = "javascript:void(0)" onclick = "open_profile_modal('company')" >Company Profile</a>
			</li>
			<li>
				<a href = "javascript:void(0)" onclick = "open_profile_modal('college')" >College Profile</a>
			</li>
		</ul>
	</div>
</div>