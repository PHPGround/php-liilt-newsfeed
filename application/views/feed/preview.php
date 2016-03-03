<hr class="style3">

<?php if($isUploaded === false) { ?>
	
    <?
    	if($url_type == 'photo'){
			?>
				<div class="post_img">
                    <span onclick="hidePreview()" style="float:right;">
                        <img class="cancel_post" src="<?php echo base_url() ?>resources/frontend/images/cancel.png" />
                    </span>
                    <div class="text">
                        <div class="text_post_img">
                            <img src="<?php echo $image; ?>">
                        </div><!--text_post_img-->
                        <div class="clearfix"></div>
                    </div><!--text-->
                </div>
			<?php
		} else if($url_type == 'video'){
			?>
				<div class="post_img">
                    <span onclick="hidePreview()" style="float:right;">
                        <img class="cancel_post" src="<?php echo base_url() ?>resources/frontend/images/cancel.png" />
                    </span>
                    <div class="text">
                        <div class="text_post_img">
                            <?php echo $iframe; ?>
                        </div><!--text_post_img-->
                        <div class="clearfix"></div>
                    </div><!--text-->
                </div>
			<?php
		} elseif($url_type == 'link') {
			?>
				<div class="post_img">
                    <span onclick="hidePreview()" style="float:right;">
                        <img class="cancel_post" src="<?php echo base_url() ?>resources/frontend/images/cancel.png" />
                    </span>
                    <div class="text_post_img">
                    	<a target="_blank" href="<?php echo $original_url ; ?>"> 
						<?php 
						if($media_type == 'audio'){
							echo $original_url ;
						} else { ?>
							<img src="<?php echo $image; ?>">
							<?php
						} ?>
                        </a>
                           
                        </div><!--text_post_img-->
                        <p class="postimg_heading"><?php echo $title; ?></p>
                        <p class="postimg_text"><?php echo $description; ?></p>
                    </div>
                </div>
			<?php
		}
	?>

<?php } else { ?>
	<div class="post_img">
		<span onclick="hidePreview()" style="float:right;">
			<img class="cancel_post" src="<?php echo base_url() ?>resources/frontend/images/cancel.png" />
		</span>
		<div class="text">
			<div class="text_post_img">
				<img src="<?php echo $image; ?>">
			</div><!--text_post_img-->
			<div class="clearfix"></div>
		</div><!--text-->
	</div><!--post_img-->
<?php }?>

<input type="hidden" id = "linkdata" value="<?php echo $meta ;?>"/>