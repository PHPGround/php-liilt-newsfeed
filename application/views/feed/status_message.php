<div id = "status_message" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php
				if($status == 'success'){
					$modalclass = "alert-success";
					$heading = "Success!";
					$icon = "tick.png";
				} else if($status == 'error'){
					$modalclass = "alert-danger";
					$heading = "Error!";
					$icon = "Cross.png";
				}
				
			?>
			<div class="alert <?php echo $modalclass;?>" role="alert" style = "margin-bottom:0;border-radius:0px">
				<button type="button" class="close" aria-label="Close" onclick = "closeStatusMessage()">
					<span aria-hidden="true">&times;</span>
				</button>
				<img style = "margin: 0 10px 0 0" src="<?=base_url()?>resources/frontend/images/<?php echo $icon;?>" alt="">
				<span>
					<strong><?php echo $heading;?></strong>
					<?php echo $message;?>
				</span>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->