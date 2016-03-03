<div id = "profile_type_modal" class="modal fade modal-facbook">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close button_modal_close" aria-label="Close" onclick = "close_profile_modal()"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title modal_box_title">Select a <?php echo $type_name; ?></h4>
			</div>

			<div class="modal-body likes_box_body">
				<div class="full_box">
						<?php if((bool)$profile_type > 0) { ?>
							
								<?php foreach($profile_type as $profile){ ?>	
									<div class = "profiletype_row" >
									<span class = "profiletype_name"><?php echo $profile['name']; ?></span>
									<a href = "javascript:void(0)" class = "profiletype_button" onclick='changeProfileType("<?php echo $type_name; ?>","<?php echo $profile['id']; ?>")'>select</a>
									
									<div class="clearfix"></div>
								</div>
					
						<?php }} else { ?>
							
							<div class="col-md-12 col-sm-12">
								<div class="alert alert-danger" role="alert"><img style = "margin: 0 10px 0 0" src="<?=base_url()?>resources/frontend/images/Cross.png" alt=""><span>No <?php echo $type_name; ?> Found.</span></div>
							</div>
						
						<?php } ?>
						<div class="clearfix"></div>
				</div><!---full_box-->

				<div class="clearfix"></div>
			</div><!---modal-body-->
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->