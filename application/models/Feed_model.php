<?php if(!defined('BASEPATH')) exit("No direct access allowed");

class Feed_model extends CI_Model
{
	
	private $table = 'posts';
	private $postMediaTable = 'post_media';
	private $likeTable = 'post_like';
	private $PK = 'id';
	private $postTableFK = 'post_id';
	private $commentTable = 'post_comment';
	private $commentTableFK = 'user_id';
	private $userTable = 'users';
	private $friendTable = 'user_has_friends';
	private $postLinkTable = 'user_post_link';
	private $postImageTable = 'user_post_image';
    private $resumeTable = 'resumes';
    private $boostTable = 'boost_post';
    private $postCommentLike = 'post_comment_likes';
    private $companyTable = 'companies';
    private $collegeTable = 'colleges';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	|--------------------------------------------------------------------------
	| Get Functions
	|--------------------------------------------------------------------------
	*/
	
	public function getNewsFeed($offset = null , $limit = null, $last_id = null ){

		$user_id = $this->session->userdata('user_id');
			
		$favouriteCompanies = $this->getFavouriteCompaniesUsers($user_id);
		$favouriteColleges = $this->getFavouriteCollegeUsers($user_id);
		$contacts = $this->getContactUsers($user_id);		
		
			
		$query = "SELECT co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image,"
		."r.id as resume_id, r.profile_image, r.profile_photo_type, u.full_name,"
		."up.*, title, original_url, upm.description, provider_url, media, isUploaded, count(distinct(pl.id)) as likes, count(distinct(pc.id)) as comments,"
		."(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post "
		."FROM posts up "
		."LEFT JOIN companies co on co.id = up.posted_as_id "
		."LEFT JOIN colleges cl on cl.id = up.posted_as_id "
		."LEFT JOIN resumes r ON r.user_id = up.user_id "
		."LEFT JOIN users u ON up.user_id = u.id "
		."LEFT JOIN post_media upm ON up.id = upm.post_id "
		."LEFT JOIN post_like pl ON up.id = pl.post_id "
		."LEFT JOIN post_comment pc ON up.id = pc.post_id ";
		//."LEFT JOIN user_has_friends uf ON up.user_id = uf.request_sent_to ";
		
		
		$query .= "WHERE ((up.user_id = '$user_id') OR up.boost_post = '1'";
		
		if(!empty($contacts)){	// If posted by contacts
			$contacts = implode(',', $contacts);
			$query .= " OR up.user_id IN ($contacts)";
		}
		
		if(!empty($favouriteCompanies)){	// If posted by favourite Companies
			$favouriteCompanies = implode(',', $favouriteCompanies);
			$query .= " OR up.user_id IN ($favouriteCompanies)";
		}
		
		if(!empty($favouriteColleges)){	// If posted by favourites Colleges
			$favouriteColleges = implode(',', $favouriteColleges);
			$query .= " OR up.user_id IN ($favouriteColleges)";
		}
		
		if($last_id != null){
			$query .= ") AND up.id < $last_id";
		} else {
			$query .= ")";
		}
		
		
		$query .= " GROUP BY up.id ORDER BY update_on DESC";

		if($limit != NULL){
			$query .= " LIMIT 5";
		}
		
		$result = $this->db->query($query);
		
		//echo '<pre>';print_r($result->result_array());exit;
		
		return $result->result_array();

	}
	
	public function getTotalPosts(){

		$user_id = $this->session->userdata('user_id');
			
		$favourites = $this->getFavouriteUsers($user_id);
		$contacts = $this->getContactUsers($user_id);
			
		$query = "SELECT id FROM posts up WHERE (up.user_id = '$user_id') OR up.boost_post = '1'";

		if(!empty($contacts)){	// If posted by contacts
			$contacts = implode(',', $contacts);
			$query .= " OR up.user_id IN ($contacts)";
		}
		
		if(!empty($favourites)){	// If posted by favourites
			$favourites = implode(',', $favourites);
			$query .= " OR up.user_id IN ($favourites)";
		}
		
		$query .= " ORDER BY update_on DESC";
		
		$result = $this->db->query($query);
		
		return $result->result_array();

	}
	
	public function countTotalPosts($last_id = NULL){

		$user_id = $this->session->userdata('user_id');
			
		$favouriteCompanies = $this->getFavouriteCompaniesUsers($user_id);
		$favouriteColleges = $this->getFavouriteCollegeUsers($user_id);
		$contacts = $this->getContactUsers($user_id);		
		
			
		$query = "SELECT co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image,"
		."r.id as resume_id, r.profile_image, r.profile_photo_type, u.full_name,"
		."up.*, title, original_url, upm.description, provider_url, media, isUploaded, count(distinct(pl.id)) as likes, count(distinct(pc.id)) as comments,"
		."(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post "
		."FROM posts up "
		."LEFT JOIN companies co on co.id = up.posted_as_id "
		."LEFT JOIN colleges cl on cl.id = up.posted_as_id "
		."LEFT JOIN resumes r ON r.user_id = up.user_id "
		."LEFT JOIN users u ON up.user_id = u.id "
		."LEFT JOIN post_media upm ON up.id = upm.post_id "
		."LEFT JOIN post_like pl ON up.id = pl.post_id "
		."LEFT JOIN post_comment pc ON up.id = pc.post_id ";
		//."LEFT JOIN user_has_friends uf ON up.user_id = uf.request_sent_to ";
		
		
		$query .= "WHERE ((up.user_id = '$user_id') OR up.boost_post = '1'";
		
		if(!empty($contacts)){	// If posted by contacts
			$contacts = implode(',', $contacts);
			$query .= " OR up.user_id IN ($contacts)";
		}
		
		if(!empty($favouriteCompanies)){	// If posted by favourite Companies
			$favouriteCompanies = implode(',', $favouriteCompanies);
			$query .= " OR up.user_id IN ($favouriteCompanies)";
		}
		
		if(!empty($favouriteColleges)){	// If posted by favourites Colleges
			$favouriteColleges = implode(',', $favouriteColleges);
			$query .= " OR up.user_id IN ($favouriteColleges)";
		}
		
		$query .= ")";
		
		$query .= " GROUP BY up.id ORDER BY update_on DESC";

		$result = $this->db->query($query);
		
		//echo '<pre>';print_r($result->result_array());exit;
		
		return $result->result_array();

	}
	
	public function getPost($postId){
    	$user_id = $this->session->userdata('user_id');

        $this->db->select("co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image ,r.id as resume_id,r.profile_image,r.profile_photo_type,u.full_name, up.*, title , original_url , upm.description , provider_url, media, isUploaded , count(distinct(pl.id)) as likes,count(distinct(pc.id)) as comments,(case when (pl.user_id = up.user_id ) THEN 'Unlike' ELSE 'Like' END) as user_like,(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post",false);
        $this->db->from($this->table . ' up');
        $this->db->join($this->userTable . ' u','up.user_id = u.id','left');
        $this->db->join($this->postMediaTable . ' upm','up.id = upm.post_id','left');
        $this->db->join($this->likeTable . ' pl','up.id = pl.post_id','left');
        $this->db->join($this->commentTable . ' pc','up.id = pc.post_id','left');
        $this->db->join($this->friendTable . ' uf','up.user_id = uf.request_sent_to','left');
        $this->db->join($this->resumeTable . ' r','r.user_id = up.user_id','left');
        $this->db->join($this->companyTable . ' co','up.posted_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' cl','cl.id = up.posted_as_id','left');
        $this->db->where('up.id',$postId);
        $result = $this->db->get();

        return $result->row_array();
    }

	public function getAnalytics($id){

		$this->db->select('post_views,post_clicks');
		$this->db->from($this->table);
		$this->db->where('id', $id);

		$result = $this->db->get();

		return $result->row();

	}
	
	public function getUser($Id){
		
		$this->db->select('*');
		$this->db->from('users');
	    $this->db->where('id',$Id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row();
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
	}
	
	public function getCompany($Id){
		
		$this->db->select('*');
		$this->db->from('companies');
	    $this->db->where('id',$Id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row();
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
	}
	
	public function getCollege($Id){
		
		$this->db->select('*');
		$this->db->from('colleges');
	    $this->db->where('id',$Id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row();
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
	}
	
	public function getFavouriteUsers($id){
		$this->db->select('*');
		$this->db->from('favourites');
	    $this->db->where('user_id',$id);

		$resultSet = $this->db->get();
		
		$returnValue = array();
			
		if ($resultSet->result_id->num_rows > 0) {
			$favourites = $resultSet->result();
			
			//echo '<pre>';print_r($favourites);exit;
			foreach($favourites as $favourite){
				if($favourite->entity_type_id == 2){
					$collegeData = $this->getCollege($favourite->entity_id);
					if($collegeData != false){
						$returnValue[] = $collegeData->user_id;
					}
				}
				else if($favourite->entity_type_id == 4){
					$companyData = $this->getCompany($favourite->entity_id);
					if($companyData != false){
						$returnValue[] = $companyData->user_id;
					}
				}
			}
		}
		
		return $returnValue;
	}
	
	public function getFavouriteCompanies($id){
		$this->db->select('companies.id');
		$this->db->from('favourites');
		$this->db->join('companies', 'favourites.entity_id = companies.id');
	    $this->db->where('favourites.user_id',$id);
	    $this->db->where('entity_type_id',4);

		$resultSet = $this->db->get();
		
		$returnValue = array();
			
		if ($resultSet->result_id->num_rows > 0) {
			
			foreach($resultSet->result_array() as $fav){
				$returnValue[] = $fav['id'];
			}
			
			return $returnValue;
		}
		
		return false;
	}
	
	public function getFavouriteCompaniesUsers($id){
		$this->db->select('companies.user_id');
		$this->db->from('favourites');
		$this->db->join('companies', 'favourites.entity_id = companies.id');
	    $this->db->where('favourites.user_id',$id);
	    $this->db->where('entity_type_id',4);

		$resultSet = $this->db->get();
		
		$returnValue = array();
			
		if ($resultSet->result_id->num_rows > 0) {
			
			foreach($resultSet->result_array() as $fav){
				$returnValue[] = $fav['user_id'];
			}
			
			return $returnValue;
		}
		
		return false;
	}
	
	public function getFavouriteColleges($id){
		$this->db->select('colleges.id');
		$this->db->from('favourites');
		$this->db->join('colleges', 'favourites.entity_id = colleges.id');
	    $this->db->where('favourites.user_id',$id);
	    $this->db->where('entity_type_id',2);

		$resultSet = $this->db->get();
		
		$returnValue = array();
			
		if ($resultSet->result_id->num_rows > 0) {
			
			foreach($resultSet->result_array() as $fav){
				$returnValue[] = $fav['id'];
			}
			
			return $returnValue;
		}
		
		return false;
	}
	
	public function getFavouriteCollegeUsers($id){
		$this->db->select('colleges.user_id');
		$this->db->from('favourites');
		$this->db->join('colleges', 'favourites.entity_id = colleges.id');
	    $this->db->where('favourites.user_id',$id);
	    $this->db->where('entity_type_id',2);

		$resultSet = $this->db->get();
		
		$returnValue = array();
			
		if ($resultSet->result_id->num_rows > 0) {
			
			foreach($resultSet->result_array() as $fav){
				$returnValue[] = $fav['user_id'];
			}
			
			return $returnValue;
		}
		
		return false;
	}
	
	public function getFavourites($userID, $type){
		$this->db->select('entity_id');
		$this->db->from('favourites');
	    $this->db->where('user_id',$userID);
	    $this->db->where('entity_type_id',$type);

		$resultSet = $this->db->get();
		if ($resultSet->result_id->num_rows > 0) {
			$favourites = $resultSet->result_array();
		} else {
			$favourites = false;
		}
		return $favourites;
	}
	
	public function getContacts($userID){
		$this->db->select('request_sent_from');
		$this->db->from('user_has_friends');
	    $this->db->where('request_sent_to',$userID);
	    $this->db->where('is_accepted',1);
	    $this->db->where('is_blocked',0);

		$resultSet = $this->db->get();
		if ($resultSet->result_id->num_rows > 0) {
			$favourites = $resultSet->result_array();
		} else {
			$favourites = false;
		}
		return $favourites;
	}
	
	public function getContactUsers($userID, $debug = NULL){
		
		$this->load->model('Contact_model');
		$contactsData = $this->Contact_model->get_all_friends($userID);
		
		$returnValue = array();
		
		if($contactsData['rc'] != false){
			$contacts = $contactsData['data'];
			foreach($contacts as $contact){
				if($contact['blocked_user'] != 1){
					$returnValue[] = $this->get_user_id_by_resume_id($contact['id']);
				}
			}
		}
		
		return $returnValue;
		
	}
	
	public function getEntityData($entity_id, $entity_type){
		
		if($entity_type == 1){
			$table = 'users';
		} else if($entity_type == 2){
			$table = 'companies';
		} else if($entity_type == 3){
			$table = 'colleges';
		}
		
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id', $entity_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return $resultSet->row_array();
		}
		
		return false;
	}
	
	public function getComments($id,$limit = null,$posted_as = null,$posted_as_id = null){

		$user_id = $this->session->userdata('user_id');
		$this->db->select("co.id as company_id, co.name as company_name,co.logo as company_image, col.id as college_id, col.name as college_name, col.logo as college_image , count(distinct(cl.id)) as likes , r.id as resume_id,r.profile_image,r.profile_photo_type,pc.*,u.profile_photo_type, u.full_name,(case when (cl.user_id = '$user_id' AND cl.liked_as = '$posted_as' AND cl.liked_as_id = '$posted_as_id' ) THEN 'Unlike' ELSE 'Like' END) as user_like, (case when pc.user_id = '$user_id' THEN TRUE else FALSE END) as delete_comment",false);
		$this->db->from($this->commentTable . ' pc');
		$this->db->join($this->userTable . ' u', 'u.id = pc.user_id');
        $this->db->join($this->table . ' up', 'up.id = pc.post_id');
        $this->db->join($this->resumeTable . ' r','r.user_id = pc.user_id','left');
        $this->db->join($this->postCommentLike . ' cl','cl.comment_id = pc.id','left');
        $this->db->join($this->companyTable . ' co','pc.posted_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' col','col.id = pc.posted_as_id','left');
        $this->db->where($this->postTableFK,$id);
        if($limit != null){
			$this->db->limit($limit);
		}
            

		$this->db->group_by("pc.id");
        $this->db->order_by("comment_on", "desc");

        $result = $this->db->get();

		return $result->result_array();

	}
	
	public function getComment($id) {
		
		$user_id = $this->session->userdata('user_id');
		
		$this->db->select("co.id as company_id, co.name as company_name,co.logo as company_image, col.id as college_id, col.name as college_name, col.logo as college_image, r.id as resume_id,r.profile_image,r.profile_photo_type,pc.*,u.profile_photo_type, u.full_name,(case when (cl.user_id = '$user_id' ) THEN 'Unlike' ELSE 'Like' END) as user_like, (case when pc.user_id = '$user_id' THEN TRUE else FALSE END) as delete_comment",false);
		$this->db->from($this->commentTable . ' pc');
		$this->db->join($this->userTable . ' u', 'u.id = pc.user_id');
        $this->db->join($this->table . ' up', 'up.id = pc.post_id');
        $this->db->join($this->resumeTable . ' r','r.user_id = pc.user_id','left');
        $this->db->join($this->postCommentLike . ' cl','cl.comment_id = pc.id','left');
        $this->db->join($this->companyTable . ' co','pc.posted_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' col','col.id = pc.posted_as_id','left');
        $this->db->where('pc.id',$id);

        $result = $this->db->get();

		return $result->row_array();
	}
	
	public function getPostAuthor($postId){
		
		$this->db->select('user_id');
		$this->db->from($this->table);
		$this->db->where('id', $postId);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row();
			$returnValue = $returnValue->user_id;
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
		
	}
	
	public function getProfilePhoto(){
        $user_id = $this->session->userdata('user_id');
        $this->db->select('id as resume_id,profile_image,profile_photo_type');
        $this->db->from($this->resumeTable);
        $this->db->where('user_id',$user_id);
        $result = $this->db->get();

        return $result->row_array();

    }
	
	public function get_user_id_by_resume_id($resume_id){
		$this->db->select('user_id');
        $this->db->from($this->resumeTable);
        $this->db->where('id',$resume_id);
        $result = $this->db->get();

		$returnValue = $result->row_array();
		
        return $returnValue['user_id'];
	}
	
	public function getProfiles($type){
		
		$user_id = $this->session->userdata('user_id');

		if($type == "college"){
			// getting user colleges
			$this->db->select("c.id , name , logo",false);
			$this->db->from($this->userTable . ' u');
			$this->db->join($this->collegeTable . ' c' , 'c.user_id = u.id');
		}else if($type == "company"){
			// getting user companies
			$this->db->select("c.id , name , logo",false);
			$this->db->from($this->userTable . ' u');
			$this->db->join($this->companyTable . ' c' , 'c.user_id = u.id');
		}
		$this->db->where('u.id',$user_id);


		return $this->db->get()->result_array();

	}
	
	public function getSingleRow_OneArgument($compareFieldName, $dbFieldName, $table) {

       	$this->db->select('*');
        $this->db->from($table);
		$this->db->where($dbFieldName, $compareFieldName);
		
        $resultSet = $this->db->get();
        if ($resultSet->num_rows > 0) {
            $returnValue = $resultSet->row_array();			
        } else {
            $returnValue = false;
        }

        $resultSet->free_result();
        return $returnValue;
    }
	
	public function countCommentsByPost($post){
		$this->db->select('*');
		$this->db->from('post_comment');
		$this->db->where('post_id',$post);
		
        return $this->db->count_all_results();
        
	}
	
	public function getlikedPosts($liked_as, $liked_as_id){
		
		$this->db->select('post_id');
		$this->db->from($this->likeTable);
		//$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->where('liked_as',$liked_as);
		$this->db->where('liked_as_id',$liked_as_id);
		$result = $this->db->get()->result_array();
		
	   	return $result;

	}

	public function getlikedComments($liked_as, $liked_as_id){
		
		$this->db->select('comment_id');
		$this->db->from($this->postCommentLike);
		//$this->db->where('user_id',$this->session->userdata('user_id'));
		$this->db->where('liked_as',$liked_as);
		$this->db->where('liked_as_id',$liked_as_id);
		
		$result = $this->db->get()->result_array();
		
	   	return $result;

	}
	
	public function getSharedPost($post_id){
		
		$this->db->select('posts.id, posts_shared.shared_posted_as as posted_as, posts_shared.shared_posted_as_id as 	posted_as_id, posts_shared.post_status as post_status');
		$this->db->from('posts');
		$this->db->join('posts_shared', 'posts.id = posts_shared.post_id');
		$this->db->where('posts.id',$post_id);
		
		$result = $this->db->get()->row_array();
		
	   	return $result;

	}
	
	public function getLikes($id){

		$this->db->select('co.id as company_id, co.name as company_name, co.logo as company_image, col.id as college_id, col.name as college_name,col.logo as college_image,  pl.*,u.full_name,r.id as resume_id,r.profile_image,r.profile_photo_type');
		$this->db->from($this->likeTable . ' pl');
		$this->db->join($this->userTable . ' u', 'u.id = pl.user_id');
		$this->db->join($this->resumeTable . ' r','r.user_id = pl.user_id','left');
        $this->db->join($this->companyTable . ' co','pl.liked_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' col','col.id = pl.liked_as_id','left');
		$this->db->where('post_id',$id);

		$result = $this->db->get();

		return $result->result_array();

	}
	
	public function getCommentLikes($id){

		$this->db->select('co.id as company_id, co.name as company_name, co.logo as company_image, col.id as college_id, col.name as college_name,col.logo as college_image,  pl.*,u.full_name,r.id as resume_id,r.profile_image,r.profile_photo_type');
		$this->db->from($this->postCommentLike . ' pl');
		$this->db->join($this->userTable . ' u', 'u.id = pl.user_id');
		$this->db->join($this->resumeTable . ' r','r.user_id = pl.user_id','left');
        $this->db->join($this->companyTable . ' co','pl.liked_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' col','col.id = pl.liked_as_id','left');
		$this->db->where('comment_id',$id);

		$result = $this->db->get();

		return $result->result_array();

	}
	
	public function get_all_friends($user_id){
        $sql = "SELECT
                    resumes.id,
					users.id as user_id,
                    users.full_name as name,
                    resumes.profile_photo_type,
                    resumes.profile_image,
                    user_has_friends.id as friendship_id,
                    user_has_friends.is_blocked as blocked_user,
                    user_has_friends.blocked_user_id
                FROM
                    user_has_friends,
                    resumes,
                    users
                WHERE
                    resumes.user_id = user_has_friends.request_sent_to
                AND
                    users.id = resumes.user_id
                AND
                    user_has_friends.is_accepted = 1
                AND
                    user_has_friends.request_sent_from = ?

                UNION

                SELECT
                    resumes.id,
					users.id as user_id,
                    users.full_name as name,
                    resumes.profile_photo_type,
                    resumes.profile_image,
                    user_has_friends.id as friendship_id,
                    user_has_friends.is_blocked as blocked_user,
                    user_has_friends.blocked_user_id
                FROM
                    user_has_friends,
                    resumes,
                    users
                WHERE
                    resumes.user_id = user_has_friends.request_sent_from
                AND
                    users.id = resumes.user_id
                AND
                    user_has_friends.is_accepted = 1
				AND
                    user_has_friends.is_blocked = 0
                AND
                    user_has_friends.request_sent_to = ?
                ";
    
        $users = $this->db->query($sql,array($user_id,$user_id))->result_array();
    	return $users;
    }
	
	/*
	|--------------------------------------------------------------------------
	| Add, Update Functions
	|--------------------------------------------------------------------------
	*/
	
	public function updateAnalytics($id, $field, $value){
		
		$dbFields = array($field => $value);

		$this->db->where('id', $id);
		return $this->db->update($this->table, $dbFields); 

	}
	
	public function addInfo_Simple($insertDbFieldsAry, $insertInfoAry, $table){
		$data = array();
		
		for($i=0; $i<sizeof($insertDbFieldsAry); $i++){
			$data[$insertDbFieldsAry[$i]] = $insertInfoAry[$i];	
		}
				
		if($this->db->insert($table, $data)){
			return true;	
		}
		return false;
	}
	
	public function updateInfo_Simple($compareFieldName, $dbFieldName, $updateDbFieldsAry, $updateInfoAry, $table){
		
		$data = array();		
		for($i=0; $i<sizeof($updateDbFieldsAry); $i++){			
			$data[$updateDbFieldsAry[$i]] = $updateInfoAry[$i];	
		}
				
		
		$this->db->where($dbFieldName, $compareFieldName);
		
		if($this->db->update($table, $data)){
			return true;
		}
		
		return false;
	}
	
	public function boostPost($postId,$posted_as,$posted_as_id){
		
		date_default_timezone_set('GMT');
		$boostDate = date('Y-m-d H:i:s');
		
		$query = "UPDATE posts SET update_on = '$boostDate' , boost_post = '1', share_post = '0' , posted_as = '$posted_as', posted_as_id = '$posted_as_id' WHERE id = '$postId'";

		return $this->db->query($query); 

	}
	
	public function likePost($id,$liked_as,$liked_as_id){
		
		$this->db->select('*');
		$this->db->from($this->likeTable);
		$this->db->where('post_id', $id);
        $this->db->where('liked_as', $liked_as);
        $this->db->where('liked_as_id', $liked_as_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			
			$this->db->where('post_id', $id);
       		$this->db->where('liked_as', $liked_as);
			$this->db->where('liked_as_id', $liked_as_id);
  			if($this->db->delete($this->likeTable)){
				$post = $this->getPost($id);
		
				$likes_count = $post['total_post_likes'] - 1;
				
				$updateDbFieldsAry = array('total_post_likes');
				$updateInfoAry = array($likes_count);
				$this->Feed_model->updateInfo_Simple($id, 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
				
			}
		} else {
			$dbFields = array('user_id' => $this->session->userdata('user_id') , 'post_id' => $id , 'liked_as' => $liked_as , 'liked_as_id' => $liked_as_id );
			if($this->db->insert($this->likeTable, $dbFields)){
				$post = $this->getPost($id);
		
				$likes_count = $post['total_post_likes'] + 1;
				
				$updateDbFieldsAry = array('total_post_likes');
				$updateInfoAry = array($likes_count);
				$this->Feed_model->updateInfo_Simple($id, 'id', $updateDbFieldsAry, $updateInfoAry, 'posts');
			}
		}
		
		return $likes_count;
	}
	
	public function likeComment($id,$liked_as,$liked_as_id){
		
		$this->db->select('*');
		$this->db->from($this->postCommentLike);
		$this->db->where('comment_id', $id);
        $this->db->where('liked_as', $liked_as);
        $this->db->where('liked_as_id', $liked_as_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			
			$this->db->where('comment_id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where('liked_as', $liked_as);
			$this->db->where('liked_as_id', $liked_as_id);
			
  			if($this->db->delete($this->postCommentLike)){
				$comment = $this->getComment($id);
		
				$likes_count = $comment['total_comment_likes'] - 1;
				
				$updateDbFieldsAry = array('total_comment_likes');
				$updateInfoAry = array($likes_count);
				$this->Feed_model->updateInfo_Simple($id, 'id', $updateDbFieldsAry, $updateInfoAry, $this->commentTable);
			}
		} else {
			$dbFields = array('user_id' => $this->session->userdata('user_id') , 'comment_id' => $id, 'liked_as' => $liked_as , 'liked_as_id' => $liked_as_id);
			if( $this->db->insert($this->postCommentLike, $dbFields)){
				$comment = $this->getComment($id);
		
				$likes_count = $comment['total_comment_likes'] + 1;
				
				$updateDbFieldsAry = array('total_comment_likes');
				$updateInfoAry = array($likes_count);
				$this->Feed_model->updateInfo_Simple($id, 'id', $updateDbFieldsAry, $updateInfoAry, $this->commentTable);
			}
		}
		
		return $likes_count;
	}

	
	/*
	|--------------------------------------------------------------------------
	| Delete Functions
	|--------------------------------------------------------------------------
	*/
	
	public function deleteInfo_Simple($compareFieldName, $dbFieldName, $table) {
       	       
		$this->db->where($dbFieldName, $compareFieldName);
		
		if($this->db->delete($table)){
			return true;
		}
		
		return false;		        
    }
	
	public function deleteComment($id){
		
		$this->db->where($this->PK, $id);
  		return $this->db->delete($this->commentTable);
	}
	
	/*
	|--------------------------------------------------------------------------
	| Check Functions
	|--------------------------------------------------------------------------
	*/
	
	public function checkEntityExist($entity_id, $entity_type){
		
		if($entity_type == 1){
			$table = 'users';
		} else if($entity_type == 2){
			$table = 'companies';
		} else if($entity_type == 3){
			$table = 'colleges';
		}
		
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where('id', $entity_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return true;
		}
		
		return false;
	}
	
	public function checkCommentlikedByActiveUser($user, $comment, $liked_as, $liked_as_id){
		
		$this->db->select('*');
		$this->db->from($this->postCommentLike);
		$this->db->where('comment_id', $comment);
		$this->db->where('user_id', $user);
		$this->db->where('liked_as', $liked_as);
		$this->db->where('liked_as_id', $liked_as_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return true;
		}
		
		return false;
	}
	
	public function checkPostlikedByActiveUser($user, $post, $liked_as, $liked_as_id){
		
		$this->db->select('*');
		$this->db->from($this->likeTable);
		$this->db->where('user_id', $user);
		$this->db->where('post_id', $post);
		$this->db->where('liked_as', $liked_as);
		$this->db->where('liked_as_id', $liked_as_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return true;
		}
		
		return false;
	}
	
	
	/*
	|--------------------------------------------------------------------------
	| Count Functions
	|--------------------------------------------------------------------------
	*/
	
	public function countPostComments($post_id){
		
		$this->db->select('*');
		$this->db->from($this->commentTable);
		$this->db->where('post_id', $post_id);
		
		$resultSet = $this->db->get();
		
		$count = 0;
		
		if ($resultSet->result_id->num_rows > 0) {
			foreach($resultSet->result_array() as $result){
				if($this->checkEntityExist($result['posted_as_id'], $result['posted_as'])){
					$count = $count + 1;
				}
			}
		}
		return $count;
		//echo '<pre>';print_r($resultSet);exit;
	}
	
	public function countPostLikes($post_id){
		
		$this->db->select('*');
		$this->db->from($this->likeTable);
		$this->db->where('post_id', $post_id);
		
		$resultSet = $this->db->get();
		
		$count = 0;
		
		if ($resultSet->result_id->num_rows > 0) {
			foreach($resultSet->result_array() as $result){
				if($this->checkEntityExist($result['liked_as_id'], $result['liked_as'])){
					$count = $count + 1;
				}
			}
		}
		return $count;
		//echo '<pre>';print_r($resultSet);exit;
	}
	
	public function countCommentLikes($comment_id){
		
		$this->db->select('*');
		$this->db->from($this->postCommentLike);
		$this->db->where('comment_id', $comment_id);
		
		$resultSet = $this->db->get();
		
		$count = 0;
		
		if ($resultSet->result_id->num_rows > 0) {
			foreach($resultSet->result_array() as $result){
				if($this->checkEntityExist($result['liked_as_id'], $result['liked_as'])){
					$count = $count + 1;
				}
			}
		}
		return $count;
		//echo '<pre>';print_r($resultSet);exit;
	}
	
	public function test1(){
		
		$user_id = $this->session->userdata('user_id');
		
		$favouriteCompanies = $this->getFavouriteCompaniesUsers($user_id);
		$favouriteColleges = $this->getFavouriteCollegeUsers($user_id);
		$contacts = $this->getContactUsers($user_id);		
		
		$query = "SELECT up.*,r.id as resume_id, r.profile_image, r.profile_photo_type, u.full_name, title, original_url, upm.description, provider_url, media, isUploaded, (case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post ";
		$query .= "FROM posts up ";
		$query .= "LEFT JOIN post_media upm ON up.id = upm.post_id ";
		$query .= "LEFT JOIN resumes r ON r.user_id = up.user_id ";
		$query .= "LEFT JOIN users u ON up.user_id = u.id ";
		$query .= "WHERE ((up.user_id = '$user_id') OR up.boost_post = '1'";
		
		if(!empty($contacts)){	// If posted by contacts
			$contacts = implode(',', $contacts);
			$query .= " OR up.user_id IN ($contacts)";
		}
		
		if(!empty($favouriteCompanies)){	// If posted by favourite Companies
			$favouriteCompanies = implode(',', $favouriteCompanies);
			$query .= " OR up.user_id IN ($favouriteCompanies)";
		}
		
		if(!empty($favouriteColleges)){	// If posted by favourites Colleges
			$favouriteColleges = implode(',', $favouriteColleges);
			$query .= " OR up.user_id IN ($favouriteColleges)";
		}
		
		$query .= ")";
		
		
		$resultSet = $this->db->query($query);
		
		$posts = array();
		
		if($resultSet->result_id->num_rows > 0) {
			foreach($resultSet->result_array() as $result){
				if($this->checkEntityExist($result['posted_as_id'], $result['posted_as'])){
					$entityData = $this->getEntityData($result['posted_as_id'], $result['posted_as']);
					if($result['posted_as'] == 2){
						$result['company_id'] = $entityData['id'];
						$result['company_name'] = $entityData['name'];
						$result['company_image'] = $entityData['logo'];
					}
					if($result['posted_as'] == 3){
						$result['college_id'] = $entityData['id'];
						$result['college_name'] = $entityData['name'];
						$result['college_image'] = $entityData['logo'];
					}
					
					$posts[] = $result;
				}
			}
		}
		
		echo '<pre>';print_r($posts);exit;
		
	}
	

}
