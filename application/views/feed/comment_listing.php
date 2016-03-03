
<li id="comment_<?php echo $comment['id']; ?>" class = "main_comment">
	<?php 
		$commentLength = strlen($comment['comment']);
		if($commentLength > 100){
			$comment_excerpt = substr($comment['comment'], 0, 100);
			$comment_excerpt .= " . . . <span class='liilt_color cursor_class' onclick = 'seemore_mainComment(".$comment['id'].")'>See More</span>";
		} else {
			$comment_excerpt = $comment['comment'];
		}
		$comment_excerpt = nl2br($comment_excerpt);
	?>
	
	
	<input type = "hidden" id = "comment_main_full<?php echo $comment['id']; ?>" value = "<?php echo nl2br(make_clickable($comment['comment'])); ?>" />
	
	
	<span class="comment_user_img">
		<?php 
			
			$fullName = $comment['full_name'];
			$profileLink = base_url() . 'personalprofile/' . $comment['resume_id'] ;

			if($comment['posted_as'] ==  2){
				if($comment['company_image'] !=''){
					$image = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/companies/logos/'.$comment['company_id'].'/'.$comment['company_image']) : $comment['company_image'];
				} else {
					$image = site_url('uploads/companies/logos/default.png');
				}
				$fullName = $comment['company_name'];
				$profileLink = base_url() . 'company/' . $comment['company_id'] ;

			}
			else if($comment['posted_as'] == 3){
				if($comment['college_image'] !=''){
					$image = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/colleges/logos/'.$comment['college_id'].'/'.$comment['college_image']) : $comment['college_image'];
				} else {
					$image = site_url('uploads/colleges/logos/default.png');
				}
				$fullName = $comment['college_name'];
				$profileLink = base_url() . 'college/' . $comment['college_id'] ;
			}
			else if($comment['profile_image'] !='') {
				$image = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$comment['resume_id'].'/'.$comment['profile_image']) : $comment['profile_image'];
			}

			else {
				$image = site_url('uploads/resumes/profile_images/default.jpg');
			} 
			
			if(!file_exists($image)){
				$image = site_url('uploads/resumes/profile_images/default.jpg');
			}
		?>
		
		<a href = "<?php echo $profileLink ;?>" class = "comment_author_header">
			<img title = "<?php echo $fullName;?>" src='<?php echo $image; ?>' />
		</a>
	</span>
	
	<p>
		<span class="comment_user_name">
			<a href = "<?php echo $profileLink ;?>" class = "comment_author_header"><?php echo $fullName ;?></a>
		</span>
		
		</br>
		<span id = "comment_main_excerpt<?php echo $comment['id']; ?>" class="read-more">
			<?php echo $comment_excerpt; ?>
		</span>
		</br>
		<span class="comment_date">			<?php 				if($comment['is_comment_liked'] === true) {					$button_comment_like = "Liked";
					$button_comment_class = "comment_liked";				} else {					$button_comment_like = "Like";
					$button_comment_class = "comment_like";				}			?>
			<a id = "comment_likes_text<?php echo $comment['id']; ?>" class="pull-left comments_likes_main_text <?php echo $button_comment_class ?> post-options" href="javascript:void(0)" onclick="likeComment('<?php echo $comment['id']; ?>',this)">
				<?php echo $button_comment_like ; ?>
			</a> 						<span class = "span_dot">.</span> 
            
			<?php
				$comment_likes_class = "";
				if($comment['total_comment_likes'] <= 0){
					$comment_likes_class = "hidden";
				}
				
				$comment_delete_class = "";
				if($comment['delete_comment'] == false){
					$comment_delete_class = "hidden";
				}
			?>
			
			<span class="liilt_color <?php echo $comment_delete_class?>">
				<a class="pull-left action_link " href="javascript:void(0)" onclick="deleteComment(<?php echo $comment['id']?>, <?php echo $comment['post_id']?>)">Delete</a>
				<span class = "span_dot">.</span> 
			</span> 
			
			<span id = "comment_likes_count_div<?php echo $comment['id']; ?>" class="liilt_color cursor_class <?=$comment_likes_class?>">
				<a href = "javascript:void(0)" onclick = "getLikesforComment(<?php echo $comment['id']; ?>)" class = "pull-left liilt_color" style = "text-decoration:none">
					<i class="fa fa-thumbs-o-up"></i>
					<span id = "comment_likes_count<?php echo $comment['id']; ?>"><?php echo $comment['total_comment_likes'] ; ?></span>
				</a>
				<span class = "span_dot">.</span>
			</span>
            <!-- date format -->
			<?php 
				
				//===========================================================================================
				
				// Time passed
				
				date_default_timezone_set('GMT');
				
				$date_a = new DateTime($comment['comment_on']);
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
			?>
			
            <span title = "<?php echo date('d-M-y h:i a',strtotime($comment['comment_on']))?>" ><?php echo $time_passed.' ago'; ?></span>
		</span>
	</p>
</li>