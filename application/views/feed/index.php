<section class = "background_ecf0f1">

	<div id = "overlay_div" class = "overlay_div hidden">
		<img id = "overlay_img" class = "overlay_img" src="<?php echo base_url().'resources/frontend/images/loading-blue.gif' ; ?>">
	</div>
	
	<? $this->load->view('feed/timeline_options');?>
	
	<div class="clearfix"></div>
	<div class="container">
		<div id="main_section">
			<div class="row">
				<div class="a-side col-md-8">
					<form enctype="multipart/form-data" id="post_feed">
						<?php
							$this->load->view('feed/postbox');
						?>
					</form>

                    <?php

                    $posted_as = 1;
                    $posted_as_id = $this->session->userdata('user_id');
                        if((bool)$this->session->userdata('posted_as') && (bool)$this->session->userdata('posted_as_id')) {
                            $posted_as = $this->session->userdata('posted_as');
                            $posted_as_id = $this->session->userdata('posted_as_id');
                        }
                    ?>
					
					<input type = "hidden" id = "posted_as" value = "<?php echo $posted_as;?>" />
					<input type = "hidden" id = "posted_as_id" value = "<?php echo $posted_as_id;?>" />

					<!-- user feeds start -->
				
					<div id="message_box"></div>
				
					<div id="boost_posts"></div>

					<div id="post_detail"></div>
				
					<div id="likes_box_div"></div>
				
					<div class="modal fade modal-facbook modal-boost">
						<div class="modal-dialog">
							<div class="modal-content">
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
					<div id="feeds">
						<?php
							
							$lastID = "";
						
							if((bool)$user_posts > 0){
								foreach($user_posts as $post){
									$lastID = $post['id'];
									$params['user_post'] = $post;
									$this->load->view('feed/post', $params);
								}
							}
						?>
					</div>

					<input type = "hidden" id = "lastpost" value = "<?php echo $lastID?>" />
					
					<?php
						$load_more_class = "hidden";
						if($total_posts_counter > 5){
							$load_more_class = "";
						}
					?>
						
					<div class = "load_more_wrap">
						<button id="load_more" class="load_more_btn <?php echo $load_more_class;?>" onclick = "load_more()">Load More</button>
					</div>
				</div><!--a-side-->
				
				<div id="side_bar" class="b-side col-md-4">
					<?php echo $sidebar?>
				</div><!--b-side-->

				<!-- user feeds ends -->
			</div><!--row-->
		</div><!--main_section-->

		

		<script>
			//loadFeeds();
			//loadSideBar();
            //loadProfiles();
		</script>

	</div>
</section>



