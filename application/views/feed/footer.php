<div class="post_text_wrap">
	<div class="post_text_box">
		<?php
			$link_status_class = "hidden";
			
			if($user_post['post_type'] != '1' && $user_post['status'] != '') {
				$link_status_class = "";
			}
		?>
	
		<p class = "<?php echo $link_status_class?>" ><?php echo nl2br(make_clickable($user_post['status']) );?></p>
		<?php
			$comments_active_class = "";
			$comments_class = "";
			
			if($user_post['post_comments'] === false){
				$comments_class = "hidden";
			} else {
				$comments_active_class = "active";
			}
			
			if($user_post['total_post_comments'] == 0){
				$total_comments = " Comments";
			} else if($user_post['total_post_comments'] == 1) {
				$total_comments = " 1 Comment";
			} else if($user_post['total_post_comments'] > 1) {
				$total_comments = " ".$user_post['total_post_comments']." Comments";
			}
			
			$likes_active_class = "";
			$like_list_class = "hidden";
			
			if($user_post['total_post_likes'] > 0){
				$like_list_class = "";
			}
			
			if($user_post['total_post_likes'] == 0){
				$total_likes = " Like";
			} else if($user_post['total_post_likes'] == 1) {
				$total_likes = "1 Like";
			} else if($user_post['total_post_likes'] > 1) {
				$total_likes = $user_post['total_post_likes']." Likes";
			}
			
			if($user_post['is_post_liked'] === true){
				$likes_active_class = "active";
				$like_button_text = "Liked";
			} else {
				$like_button_text = "Like";
			}
			
			$readmore_class = "hidden";
			if($user_post['total_post_comments'] > 2){
				$readmore_class = "";
			}
		?>
	
		<div id = "options_box_<?php echo $user_post['id']; ?>" class="like_box">
			<ul>
				<li>
					<span id = "likes_li_<?php echo $user_post['id']; ?>" class = "post-options footer_likes cursor_class <?php echo $likes_active_class?>" onclick="likePost('<?php echo $user_post['id']; ?>',this)" >
						<i class="fa fa-thumbs-up"></i> 
						<span id = "btn_like_main_<?php echo $user_post['id']; ?>" class = "posts_likes_main_text"><?php echo $like_button_text;?></span>
					</span>
					<span id = "likes_main_counter_<?php echo $user_post['id']; ?>" class = "cursor_class liilt_color footer_likes_text <?php echo $like_list_class;?>" onclick = "getLikesforPost('<?php echo $user_post['id']; ?>')">(<?php echo $total_likes ?>)</span>
				</li>
				<li id = "comments_li_<?php echo $user_post['id']; ?>" class = "footer_comments cursor_class <?php echo $comments_active_class?>" onclick = "open_detail_modal(<?php echo $user_post['id']?>)">
					<i class="fa fa-comment "></i>
					<span id = "comments_main_counter_<?php echo $user_post['id']; ?>"><?php echo $total_comments ?></span>
				</li>
				<li onclick = "open_boost_modal(<?php echo $user_post['id']; ?>)">
					<i class="fa fa-power-off"></i> 
					<span class = "cursor_class post-options">Boost</span>
				</li>
				<li onclick = "open_share_modal(<?php echo $user_post['id']; ?>)">
					<i class="fa fa-share"></i> 
					<span class = "cursor_class post-options">Share</span>
				</li>
				<div class="clearfix"></div>
			</ul>
		</div><!--like_box-->
		
		<div id = "comment_box_<?php echo $user_post['id']; ?>" class="comment_box <?php echo $comments_class?>">
			<ul>
				<?php 
					if($user_post['post_comments'] !== false){
						foreach($user_post['post_comments'] as $comment){
							$params['comment'] = $comment;
							$this->load->view('feed/comment_listing', $params);
						}
					}
				?>
			</ul>
			<div class="clearfix"></div>
			<div id = "view_all_comments_<?php echo $user_post['id']?>" class = "view_all_comments <?php echo $readmore_class?>">
				<a href = "javascript:void(0)" onclick = "open_detail_modal(<?php echo $user_post['id']?>)">View All Comments</a>
			</div>
		</div><!--comment_box-->
		
		<div class="send_box">
			<textarea id = "commentbox<?php echo $user_post['id']; ?>" class="comment_area" placeholder="comment here" maxlength="300" ></textarea>
			<button id = "btn_comment_main" onclick="addComment(<?php echo $user_post['id']; ?>)">Send</button>
			<div class="clearfix"></div>
		</div><!--send_box-->
	</div>
</div>