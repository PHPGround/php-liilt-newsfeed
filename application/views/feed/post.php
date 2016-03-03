<div class="post_img_box feed_post" data-value = "<?php echo $user_post['id']?>" id = "post_<?php echo $user_post['id']?>">
	
	<?php $this->load->view('feed/header');?>
	
	<div class="post_img">
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
	
	<?php $this->load->view('feed/footer');?>
</div><!--post_img_box-->