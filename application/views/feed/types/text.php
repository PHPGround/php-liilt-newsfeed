<div class="text">	<?php if(isset($user_post['shared_status']) && $user_post['shared_status'] != '') { ?>		<div class="text"><p style = "font-size:16px;" class='liilt_color'><?php echo $user_post['shared_status']; ?></p></div>	<?php } ?>	
	<?php 
		$statusLength = strlen($user_post['status']);
		if($statusLength > 500){
			$excerpt = substr($user_post['status'], 0, 500);
			$excerpt .= " . . . <span class='liilt_color cursor_class' onclick = 'postDetail(".$user_post['id'].")'>See More</span>";
		} else {
			$excerpt = $user_post['status'];
		}
	?>

	<p class="more"><?php echo nl2br(make_clickable($excerpt) ); ?></p>
</div><!--text-->


     