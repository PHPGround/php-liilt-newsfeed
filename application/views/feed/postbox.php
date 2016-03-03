<div id = "share_post_area" class="share_post">
	<ul class="nav nav-tabs">
		<li class="active status">
			<a data-toggle="tab" href="javascript:void(0)">
				<span>
					<i class="fa fa-mail-reply-all "></i>
				</span> 
				Status
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="javascript:void(0)" id="file_upload"></a>
		</li>
	</ul>

	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
			<textarea id = "status_box" class="status_box" class="modal_textarea" placeholder = "Share your thoughts..."onkeypress="auto_grow_textarea(this)" onkeyup="auto_grow_textarea(this)" onpaste="auto_grow_textarea(this)" maxlength="500"></textarea>
			<!-- Share post box-->

			<img id = "post_loader" class = "loader_class" src="<?php echo base_url().'resources/frontend/images/loading-blue.gif' ; ?>">
			<div class="clearfix"></div>
		</div>

		<div id="boost_post"></div>
		<div id="post_preview"></div>
	</div><!--tab-content-->
</div><!--share_post-->

<div class="share_footer ">

	<input type="text" name="privacy" value="connection" style="display:none;"/>
	<button id="btn_add_post" class="post" onclick="addNewPost(event)">Post</button>
	<div class="clearfix"></div>

</div><!--share_footer-->