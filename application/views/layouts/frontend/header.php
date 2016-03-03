<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-GB">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-GB">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="en-GB">
<!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="msvalidate.01" content="64157E4A2C26DC80B65CB26327262829" />
	<meta name="keywords" content="<?php echo isset($meta_keywords)?$meta_keywords:'EDM, DJ, EDM Jobs, Music Jobs, DJ Jobs, Music Careers, Electronic Music Network, Music School, DJ School, Music News, EDM News, Music Sites'; ?>" />
	<meta name="description" content="LiiLT is the worldwide professional network for electronic music careers. LiiLT provides free access to worldwide jobs and courses as well as networking opportunities from our global directory of professionals, brands, companies and colleges within electronic music and EDM.">
	<meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'LiiLT | Electronic Music Careers'; ?>"/>
	<meta property="og:image" content="<?php echo base_url(); ?>resources/frontend/images/FB_IMG_1439980260684.png" />
	<meta property="og:description" content="LiiLT is the worldwide professional network for electronic music careers. LiiLT provides free access to worldwide jobs and courses as well as networking opportunities from our global directory of professionals, brands, companies and colleges within electronic music and EDM."/>
	
	<title><?php echo isset($page_title) ? $page_title : 'LiiLT | Electronic Music Careers'; ?></title>
	
	<!--[if lt IE 9]>
	<?php // <script src="http://liilt.com/wp-content/themes/wpjobus/js/html5.js"></script> ?>
	<![endif]-->

	<link rel="shortcut icon" href="<?php echo base_url(); ?>resources/frontend/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url(); ?>resources/frontend/img/favicon.ico" type="image/x-icon">
	<?php //echo Assets::css("frontend/css/bootstrap.css");?>
	<?php echo Assets::css("frontend/plugins/menu-icons/css/genericons.min.css");?>
	<?php echo Assets::css("frontend/plugins/LayerSlider/static/css/layerslider.css");?>
	
	<?php echo Assets::css("frontend/css/flexslider.css");?>
	<?php echo Assets::css("frontend/fonts/genericons.css");?>
	<!--[if lt IE 9]>
	<?php echo Assets::css("frontend/css/ie.css");?>
	<![endif]-->
	<?php echo Assets::css("frontend/css/owl.carousel.css");?>
	<?php echo Assets::css("frontend/css/owl.theme.css");?>
	<?php echo Assets::css("frontend/css/owl.transitions.css");?>
	<?php echo Assets::css("frontend/css/lightbox.css");?>
	<?php echo Assets::css("frontend/css/jquery.fancybox.css");?>
	<?php echo Assets::css("frontend/css/main.css");?>
    <?php echo Assets::css("frontend/css/custom/custom.css");?>
    <?php echo Assets::css("frontend/css/custom/misc.css");?>
	<?php echo Assets::css("frontend/css/responsive.css");?>
	<?php echo Assets::css("frontend/css/font-awesome.css",array('id'=>'awesomefont-style-css','media'=>'all'));?>
	<?php echo Assets::css("frontend/css/flexslider.css");?>
	<?php echo Assets::css("frontend/css/print.css");?>
    <?php echo Assets::css("frontend/css/bootstrap.css");?>
    

	<?php echo Assets::css("frontend/plugins/mailchimp-for-wp/assets/css/form.min.css");?>
    
	<style type="text/css">
		div.register-front-block.register-block-blue { border-image-repeat: stretch; }
		.featured-title { color:#08dfff; }
		.load-more 
		{
			box-shadow: 0 3px 0 #3bdbed;
			color: #fff;
  			background: #3bdbed;
  			padding: 12px 20px 9px 20px;
			text-transform: uppercase;
			width: 150px;
			display: inline-block;
			float: none;
			font-weight: bold;
			font-size: 14px;
			cursor: pointer;
			margin-right: 4px;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;

		}
	</style>
	<?php echo Assets::js("frontend/js/jquery.js");?>
	<?php echo Assets::js("frontend/plugins/LayerSlider/static/js/layerslider.kreaturamedia.jquery.js");?>

    <script type="text/javascript">
            var BASE_URL = '<?php echo base_url(); ?>';
    </script>

	<script type="text/javascript" src="<?php echo base_url();?>resources/frontend/js/jquery.uploadifive.js" ></script>
	<?php 
		if ($this->session->userdata('user_id')) {
			?>
				<!--
				<script type="text/javascript" src="<?php echo base_url();?>resources/frontend/js/custom/news_feed.js" ></script>-->
				<script type="text/javascript" src="<?php echo base_url();?>resources/frontend/js/custom/feeds.js" ></script>
			<?php
		}
	?>

	<script>
		jQuery(function(){
		
			jQuery('#file_upload').uploadifive({

				'uploadScript': '<?php echo site_url();?>feed/uploadImage',
				'buttonClass' : 'upload_button',
				'fileType'     : 'image/*',
				'hideButton': true,
				'wmode'     : 'transparent',
				'buttonText': '<span><i class="fa fa-image"></i></span> Photo',
				'onProgress' : function(){
					showPostLoader();
				},
				'onUploadComplete': function(file, data){
					hidePostLoader();
					var obj = JSON.parse(data);
					if(obj.code == 200){
						jQuery("#post_preview").html(obj.view);
					}
					jQuery('#uploadifive-file_upload').css('background-color', '#051b4c');
					jQuery('#uploadifive-file_upload').css('color', 'white');
					jQuery('.status').removeClass('active');
					jQuery('#uploadifive-file_upload').addClass('active');
					
					embedFlag = 1;

				},
				'onError': function(errorType) {
					alert("There was an error in upload: " + errorType);
					hidePostLoader();
				}
			});
		});
	</script>
	
    
    <?php
    $controller = $this->router->fetch_class();
    $action = $this->router->fetch_method();
    
    
    $media_css_params = array('media' => 'all');

    echo Assets::js("frontend/js/custom/".$controller."_".$action.".js");
    echo Assets::css("frontend/css/custom/".$controller."_".$action.".css");
    echo Assets::css("frontend/css/custom/notification.css");
    echo Assets::css("frontend/css/custom/inbox.css");
    ?>

	<script>
		var BASE_URL = '<?php echo site_url(); ?>';
	</script>
	<script type="text/javascript" >
	jQuery(document).ready(function()
	{
	jQuery("#notificationLink").click(function()
	{
		jQuery("#inboxContainer").hide();
		jQuery("#notificationContainer").fadeToggle(300);
		jQuery("#notification_count").fadeOut("slow");
		jQuery.ajax({
			url: BASE_URL + 'user/notification_read',
			data:{},
			dataType:"json",
			method:"post",
			success:function(response){

			}
		});
	return false;
	});
	
	jQuery('#uploadifive-file_upload').css('line-height', '');
	jQuery('#uploadifive-file_upload').css('height', '');
	jQuery('#uploadifive-file_upload').css('width', '');

	//Document Click
	jQuery(document).click(function()
	{
	jQuery("#notificationContainer").hide();
	});
	//Popup Click
	jQuery("#notificationsBody").click(function()
	{
	//return false
	});

	//Inbox 
	jQuery("#inboxLink").click(function()
	{
		jQuery("#notificationContainer").hide();
		jQuery("#inboxContainer").fadeToggle(300);
		jQuery("#inbox_count").fadeOut("slow");
		jQuery.ajax({
			url: BASE_URL + 'user/inbox_read',
			data:{},
			dataType:"json",
			method:"post",
			success:function(response){

			}
		});
	return false;
	});

	//Document Click
	jQuery(document).click(function()
	{
	jQuery("#inboxContainer").hide();
	});
	//Popup Click
	jQuery("#inboxBody").click(function()
	{
	//return false
	});

	});
	</script>
</head>
<body>

	<section id="top">

		<div class="container">

			<div class="header-stats">
				<?php
				// Get the latest stats
				$jsondata['latest_stats'] = read_file(FCPATH.'latest_count_stats.txt');
				$latest_stats = json_decode($jsondata['latest_stats'],TRUE);
				?>
				<span>
					<?php echo $latest_stats['jobs_count'];?>				Jobs				
				</span>

				<span class="header-stats-divider">|</span>

				<span>
					<?php echo $latest_stats['resumes_count'];?>				Members				
				</span>

				<span class="header-stats-divider">|</span>

				<span>
					<?php echo $latest_stats['companies_count'];?>				Companies				
				</span>
				
				<span class="header-stats-divider">|</span>

				<span>
					<?php echo $latest_stats['colleges_count'];?>				Colleges				
				</span>
				
				<span class="header-stats-divider">|</span>

				<span>
					<?php echo $latest_stats['courses_count'];?>				Courses				
				</span>


			</div>

			<div class="top_menu account-menu">
			<!--
				<ul class="menu" style="padding-left: 0;">
					<?php 
						if ( $this->session->userdata("user_id")) {
					?>
					<li class="first">
						<a href="<?php echo site_url("my-account"); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login">My Account</a>
					</li>
					<li class="last">
						<a href="<?php echo site_url("logout"); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Logout">Logout</a>
					</li>
					<?php } else { ?>
					<li class="first">
						<a href="<?php echo site_url("login"); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Login">Login</a>
					</li>
					<li class="last">
						<a href="<?php echo site_url("register"); ?>" class="ctools-use-modal ctools-modal-ctools-ajax-register-style" title="Register">Register</a>
					</li>
					<?php } ?>	
				</ul> -->
			</div>

			<div class="top-social-icons">
				<a class="target-blank" href="https://www.facebook.com/weareliilt">
					<i class="fa fa-facebook-square"></i>
				</a>
				<a class="target-blank" href="https://twitter.com/weareliilt">
					<i class="fa fa-twitter-square"></i>
				</a>					
				<!-- <a class="target-blank" href="http://www.youtube.com/weareliilt/">
					<i class="fa fa-youtube-square"></i>
				</a> -->					
				<a class="target-blank" href="https://plus.google.com/b/115333515741291911124/115333515741291911124">
					<i class="fa fa-google-plus-square"></i>
				</a>	
				<a class="target-blank" href="https://www.linkedin.com/company/liilt">
					<i class="fa fa-linkedin-square"></i>
				</a>
			</div>

			<div class="top_menu">
			</div>

		</div>

	</section>

	<header id="header">

		<div class="container">

			<div class="full" style="margin-bottom: 0;">

				<a class="logo" href="<?php echo base_url();?>">
					<img src="<?php echo base_url();?>resources/frontend/images/LiiLT-Logo-Bluu-R-Beta.png" alt="Logo" />
				</a>

				<div class="top_menu new-posts-menu">
				<?php  ?>
					<?php 
							if ( $this->session->userdata('user_id'))
							{
								$inbox_data = get_inbox();
								$inbox = $inbox_data['inbox_data'];
								$inbox_count = $inbox_data['inbox_count'];
								
						?>
						<a href="#" id="inboxLink">
								<?php if(isset($inbox_count) && $inbox_count != 0) { ?>
								<span id="inbox_count"><?php echo $inbox_count; ?></span>
								<?php } ?>
								<i class="fa fa-envelope" style="font-size: 18px !important;"></i>
							</a>
							<div id="inboxContainer">
							<div id="inboxTitle">Inbox</div>
							<div id="inboxBody" class="inbox">
								<?php if( ! empty( $inbox ) ) { ?>
								<div id="inbox-block-list">
									<?php foreach( $inbox as $i ) {

					    				$profile_image = "";

					    				if($i['profile_image'] != "")
								        {
								            $profile_image = ($i['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$i['resume_id'].'/'.$i['profile_image']) : $i['profile_image'];
								        }

								        if($this->user['id'] != $i['from_user_id'])
										{
											$from_user_id = $i['from_user_id'];
										}
										else
										{
											$from_user_id = $i['to_user_id'];
										}

								        $url= site_url('message/view/'.$from_user_id);
					    			?>
					    			<a href="<?php echo $url; ?>" class="notification-link">
			    					<div class='inbox-holder-block'>
			    						<span class='inbox-item-icon'>
											<?php if($i['profile_image'] != "") { ?>
												<img src="<?php echo $profile_image; ?>" alt="<?php echo $i['full_name']; ?>"/>
											<?php } else { ?>
												<i class="fa fa-user" style="color:#fff;margin:8px;font-size:45px !important"></i>
											<?php } ?>
										</span>

										<span class='inbox-item-name-block'>
											<span class='inbox-item-name'><?php echo $i['full_name']; ?></span>
											<span class='inbox-text'><?php echo substr($i['message'],0,100);; ?></span>
											<span class='inbox-item-time'><i class="fa fa-clock-o" style="margin-left:0"></i>
												<?php echo ago(strtotime($i['created'])); ?> ago
											</span>
										</span>
				    				</div>
				    				</a>
				    				<?php } ?>
	    						</div>
	    						<?php } else { ?>
	    						<div id="inbox-block-list">
	    							<span class='inbox-item-name-block'>
	    								<span class='inbox-text'>No messages</span>
	    							</span>
	    						</div>
	    						<?php } ?>
							</div>
							<div id="inboxFooter"><a href="<?php echo site_url('message/inbox'); ?>">See All</a></div>
							</div>
						<?php
								$notification_data = get_notifications();
								$notifications = $notification_data['notifications'];
								$notification_count = $notification_data['notification_count'];
						?>
							<a href="#" id="notificationLink">
								<?php if(isset($notification_count) && $notification_count != 0) { ?>
								<span id="notification_count"><?php echo $notification_count; ?></span>
								<?php } ?>
								<i class="fa fa-bell" style="font-size: 18px !important;"></i>
							</a>
							<div id="notificationContainer">
							<div id="notificationTitle">Notifications</div>
							<div id="notificationsBody" class="notifications">
								<?php if( ! empty( $notifications ) ) { ?>
								<div id="notification-block-list">
									<?php foreach( $notifications as $notification ) {

					    				$profile_image = "";

					    				if($notification['profile_image'] != "")
								        {
								            $profile_image = ($notification['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$notification['resume_id'].'/'.$notification['profile_image']) : $notification['profile_image'];
								        }

								        $url = "";

								        $other_data = unserialize($notification['other_data']);
								        if(isset($other_data['link']))
								        {
								        	$url = site_url($other_data['link']);
								        }

								        switch($notification['notification_type_id'])
								        {
								        	case NOTIFICATION_MESSAGE_RECEIVED : $url= site_url('message/view/'.$notification['from_user_id']);
								            break;
								            
								            case NOTIFICATION_FRIEND_REQUEST_RECEIVED :;

								            case NOTIFICATION_FRIEND_REQUEST_ACCEPTED :;

								            case NOTIFICATION_FRIEND_REQUEST_REJECTED :$url=site_url('user/contacts');
								            break;

								            case NOTIFICATION_INVOICE_AVAILABLE :
								            break;

								            default:
								                break;
								        }
					    			?>
					    			<a href="<?php echo $url; ?>" class="notification-link">
			    					<div class='notification-holder-block'>
			    						<span class='notification-item-icon'>
											<?php if($notification['profile_image'] != "") { ?>
												<img src="<?php echo $profile_image; ?>" alt="<?php echo $notification['from_username']; ?>"/>
											<?php } else { ?>
												<i class="fa fa-user" style="color:#fff;margin:8px;font-size:45px !important"></i>
											<?php } ?>
										</span>

										<span class='notification-item-name-block'>
											<span class='notification-item-name'><?php echo $notification['from_username']; ?></span>
											<span class='notification-text'><?php echo $notification['notification_text']; ?></span>
											<span class='notification-item-time'><i class="fa fa-clock-o" style="margin-left:0"></i>
												<?php echo ago($notification['notification_on']); ?> ago
											</span>
										</span>
				    				</div>
				    				</a>
				    				<?php } ?>
	    						</div>
	    						<?php } else { ?>
	    						<div id="notification-block-list">
	    							<span class='notification-item-name-block'>
	    								<span class='notification-text'>No notifications</span>
	    							</span>
	    						</div>
	    						<?php } ?>
							</div>
							<div id="notificationFooter"><a href="<?php echo site_url('notification/view'); ?>">See All</a></div>
							</div>
						<?php } ?>

					<ul class="menu">
						
						<li>
							<a href="#" class="button-ag-full">
								Add<i class="fa fa-plus-circle"></i>
							</a>
							<ul class="sub-menu add-listing-submenu">
								<img class="sub-menu-top-corner" src="<?php echo base_url();?>resources/frontend/images/sub-menu-corner.png" alt=""/>
								<li>
									<a href="<?php echo site_url("college/choose_listing");?>">
										<i class="fa fa-book"></i>College
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("course/choose_listing");?>">
										<i class="fa fa-graduation-cap"></i>Course
									</a>
								</li>								
								<li>
									<a href="<?php echo site_url("company/choose_listing");?>">
										<i class="fa fa-briefcase"></i>Company
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("job/choose_listing");?>">
										<i class="fa fa-unlock"></i>Job
									</a>
								</li>
								
							</ul>
						</li>
						<?php 
						if ( $this->session->userdata("user_id")) {
						?>
						<li>
							<a href="<?php echo site_url("my-account"); ?>" class="button-ag-full">
								My Account<i class="fa fa-lock"></i>
							</a>
							<ul class="sub-menu add-listing-submenu" style="margin-left: 0px;">
								<img class="sub-menu-top-corner" src="<?php echo base_url();?>resources/frontend/images/sub-menu-corner.png" alt=""/>
								<li>
									<a href="<?php echo site_url("my-account");?>">
										<i class="fa fa-lock"></i>My Listings
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("message/inbox");?>">
										<i class="fa fa-envelope"></i>Inbox
									</a>
								</li>								
								<li>
									<a href="<?php echo site_url("user/contacts");?>">
										<i class="fa fa-users"></i>Contacts
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a href="<?php echo site_url("logout"); ?>" class="button-ag-full">
								Logout<i class="fa fa-power-off"></i>
							</a>
							
						</li>
						<?php } else { ?>
						<li>
							<a href="<?php echo site_url("login"); ?>" class="button-ag-full">
								Login<i class="fa fa-user"></i>
							</a>
						</li>
						<li>
							<a href="<?php echo site_url("register/choose"); ?>" class="button-ag-full">
								Register<i class="fa fa-key"></i>
							</a>
							
						</li>
						<?php } ?>
					</ul>
				</div>

				<div class="main_menu">
					<ul id="menu-main-menu" class="menu">
						<li id="menu-item-1048" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1048 <?php echo isset($active_tab) && $active_tab == 'home'? "current_page_item " :"" ?>">
							<?php if((bool)$this->session->userdata('user_id')){ ?>
							<a href="<?php echo base_url().'feeds' ; ?>" >
								<i class="_mi _before fa fa-home"></i><span>Home</span>
							</a>
							<?php }else{ ?>
				                <a href="<?php echo base_url(); ?>" >
									<i class="_mi _before fa fa-home"></i><span>Home</span>
								</a>
					        <?php } ?>
							<!--
							<a href="<?php //echo site_url();?>" >
								<i class="_mi _before fa fa-home"></i><span>Home</span>
							</a>
							-->
						</li>
						<li id="menu-item-1035" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1035 <?php echo isset($active_tab) && $active_tab == 'jobs'? "current_page_item" :"" ?>">
							<a href="<?php echo site_url("jobs");?>">
								<i class="_mi _before fa fa-unlock"></i><span>Jobs</span>
							</a>
						</li>
						<li id="menu-item-1453" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1453 <?php echo isset($active_tab) && $active_tab == 'courses'? "current_page_item" :"" ?>">
							<a href="<?php echo site_url("courses");?>">
								<i class="_mi _before fa fa-graduation-cap"></i><span>Courses</span>
							</a>
						</li>
						<li id="menu-item-1339" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-1339 has-submenu <?php echo isset($active_tab) && $active_tab == 'profiles'? "current_page_item " :"" ?>">
							<a href="javascript:void(0)">
								<i class="_mi _before fa fa-users"></i><span>Profiles</span>
							</a>
							<ul class="sub-menu">
								<li id="menu-item-1168" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1168">
									<a href="<?php echo site_url("people");?>">
										<i class="_mi _before fa fa-user"></i><span>People</span>
									</a>
								</li>
								<li id="menu-item-1036" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1036">
									<a href="<?php echo site_url("companies");?>">
										<i class="_mi _before fa fa-briefcase"></i><span>Companies</span>
									</a>
								</li>
								<li id="menu-item-1403" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1403">
									<a href="<?php echo site_url("colleges");?>">
										<i class="_mi _before fa fa-book"></i><span>Colleges</span>
									</a>
								</li>
							</ul>
						</li>
						<li id="menu-item-1454" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1453 ">
							<a href="<?php echo site_url("thebuzz");?>" target="_blank">
								<i class="_mi _before fa fa-fa fa-microphone"></i><span>The Buzz</span>
							</a>
						</li>

						<li style="display:none;">
							<a href="#" class="button-ag-full">
								Add<i class="fa fa-plus-circle"></i>
							</a>
							<ul class="sub-menu add-listing-submenu">
								<img class="sub-menu-top-corner" src="<?php echo base_url();?>resources/frontend/images/sub-menu-corner.png" alt=""/>
								<li>
									<a href="<?php echo site_url("college/choose_listing");?>">
										<i class="fa fa-book"></i>College
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("course/choose_listing");?>">
										<i class="fa fa-graduation-cap"></i>Course
									</a>
								</li>								
								<li>
									<a href="<?php echo site_url("company/choose_listing");?>">
										<i class="fa fa-briefcase"></i>Company
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("job/choose_listing");?>">
										<i class="fa fa-unlock"></i>Job
									</a>
								</li>
								
							</ul>
						</li>
						<?php 
						if ( $this->session->userdata("user_id")) {
						?>
						<li style="display:none;">
							<a href="<?php echo site_url("my-account"); ?>" class="button-ag-full">
								My Account<i class="fa fa-lock"></i>
							</a>
							<ul class="sub-menu add-listing-submenu" style="margin-left: 0px;">
								<img class="sub-menu-top-corner" src="<?php echo base_url();?>resources/frontend/images/sub-menu-corner.png" alt=""/>
								<li>
									<a href="<?php echo site_url("my-account");?>">
										<i class="fa fa-lock"></i>My Listings
									</a>
								</li>
								<li>
									<a href="<?php echo site_url("message/inbox");?>">
										<i class="fa fa-envelope"></i>Inbox
									</a>
								</li>								
								<li>
									<a href="<?php echo site_url("user/contacts");?>">
										<i class="fa fa-users"></i>Contacts
									</a>
								</li>
							</ul>
						</li>
						<li style="display:none;">
							<a href="<?php echo site_url("logout"); ?>" class="button-ag-full">
								Logout<i class="fa fa-power-off"></i>
							</a>
							
						</li>
						<?php } else { ?>
						<li style="display:none;">
							<a href="<?php echo site_url("login"); ?>" class="button-ag-full">
								Login<i class="fa fa-user"></i>
							</a>
						</li>
						<li style="display:none;">
							<a href="<?php echo site_url("register/choose"); ?>" class="button-ag-full">
								Register<i class="fa fa-key"></i>
							</a>
							
						</li>
						<?php } ?>
						
						<!-- <li id="menu-item-1455" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-1453 ">
							<a href="<?php echo site_url("discount");?>">
								<i class="_mi _before fa fa-keyboard-o"></i><span>Discount</span>
							</a>
						</li> -->
					</ul>				
				</div>

			</div>

		</div>

	</header>