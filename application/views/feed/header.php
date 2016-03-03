<div class="post_header" >
	<div class="post_user_img">
	

		<?php
		
			$fullName = $user_post['full_name'];
			$profileLink = base_url() . 'personalprofile/' . $user_post['resume_id'] ;
			
			if($user_post['posted_as'] ==  2){
				
				if($user_post['company_image'] !=''){
					$image = ($user_post['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/companies/logos/'.$user_post['company_id'].'/'.$user_post['company_image']) : $user_post['company_image'];
				} else {
					$image = site_url('uploads/companies/logos/default.png');
				}
				
				$fullName = $user_post['company_name'];
				$profileLink = base_url() . 'company/' . $user_post['company_id'] ;
			}
			else if($user_post['posted_as'] == 3){
				if($user_post['college_image'] !=''){
					$image = ($user_post['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/colleges/logos/'.$user_post['college_id'].'/'.$user_post['college_image']) : $user_post['college_image'];
				} else {
					$image = site_url('uploads/colleges/logos/default.png');
				}
				
				$fullName = $user_post['college_name'];
				$profileLink = base_url() . 'college/' . $user_post['college_id'] ;
			}
			else if($user_post['profile_image'] != '') {
				$image = ($user_post['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$user_post['resume_id'].'/'.$user_post['profile_image']) : $user_post['profile_image'];
			}
			else{
				$image = site_url('uploads/resumes/profile_images/default.jpg');
			}
		?>
		
		<?php
			if(!file_exists($image)){
				$image = site_url('uploads/resumes/profile_images/default.jpg');
			}
		?>
		
		<a href="<?php echo $profileLink; ?>">
			<img title = "<?php echo $fullName;?>" src='<?php echo  $image; ?>' />
		</a>
	</div><!--post_user_img-->
	
	<?php
	
		//===========================================================================================
		
		// Post Type (Status, Link, Photo, Video)
		
		if($user_post['post_type'] == 1){
			$post_type_text = "Status";
		} else if($user_post['post_type'] == 2){
			$post_type_text = "Link";
		} else if($user_post['post_type'] == 3){
			$post_type_text = "Photo";
		} else if($user_post['post_type'] == 4){
			$post_type_text = "Video";
		}
		
		//===========================================================================================
		
		// Post Action (Post, Share, Sponsor)
		
		if($user_post['share_post'] == 0 && $user_post['boost_post'] == 0){
			$post_action_text = 'posted';
		} else if($user_post['share_post'] == 1 && $user_post['boost_post'] == 0){
			$post_action_text = 'shared';
		} else if($user_post['boost_post'] == 1){
			$post_action_text = 'sponsored';
		}
		
		//===========================================================================================
		
		// Time passed
		
		date_default_timezone_set('GMT');
		
		$date_a = new DateTime($user_post['update_on']);
		$date_b = new DateTime(date('Y-m-d H:i:s'));

		$interval = date_diff($date_a,$date_b);
		
		//echo $user_post['update_on'].'=='.date('Y-m-d H:i:s');exit;
		
		$years = $interval->format('%y');
		$months = $interval->format('%m');
		$days = $interval->format('%d');
		$hours = $interval->format('%h');
		$minutes = $interval->format('%i');
		$seconds = $interval->format('%s');
		
		if($years != 0){
			$time_passed = $years > 1 ? $years.' years' : $years.' year';
		} else if($months != 0){
			$time_passed = $months > 1 ? $months.' months' : $months.' month';
		} else if($days != 0){
			$time_passed = $days > 1 ? $days.' days' : $days.' day';
		} else if($hours != 0){
			$time_passed = $hours > 1 ? $hours.' hours' : $hours.' hour';
		} else if($minutes != 0){
			$time_passed = $minutes > 1 ? $minutes.' minutes' : $minutes.' minute';
		} else if($seconds != 0){
			if($seconds <= 1 ){
				$time_passed = 'Few moments';
			} else {
				$time_passed = $seconds.' seconds';
			}
		} else {
			$time_passed = 'Few moments';
		}
		
		//===========================================================================================
		
		$del_class = "";
		
		if($user_post['del_post'] == false){
			$del_class = "hidden";
		}
		
		//===========================================================================================
		
		$post_tooltip_text = "Please click on ".$post_type_text." to view detail";
		
	?>
	
	<p>
		<a class = "post_author_header" href="<?php echo $profileLink ;?>">
			<span id = "post_author_<?php echo $user_post['id']?>"><?php echo $fullName;?></span>
		</a>
        
        <?php
			if($user_post['share_post'] == 0 && $user_post['boost_post'] == 0){
				?><span id = "post_action_text<?php echo $user_post['id']?>"><?php echo $post_action_text;?></span> a<?php
			} else if($user_post['share_post'] == 1 && $user_post['boost_post'] == 0){
				?><span id = "post_action_text<?php echo $user_post['id']?>"><?php echo $post_action_text;?></span> <?php echo $user_post['shared_author'];?>'s <?php
			} else if($user_post['boost_post'] == 1){
				?><span id = "post_action_text<?php echo $user_post['id']?>"><?php echo $post_action_text;?></span> a<?php
			}
		?>
        
		 
		<span class="liilt_color cursor_class" onclick = "open_detail_modal(<?php echo $user_post['id']?>)" title = "<?php echo $post_tooltip_text?>"><?php echo $post_type_text;?></span>
		<br/>
		<span class="font-12 cursor_class post_on" onclick = "open_detail_modal(<?php echo $user_post['id']?>)" title = "<?php echo date('d-M-y h:i a',strtotime($user_post['update_on']))?>" ><?php echo $time_passed;?> ago</span>
	</p>
	
	<div class="dropdown">
		<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
			<li><a href="javascript:void(0)" onclick = "open_share_modal(<?php echo $user_post['id']?>)">Share</a></li>
			<li class = "<?php echo $del_class?>"><a href="javascript:void(0)" onclick = "deletePost(<?php echo $user_post['id']?>)" >Delete</a></li>
			<li><a href="javascript:void(0)" onclick = "open_boost_modal(<?php echo $user_post['id']; ?>)">Boost</a></li>
			<li><a href="javascript:void(0)" onclick = "open_detail_modal(<?php echo $user_post['id']; ?>)">View Detail</a></li>
		</ul>
		<i class="fa  fa-angle-down" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
		<div class="clearfix"></div>
	</div><!-- drop down -->
	<div class="clearfix"></div>
</div><!--post_header-->