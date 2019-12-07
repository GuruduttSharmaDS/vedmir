<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by Dream Steps Pvt Ltd
 * Created on 30 Nov 2019
 * Vedmir -  module
**/
class Commonajax extends CI_Controller {

	public $sessLang  	= '';
	public $langSuffix  	= '';
	
	public function __construct(){
		parent::__construct();

        if (empty($this->sessLang)) {
        	if ($this->session->userdata(PREFIX.'sessLang') == '') {
				$this->session->set_userdata(PREFIX.'sessLang', "english");
        		$this->sessLang = 'english';
        	}else{
        		$this->sessLang = $this->session->userdata(PREFIX.'sessLang');
        	}
        }

		$this->lang->load('custom_language_frontend',$this->sessLang);	
		$this->langSuffix = $this->lang->line('langSuffix');
	}
	
	// Vedmir - Ajax landing page
	public function index(){
		$action='';
		$action=$_POST['action'];
		if($action=="Get_Blog_List"){
			$return=$this->blog_list($_POST);
			$return['valid']=true;
		}

		$this->output->set_content_type('application/json')->set_output(json_encode($return));

	}

	// admin blog list view
	public function blog_list($data){
		$pagesize = $data['pagesize'];
		$pageno = $data['pageno'];
		$filterType = (isset($data['filterType']))?$data['filterType']:'';
		$filterBy = (isset($data['filterBy']))?$data['filterBy']:'';
		$filterBlogCond = '';
		if ($filterType == 'category' && !empty($filterBy)) {
			$categoryId = $this->Common_model->getSelectedField("vm_blog_category","categoryId","slug='".trim($filterBy)."'");
			$filterBlogCond = ($categoryId > 0)?" and bl.categoryId=".$categoryId:'';
		}
		if ($filterType == 'search' && !empty($filterBy)) {
			$filterBlogCond = " and bl.title like '%".trim($filterBy)."%'";
		}
		$startp=$pageno>0?($pageno)*$pagesize:$pageno;
		$endp=$pagesize;
		$blogData =array();
		$blogData['id'] ='';
		$query	=	"SELECT bl.blogId,
		 (SELECT count(*) from vm_blog_comment where vm_blog_comment.blogId = bl.blogId ) as totalComment,
		 bc.categoryName$this->langSuffix as categoryName,
		 bc.slug as bcslug,
		 bl.img,
		 bl.title$this->langSuffix as title,SUBSTRING(bl.description$this->langSuffix, 1, 150) as description,
		    bl.tags$this->langSuffix as tags,bl.slug, DATE_FORMAT(bl.addedOn, '%M %d,%Y')as addedOn,case when bl.status='0' then 'Active' else 'DeActive' end as status,
			case when bl.status='0' then 'act fa fa-circle' else 'dct fa fa-circle' end as class from vm_blog as bl left join vm_blog_category as bc on bc.categoryId=bl.categoryId  where bl.status = 0 ".$filterBlogCond." order by bl.blogId desc limit $startp,$endp ";

			
		$blogs =	$this->Common_model->exequery($query);
		if (valResultSet($blogs)) {
			foreach ($blogs as $blog) {
				$img = (!empty($blog->img))?UPLOADPATH.'/blog_images/'.$blog->img:DASHSTATIC.'/restaurant/assets/img/blog.png';
				$blogData['id'] .='<div class="blog1"><img src="'.$img.'"><div class="blog-coment"><p>'.$this->lang->line('postOn').' - '.$blog->addedOn.' | '.$blog->categoryName.' </p><p><i class="fa fa-tag">'.$blog->tags.'</i> <i class="fa fa-comments-o"> '.$blog->totalComment.'</i></p></div><div class="blog-content"><h1>'.$blog->title.'</h1><p>'.$blog->description.'...</p> <a href="'.BASEURL.'/blog/'.$blog->slug.'">'.$this->lang->line('readMore').'</a></div></div></div>'; 


			}
		}else{

			$blogData['id'] .='<h4 class="alert alert-danger">'.$this->lang->line('blogNotFound').'</h4>';
		}
		// $blogData['id'] ='<h4 class="alert alert-danger">'.$query.'</h4>';
		$query	=	"SELECT count(*) as count from vm_blog as bl where status != 2 ".$filterBlogCond." order by blogId desc";
		// exit;
		$coundata =	$this->Common_model->exequery($query,1);
		$blogData['count'] = $coundata->count;
		return $blogData;
	}

	
	
}