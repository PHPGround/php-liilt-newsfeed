<div id = "modal_share" class="modal fade modal-facbook">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close button_modal_close" onclick="close_share_modal()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				
				<?php
					
					if($user_post['post_type'] == 1){
						$post_type = "Status";
					} else if($user_post['post_type'] == 2){
						$post_type = "Link";
					} else if($user_post['post_type'] == 3){
						$post_type = "Photo";
					} else if($user_post['post_type'] == 4){
						$post_type = "Video";
					}
				?>
				
				<h4 class="modal-title modal_box_title">Share this <?php echo $post_type;?> </h4>
			</div><!-- /.modal-header -->
			
			<div class="modal-body"> 

				<!-- adding status if not image or Link -->

				<textarea class="modal_textarea status_box" id = "share_status" maxlength="500"></textarea>
				<hr class="style3">
				
				<div id = "post_img_div" class="post_img">
					<?php
						if($user_post['post_type'] == '1'){
							$this->load->view('feed/types/text');
						} else if($user_post['post_type'] == '2'){
							$this->load->view('feed/types/link');
						} else if($user_post['post_type'] == '3'){
							$this->load->view('feed/types/photo');
						} else if($user_post['post_type'] == '4'){
							$this->load->view('feed/types/video');
						}
					?>
				</div><!--post_img-->
				

			</div><!-- /.modal-body -->
			
			<div class="modal-footer">

				<button id = "btn_share" type="button" class="btn share_btn" onclick='sharePost(event,<?php echo $user_post['id'] ?>, this)'>
					<img id = "share_loader" class = "button_progress hidden" src="<?php echo base_url().'resources/frontend/images/loader-16.gif' ; ?>">
					<span>Share</span>
				</button>
				<button type="button" class="btn btn-default" onclick="close_share_modal()">Close</button>
			</div><!-- /.modal-footer -->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
	jQuery(function(){
		jQuery('#post_img_div').css({ "width": "100%", "overflow": "hidden" });
		jQuery('#post_img_div img').css('width', '100%');
	});
</script>