<?php if(!defined('BASEPATH')) exit("No direct access allowed");

class Post_model extends CI_Model
{
	private $table = 'user_post';
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

	
	public function getNewsFeed($offset = null , $limit = null ){

			$user_id = $this->session->userdata('user_id');
			
			$favourites = $this->getFavouriteUsers($user_id);
			$contacts = $this->getContactUsers($user_id);
			
			$query = "SELECT co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image,"
			."r.id as resume_id, r.profile_image, r.profile_photo_type, u.full_name,"
		    ."up.*, pic, title, original_url, upl.description, provider_url, thumbnail_url, link_type, iframe, count(distinct(pl.id)) as likes, count(distinct(pc.id)) as comments,"
		    ."(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post "
		    ."FROM user_post up "
            ."LEFT JOIN companies co on co.id = up.posted_as_id "
            ."LEFT JOIN colleges cl on cl.id = up.posted_as_id "
            ."LEFT JOIN resumes r ON r.user_id = up.user_id "
		    ."LEFT JOIN users u ON up.user_id = u.id "
	        ."LEFT JOIN user_post_link upl ON up.id = upl.post_id "
	        ."LEFT JOIN user_post_image upi ON up.id = upi.post_id "
	        ."LEFT JOIN post_like pl ON up.id = pl.post_id "
            ."LEFT JOIN post_comment pc ON up.id = pc.post_id "
            ."LEFT JOIN user_has_friends uf ON up.user_id = uf.request_sent_to ";

            //$query .= "WHERE (up.user_id = '$user_id') OR up.boost_post = '1' OR EXISTS (SELECT * FROM user_has_friends WHERE"
            //." up.user_id IN (request_sent_from,request_sent_to) AND '$user_id' IN (request_sent_from,request_sent_to) AND is_accepted = '1' AND is_blocked = '0')";
			
			$query .= "WHERE (up.user_id = '$user_id') OR up.boost_post = '1'";
			
			if(!empty($contacts)){	// If posted by contacts
				$contacts = implode(',', $contacts);
				$query .= " OR up.user_id IN ($contacts)";
			}
			
			if(!empty($favourites)){	// If posted by favourites
				$favourites = implode(',', $favourites);
				$query .= " OR up.user_id IN ($favourites)";
			}
			
            $query .= " GROUP BY up.id ORDER BY update_on DESC";
			
            if($limit != null){
                $query  .= " LIMIT $offset,$limit";
			}
			
            $result = $this->db->query($query);
			
			//echo '<pre>';print_r($result->result_array());exit;
			
			return $result->result_array();

	}

	function getPostLikeStatus($liked_as_id){
		
		$this->db->select('post_id');
		$this->db->from($this->likeTable);
		$this->db->where('liked_as_id',$liked_as_id);
		$result = $this->db->get()->result_array();
		
	   	return $result;

	}


	function getlikedComments($liked_as, $liked_as_id){
		
		$this->db->select('comment_id');
		$this->db->from($this->postCommentLike);
		
		$array = array('user_id' => $this->session->userdata('user_id'), 'liked_as' => $liked_as, 'liked_as_id' => $liked_as_id);

		$this->db->where($array);
		
		$result = $this->db->get()->result_array();
		
	   	return $result;

	}
	
	public function checkUserProfiles(){
		
		$user_id = $this->session->userdata('user_id');
		
		$this->db->select("count(*) as total",false);
		$this->db->from($this->userTable . ' u');
		$this->db->join($this->collegeTable . ' cl' , 'cl.user_id = u.id');
		$this->db->where('u.id',$user_id);

		$result['colleges'] = $this->db->get()->row();

		$this->db->select("count(*) as total",false);
		$this->db->from($this->userTable . ' u');
		$this->db->join($this->companyTable . ' co' , 'co.user_id = u.id');
		$this->db->where('u.id',$user_id);

		$result['companies'] = $this->db->get()->row();

		return $result;
	}
	
	public function profileType($type){
		
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
	
	public function countNewsFeed(){

		$user_id = $this->session->userdata('user_id');
		$whereClause = "(up.user_id = '$user_id') OR up.boost_post = '1' OR EXISTS (SELECT * FROM user_has_friends WHERE
         up.user_id IN (request_sent_from,request_sent_to) AND '$user_id' IN (request_sent_from,request_sent_to) AND is_accepted = '1')";

		$this->db->select(" r.id as resume_id,r.profile_image,r.profile_photo_type,u.full_name, up.*, pic , title , original_url , description , provider_url , thumbnail_url , link_type , iframe , count(distinct(pl.id)) as likes,count(distinct(pc.id)) as comments,(case when (pl.user_id = '$user_id' ) THEN 'Unlike' ELSE 'Like' END) as user_like,(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post",false);
		$this->db->from($this->table . ' up');
        $this->db->join($this->userTable . ' u','up.user_id = u.id','left');
		$this->db->join($this->postLinkTable . ' upl','up.id = upl.post_id','left');
		$this->db->join($this->postImageTable . ' upi','up.id = upi.post_id','left');
		$this->db->join($this->likeTable . ' pl','up.id = pl.post_id','left');
		$this->db->join($this->commentTable . ' pc','up.id = pc.post_id','left');
		$this->db->join($this->friendTable . ' uf','up.user_id = uf.request_sent_to','left');
        $this->db->join($this->resumeTable . ' r','r.user_id = up.user_id','left');

		$this->db->where($whereClause);
		$this->db->group_by('up.id');
        $this->db->order_by("post_on", "desc");

        $result = $this->db->get();

		return $result->result_array();

	}

    public function getPost($postId){
    	$user_id = $this->session->userdata('user_id');

        $this->db->select("co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image ,r.id as resume_id,r.profile_image,r.profile_photo_type,u.full_name, up.*, pic , title , original_url , upl.description , provider_url , thumbnail_url , link_type , iframe , count(distinct(pl.id)) as likes,count(distinct(pc.id)) as comments,(case when (pl.user_id = up.user_id ) THEN 'Unlike' ELSE 'Like' END) as user_like,(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post",false);
        $this->db->from($this->table . ' up');
        $this->db->join($this->userTable . ' u','up.user_id = u.id','left');
        $this->db->join($this->postLinkTable . ' upl','up.id = upl.post_id','left');
        $this->db->join($this->postImageTable . ' upi','up.id = upi.post_id','left');
        $this->db->join($this->likeTable . ' pl','up.id = pl.post_id','left');
        $this->db->join($this->commentTable . ' pc','up.id = pc.post_id','left');
        $this->db->join($this->friendTable . ' uf','up.user_id = uf.request_sent_to','left');
        $this->db->join($this->resumeTable . ' r','r.user_id = up.user_id','left');
        $this->db->join($this->companyTable . ' co','up.posted_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' cl','cl.id = up.posted_as_id','left');
        $this->db->where('up.id',$postId);
        $result = $this->db->get();

        return $result->result_array();
    }
	
	public function getPostData($postId){
    	$user_id = $this->session->userdata('user_id');

        $this->db->select("co.id as company_id, co.name as company_name, co.logo as company_image, cl.id as college_id, cl.name as college_name, cl.logo as college_image ,r.id as resume_id,r.profile_image,r.profile_photo_type,u.full_name, up.*, pic , title , original_url , upl.description , provider_url , thumbnail_url , link_type , iframe , count(distinct(pl.id)) as likes,count(distinct(pc.id)) as comments,(case when (pl.user_id = up.user_id ) THEN 'Unlike' ELSE 'Like' END) as user_like,(case when up.user_id = '$user_id' THEN TRUE ELSE FALSE END) as del_post",false);
        $this->db->from($this->table . ' up');
        $this->db->join($this->userTable . ' u','up.user_id = u.id','left');
        $this->db->join($this->postLinkTable . ' upl','up.id = upl.post_id','left');
        $this->db->join($this->postImageTable . ' upi','up.id = upi.post_id','left');
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

    public function getComments($id,$limit = null){

		$user_id = $this->session->userdata('user_id');
		$this->db->select("co.id as company_id, co.name as company_name,co.logo as company_image, col.id as college_id, col.name as college_name, col.logo as college_image , count(distinct(cl.id)) as likes , r.id as resume_id,r.profile_image,r.profile_photo_type,pc.*,u.profile_photo_type, u.full_name,(case when (cl.user_id = '$user_id' ) THEN 'Unlike' ELSE 'Like' END) as user_like, (case when pc.user_id = '$user_id' THEN TRUE else FALSE END) as delete_comment",false);
		$this->db->from($this->commentTable . ' pc');
		$this->db->join($this->userTable . ' u', 'u.id = pc.user_id');
        $this->db->join($this->table . ' up', 'up.id = pc.post_id');
        $this->db->join($this->resumeTable . ' r','r.user_id = pc.user_id','left');
        $this->db->join($this->postCommentLike . ' cl','cl.comment_id = pc.id','left');
        $this->db->join($this->companyTable . ' co','pc.posted_as_id = co.id','left');
        $this->db->join($this->collegeTable . ' col','col.id = pc.posted_as_id','left');
        $this->db->where($this->postTableFK,$id);
        if($limit != null)
            $this->db->limit($limit);

		$this->db->group_by("pc.id");
        $this->db->order_by("comment_on", "desc");

        $result = $this->db->get();

		return $result->result_array();

	}
    
	function getComment($id) {
	   $this->db->select('*');
	   $this->db->from('post_comment');
	   $this->db->where('id',$id);
	   $comment = $this->db->get()->row_array();
		
	   return $comment;
	}
	
	function countLikesByComment($comment){
		$this->db->select('*');
		$this->db->from('post_comment_likes');
		$this->db->where('comment_id',$comment);
		
        return $this->db->count_all_results();
	}
	
	function countCommentsByPost($post){
		$this->db->select('*');
		$this->db->from('post_comment');
		$this->db->where('post_id',$post);
		
        return $this->db->count_all_results();
        
	}
	
	public function getProfilePhoto(){
        $user_id = $this->session->userdata('user_id');
        $this->db->select('id as resume_id,profile_image,profile_photo_type');
        $this->db->from($this->resumeTable);
        $this->db->where('user_id',$user_id);
        $result = $this->db->get();

        return $result->row_array();

    }
	
	public function getProfilePhotoByUserID($user_id){
		
        $this->db->select('id as resume_id,profile_image,profile_photo_type');
        $this->db->from($this->resumeTable);
        $this->db->where('user_id',$user_id);
        $result = $this->db->get();

        return $result->row_array();

    }

	public function deletePost($id){
		$this->db->where($this->PK, $id);
  		return $this->db->delete($this->table);

	}

	public function deleteComment($id){
		$this->db->where($this->PK, $id);
  		return $this->db->delete($this->commentTable);
	}

	public function likePost($id,$value,$liked_as,$liked_as_id){
		
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
  			return $this->db->delete($this->likeTable);
		} else {
			$dbFields = array('user_id' => $this->session->userdata('user_id') , 'post_id' => $id , 'liked_as' => $liked_as , 'liked_as_id' => $liked_as_id );
			return $this->db->insert($this->likeTable, $dbFields);
			
		}
		
	}

	public function likeComment($id,$value,$liked_as,$liked_as_id){
		
		$this->db->select('*');
		$this->db->from($this->postCommentLike);
		$this->db->where('comment_id', $id);
        $this->db->where('liked_as', $liked_as);
        $this->db->where('liked_as_id', $liked_as_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			
			$this->db->where('comment_id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
  			return $this->db->delete($this->postCommentLike);
		} else {
			$dbFields = array('user_id' => $this->session->userdata('user_id') , 'comment_id' => $id, 'liked_as' => $liked_as , 'liked_as_id' => $liked_as_id);
			return $this->db->insert($this->postCommentLike, $dbFields);
		}
	}
	
	public function checkCommentlikedByActiveUser($user, $comment){
		
		$this->db->select('*');
		$this->db->from('post_comment_likes');
		$this->db->where('comment_id', $comment);
		$this->db->where('user_id', $user);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return true;
		}
		
		return false;
	}
	
	public function checkPostExists($post_id){
		
		$this->db->select('*');
		$this->db->from('user_post');
		$this->db->where('id', $post_id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			return true;
		}
		
		return false;
	}
	
	public function addCommentLike($id,$liked_as,$liked_as_id){
		$dbFields = array('user_id' => $this->session->userdata('user_id') , 'comment_id' => $id, 'liked_as' => $liked_as, 'liked_as_id' => $liked_as_id);
		return $this->db->insert('post_comment_likes', $dbFields);
	}
	
	public function deleteCommentLike($id){
		
		$this->db->where('comment_id', $id);
		$this->db->where('user_id', $this->session->userdata('user_id'));
		return $this->db->delete('post_comment_likes');
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

	public function addComment($dbFields){
		if($this->db->insert($this->commentTable, $dbFields)){
			return $this->db->insert_id();	
		}
		return false;
	}

	public function changePrivacy($id,$value){
		
		$dbFields = array('privacy_type' => $value);

		$this->db->where('id', $id);
		return $this->db->update($this->table, $dbFields); 

	}

	public function addNewPost($dbFields){

		if($this->db->insert($this->table, $dbFields)){
			return $this->db->insert_id();	
		}
		return false;
	}

	public function addNewLinkPost($dbFields){

		if($this->db->insert($this->postLinkTable, $dbFields)){
			return $this->db->insert_id();	
		}
		return false;
	}

	public function addNewImagePost($dbFields){

		if($this->db->insert($this->postImageTable, $dbFields)){
			return $this->db->insert_id();	
		}
		return false;
	}
	
	public function getAnalytics($id){

		$this->db->select('post_views,post_clicks');
		$this->db->from($this->table);
		$this->db->where('id', $id);

		$result = $this->db->get();

		return $result->row();

	}
	
	public function updateAnalytics($id, $field, $value){
		
		$dbFields = array($field => $value);

		$this->db->where('id', $id);
		return $this->db->update($this->table, $dbFields); 

	}

	public function boostPost($postId,$posted_as,$posted_as_id){
		
		$boostDate = date('Y-m-d h:i:s');
		
		$query = "UPDATE user_post SET update_on = '$boostDate' , boost_post = '1', share_post = '0' , posted_as = '$posted_as', posted_as_id = '$posted_as_id' WHERE id = '$postId'";

		return $this->db->query($query); 

	}

	public function updateViews($postId){

		$user_id = $this->session->userdata('user_id');

		$query = "UPDATE user_post SET post_views = post_views + 1 WHERE id = '$postId' AND user_id <> '$user_id'";

		$this->db->query($query);
	}
	
	public function getPostPhoto($postId){
		
		$this->db->select('pic');
		$this->db->from('user_post_image');
		$this->db->where('post_id', $postId);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row();
			$returnValue = $returnValue->pic;
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
		
	}
	
	public function getPostAuthor($postId){
		
		$this->db->select('user_id');
		$this->db->from('user_post');
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
	
	public function getSharedPostAuthor($id){
		
		$this->db->select('posted_as,posted_as_id');
		$this->db->from('user_post_shared');
		$this->db->where('post_id', $id);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row_array();
		} else {
			$returnValue = false;
		}
		
		return $returnValue;
		
	}
	
	public function getPostRealAuthor($postId){
		
		$this->db->select('posted_as,posted_as_id');
		$this->db->from('user_post');
		$this->db->where('id', $postId);

		$resultSet = $this->db->get();
		
		if ($resultSet->result_id->num_rows > 0) {
			$returnValue = $resultSet->row_array();
		} else {
			$returnValue = 0;
		}
		
		return $returnValue;
		
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
	
	public function getContactUsers($userID){
		
		$this->load->model('Contact_model');
		$contactsData = $this->Contact_model->get_all_friends($userID);
		
		$returnValue = array();
		
		if($contactsData['rc'] != false){
			$contacts = $contactsData['data'];
			foreach($contacts as $contact){
				if($contact['blocked_user'] != '1'){
					$returnValue[] = $contact['id'];
				}
			}
		}
		
		return $returnValue;
	}
	
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
	
	
	
	
	public function updatePost($id,$data){
		
		$this->db->where('id', $id);
		return $this->db->update($this->table, $data); 

	}
	
	
	public function insertSharedPost($data){
		
		return $this->db->insert('user_post_shared', $data);

	}

}
