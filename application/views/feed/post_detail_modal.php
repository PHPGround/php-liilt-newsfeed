<!-- Large modal -->
<?php 
	
	if($post_type == 1){
		$post_detail_type = 'Status';	//	Status Post
	} else if($post_type == 2){
		$post_detail_type = 'Link';			//	Link Post
	} else if($post_type == 3){
		$post_detail_type = 'Photo';			//	Link Post
	} else if($post_type == 4){
		$post_detail_type = 'Video';		//	Video Post
	} 
	
	if((bool)$PostComments > 0 || $boost_post == 1) {
		$CommentBox_class = '';
	} else {
		$CommentBox_class = 'hidden';
	}
?>
<div class="post_detail_modal_wrap">
	<div id = "post_detail_modal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<div class="close_btn">
					<button type="button" class="close button_detailmodal_close" aria-label="Close" onclick = "close_detail_modal()"><span aria-hidden="true">&times;</span></button>
					<div class="clearfix"></div>
				</div><!--close_btn-->
				
				<div class="post_detail-modal"> 
					<p class="modal_heading">Timeline <?php echo $post_detail_type; ?></p>
					<hr>
					<div class="row">
						<div class="a-side-modal col-md-8">
							<div class="modal_post_box">
								<div class="post_img">
									<?php if($post_detail_type == 'Status') { ?>
										
										<!-- Status Post -->
										
										<div class="text post_detail_a_side_text_post">
											<p class = "more"><?php echo $post_description;?></p>
											<div class="clearfix"></div>
										</div>
										
									<?php } else if($post_detail_type == 'Photo') { ?>
									
										<!-- Photo Post -->
										<?php
											if($isUploaded == '0'){
												$Image = $post_media;
											} else {
												$Image = base_url().'uploads/news_feeds/images/'.$post_media;
											}
											
											$ImageInfo = @getimagesize($Image);
											if($ImageInfo){
												$ImageWidth = $ImageInfo[0];
												if($ImageWidth < 751){
													?>
														<div class = "post_detail_image_wrap">
															<img class = "image_width_normal" src="<?php echo $Image; ?>">
														</div>
													<?php
												} else {
													?><img src="<?php echo $Image; ?>"><?php
												}
											} else {
												?><img src="<?php echo $Image; ?>"><?php
											}
										?>
										
									<?php } 
									else if($post_detail_type == 'Link') { 
									
									?>
										<div class="text post_detail_a_side_link_post">
											<?php if($post_status != '') { ?>
												<p><?php echo $post_status;?></p>
											<?php } ?>
											<a class = "detail_link_url" href="<?php echo $post_original_url;?>" target="_blank"><?php echo $post_original_url;?></a>

											<div class="text_post_img detail_link_div_img">
												<img src="<?php echo $post_media;?>">
											</div><!--text_post_img-->
											<p class="postimg_heading detail_link_heading"><?php echo $post_title;?></p>
											<p class="postimg_text"><?php echo $post_description;?></p>
											<div class="clearfix"></div>
										</div>
										
										<!-- Link Post -->
										
									<?php } 
									else if($post_detail_type == 'Video') { 

										// <!-- Video Post -->								
										$post_media = str_replace('height="150"', 'height="437"', $post_media);
										$post_media = str_replace('width="250"', 'width="751"', $post_media);

										echo $post_media;
										
									} ?>
									
								</div><!--post_img-->
							</div><!--modal_post_box-->
						</div><!--a-side-modal-->
					
						<!----------------------b-side-modal-------------------------->
					
						<div class="b-side-modal col-md-4">
							<div class="modal_bside_bg">
								<div class="modal_dropdown hidden">
									<div class="dropdown">
										<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
											<li><a href="#">Action</a></li>
											<li><a href="#">Another action</a></li>
											<li><a href="#">Something else here</a></li>
											<li><a href="#">Separated link</a></li>
										</ul>
										<i class="fa  fa-bars pointer" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"></i>
									</div><!--dropdown open-->
									<div class="clearfix"></div>
								</div><!--modal_dropdown-->
								
								<?
									if($posted_as == 2){
										$image_display = ($profile_photo_type == PROFILE_PHOTO) ? site_url('uploads/companies/logos/'.$company_id.'/'.$company_image) : $company_image;
										$fullName_display = $company_name;
										$profileLink_display = base_url().'company/'.$company_id;
									}
									else if($posted_as == 3){
										$image_display = ($profile_photo_type == PROFILE_PHOTO) ? site_url('uploads/colleges/logos/'.$college_id.'/'.$college_image) : $college_image;
										$fullName_display = $college_name;
										$profileLink_display = base_url().'college/'.$college_id;
									}
									else if($post_author_photo != '') {
										$image_display = ($profile_photo_type == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$post_resume_id.'/'.$post_author_photo) : $post_author_photo;
										$fullName_display = $post_author;
										$profileLink_display = base_url() . 'personalprofile/' . $post_resume_id ;
									}
									else {
										$image_display = site_url('uploads/resumes/profile_images/default.jpg');
										$fullName_display = $post_author;
										$profileLink_display = base_url() . 'personalprofile/' . $post_resume_id ;
									}
								?>
								<?
									if(!file_exists($image_display)){
										$image_display = site_url('uploads/resumes/profile_images/default.jpg');
									}
								?>
								
								<span class="comment_user_img">
									<a href="<?php echo $profileLink_display;?>">
										<img title = "<?php echo $fullName_display?>" src="<?php echo $image_display;?>">
									</a>
								</span>
								<?php
									if($time_passed == '1 Second'){
										$time_passed = 'Few Moments';
									}
								?>
								<p class="top_name">
									<a class = "post_detail_name_link" href="<?php echo $profileLink_display;?>">
										<?php echo $fullName_display?>
									</a>
									<br>
									<span class="date" title = "<?php echo date('d-M-y h:i a',strtotime($update_on));?>">
										<?php echo $time_passed;?> ago
									</span>
								</p>
								<p class="top_text">
									<?php
										if($post_showmore === false){
											if($post_description != ''){
												echo $post_description;
											}
										} else {
											if($post_excerpt != ''){
												?><span id = "post_status_excerpt"><?php echo $post_excerpt; ?></span><?php
											}
										}
										$likes_active_class = "";
										$comments_active_class = "";
										$comments_class = "";
										
										if($post_comments > 0){
											$comments_active_class = "active";
										}
										
										if($post_comments == 0){
											$total_comments = " Comments";
										} else if($post_comments == 1) {
											$total_comments = " 1 Comment";
										} else if($post_comments > 1) {
											$total_comments = " ".$post_comments." Comments";
										}
										
										if($is_post_liked === true){
											$likes_active_class = "active";
											$like_button_text = "Liked";
										} else {
											$like_button_text = "Like";
										}
										
										if($post_likes == 0){
											$total_likes = " Like";
										} else if($post_likes == 1) {
											$total_likes = " 1 Like";
										} else if($post_likes > 1) {
											$total_likes = " ".$post_likes." Likes";
										}
										
										$like_list_class = "hidden";
										if($post_likes > 0){
											$like_list_class = "";
										}
									?>
								</p>
								<input type = "hidden" id = "post_status_full" value = "<?php echo $post_description;?>" />
								<div class="like_box">
									<ul>
										<li id = "likes_detail_li_<?php echo $PostID; ?>" class="pointer post-options <?php echo $likes_active_class;?>">
											<i class="fa fa-thumbs-up"></i> 
											<span id = "btn_like_detail_<?php echo $PostID;?>" onclick="likePost('<?php echo $PostID; ?>',this)">
												<?php echo $like_button_text;?>
											</span>
											<span id = "likes_detail_counter_<?php echo $PostID; ?>" class = "cursor_class liilt_color <?php echo $like_list_class;?>" onclick = "getLikesforPost('<?php echo $PostID; ?>')">(<?php echo $post_likes ?>)</span>
										</li>
										<li id = "comments_detail_li_<?php echo $PostID?>" class="<?php echo $comments_active_class?>">
											<i class="fa fa-comment "></i>
											<span id = "comment_count_detail_<?php echo $PostID;?>">
												<?php echo $total_comments;?>
											</span>
										</li>
										<li class="pointer" onclick = "open_boost_modal(<?php echo $PostID; ?>)">
											<i class="fa fa-power-off"></i> 
											Boost
										</li>
										<li class="pointer" onclick = "open_share_modal(<?php echo $PostID; ?>)" >
											<i class="fa fa-share"></i> 
											Share
										</li>
										<div class="clearfix"></div>
									</ul>
								</div>
								
								<div id = "modal_comments_box_<?php echo $PostID;?>" class="modal_comments_box <?php echo $CommentBox_class?>">
									<?php if($boost_post == 1) { ?>
										<p class="analytics_text"><?php echo $post_views;?> View(s) | <?php echo $post_clicks;?> Click(s)</p>
									<?php } ?>
										<div class="comment_box">
											<ul id = "comment_detail_ul_<?php echo $PostID;?>">
												<?php if((bool)$PostComments > 0) { ?>
													<?php foreach($PostComments as $comment) { ?>
														<li id = "comments_detail_li<?php echo $comment['id']; ?>">
															<?php 
																$commentLength = strlen($comment['comment']);
																if($commentLength > 80){
																	$comment_excerpt = substr($comment['comment'], 0, 80);
																	$comment_excerpt .= " . . . <span class='liilt_color cursor_class' onclick = 'seemore_detailComment(".$comment['id'].")'>See More</span>";
																} else {
																	$comment_excerpt = $comment['comment'];
																}
																$comment_excerpt = nl2br($comment_excerpt);
															?>
															<input type = "hidden" id = "comment_detail_full<?php echo $comment['id']; ?>" value = "<?php echo nl2br(make_clickable($comment['comment'])); ?>" />
															
															<?
																if($comment['posted_as'] ==  2){
																	
																	if($comment['company_image'] == ''){
																		$image_comment = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/companies/logos/'.$comment['company_id'].'/'.$comment['company_image']) : $comment['company_image'];
																	} else {
																		$image_comment = site_url('uploads/companies/logos/default.png');
																	}
																	$fullName_comment = $comment['company_name'];
																	$profileLink_comment = base_url() . 'company/' . $comment['company_id'] ;
																}
																else if($comment['posted_as'] == 3){
																	if($comment['college_image'] != ''){
																		$image_comment = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/colleges/logos/'.$comment['college_id'].'/'.$comment['college_image']) : $comment['college_image'];
																	} else {
																		$image_comment = site_url('uploads/colleges/logos/default.png');
																	}
																	
																	$fullName_comment = $comment['college_name'];
																	$profileLink_comment = base_url() . 'college/' . $comment['college_id'] ;
																}
																else if($comment['profile_image'] != '') {
																	if($comment['profile_image'] != ''){
																		$image_comment = ($comment['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$comment['resume_id'].'/'.$comment['profile_image']) : $comment['profile_image'];
																	} else {
																		$image_comment = site_url('uploads/resumes/profile_images/default.jpg');
																	}
																	$fullName_comment = $comment['full_name'];
																	$profileLink_comment = base_url() . 'college/' . $comment['resume_id'] ;
																}

																 else {
																	$image_comment = site_url('uploads/resumes/profile_images/default.jpg');
																	$fullName_comment = $comment['full_name'];
																	$profileLink_comment = base_url() . 'college/' . $comment['resume_id'] ;
																}
																
																if(!file_exists($image_comment)){
																	$image_comment = site_url('uploads/resumes/profile_images/default.jpg');
																}
															?>
															
															<span class="comment_user_img">
																<a href="<?php echo $profileLink_comment; ?>">
																	<img title = "<?php echo $fullName_comment;?>" src="<?php echo $image_comment; ?>">
																</a>
															</span>
															<p>
																<span class="liilt_color">
																	<a class = "post_detail_comment_name_link" href="<?php echo $profileLink_comment;?>" >
																		<?php echo $fullName_comment;?>
																	</a>
																</span>
																<br>
																<span class = "more" id = "comment_detail_excerpt<?php echo $comment['id']; ?>">
																	<?php echo $comment_excerpt; ?>
																</span>
																<br>
																
																<?php
																	
																	//=============================================
				
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
																
																<span class="comment_date" title = "<?php echo date('d-M-y h:i a',strtotime($comment['comment_on']));?>">
																	<?php echo $time_passed.' ago';?>
																</span>
																<br>
																<?php 
																	if($comment['is_comment_liked'] === true) {
																		$button_comment_like = "Liked";
																		$button_comment_class = "comment_liked";
																	} else {
																		$button_comment_like = "Like";
																		$button_comment_class = "comment_like";
																	}
																?>
																<a id = "comment_details_likes_text<?php echo $comment['id'];?>" class="<? echo $button_comment_class;?> pull-left post-options" href="javascript:void(0)" onclick="likeDetailComment('<?php echo $comment['id']; ?>')">
																	<?php echo $button_comment_like; ?>
																</a> 
																<?php echo ($comment['delete_comment']) ? ' <span class = "span_dot">.</span>  <span class="liilt_color"><a class="pull-left action_link" href="javascript:void(0)" onclick="deleteDetailComment(\''.$comment['id'].'\',\''.$comment['post_id'].'\')">Delete</a></span> ' : '' ?>
																<?php
																	$comment_likes_class = "";
																	if($comment['likes'] <= 0){
																		$comment_likes_class = "hidden";
																	}
																?>
																<span id = "comment_detail_likes_count_div<?php echo $comment['id']; ?>" class="liilt_color cursor_class <?=$comment_likes_class?>">
																	<span class = "span_dot">.</span>
																	<a href = "javascript:void(0)" onclick = "getLikesforComment(<?php echo $comment['id'];?>)" class = "liilt_color" style = "text-decoration:none">
																		<i class="fa fa-thumbs-o-up"></i>
																		<span id = "comment_details_likes_count<?php echo $comment['id'];?>" ><?php echo $comment['likes'] ; ?></span>
																	</a>
																</span>
																
															</p>
														</li>
													<?php } ?>
												<div class="clearfix"></div>
												<?php } ?>
											</ul>
											<div class="clearfix"></div>
										</div><!---comment_box-->
								</div><!--modal_comments_box-->
								
								<div class="modal_input_box">
									<textarea class = "detail_cmt_box" id = "post_detail_comment_box_<?php echo $PostID;?>"placeholder="Add a comment or a reply here ..." onkeypress="auto_grow_textarea(this)" onkeyup="auto_grow_textarea(this)" onpaste="auto_grow_textarea(this)"></textarea>
									<button id = "post_detail_comment_btn_<?php echo $PostID;?>" onclick = "addCommentDetail(<?php echo $PostID;?>)">Send</button>
									<div class="clearfix"></div>
								</div><!--modal_input_box-->
							</div><!--modal_bside_bg-->
						</div><!--a-side-modal-->
					</div><!--row-->            
				</div><!--post_detail-modal-->
				<div class="clearfix"></div>
			</div>
		</div>
	</div>  <!--Large modal-->
	<div class="clearfix"></div>
</div> <!--post_detail_modal_wrap-->
