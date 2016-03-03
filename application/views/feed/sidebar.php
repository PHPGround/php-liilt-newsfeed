
    <div class="user_profile_box">
        <div class="profile_img_box">
            <?php 
			
			if($profile_photo['profile_image']!='') {
                $image = ($profile_photo['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$profile_photo['resume_id'].'/'.$profile_photo['profile_image']) : $profile_photo['profile_image'];
            } else {
                $image = site_url('uploads/resumes/profile_images/default.jpg');
            }
            $profileLink = base_url() . 'personalprofile/' . $profile_photo['resume_id'];
			
            if((bool)$this->session->userdata('account_name')) {
				//echo '<pre>';print_r($this->session->all_userdata());exit;
                
				$image = $this->session->userdata('account_image');
                $full_name = $this->session->userdata('account_name');

                if($this->session->userdata('posted_as') == 2){
					if($image == ''){
						$image = site_url('uploads/companies/logos/default.png');
					}
                    $profileLink = base_url() . 'company/' . $this->session->userdata('posted_as_id');
				}
                else if($this->session->userdata('posted_as') == 3){
					if($image == ''){
						$image = site_url('uploads/colleges/logos/default.png');
					}
                    $profileLink = base_url() . 'college/' . $this->session->userdata('posted_as_id');
				}

            }						if(!file_exists($image)){				$image = site_url('uploads/resumes/profile_images/default.jpg');			}
			
            ?>
			<a id = "sidebar_profile_link" href="<?php echo $profileLink; ?>">
				<img id = "sidebar_profile_image" title = "<?php echo $full_name; ?>" src='<?php echo $image; ?>' />
			</a>
        </div>

        <input type="hidden" id="profile_photo" value="<?php echo $image; ?>"/>

        <p class="profile_name">
			<a class = "sidebar_author_header" href="<?php echo $profileLink;?>">
				<span id = "sidebar_author_name">
					<?php echo $full_name ?>
				</span>
			</a>
		</p>
        <p>
            <a href="<?php echo base_url() . 'resume/edit/' . $profile_photo['resume_id'] ;?>" title = "Edit Profile"><i class="fa fa-edit"></i></a>
            <a href="<?php echo base_url() . 'my-account' ;?>" ><i class="fa fa-unlock" title = "My Account"></i></a>
            <a href="<?php echo base_url() . 'user/contacts' ; ?>" ><i class="fa fa-users" title = "My Contacts"></i></a>
            <a href="<?php echo base_url() . 'message/inbox' ; ?>"> <i class="fa fa-envelope" title = "Inbox"></i></a>
        </p>
        <input type="hidden" id="default_account_name" value="<?php echo $full_name; ?>"/>
    </div><!--user_profile_box-->

    <!-------------- Featured box start ------------------->
    <?php if(!empty($featured_job)) { ?>
        <div class="featured_box">
            <div class="featured_header">
                <p>Featured Job !</p>
            </div><!--featured_header-->
            <a href='<?php echo site_url('job/'.$featured_job['id']); ?>'>
                <div class="featured_img">
                    <?php if($featured_job['cover_image'] != "" && file_exists(FCPATH.'uploads/jobs/'.$featured_job['id'].'/'.$featured_job['cover_image'])) { ?>
                        <img class='big-img' src='<?php echo base_url('uploads/jobs/'.$featured_job['id'].'/'.$featured_job['cover_image']); ?>' alt=''/>
                    <?php } else { ?>
                        <img class='big-img' src='<?php echo base_url('resources/frontend/images/job.png'); ?>' alt=''/>
                    <?php } ?>

                    <span class="featured_btn"><?php echo $featured_job['job_type']; ?></span>
                </div><!--featured_img-->
            </a>

            <p class="featured_name"><?php echo $featured_job['name']; ?></p>
            <p><i class="fa fa-briefcase"></i> <?php echo $featured_job['company']; ?> &nbsp; <i class="fa fa-map-marker"></i> <?php echo $featured_job['location']; ?> </p>
            <a href="<?php echo site_url('jobs'); ?>" >SEE ALL</a>
        </div><!--featured_box-->
    <?php } ?>
    <!--------------Featured box end------------------->

    <div class="side_banner">
        <img src="<?php echo base_url(); ?>resources/frontend/images/side_banner.png">
    </div><!--side_banner--->

    <!--------------Featured box start------------------->
    <?php if(!empty($featured_course)) { ?>
        <div class="featured_box">
            <div class="featured_header">
                <p>Featured Course !</p>
            </div><!--featured_header-->
            <a href='<?php echo site_url('course/'.$featured_course['id']); ?>'>
                <div class="featured_img">
                    <?php if($featured_course['cover_image'] != "" && file_exists(FCPATH.'uploads/courses/'.$featured_course['id'].'/'.$featured_course['cover_image'])) { ?>
                        <img class='big-img' src='<?php echo base_url('uploads/courses/'.$featured_course['id'].'/'.$featured_course['cover_image']); ?>' alt=''/>
                    <?php } else { ?>
                        <img class='big-img' src='<?php echo base_url('resources/frontend/images/book.png'); ?>' alt=''/>
                    <?php } ?>
                    <div class="btn_wrap">
                        <div class="featured_btn1"><?php echo $featured_course['qualification_type']; ?></div>
                        <div class="featured_btn2"><?php echo $featured_course['course_type']; ?></div>
                    </div><!--btn_wrap-->

                </div><!--featured_img-->
            </a>

            <p class="featured_name"><?php echo $featured_course['name']; ?></p>
            <p><i class="fa fa-book "></i> <?php echo $featured_course['college']; ?> &nbsp; <i class="fa fa-map-marker"></i> <?php echo $featured_course['location']; ?></p>
            <a href="<?php echo site_url('courses'); ?>" >SEE ALL</a>
        </div><!--featured_box-->
    <?php } ?>
    <!--------------Featured box end------------------->

    <!--------------Featured box start------------------->
    <?php if(!empty($featured_resume)) { ?>
        <div class="featured_box">
            <div class="featured_header">
                <p>Featured Personal Profile !</p>
            </div><!--featured_header-->
            <a href='<?php echo site_url('personalprofile/'.$featured_resume['id']); ?>'>
                <div class="featured_img">
                    <?php if($featured_resume['cover_image']!="" && $featured_resume['imgPath'] === TRUE ) { ?>
                        <img class='big-img' src='<?php echo site_url("uploads/resumes/cover_images/".$featured_resume["id"]."/".$featured_resume["cover_image"]); ?>' alt='' />
                    <?php } else { ?>
                        <img class='big-img' src='<?php echo base_url('resources/frontend/images/resume.png'); ?>' alt=''/>
                    <?php } ?>

                    <span class="featured_btn"><?php echo $featured_resume["experience"]; ?> YEAR<?php if($featured_resume["experience"] != 1) { ?>S<?php } ?></span>
                </div><!--featured_img-->
            </a>

            <p class="featured_name"><?php echo $featured_resume["name"]; ?></p>
            <p> <i class="fa fa-map-marker"></i> <?php echo $featured_resume["location"]; ?></p>
            <a href="<?php echo site_url('people'); ?>" >SEE ALL</a>
        </div><!--featured_box-->
    <?php } ?>
    <!--------------Featured box end------------------->

    <!--------------Featured box start------------------->
    <?php if(!empty($featured_company)) { ?>
        <div class="featured_box">
            <div class="featured_header">
                <p>Featured Company !
                </p>
            </div><!--featured_header-->
            <a href='<?php echo site_url("company/".$featured_company['id']); ?>'>
                <div class="featured_img">
                    <?php if($featured_company['cover_image']!="" && $featured_company['imgPath'] === TRUE) { ?>
                        <img class='big-img' src='<?php echo site_url("uploads/companies/cover_images/".$featured_company["id"]."/".$featured_company["cover_image"]); ?>' alt='' />
                    <?php } else { ?>
                        <img class='big-img' src='<?php echo base_url('resources/frontend/images/company.png'); ?>' alt=''/>
                    <?php } ?>
                    <div class="btn_wrap">
                        <div class="featured_btn1">EST. IN <?php echo $featured_company['foundation_year'];?></div>
                        <div class="featured_btn2"><?php echo $featured_company['jobs_count'];?> JOB<?php if($featured_company['jobs_count'] != 1) { ?>S<?php } ?></div>
                    </div><!--btn_wrap-->
                </div><!--featured_img-->
            </a>

            <p class="featured_name"><?php echo $featured_company['name']; ?></p>
            <p><i class="fa fa-map-marker"></i> <?php echo $featured_company['location'];?></p>
            <a href="<?php echo site_url('companies'); ?>" >SEE ALL</a>
        </div><!--featured_box-->
    <?php } ?>
    <!--------------Featured box end------------------->

    <!--------------Featured box start------------------->
    <?php if(!empty($featured_college)) { ?>
        <div class="featured_box">
            <div class="featured_header">
                <p>Featured College !
                </p>
            </div><!--featured_header-->
            <a href='<?php echo site_url('college/'.$featured_college['id']); ?>'>
                <div class="featured_img">
                    <?php if($featured_college['cover_image']!="" && $featured_college['imgPath'] === TRUE ) { ?>
                        <img class='big-img' src='<?php echo site_url("uploads/colleges/cover_images/".$featured_college["id"]."/".$featured_college["cover_image"]); ?>' alt='' />
                    <?php } else { ?>
                        <img class='big-img' src='<?php echo base_url('resources/frontend/images/college.png'); ?>' alt=''/>
                    <?php } ?>
                    <div class="btn_wrap">
                        <div class="featured_btn1">EST. IN <?php echo $featured_college['foundation_year']; ?></div>
                        <div class="featured_btn2"><?php echo $featured_college['courses_count']; ?> COURSE<?php if($featured_college['courses_count'] != 1) { ?>S<?php } ?></div>
                    </div><!--btn_wrap-->
                </div><!--featured_img-->
            </a>

            <p class="featured_name"><?php echo $featured_college['name']; ?></br><?php echo $featured_college['tag_line']; ?></p>
            <p><i class="fa fa-map-marker"></i> <?php echo $featured_college['location']; ?></p>
            <a href="<?php echo site_url('colleges'); ?>" >SEE ALL</a>
        </div><!--featured_box-->
    <?php } ?>
    <!--------------Featured box end------------------->

