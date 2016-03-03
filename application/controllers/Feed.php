<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('Embedly.php');

class Feed extends BaseController
{
	private $status;
	private $privacyType;
	private $postType;
    private $postedAs;
    private $postedAsId;
    private $share_post;
    private $share_post_id;
	
	private $Table;
	public $rootPath;

	public function __construct(){

		parent::__construct();

		$this->load->model(array('Post_model','Contact_model','Feed_model','Job_model','Course_model','Resume_model','Company_model','College_model','User_model'));
		
        if((bool)$this->session->userdata('user_id') ===  FALSE){
			$this->session->sess_destroy();
            redirect(base_url(),'refresh');  
        } else if($this->Feed_model->checkEntityExist($this->session->userdata('user_id'), 1) ===  FALSE){
			$this->session->sess_destroy();
            redirect(base_url(),'refresh');  
        }
		
		if (strpos($_SERVER['HTTP_HOST'], 'localhost/liilt-dl') !== FALSE) {
			$this->rootPath = $_SERVER['DOCUMENT_ROOT'].'/liilt-dl/';
		} else if (strpos($_SERVER['HTTP_HOST'], 'localhost/liilt') !== FALSE) {
			$this->rootPath = $_SERVER['DOCUMENT_ROOT'].'/liilt/';
		} else if (strpos($_SERVER['HTTP_HOST'], 'dynamologic.info') !== FALSE) {
			$this->rootPath = $_SERVER['DOCUMENT_ROOT'].'/liilt/';
		} else if (strpos($_SERVER['HTTP_HOST'], 'www.liilt.com') !== FALSE) {
			$this->rootPath = $_SERVER['DOCUMENT_ROOT'];
		}
		error_reporting(0);

		
		if(($this->session->userdata('posted_as') != false) && ($this->session->userdata('posted_as_id') != false)){
			
			$entityType = $this->session->userdata('posted_as');
			$entityID = $this->session->userdata('posted_as_id');
		
			$entityData = $this->Feed_model->getEntityData($entityID, $entityType);
			if(!$entityData){
				// Check if entity Data doesn't exist then set it to user profile
				$this->setAccountType('user', $this->session->userdata('user_id'));
			}
		} else {
			
			$this->session->set_userdata('posted_as', 1);
			$this->session->set_userdata('posted_as_id', $this->session->userdata('user_id'));
			
			$entityType = $this->session->userdata('posted_as');
			$entityID = $this->session->userdata('posted_as_id');
			
			$entityData = $this->Feed_model->getEntityData($entityID, $entityType);
			if(!$entityData){
				// Check if entity Data doesn't exist then set it to user profile
				$this->setAccountType('user', $this->session->userdata('user_id'));
			}
		}

	}

    public function home($debug = NULL){
		
		if(!$this->session->userdata('posted_as') && !$this->session->userdata('posted_as_id')){
			$this->session->set_userdata('posted_as', 1);
			$this->session->set_userdata('posted_as_id', $this->session->userdata('user_id'));
		}
		
		$offset = 0;
		$limit = 5;
		$counter = 0;
		
		$user_posts = $this->Feed_model->getNewsFeed();
		
		$posts = array();
			
		if(count($user_posts) > 0){
			
			$fav_colleges = array();
			$fav_companies = array();
			$my_contacts = array();
            
			$fav_colleges = $this->Feed_model->getFavouriteColleges($this->session->userdata('user_id'));
			
			$fav_companies = $this->Feed_model->getFavouriteCompanies($this->session->userdata('user_id'));
			
            $my_contacts = $this->Feed_model->getContactUsers($this->session->userdata('user_id'));
			
			foreach($user_posts as $record){
				
				if($counter > 5){
					break;
				}
				$counter = $counter + 1;
				
				$flag = false;
				
				if($record['boost_post'] == 1){
					// If sponsored post
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				}
				else if($record['posted_as'] == 1 && $record['user_id'] == $this->session->userdata('user_id')){	
					// If posted as his own profile
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 1 && in_array($record['user_id'], $my_contacts)){	
					// If posted by your contact as his own profile
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if(($record['posted_as'] == 2 || $record['posted_as'] == 3) && $record['user_id'] == $this->session->userdata('user_id')) {
					// If posted as his own company or college
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true || $this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 2 && in_array($record['posted_as_id'], $fav_companies)){
					// If posted by favourite companies
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 3 && in_array($record['posted_as_id'], $fav_colleges)){
					// If posted by favourite colleges
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				}
				
				//======================================================================================================
				
				if($flag === true) {
					
					$Analytics = $this->Feed_model->getAnalytics($record['id']);
					$post_views = $Analytics->post_views;
					$post_views = $post_views + 1;
					
					$this->Feed_model->updateAnalytics($record['id'], 'post_views', $post_views);
					
					//==========================================================
					
					if($record['share_post'] == 1){
						
						$shared_author = "";
						
						$shared_post_data = $this->Feed_model->getSharedPost($record['id']);
						
						if($shared_post_data != false){
							
							$entityData = $this->Feed_model->getEntityData($shared_post_data['posted_as_id'], $shared_post_data['posted_as']);
							
							if($entityData != false){
								if($shared_post_data['posted_as'] == 1){
									$shared_author = $entityData['full_name'];
								} else if($shared_post_data['posted_as'] == 2){
									$shared_author = $entityData['name'];
								} else if($shared_post_data['posted_as'] == 3){
									$shared_author = $entityData['name'];
								}
								$record['shared_author'] = $shared_author;
								$record['shared_status'] = $shared_post_data['post_status'];
							
							} else {
								$updateDbFieldsAry = array('share_post');
								$updateInfoAry = array('0');
								$this->Feed_model->updateInfo_Simple($record['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
								$record['share_post'] = 0;
							}
						} else {
							$updateDbFieldsAry = array('share_post');
							$updateInfoAry = array('0');
							$this->Feed_model->updateInfo_Simple($record['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
							$record['share_post'] = 0;
						}
					}
					
					//==========================================================
					
					$record['total_post_likes'] = $this->Feed_model->countPostLikes($record['id']);
					$record['is_post_liked'] = $this->Feed_model->checkPostlikedByActiveUser($this->session->userdata('user_id'), $record['id'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
					
					//==========================================================
					
					$PostComments = $this->Feed_model->getComments($record['id'],2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
					
					$record['total_post_comments'] = 0;
					
					$record['post_comments'] = false;
					
					if(!empty($PostComments)){
						
						$record['total_post_comments'] = $this->Feed_model->countPostComments($record['id']);
						
						foreach($PostComments as $comment){
							
							$comment['is_comment_liked'] = $this->Feed_model->checkCommentlikedByActiveUser($this->session->userdata('user_id'), $comment['id'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
							
							$comment['total_comment_likes'] = $this->Feed_model->countCommentLikes($comment['id']);
							
							if($comment['posted_as'] == 1){
								$record['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
								$record['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
								$record['post_comments'][] = $comment;
							}
						}
					}
					
					$posts[] = $record;
				}
			}
		}
		$data['user_posts'] = $posts;
		
		$totalposts = $this->totalPosts();
		
		$data['total_posts_counter'] = $totalposts['count'];	// Total Posts
		//echo '<pre>';print_r($data);exit;
		
		$data['sidebar'] = $this->sidebar();
		
		if($debug != NULL){
			
			
			echo $data['total_posts_counter'].'<br><br><br><br>';
			
			echo '<pre>';print_r($posts);exit;
			echo 'Colleges'.'<br>';
			echo '<pre>';print_r($fav_colleges);
			echo 'Companies'.'<br>';
			echo '<pre>';print_r($fav_companies);
			echo 'Contacts'.'<br>';
			echo '<pre>';print_r($my_contacts);
			echo 'Posts'.'<br>';
			echo '<pre>';print_r($posts);exit;
		}
		
        $data['_view'] = "feed/index";
        $this->load->view("layouts/frontend",$data);
    }
	
	public function test(){
		$totalposts = $this->totalPosts();
		
		$data['total_posts_counter'] = $totalposts['count'];	// Total Posts
		
		echo $data['total_posts_counter'];
		
		$this->Feed_model->test1();
	}
	
	public function totalPosts($debug = NULL){
		
		$user_posts = $this->Feed_model->countTotalPosts();
		
		$posts = array();
			
		if(count($user_posts) > 0){
			
			$fav_colleges = array();
			$fav_companies = array();
			$my_contacts = array();
            
			
			$fav_colleges = $this->Feed_model->getFavouriteColleges($this->session->userdata('user_id'));
			
			$fav_companies = $this->Feed_model->getFavouriteCompanies($this->session->userdata('user_id'));
			
            $my_contacts = $this->Feed_model->getContactUsers($this->session->userdata('user_id'));

			foreach($user_posts as $record){
				
				$flag = false;
				
				if($record['boost_post'] == 1){
					// If sponsored post
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				}
				else if($record['posted_as'] == 1 && $record['user_id'] == $this->session->userdata('user_id')){	
					// If posted as his own profile
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 1 && in_array($record['user_id'], $my_contacts)){	
					// If posted by your contact as his own profile
					if($this->Feed_model->checkEntityExist($record['user_id'],1) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if(($record['posted_as'] == 2 || $record['posted_as'] == 3) && $record['user_id'] == $this->session->userdata('user_id')) {
					// If posted as his own company or college
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true || $this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 2 && in_array($record['posted_as_id'], $fav_companies)){
					// If posted by favourite companies
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 3 && in_array($record['posted_as_id'], $fav_colleges)){
					// If posted by favourite colleges
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				}
				
				//======================================================================================================
				
				if($flag === true) {
					$posts[] = $record;
				}
			}
		}
		
		if($debug != NULL){
			echo count($posts);
			echo '<pre>';print_r($posts);
		} else {
			$response = array('posts' => $posts, 'count' => count($posts));
			return $response;
		}
	}
	
	public function sidebar(){

        // Featured Job
        $featured_job = $this->Job_model->get_featured(1,"id","RANDOM");

        if($featured_job['rc'])
            $data['featured_job'] = $featured_job['data'][0];
        else
            $data['featured_job'] = "";


        // Featured Course
        $featured_course = $this->Course_model->get_featured(1,"id","RANDOM");

        if($featured_course['rc'])
            $data['featured_course'] = $featured_course['data'][0];
        else
            $data['featured_course'] = "";

        // Featured Resume
        $featured_resume = $this->Resume_model->get_featured(1,"id","RANDOM");

        if($featured_resume['rc'])
        {
            $data['featured_resume'] = $featured_resume['data'][0];
            $imagePath = $this->rootPath . 'uploads/resumes/cover_images/'.$data['featured_resume']['id']."/".$data['featured_resume']['cover_image'];
            $data['featured_resume']['imgPath'] = (file_exists($imagePath) ? TRUE : FALSE );
        }
        else
        {
            $data['featured_resume'] = "";
        }

        // Featured Companies
        $featured_company = $this->Company_model->get_featured(1,"id","RANDOM");

        if($featured_company['rc'])
        {
            $data['featured_company'] = $featured_company['data'][0];
            $imagePath = $this->rootPath . 'uploads/resumes/cover_images/'.$data['featured_company']['id']."/".$data['featured_company']['cover_image'];
            $data['featured_company']['imgPath'] = (file_exists($imagePath) ? TRUE : FALSE );
        }
        else
        {
            $data['featured_company'] = "";
        }

        // Featured College
        $featured_college = $this->College_model->get_featured(1,"id","RANDOM");

        if($featured_college['rc'])
        {
            $data['featured_college'] = $featured_college['data'][0];
            $imagePath = $this->rootPath . 'uploads/resumes/cover_images/'.$data['featured_college']['id']."/".$data['featured_college']['cover_image'];
            $data['featured_college']['imgPath'] = (file_exists($imagePath) ? TRUE : FALSE );
        }
        else
        {
            $data['featured_college'] = "";
        }

        // Profile Image
        $data['profile_photo'] = $this->Feed_model->getProfilePhoto();
		
		// Get User's Data (ID, Name, Profile Photo, Profile Link) and Save it in session for future use.
		
		if($data['profile_photo']['profile_image'] != ''){
			$original_image = ($data['profile_photo']['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$data['profile_photo']['resume_id'].'/'.$data['profile_photo']['profile_image']) : $data['profile_photo']['profile_image'];
		} else {
			$original_image = site_url('uploads/resumes/profile_images/default.jpg');
		}
		
		$original_profileLink = base_url() . 'personalprofile/' . $data['profile_photo']['resume_id'];
		
        $data['full_name'] = $this->session->userdata('full_name');
		
		$original_full_name = $data['full_name'];
		$original_user_id = $this->session->userdata('user_id');
		
		$sessionData = array(
            'user_account_name' => $original_full_name,
            'user_account_image' => $original_image,
            'user_profile_link' => $original_profileLink,
            'user_posted_as' => 1,
            'user_posted_as_id' => $original_user_id
        );

        $this->session->set_userdata($sessionData);
		
        $sideBar = $this->load->view('feed/sidebar',$data,true);

        return $sideBar;

    }
	
	public function parseLink(){

		$pro = new Embedly\Embedly(array(
		    'key' => '1031d643911941cb97ceb4b9af7e85ec',
		    'user_agent' => 'Mozilla/5.0 (compatible; mytestapp/1.0)'
		));

		if(isset($_POST['url'])){
			$objs = $pro->extract(array(
			    'urls' => array(
			        $_POST['o_url']
			        
			    ),'width' => 250, 'height' => 150
			));
			
			
			//else if($type == 'html' && (($title != '' && $description != '') || ($title == '' && $description != '') || ($title != '' && $description == ''))){
				//$data['url_type'] = 'link';
			//}
			

			if(isset($objs[0]->media->type)) {
			    $link['media_type'] = $objs[0]->media->type;
			}
			else {
				$link['media_type'] = $objs[0]->type;
			}
			
			$link['title'] = (isset($objs[0]->title) ? $objs[0]->title : '');
			$link['original_url'] = (isset($objs[0]->original_url) ? $objs[0]->original_url : 'javascript:void(0)');
			$link['provider_url'] = (isset($objs[0]->provider_url) ? $objs[0]->provider_url : ''); 
			$link['image'] = (isset($objs[0]->images[0]->url) ? $objs[0]->images[0]->url : base_url() . 'uploads/news_feeds/no_image.jpg');
			$link['description'] = (isset($objs[0]->description) ? $objs[0]->description : '');
			$link['iframe'] =  (isset($objs[0]->media->html) ? $objs[0]->media->html : null );
			$link['type'] = 'link';
			$link['isUploaded'] = false;

			$type = $objs[0]->type;
			$title = $objs[0]->title;
			$description = $objs[0]->description;
			
			if($type == 'image'){
				$link['url_type'] = 'photo';
				
			} else if($type == 'html' && (isset($objs[0]->media->type) && $objs[0]->media->type == 'video')){
				$link['url_type'] = 'video';
			} else if($type == 'html' && (($title != '' && $description != '') || ($title == '' && $description != '') || ($title != '' && $description == ''))){
				$link['url_type'] = 'link';
			}
			
			$data = $link;
			$data['meta'] = htmlentities(json_encode($link));
			$data['url_type'] = $link['url_type'];
			
			$linkPost = $this->load->view('feed/preview',$data,true);
			
			
			echo json_encode(array('status' => true, 'view' => $linkPost, 'type' => $link['url_type'], 'message' => 'success' ));
		}
		else{
			echo json_encode(array('status' => false, 'message' => 'There is some problem' ));
		}

	}
	
	public function uploadImage(){

		//$targetFolder = $this->rootPath . 'uploads/news_feeds/temp'; // Relative to the root
		$targetFolder = 'uploads/news_feeds/temp'; // Relative to the root
		if (!empty($_FILES)) {

			$tempFile = $_FILES['Filedata']['tmp_name'];
			$targetPath = $targetFolder;

			$imageName = time() ."_". $_FILES['Filedata']['name'];
			$targetFile = rtrim($targetPath,'/') . '/' . $imageName;
			
			// Validate the file type
			$fileTypes = array('jpg','jpeg','gif','png','JPG','JPEG','GIF','PNG'); // File extensions
			$fileParts = pathinfo($imageName);
			
			if (in_array($fileParts['extension'],$fileTypes)) {
				$imageUrl = base_url() . 'uploads/news_feeds/temp/' . $imageName;
				if(move_uploaded_file($tempFile,$targetFile)){
						
					$data['meta'] = htmlentities(json_encode(array('imageName' => $imageName , 'type' => 'image' , 'ext' => $fileParts['extension'], 'post' => 'upload', 'isUploaded' => 1 )));
					$data['image'] = $imageUrl;
					$data['isUploaded'] = true;

					$imagePost = $this->load->view('feed/preview',$data,true);

					echo json_encode(array('code' => 200,'view' => $imagePost, 'image_name' => $imageName));
				} else {
					echo 'An Error occured';
				}

			} else {
				echo 'Invalid file type.';
			}

		}
	}
	
	public function setAccountType($userType = NULL, $userID = NULL){
		
		if($userType == NULL && $userID == NULL){
			$type = $this->input->post('type');
			$entityID = $this->input->post('id');
		} else {
			$type = $userType;
			$entityID = $userID;
		}
		
		if($type == 'user'){
			$entityType = 1;
		} else if($type == 'company'){
			$entityType = 2;
		} else if($type == 'college'){
			$entityType = 3;
		}
		
		$entityData = $this->Feed_model->getEntityData($entityID, $entityType);
		
		$posted_as = $entityType;				// 	Posted as
		$posted_as_id = $entityData['id'];		//	Posted as id
		$active_name = ($entityType == 1) ? $entityData['full_name'] : $entityData['name'];	// Active Name
		$active_image = site_url('uploads/resumes/profile_images/default.jpg');	// Active Image
		$active_link = base_url() . 'personalprofile/' . $this->session->userdata('id');
		
		if($entityType == 1){
			
			$data['profile_photo'] = $this->Feed_model->getProfilePhoto();
			
			if($data['profile_photo']['profile_image'] != ''){
				
				$active_image = ($data['profile_photo']['profile_photo_type'] == PROFILE_PHOTO) ? site_url('uploads/resumes/profile_images/'.$data['profile_photo']['resume_id'].'/'.$data['profile_photo']['profile_image']) : $data['profile_photo']['profile_image'];
			}
			$active_link = base_url() . 'personalprofile/' . $data['profile_photo']['resume_id'];
		} 
		else if($entityType == 2){
			if($entityData['logo'] != ''){
				$active_image = site_url('uploads/companies/logos/'.$entityData['id'].'/'.$entityData['logo']);
			}
			$active_link = base_url() . 'company/' . $entityData['id'];
		} 
		else if($entityType == 3){
			if($entityData['logo'] != ''){
				$active_image = site_url('uploads/colleges/logos/'.$entityData['id'].'/'.$entityData['logo']);
			}
			$active_link = base_url() . 'college/' . $entityData['id'];
		}
			
		$data = array(
            'posted_as' => $posted_as,
            'posted_as_id' => $posted_as_id,
            'account_name' => $active_name,
            'account_image' => $active_image,
            'account_link' => $active_link
        );
		
		$this->session->set_userdata($data);
		
		if($userType == NULL && $userID == NULL){
		
			$postResult = $this->Feed_model->getlikedPosts($posted_as, $posted_as_id);
			$commentResult = $this->Feed_model->getlikedComments($posted_as, $posted_as_id);
			
			echo json_encode(array('status' => true, 'posted_as' => $posted_as, 'posted_as_id' => $posted_as_id, 'active_name' => $active_name, 'active_image' => $active_image, 'active_link' => $active_link, 'postResult' => $postResult, 'commentResult' => $commentResult));
		
		}
	}
	
	//===========================================================================================================
	
	public function loadMore(){
		
		//echo '<pre>';print_r($_POST);exit;
		
		$display_count = $_POST['count'];
		$offset = $_POST['lastId'];
		
		//=============================================================
		
		$user_posts = $this->Feed_model->getNewsFeed(null,5,$offset);
		
		//echo '<pre>';print_r($user_posts);exit;
		
		$posts = array();
			
		$lastpost = "";
		
		if(count($user_posts) > 0){
			
			$fav_colleges = array();
			$fav_companies = array();
			$my_contacts = array();
            
			$fav_colleges = $this->Feed_model->getFavouriteColleges($this->session->userdata('user_id'));
			
			$fav_companies = $this->Feed_model->getFavouriteCompanies($this->session->userdata('user_id'));
			
            $my_contacts = $this->Feed_model->getContactUsers($this->session->userdata('user_id'));
			
			foreach($user_posts as $record){
				
				$flag = false;
				
				if($record['boost_post'] == 1){
					// If sponsored post
					$flag = true;
				}
				else if($record['posted_as'] == 1 && $record['user_id'] == $this->session->userdata('user_id')){	
					// If posted as his own profile
					$flag = true;
				} 
				else if($record['posted_as'] == 1 && in_array($record['user_id'], $my_contacts)){	
					// If posted by your contact as his own profile
					$flag = true;
				} 
				else if(($record['posted_as'] == 2 || $record['posted_as'] == 3) && $record['user_id'] == $this->session->userdata('user_id')) {
					// If posted as his own company or college
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true || $this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 2 && in_array($record['posted_as_id'], $fav_companies)){
					// If posted by favourite companies
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],2) === true){
						//Check if entity exists
						$flag = true;
					}
				} 
				else if($record['posted_as'] == 3 && in_array($record['posted_as_id'], $fav_colleges)){
					// If posted by favourite colleges
					if($this->Feed_model->checkEntityExist($record['posted_as_id'],3) === true){
						//Check if entity exists
						$flag = true;
					}
				}
				
				if($flag === true) {
					$Analytics = $this->Feed_model->getAnalytics($record['id']);
					$post_views = $Analytics->post_views;
					$post_views = $post_views + 1;
					
					$this->Feed_model->updateAnalytics($record['id'], 'post_views', $post_views);
					
					//==========================================================
					
					if($record['share_post'] == 1){
						
						$shared_author = "";
						
						$shared_post_data = $this->Feed_model->getSharedPost($record['id']);
						
						if($shared_post_data != false){
							
							$entityData = $this->Feed_model->getEntityData( $shared_post_data['posted_as_id'], $shared_post_data['posted_as']);
							
							if($entityData != false){
								if($shared_post_data['posted_as'] == 1){
									$shared_author = $entityData['full_name'];
								} else if($shared_post_data['posted_as'] == 2){
									$shared_author = $entityData['name'];
								} else if($shared_post_data['posted_as'] == 3){
									$shared_author = $entityData['name'];
								}
								$record['shared_author'] = $shared_author;
							} else {
								$updateDbFieldsAry = array('share_post');
								$updateInfoAry = array('0');
								$this->Feed_model->updateInfo_Simple($record['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
								$record['share_post'] = 0;
							}
						} else {
							$updateDbFieldsAry = array('share_post');
							$updateInfoAry = array('0');
							$this->Feed_model->updateInfo_Simple($record['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
							$record['share_post'] = 0;
						}
					}
					
					//==========================================================
					
					$record['total_post_likes'] = $this->Feed_model->countPostLikes($record['id']);
					$record['is_post_liked'] = $this->Feed_model->checkPostlikedByActiveUser($this->session->userdata('user_id'), $record['id'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
					//==========================================================
					
					$PostComments = $this->Feed_model->getComments($record['id'], 2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
					
					$record['post_comments'] = false;
					$record['total_post_comments'] = 0;
					
					if(!empty($PostComments)){
						
						$record['total_post_comments'] = $this->Feed_model->countPostComments($record['id']);
						
						foreach($PostComments as $comment){
							
							$comment['total_comment_likes'] = $this->Feed_model->countCommentLikes($comment['id']);
							$comment['is_comment_liked'] = $this->Feed_model->checkCommentlikedByActiveUser($this->session->userdata('user_id'), $comment['id'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
							
							if($comment['posted_as'] == 1){
								$record['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
								$record['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
								$record['post_comments'][] = $comment;
							}
						}
					}
					
					$lastpost = $record['id'];
					
					$posts[] = $record;
					
				}
			}
		}
		
		$view = "";
		
		if((bool)$posts > 0){
			foreach($posts as $post){
				$params['user_post'] = $post;
				$view .= $this->load->view('feed/post', $params, true);
			}
			
			
			$total_count = $this->totalPosts();
			$total_count = $total_count['count'];
			$display_count = $_POST['count'];
			$count = ($total_count - $display_count) - count($posts);
			
			echo json_encode(array('status' => true, 'count' => $count, 'view' => $view, 'lastpost' => $lastpost, 'message' => 'Success'));
		} else {
			echo json_encode(array('status' => false, 'message' => 'No more posts to show'));
		}
	}
	
	public function addPost(){
		
		//echo '<pre>';print_r($_POST);exit;
		
		$data['status'] = $this->input->post('statusText');
		$data['linkFlag'] = $this->input->post('linkFlag');
		$data['linkData'] = $this->input->post('linkdata');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['posted_as'] = $this->input->post('posted_as');
		$data['posted_as_id'] = $this->input->post('posted_as_id');
		$data['isUploaded'] = 0;
		
		if($data['linkFlag'] == 0){
			$data['post_type'] = 1;			// Status Post
			if(isset($_POST['linkdata'])){
				$data['post_type'] = 3;			// Photo Post (Uploaded)
				$data['isUploaded'] = 1;
			} else {
				$data['post_type'] = 1;			// Status Post
			}
			
		} else if($data['linkFlag'] == 1){
			$linkData = json_decode($data['linkData']);
			$type = $linkData->url_type;
			
			if($type == 'link'){
				$data['post_type'] = 2;			// Link Post
			} else if($type == 'photo'){
				$data['post_type'] = 3;			// Photo Post (Embedded)
			} else if($type == 'video'){
				$data['post_type'] = 4;			//	Video Post
			}
		}
		
		$data['post_id'] = "";
		
		date_default_timezone_set('GMT');
	
		$post_date = date('Y-m-d H:i:s');
		
		$insertDbFieldsAry = array('user_id','status','post_type','post_on','update_on','posted_as','posted_as_id','shared_post_id');
		$insertInfoAry = array($data['user_id'], $data['status'], $data['post_type'], $post_date, $post_date ,$data['posted_as'], $data['posted_as_id'],0);
		if($this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'posts')){
			$data['post_id'] = $this->db->insert_id();
		}
		
		if($data['linkFlag'] == 1){
			$linkData = json_decode($data['linkData']);
			
			$data['type'] = $linkData->url_type;
			
			if($data['type'] == 'video'){
				$data['media'] = $linkData->iframe;
			} else {
				$data['media'] = $linkData->image;
			}
			
			$data['url_type'] = $linkData->url_type;
			$data['title'] = $linkData->title;
			$data['description'] = $linkData->description;
			$data['original_url'] = $linkData->original_url;
			$data['provider_url'] = $linkData->provider_url;
			
			if($data['post_id'] != ''){
				
				$insertDbFieldsAry = array('post_id','title','description','original_url','provider_url','media','isUploaded');
				$insertInfoAry = array($data['post_id'], $data['title'], $data['description'], $data['original_url'], $data['provider_url'], $data['media'],'1');
				$this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'post_media');
				
			}
			
			//=====================================================================================================
				
		}
		// If Photo post (uploaded)
		if($data['isUploaded'] == 1){
			$linkData = json_decode($data['linkData']);
			if($data['post_id'] != ''){
				
				$data['media'] = $linkData->imageName;
				
				$insertDbFieldsAry = array('post_id','title','description','original_url','provider_url','media','isUploaded');
				$insertInfoAry = array($data['post_id'], '', '', '', '', $data['media'],'1');
				$this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'post_media');
				
				$targetPath = 'uploads/news_feeds/temp/'.$data['media'];
				if(file_exists($targetPath)){
					rename($targetPath,'uploads/news_feeds/images/'. $data['media']);
				}
				
			}
		}
		
		//==========================================================
		
		$user_post = $this->Feed_model->getPost($data['post_id']);
		
		if($user_post['share_post'] == 1){
			
			$shared_author = "";
			
			$shared_post_data = $this->Feed_model->getSharedPost($user_post['id']);
			
			if($shared_post_data != false){
				
				$entityData = $this->Feed_model->getEntityData( $shared_post_data['posted_as_id'], $shared_post_data['posted_as']);
				
				if($entityData != false){
					if($shared_post_data['posted_as'] == 1){
						$shared_author = $entityData['full_name'];
					} else if($shared_post_data['posted_as'] == 2){
						$shared_author = $entityData['name'];
					} else if($shared_post_data['posted_as'] == 3){
						$shared_author = $entityData['name'];
					}
					$user_post['shared_author'] = $shared_author;
				} else {
					$updateDbFieldsAry = array('share_post');
					$updateInfoAry = array('0');
					$this->Feed_model->updateInfo_Simple($user_post['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
					$user_post['share_post'] = 0;
				}
			} else {
				$updateDbFieldsAry = array('share_post');
				$updateInfoAry = array('0');
				$this->Feed_model->updateInfo_Simple($user_post['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
				$user_post['share_post'] = 0;
			}
		}
		
		
		$PostComments = $this->Feed_model->getComments($data['post_id'], 2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
		
		$user_post['post_comments'] = false;
		
		if(!empty($PostComments)){
			foreach($PostComments as $comment){
				if($comment['posted_as'] == 1){
					$user_post['post_comments'][] = $comment;
				} 
				else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
					$user_post['post_comments'][] = $comment;
				} 
				else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
					$user_post['post_comments'][] = $comment;
				}
			}
		}
		
		$data['user_post'] = $user_post;
		
		$view = $this->load->view('feed/post', $data, true);
		
		$count = $this->totalPosts();
		$count = $count['count'];
				
		echo json_encode(array('status' => true, 'count' => $count, 'view' => $view, 'message' => 'success' ));
		
	}
	
	public function deletePost(){
		
		$id = $_POST['id'];
		$lastId = $_POST['lastId'];
		$display_count = $_POST['count'];
		
		if($id != ''){
			
			$next = "";
			$foundFlag = false;
			
			$tp = $this->totalPosts();	// Total Posts
			
			//echo '<pre>';print_r($tp);exit;
		
			if(!empty($tp['posts'])){
				foreach($tp['posts'] as $post){
					if($foundFlag === true){
						$next = $post['id'];
						break;
					}
					if($lastId == $post['id']){
						$foundFlag = true;
					}
				}
			}
			
			
			if($this->Feed_model->deleteInfo_Simple($id, 'id', 'posts')){
				
				$this->Feed_model->deleteInfo_Simple($id, 'post_id', 'post_media');
				$this->Feed_model->deleteInfo_Simple($id, 'post_id', 'post_like');
				$this->Feed_model->deleteInfo_Simple($id, 'post_id', 'posts_shared');
				$this->Feed_model->deleteInfo_Simple($id, 'post_id', 'post_comment');
				
				//$this->deletePostMedia($PostImage);
				
				//==========================================================================
				$flag = false;
				$view = "";
				
				if($next != ""){
					
					$flag = true;
					
					$user_post = $this->Feed_model->getPost($next);
					
					$PostComments = $this->Feed_model->getComments($next, 2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
					
					$user_post['post_comments'] = false;
					
					if(!empty($PostComments)){
						foreach($PostComments as $comment){
							if($comment['posted_as'] == 1){
								$user_post['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
								$user_post['post_comments'][] = $comment;
							} 
							else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
								$user_post['post_comments'][] = $comment;
							}
						}
					}
					
					$data['user_post'] = $user_post;
					
					$view = $this->load->view('feed/post', $data, true);
				}
				
				//==========================================================================
				
					
				$total_count = $this->totalPosts();
				$total_count = $total_count['count'];
				$display_count = $_POST['count'];
				$count = ($total_count - $display_count) - 1;
				
				echo json_encode(array('status' => true, 'flag' => $flag, 'count' => $count, 'next' => $next, 'view' => $view, 'msg' => 'Post successfully deleted.'));
			}
			else {
				echo json_encode(array('status' => false , 'msg' => 'There is some problem.'));
			}
		} else {
			echo json_encode(array('status' => false , 'msg' => 'There is some problem.'));
		}
	}
	
	public function addComment(){
		
		$data['post_id'] = $_POST['id'];
		$data['user_id'] = $this->session->userdata('user_id');
		$data['comment'] = $_POST['comment'];
		$data['posted_as'] = $_POST['posted_as'];
		$data['posted_as_id'] = $_POST['posted_as_id'];
		
		
		date_default_timezone_set('GMT');
	
		$comment_date = date('Y-m-d H:i:s');
		
		$insertDbFieldsAry = array('post_id','user_id','comment','comment_on','posted_as','posted_as_id');
		$insertInfoAry = array($data['post_id'], $data['user_id'], $data['comment'], $comment_date, $data['posted_as'], $data['posted_as_id']);
		$this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'post_comment');
				
		$data['comment_id'] = $this->db->insert_id();
		
		//====================================
		
		$comments_count = $this->Feed_model->countPostComments($data['post_id']);
		
		$updateDbFieldsAry = array('total_post_comments');
		$updateInfoAry = array($comments_count);
		$this->Feed_model->updateInfo_Simple($data['post_id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
		
		if($comments_count == 0){
			$total_comments = " Comments";
		} else if($comments_count == 1) {
			$total_comments = " 1 Comment";
		} else if($comments_count > 1) {
			$total_comments = " ".$comments_count." Comments";
		}
		
		//====================================
		
		$data['comment'] = $this->Feed_model->getComment($data['comment_id']);
		
		$view = $this->load->view('feed/comment_listing', $data, true);
		
		echo json_encode(array('status' => true, 'view' => $view, 'message' => 'success', 'count' => $comments_count, 'count_text' => $total_comments  ));
		
	}
	
	public function addDetailedComment(){
		
		$data['comment'] = str_replace('<br/>','\n', $_POST['Comment']);
		$data['post_id'] = $_POST['ID'];
		
		date_default_timezone_set('GMT');
	
		$comment_date = date('Y-m-d H:i:s');
		
		$insertDbFieldsAry = array('post_id','user_id','comment','comment_on','posted_as','posted_as_id');
		$insertInfoAry = array($data['post_id'], $this->session->userdata('user_id'), $data['comment'], $comment_date, $_POST['posted_as'],$_POST['posted_as_id']);
		
		if($this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'post_comment')){
			
			$data['comment_id'] = $this->db->insert_id();
			
			//====================================
			
			$comments_count = $this->Feed_model->countPostComments($data['post_id']);
			
			$updateDbFieldsAry = array('total_post_comments');
			$updateInfoAry = array($comments_count);
			$this->Feed_model->updateInfo_Simple($data['post_id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
			
			//====================================
				
		
			$data['comment'] = $this->Feed_model->getComment($data['comment_id']);
			
			$mainCommentView = $this->load->view('feed/comment_listing', $data, true);
		
			$detailCommentView = $this->load->view('feed/commentbox_detail',$data,true);
			
			$comment = $this->Feed_model->getComments($data['post_id'],2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
            $data['user_post']['post_comments'] = $comment; 
			
			$TotalComments = $comments_count;
			
			if($comments_count == 0){
				$total_comments = " Comments";
			} else if($comments_count == 1) {
				$total_comments = " 1 Comment";
			} else if($comments_count > 1) {
				$total_comments = " ".$comments_count." Comments";
			}
			
			echo json_encode(array('status' => true, 'detailView' => $detailCommentView, 'mainView' => $mainCommentView, 'total' => $TotalComments, 'count_text' => $total_comments));
		} else {
			
			echo json_encode(array('status' => false));
		}
		
			
	}
	
	public function deleteComment(){

		if(isset($_POST['commentId'])){
			$id = $_POST['commentId'];

			if($this->Feed_model->deleteComment($id)){

				$comments_count = $this->Feed_model->countPostComments($_POST['postId']);
				
				$updateDbFieldsAry = array('total_post_comments');
				$updateInfoAry = array($comments_count);
				$this->Feed_model->updateInfo_Simple($_POST['postId'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
				
				if($comments_count == 0){
					$total_comments = " Comments";
				} else if($comments_count == 1) {
					$total_comments = " 1 Comment";
				} else if($comments_count > 1) {
					$total_comments = " ".$comments_count." Comments";
				}
				
				
                $comments = $this->Feed_model->getComments($_POST['postId'],2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
				
				$view = "";
				
				if($comments !== false){
					foreach($comments as $comment){
						$params['comment'] = $comment;
						$view .= $this->load->view('feed/comment_listing', $params,true);
					}
				}
				
                echo json_encode(array('status' => true, 'view' => $view, 'count' => $comments_count, 'count_text' => $total_comments ));
            }
			else {
				echo json_encode(array('status' => false , 'msg' => 'There is some problem.'));
			}
				
		}
	}
	
	public function deleteDetailedComment(){
		
		$comment = $_POST['comment'];
		$post = $_POST['post'];
		
		if($this->Feed_model->deleteComment($comment)){
			
			$TotalComments = $this->Feed_model->countPostComments($post);
			
			$updateDbFieldsAry = array('total_post_comments');
			$updateInfoAry = array($TotalComments);
			$this->Feed_model->updateInfo_Simple($post, 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
			
			//========================================================================================================
			
			if($TotalComments == 0){
				$total_comments = " Comments";
			} else if($TotalComments == 1) {
				$total_comments = " 1 Comment";
			} else if($TotalComments > 1) {
				$total_comments = " ".$TotalComments." Comments";
			}
			
			$comments = $this->Feed_model->getComments($_POST['post'],2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
				
			$view = "";
			
			if($comments !== false){
				foreach($comments as $comment){
					$params['comment'] = $comment;
					$view .= $this->load->view('feed/comment_listing', $params,true);
				}
			}
			
			echo json_encode(array('status' => true, 'view' => $view, 'count' => $TotalComments, 'count_text' => $total_comments , 'msg' => 'Comment deleted successfully'));
		}
		else {
			
			echo json_encode(array('status' => false , 'msg' => 'There is some problem.'));
		}
	}
	
	public function sharePost(){
		
		$data['postID'] = $_POST['postId'];
		$data['user_id'] = $this->session->userdata('user_id');
		$data['posted_as'] = $_POST['posted_as'];
		$data['posted_as_id'] = $_POST['posted_as_id'];
		$data['share_status'] = $_POST['share_status'];
		
		$user_post = $this->Feed_model->getPost($data['postID']);
		
		
		date_default_timezone_set('GMT');
	
		$post_date = date('Y-m-d H:i:s');
		
		$insertDbFieldsAry = array('user_id','status','post_type','post_on','update_on','share_post','posted_as','posted_as_id','shared_post_id');
		$insertInfoAry = array($data['user_id'], $user_post['status'], $user_post['post_type'], $post_date, $post_date, '1',$data['posted_as'], $data['posted_as_id'],$data['postID']);
		if($this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'posts')){
			$data['post_id'] = $this->db->insert_id();
		}
		
		//============================================================================================
		
		if($user_post['post_type'] != 1){
				
			$insertDbFieldsAry = array('post_id','title','description','original_url','provider_url','media','isUploaded');
			$insertInfoAry = array($data['post_id'], $user_post['title'], $user_post['description'], $user_post['original_url'], $user_post['provider_url'], $user_post['media'], $user_post['isUploaded']);
			$this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'post_media');
			
			if($user_post['post_type'] == 3 && $user_post['isUploaded'] == 1){
				$targetPath = 'uploads/news_feeds/images/' . $user_post['media'];
				if(file_exists($targetPath)){

					$new_media = time() . "_" . $user_post['media'];
					//if(!copy($targetPath,$this->rootPath . 'uploads/news_feeds/images/' . $data->imageName))
					if(copy($targetPath,'uploads/news_feeds/images/' . $new_media)){
						$updateDbFieldsAry = array('media');
						$updateInfoAry = array($new_media);
						$this->Feed_model->updateInfo_Simple($data['post_id'],'post_id',$insertDbFieldsAry, $insertInfoAry, 'post_media');
					}
						
				}
			}
			
		}
		
		//============================================================================================
		
		
		$insertDbFieldsAry = array('post_id','shared_post_id','shared_posted_as','shared_posted_as_id','post_status','shared_status');
		$insertInfoAry = array($data['post_id'], $data['postID'], $user_post['posted_as'], $user_post['posted_as_id'], $data['share_status'], $user_post['status']);
		$this->Feed_model->addInfo_Simple($insertDbFieldsAry, $insertInfoAry, 'posts_shared');
		
		//============================================================================================
		
		$user_post = $this->Feed_model->getPost($data['post_id']);
		
		//============================================================================================
		
		if($user_post['share_post'] == 1){
						
			$shared_author = "";
			
			$shared_post_data = $this->Feed_model->getSharedPost($user_post['id']);
			
			if($shared_post_data != false){
				
				$entityData = $this->Feed_model->getEntityData( $shared_post_data['posted_as_id'], $shared_post_data['posted_as']);
				
				if($entityData != false){
					if($shared_post_data['posted_as'] == 1){
						$shared_author = $entityData['full_name'];
					} else if($shared_post_data['posted_as'] == 2){
						$shared_author = $entityData['name'];
					} else if($shared_post_data['posted_as'] == 3){
						$shared_author = $entityData['name'];
					}
					$user_post['shared_author'] = $shared_author;
				} else {
					$updateDbFieldsAry = array('share_post');
					$updateInfoAry = array('0');
					$this->Feed_model->updateInfo_Simple($user_post['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
					$user_post['share_post'] = 0;
				}
			} else {
				$updateDbFieldsAry = array('share_post');
				$updateInfoAry = array('0');
				$this->Feed_model->updateInfo_Simple($user_post['id'], 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
				$user_post['share_post'] = 0;
			}
		}
		
		$Analytics = $this->Feed_model->getAnalytics($data['post_id']);
		$post_views = $Analytics->post_views;
		$post_views = $post_views + 1;
		
		$this->Feed_model->updateAnalytics($data['post_id'], 'post_views', $post_views);
		
		//==========================================================
		
		$PostComments = $this->Feed_model->getComments($data['post_id'], 2,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
		
		$user_post['post_comments'] = false;
		
		if(!empty($PostComments)){
			foreach($PostComments as $comment){
				if($comment['posted_as'] == 1){
					$user_post['post_comments'][] = $comment;
				} 
				else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
					$user_post['post_comments'][] = $comment;
				} 
				else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
					$user_post['post_comments'][] = $comment;
				}
			}
		}
		
		$data['user_post'] = $user_post;
		
		//============================================================================================
		
		$view = $this->load->view('feed/post', $data, true);
		
		$tp = $this->Feed_model->getTotalPosts();	// Total Posts
			
		$count = count($tp);
		
		echo json_encode(array('status' => true, 'count' => $count, 'post' => $view, 'message' => 'success' ));
	}
	
    public function boostPost(){

        $this->load->library('Stripe_Api');
        $stripeApi = new Stripe_Api;

        $params = array(
            "amount" => STRIPE_BOOST_POST_CHARGE,
            "currency" => STRIPE_CURRENCY,
            "source" => $_POST['token'],
            "description" => "Charge for ".$this->session->userdata('email'),
            "receipt_email" => $this->session->userdata('email')
        );

        // create the user card used to charge amount
        $stripeResponse = $stripeApi->create_charge($params);

		$posted_as = $_POST['posted_as'];
		$posted_as_id = $_POST['posted_as_id'];
		
        if(isset($_POST['post_id']) && $stripeResponse['status'] === 'succeeded'){
            if($this->Feed_model->boostPost($_POST['post_id'], $posted_as, $posted_as_id))
			
				$sharedData = $this->Feed_model->getSingleRow_OneArgument($_POST['post_id'], 'post_id', 'posts_shared');
				if($sharedData !== false){
					$this->Feed_model->deleteInfo_Simple($sharedData['id'], 'id', 'posts_shared');
				}
				
				
				$entityData = $this->Feed_model->getEntityData($posted_as_id, $posted_as);
				
				$sponsor = "";
					
				if($entityData !== false){
					if($posted_as == 1){
						$sponsor = $entityData['full_name'];
					} else {
						$sponsor = $entityData['name'];
					}
				}
				
				
                echo json_encode(array('status' => true, 'code' => 200 , 'msg' => 'Post Boosted Successfully', 'name' => $sponsor ));
        }else{
            echo json_encode(array('status' => false, 'code' => 202 , 'msg' => 'Some Error has occured!' ));
        }
    }
	
	public function likePost(){

		if(isset($_POST['postId'])){

			$likes_count = $this->Feed_model->likePost($_POST['postId'], $_POST['posted_as'], $_POST['posted_as_id']);
			
			$userlike = $this->Feed_model->checkPostlikedByActiveUser($this->session->userdata('user_id'), $_POST['postId'], $_POST['posted_as'], $_POST['posted_as_id']);
			
			if($userlike){
				$buttonText = "Liked";
			} else {
				$buttonText = "Like";
			}
			
			if($likes_count == 0){
				$total_likes = "(0 Likes)";
			} else if($likes_count == 1) {
				$total_likes = "(1 Like)";
			} else if($likes_count > 1) {
				$total_likes = "(".$likes_count." Likes)";
			}
		
			echo json_encode(array('status' => true, 'msg' => 'Post liked/unliked successfully' , 'count' => $likes_count, 'likes_text' => $total_likes, 'button_text' => $buttonText));
		} else {
			echo json_encode(array('status' => false, 'msg' => 'There is some problem.'));
		}	
	}
	
	public function likeComment(){

		if(isset($_POST['comment'])){

			$likes_count = $this->Feed_model->likeComment($_POST['comment'], $_POST['posted_as'], $_POST['posted_as_id']);
		
			$userlike = $this->Feed_model->checkCommentlikedByActiveUser($this->session->userdata('user_id'), $_POST['comment'], $_POST['posted_as'], $_POST['posted_as_id']);
			
			if($userlike){
				$buttonText = "Liked";
			} else {
				$buttonText = "Like";
			}
			
			$total_likes = "";
			
			echo json_encode(array('status' => true, 'msg' => 'Comment liked/unliked successfully' , 'count' => $likes_count, 'likes_text' => $total_likes, 'buttonText' => $buttonText));
		} else {
			echo json_encode(array('status' => false, 'msg' => 'There is some problem.'));
		}	
	}
	
	public function postDetail(){
		
		$data['PostID'] = $_POST['ID'];
		
		//===========================================================================================================
		
		// Update Analytics Data
		
		$AnalyticsData = $this->Feed_model->getAnalytics($data['PostID']);	// Get Analytics Data
		
		$post_views = $AnalyticsData->post_views;		// Post Views
		$post_clicks = $AnalyticsData->post_clicks;		// Post Clicks
		
		$data['post_views'] = $post_views + 1;					// Increament Post Views
		$data['post_clicks'] = $post_clicks + 1;				// Increament Post Clicks
		
		$this->Feed_model->updateAnalytics($data['PostID'], 'post_views', $data['post_views']);		// Update Post Views
		$this->Feed_model->updateAnalytics($data['PostID'], 'post_clicks', $data['post_clicks']);	// Update Post Clicks
		
		
		//===========================================================================================================
		
		$PostData = $this->Feed_model->getPost($data['PostID']);
		
		//echo '<pre>';print_r($PostData);exit;
		
		$data['post_author'] = $PostData['full_name'];
		$data['post_resume_id'] = $PostData['resume_id'];
		$data['post_author_photo'] = $PostData['profile_image'];
		$data['post_on'] = $PostData['post_on'];
		$data['update_on'] = $PostData['update_on'];
		$data['post_type'] = $PostData['post_type'];
		$data['media'] = $PostData['media'];
		$data['boost_post'] = $PostData['boost_post'];
		
		
		$data['company_id'] = $PostData['company_id'];
		$data['company_name'] = $PostData['company_name'];
		$data['company_image'] = $PostData['company_image'];
		
		$data['college_id'] = $PostData['college_id'];
		$data['college_name'] = $PostData['college_name'];
		$data['college_image'] = $PostData['college_image'];
		
		$data['posted_as'] = $PostData['posted_as'];
		$data['posted_as_id'] = $PostData['posted_as_id'];
		$data['profile_photo_type'] = $PostData['profile_photo_type'];
		
		// Time passed
		
		date_default_timezone_set('GMT');
		
		$date_a = new DateTime($data['update_on']);
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
		
		$data['time_passed'] = $time_passed;
		
		$data['post_status'] = $PostData['status'];
		$data['post_title'] = $PostData['title'];
		$data['post_original_url'] = $PostData['original_url'];
		$data['post_provider_url'] = $PostData['provider_url'];
		$data['post_media'] = $PostData['media'];
		$data['isUploaded'] = $PostData['isUploaded'];
		$data['is_post_liked'] = $this->Feed_model->checkPostlikedByActiveUser($this->session->userdata('user_id'), $data['PostID'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
		
		if($PostData['post_type'] == 1){
			$data['post_description'] = $PostData['status'];		// if simple post
		} else {
			$data['post_description'] = $PostData['description'];	// if other post
		}
		
		$description_len = strlen($data['post_description']);
		
		if($description_len > 100){
			$excerpt = substr($data['post_description'], 0, 100);
			$excerpt .= " . . . <span class='liilt_color cursor_class' onclick = 'seemore_postDetailStatus(".$data['PostID'].")'>See More</span>";
			$data['post_excerpt'] = $excerpt;
			$data['post_showmore'] = 1;
		} else {
			$data['post_excerpt'] = '';
			$data['post_showmore'] = 0;
		}
		
		$data['post_comments'] = $this->Feed_model->countPostComments($data['PostID']);
		$data['post_likes'] = $this->Feed_model->countPostLikes($data['PostID']);
		
		$PostComments = $this->Feed_model->getComments($data['PostID'],null,$this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
		
		$data['PostComments'] = false;
		
		if(!empty($PostComments)){
			foreach($PostComments as $comment){
				
				$comment['likes'] = $this->Feed_model->countCommentLikes($comment['id']);
				$comment['is_comment_liked'] = $this->Feed_model->checkCommentlikedByActiveUser($this->session->userdata('user_id'), $comment['id'], $this->session->userdata('posted_as'),$this->session->userdata('posted_as_id'));
				
				if($comment['posted_as'] == 1){
					$data['PostComments'][] = $comment;
				} 
				else if($comment['posted_as'] == 2 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],2) === true){
					$data['PostComments'][] = $comment;
				} 
				else if($comment['posted_as'] == 3 && $this->Feed_model->checkEntityExist($comment['posted_as_id'],3) === true){
					$data['PostComments'][] = $comment;
				}
			}
		}
		
		//echo '<pre>';print_r($data);exit;
		
		$viewData = $this->load->view('feed/post_detail_modal', $data, true);
			
		$response = array('view' => $viewData, 'status' => true, 'message' => 'Success', 'time_passed' => $time_passed);
		
		echo json_encode($response);
	}
	
	public function getCommentLikesBox(){
		
		//echo '<pre>';print_r($_POST);exit;
		
		$commentID = $this->input->post('commentID');
		
		$data['likes'] = $this->Feed_model->getCommentLikes($commentID);
		$data['heading'] = "Likes for this comment";
		$data['type'] = "comment";
		
		//echo '<pre>';print_r($data['likes']);exit;
		$viewData = $this->load->view('feed/likes_modal', $data, true);
		$message = "Success";
		$status = true;
		
		$response = array('view' => $viewData, 'status' => $status, 'message' => $message);
		
		echo json_encode($response);
	}
	
	public function getPostLikesBox(){
		
		$postID = $this->input->post('postID');
		
		$data['likes'] = $this->Feed_model->getLikes($postID);
		$data['heading'] = "Likes for this post";
		$data['type'] = "post";
		
		$viewData = $this->load->view('feed/likes_modal', $data, true);
		$message = "Success";
		$status = true;
		
		$response = array('view' => $viewData, 'status' => $status, 'message' => $message);
		
		echo json_encode($response);
		
		//echo json_encode(array('status' => true , 'view' => $likeView));
	}
	
    //===========================================================================================================
	
	public function openShareModal(){
		
		$postID = $_POST['postID'];
		
		$data['user_post'] = $this->Feed_model->getPost($postID);
		
		//echo '<pre>';print_r($data['user_post']);exit;
		
		$view = $this->load->view('feed/share_modal', $data, true);
		
		$response = array('view' => $view, 'status' => true);
		
		echo json_encode($response);
	}
	
	public function openBoostModal(){
		
		$data['post_id'] = $_POST['postID'];
		
		$user_id = $this->session->userdata('user_id');
		$author_id = $this->Feed_model->getPostAuthor($data['post_id']);
		$view = "";
		
		if($user_id == $author_id){
			
			$view = $this->load->view('feed/boost_modal', $data, true);
			$message = "Success";
			$status = true;
			
		} else {
			$message = 'You can only sponsor your own posts !!!';
			$status = false;
		}
		
		$response = array('view' => $view, 'status' => $status, 'message' => $message);
		
		echo json_encode($response);
	}
	
	public function profileTypeModal(){
		
		$type = $_POST['type'];

		$data['profile_type'] = $this->Feed_model->getProfiles($type);
		if($type == "company") {
			$data['type'] = 2;
		}
		else if($type == "college") {
			$data['type'] = 3;
		}

		$data['type_name'] = $type;

        $view = $this->load->view('feed/profile_type_modal', $data, true);
		
		$response = array('view' => $view, 'status' => true);
		
		echo json_encode($response);
	}
	
	public function statusMessage(){
		
		$data['status'] = $_POST['status'];
		$data['message'] = $_POST['message'];
		
		$view = $this->load->view('feed/status_message', $data, true);
		echo json_encode(array('view' => $view));

	}
	
}

?>
