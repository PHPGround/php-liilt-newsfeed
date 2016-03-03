<div id = "likes_detail_modal" class="modal fade modal-facbook">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close button_modal_close" onclick="close_Likes_modal()" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title modal_box_title"><?=$heading?></h4>
			</div>

			<div class="modal-body likes_box_body">
				<div class="full_box">
					<?php if((bool)$likes > 0) { ?>
						<?php foreach($likes as $like){ ?>	
							<?php
								$flag = false;
								
								if($like['liked_as'] == 1){
									$flag = true;
								} 
								else if($like['liked_as'] == 2 && $this->Post_model->checkEntityExist($like['liked_as_id'],2) === true){
									$flag = true;
								} 
								else if($like['liked_as'] == 3 && $this->Post_model->checkEntityExist($like['liked_as_id'],3) === true){
									$flag = true;
								}
									
								if($flag === true){
									$fullName = $like['full_name'];
									$profileLink = base_url() . 'personalprofile/' . $like['resume_id'] ;
										
									if($like['liked_as'] ==  2){
										if($like['company_image'] !=''){
											$image = ($like['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/companies/logos/'.$like['company_id'].'/'.$like['company_image']) : $like['company_image'];
										} else {
											$image = site_url('uploads/companies/logos/default.png');
										}
										$fullName = $like['company_name'];
										$profileLink = base_url() . 'company/' . $like['company_id'] ;
									}
									else if($like['liked_as'] == 3){
										if($like['college_image'] !=''){
											$image = ($like['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/colleges/logos/'.$like['college_id'].'/'.$like['college_image']) : $like['college_image'];
										} else {
											$image = site_url('uploads/colleges/logos/default.png');
										}
										$fullName = $like['college_name'];
										$profileLink = base_url() . 'college/' . $like['college_id'] ;
									}
									else if($like['profile_image'] !='') {
										$image = ($like['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$like['resume_id'].'/'.$like['profile_image']) : $like['profile_image'];
									}
									else {
										$image = site_url('uploads/resumes/profile_images/default.jpg');	
									}																		if(!file_exists($image)){										$image = site_url('uploads/resumes/profile_images/default.jpg');									}
								?>
								<a href="<?php echo $profileLink;?>">
									<img class = "likes_box_img" src="<?php echo  $image; ?>" width = "60" height = "60">
								</a>
								<a class = "post_detail_like_name_link" href="<?php echo $profileLink ;?>" title = "<?php echo $fullName; ?>">
									<p class = "likes_box_name"><?php echo $fullName; ?></p>
								</a>
								<div class="clearfix"></div>
							<?php } ?>
						<?php } ?>
					<div class="clearfix"></div>
					<?php } else { ?>
						<div class="col-md-12 col-sm-12">
							<div class="alert alert-danger" role="alert">
								<img style = "margin: 0 10px 0 0" src="<?=base_url()?>resources/frontend/images/Cross.png" alt="">
								<span>No likes for this <?=$type?></span>
							</div>
						</div>
					<?php } ?>
					<div class="clearfix"></div>
					</div><!---full_box-->
				<div class="clearfix"></div>
			</div><!---modal-body-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

