<li id = "comments_detail_li<?php echo $comment_id;?>">
	<?
	
		$full_name = $comment['full_name'];
		$profileLink = base_url() . 'personalprofile/' . $comment['resume_id'];
	
		if($comment['posted_as'] == 2){
			if($this->session->userdata('account_image') != ''){
				$image = $this->session->userdata('account_image');
			} else {
				$image = site_url('uploads/companies/logos/default.png');
			}
            $full_name = $this->session->userdata('account_name');
			$profileLink = base_url() . 'company/' . $comment['posted_as_id'];
		}
		else if($comment['posted_as'] == 3){
			if($this->session->userdata('account_image') != ''){
				$image = $this->session->userdata('account_image');
			} else {
				$image = site_url('uploads/colleges/logos/default.png');
			}
            $full_name = $this->session->userdata('account_name');
			$profileLink = base_url() . 'college/' . $comment['posted_as_id'];
		}
		else if($comment['profile_image'] != ''){
			$image = site_url('uploads/resumes/profile_images/'.$comment['resume_id'].'/'.$comment['profile_image']);
		}
		else {
			$image = site_url('uploads/resumes/profile_images/default.jpg');
		}
		
		if(!file_exists($image)){
			$image = site_url('uploads/resumes/profile_images/default.jpg');
		}
	?>
	<span class="comment_user_img">
		<a href="<?php echo $profileLink;?>">
			<img title = "<?php echo $full_name;?>" src="<?php echo $image;?>">
		</a>
	</span>
	<p>
		<span class="liilt_color">
			<a class = "post_detail_comment_name_link" href="<?php echo base_url() . 'personalprofile/' . $comment['resume_id'];?>">
				<?php echo $full_name;?>
			</a>
		</span>
		<br>
		<span class = "more"><?php echo nl2br(make_clickable($comment['comment']) ); ?></span><br>
		<?php 
			$time_span = explode(" ", $comment['comment_on']);
			$comment_on_time = $time_span[0];
			
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
		<span class="comment_date" title = "<?php echo date('d-M-y h:i a',strtotime($comment['comment_on']));?>"><?php echo $time_passed.' ago';?></span>
		<br>
		<a id = "comment_details_likes_text<?php echo $comment['id'];?>" class="comment_like pull-left post-options" href="javascript:void(0)" onclick="likeDetailComment('<?php echo $comment['id']; ?>')">
			<?php echo $comment['user_like'] . (($comment['total_comment_likes'] > 1 && $comment['user_like'] != "Unlike") ?  's' : '') ; ?>
		</a>
		<?php echo ($comment['delete_comment']) ? ' <span class = "span_dot">.</span>  <span class="liilt_color"><a class="action_link" href="javascript:void(0)" onclick="deleteDetailComment(\''.$comment['id'].'\',\''.$comment['post_id'].'\')">Delete</a></span> ' : '' ?>
		<?php
			$comment_likes_class = "";
			if($comment['total_comment_likes'] <= 0){
				$comment_likes_class = "hidden";
			}
		?>
		<span id = "comment_detail_likes_count_div<?php echo $comment['id']; ?>" class="liilt_color cursor_class <?=$comment_likes_class?>">
			<a href = "javascript:void(0)" onclick = "getLikesforComment(<?php echo $comment['id'];?>)" class = "liilt_color" style = "text-decoration:none">
				<i class="fa fa-thumbs-o-up"></i>
				<span id = "comment_details_likes_count<?php echo $comment['id'];?>" ><?php echo $comment['total_comment_likes']; ?></span>
			</a>
		</span>
	</p>
</li>