<?php if(isset($user_post['shared_status']) && $user_post['shared_status'] != '') { ?>		<div class="text"><p style = "font-size:16px;" class='liilt_color'><?php echo $user_post['shared_status']; ?></p></div><?php } ?><div class="post_img">
<?php
	$media = $user_post['media'];

    $media = str_replace('height="150"', 'height="437"', $media);
    $media = str_replace('width="250"', 'width="751"', $media);

    echo $media;
?>
</div>