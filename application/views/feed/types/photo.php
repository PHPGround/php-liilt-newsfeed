<?php if(isset($user_post['shared_status']) && $user_post['shared_status'] != '') { ?>		<div class="text"><p style = "font-size:16px;" class='liilt_color'><?php echo $user_post['shared_status']; ?></p></div><?php } ?><div class="post_img">
<?php
	$ImageInfo = array();
	if($user_post['isUploaded'] == '0'){
		$Image = $user_post['media'];
	} else {
		$Image = base_url().'uploads/news_feeds/images/'.$user_post['media'];
	}
	
	$ImageInfo = @getimagesize($Image);
	if($ImageInfo != false){
	$ImageWidth = $ImageInfo[0];
		if($ImageWidth < 751){
			?><img class = "image_width_normal" src="<?php echo $Image; ?>" /><?php
		} else {
			?><img src="<?php echo $Image; ?>" /><?php
		}
	} else {
		?><img src="<?php echo $Image; ?>" /><?php
	}
?>
</div>