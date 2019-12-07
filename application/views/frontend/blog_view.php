

<?php include 'inc/header.php';?>

 

<div class="blog-hero">

   <div class="container">

     <div class="blog-inside wow flipInX">

        <h1><?=$this->lang->line('ourBlog') ?></h1> 

        <p><?=$this->lang->line('ourBlogParagraph') ?></p> 



    </div>

  </div>

</div> <!--===========end job slide===============-->  



<div class="blog-main">

 <div class="container">

   <div class="col-md-3 col-sm-4 col-xs-12">

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

<?php if (valResultSet($categoryData)) { ?>
      <div class="categorie">

        <h3><?=$this->lang->line('categories') ?></h3>

        <ul>
<?php foreach ($categoryData as $category) { ?>
         <li><a href="<?=BASEURL.'/blog/category/'.$category->slug ?>"><?=ucfirst($category->categoryName)?></a></li>

<?php } ?>
       </ul>

     </div>
<?php } ?>
     

<?php if (valResultSet($latestBlogData)) { ?>
     <div class="latest-product">

        <h3><?=$this->lang->line('latestProducts') ?></h3>

        <ul>

<?php foreach ($latestBlogData as $latestBlog) {
      $blogslug = "javascript:location.href='".BASEURL."/blog/".$latestBlog->slug."'";

 ?>
         <li  style="cursor:pointer;" onclick="<?=$blogslug;?>" ><img src="<?=(!empty($latestBlog->img))?UPLOADPATH.'/blog_images/'.$latestBlog->img:DASHSTATIC.'/restaurant/assets/img/blog.png';?>" style="height: 70px;width: 80px;"> 

             <span><?=ucfirst($latestBlog->title)?> <i><?=ucfirst($latestBlog->addedOn)?></i></span>

         </li>
<?php } ?>

       </ul>

     </div> 
<?php } ?>

     


  

  

   </div> <!--===========end blog left===============-->

   

    <div class="col-md-9 col-sm-8 col-xs-12">
      <div class="filterbody" style="display: none;" id="<?=(isset($filterType))?$filterType:'' ?>" > <?=(isset($filterBy))?$filterBy:'' ?>
     </div>
      <div class="tablebody">
     </div>

     
               <input type="hidden" id="hidPaging" value="1">

                <input type="hidden" id="hidTotalRecord">

                <div class="col-md-12"> 

                    <div id=" " class="col-md-6 pageitem">

                        <div class="span6">

                            <label>

                                <select id="no_results" class="form-control" onchange="ChangePageNumber(this);">

                                    <option value="10" selected="selected">10</option>

                                    <option value="25">25</option>

                                    <option value="50">50</option>

                                    <option value="100">100</option>

                                </select> <?=$this->lang->line('recordsPerPage') ?>

                            </label>

                       </div>

                    </div>

                    <div id="sample_1_info" class="col-md-6 pagination hide">

                        <div class="span6" style="float:left">

                            <div class="dataTables_info"></div>

                        </div>

                        <div class="span6" style="float:right">

                            <div class="dataTables_paginate paging_bootstrap pagination">

                            </div>

                        </div>

                     </div>                                       

                </div>

      

   </div> <!--===========end blog right===============-->

 

 </div> 

</div> <!--===========end Blog main===============-->







<?php include 'inc/footer.php';?>