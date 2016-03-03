<div class="text">	<?php if(isset($user_post['shared_status']) && $user_post['shared_status'] != '') { ?>		<div class="text"><p style = "font-size:16px;" class='liilt_color'><?php echo $user_post['shared_status']; ?></p></div>	<?php } ?>
	<a target="_blank" href="<?php echo $user_post['original_url']; ?>"><?php echo  $user_post['original_url']; ?></a>
	<div class="text_post_img">
		<a target="_blank" href="<?php echo $user_post['original_url']; ?>">
			<img src="<?php echo $user_post['media']; ?>" />
		</a>
	</div><!--text_post_img-->
	<p class="postimg_heading"><?php echo $user_post['title']; ?></p>
	<p class="postimg_text"><?php echo $user_post['description']; ?></p>
	<div class="clearfix"></div>
</div><!--text-->