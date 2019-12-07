

<?php include 'inc/header.php';?>

<div class="blog-hero">
   <div class="container">
     <div class="blog-inside wow flipInX">
        <h1><?=$this->lang->line('blogDetails') ?></h1> 
        <p><?=$this->lang->line('blogDetailsParagraph') ?></p> 

    </div>
  </div>
</div> <!--===========end job slide===============-->  



<div class="blog-main">
  <div class="blog_detal">
    <div class="container">
      <div class="col-md-8">

<?php if (valResultSet($blogDetailsData)) { ?>
        <div class="blog_box">
          <div class="blog_img">
            <img src="<?=(!empty($blogDetailsData->img))?UPLOADPATH.'/blog_images/'.$blogDetailsData->img:DASHSTATIC.'/restaurant/assets/img/blog.png';?>">
          </div>
          <div class="blog_line">
            <p><?=ucfirst($blogDetailsData->description)?></p>
          <div class="bg_border">
            <h2><?=ucfirst($blogDetailsData->title)?></h2>
          </div>
          </div>
        </div>

<?php if (valResultSet($blogCommentData)) { ?>
        <div class="coment_box">
          <h2><span><i class="fa fa-comments" aria-hidden="true"></i></span><?=$this->lang->line('comments') ?></h2>
          <?php  foreach ($blogCommentData as $blogComment) {  ?>
          <div class="coment-tx">
            <h3><strong><?=$blogComment->name?></strong><span><?=$blogComment->addedOn?></span></h3>
            <p><?=$blogComment->comment?></p>
          </div>
        <?php } ?>
        </div>
        <?php } ?>
        <form method="POST" id="formBlogComment" class="contform-inside" onsubmit="addBlogComment(this,event);">
        <div class="level_replay">
          <h2><?=$this->lang->line('commentHere') ?></h2>
          <div class="form-group">
            <div class="col-md-2">
            <label for="name"><?=$this->lang->line('name') ?></label>
            </div>
            <div class="col-md-10">
            <input type="text" id="name" name="name" placeholder="<?=$this->lang->line('yourName') ?>" required="required">
            <input type="hidden"  name="ip" value="<?=$_SERVER['REMOTE_ADDR']?>">
            <input type="hidden"  name="blogId" value="<?=(isset($blogDetailsData->blogId))?$blogDetailsData->blogId:0?>">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-2">
            <label for="email"><?=$this->lang->line('email') ?></label>
            </div>
            <div class="col-md-10">
            <input type="email" id="email" name="email" placeholder="<?=$this->lang->line('email') ?>" required="required">
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-2">
            <label for="comment"><?=$this->lang->line('comment') ?></label>
            </div>
            <div class="col-md-10">
               <textarea id="comment" name="comment" placeholder="<?=$this->lang->line('writeComment') ?>.." style="height:100px" required="required"></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-2">
            </div>
            <div class="col-md-10">
               <input type="submit" id="btnBlogComment" name="btnBlogComment" value="<?=$this->lang->line('commentNow') ?>" class="btn btn-primary">
            </div>
          </div>
          <div class="form-group">
              <div class="col-sm-12 submitResponce">
              </div>
          </div>

        </div>
      </form>
        <?php } ?>
      </div>
      <div class="col-md-4">
        <div class="right-sidber">
          <div class="scrch_bg">
            <h2><?=$this->lang->line('searchPosts') ?></h2>
            <div class="input-group stylish-input-group tab-right">
              <?php $blogsearch = "javascript:if($('#blogSearch').val().trim() != ''){location.href='".BASEURL."/blog/search/'+$('#blogSearch').val()}else{alert('".$this->lang->line('searchBoxIsEmpty')."');}"; ?>
              <input type="text" class="form-control" id="blogSearch" placeholder="<?=$this->lang->line('searchPosts') ?>....." style="height:50px;">
              <span class="input-group-addon">
                <button type="submit" onclick="<?=$blogsearch ?>">
                  <span class="glyphicon glyphicon-search"></span>
                </button>  
              </span>
            </div>
          </div>

<?php if (valResultSet($latestBlogData)) { 
   foreach ($latestBlogData as $latestBlog) { 
     
    ?>
          <div class="side_vitamin">
            <div class="vtamin_fr">
              <h2><?=ucfirst($latestBlog->categoryName)?></h2>
            </div>
            <div class="wegit_bg">
              <div class="benefits_pic">
                <img src="<?=(!empty($latestBlog->img))?UPLOADPATH.'/blog_images/'.$latestBlog->img:DASHSTATIC.'/restaurant/assets/img/blog.png';?>"> 
              </div>
              <h2><?=ucfirst($latestBlog->title)?></h2>
              <p><?=$latestBlog->description ?></p>
              <div class="button_red">
                <a href="<?= BASEURL.'/blog/'.$latestBlog->slug ?>"><?=$this->lang->line('readMore') ?></a>
              </div>
            </div>
          </div>
          <?php }  } ?>
        </div>
      </div>
    </div>
  </div>  
 
</div> <!--===========end Blog main===============-->







<?php include 'inc/footer.php';?>