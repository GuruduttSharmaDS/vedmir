 <?php include 'inc/header.php';?>
 
<div class="job-hero">
   <div class="container">
     <div class="job-inside wow flipInX">
        <h1><?=$this->lang->line('jobOpportunities') ?></h1> 
        <p><?=$this->lang->line('jobOpportunitiesParagraph') ?></p> 

    </div>
  </div>
</div> <!--===========end job slide===============-->  

<div class="weget-main">
  <div class="container">  
        <div class="weget-inside wow shake">
           <h1><?=$this->lang->line('weGetIt') ?>...</h1>
           <p><?=$this->lang->line('weGetItParagraph') ?></p>
           <span><?=$this->lang->line('weGetItParagraphSpan') ?></span>
        </div>  
         <div class="bigger-items">
              <div class="col-md-4 col-sm-12 col-xs-12">
                 <div class="bigger-inside">    
                   <h4><img src="<?php echo FRONTSTATIC; ?>/images/comp.png"></h4>
                    <h3><?=$this->lang->line('competitiveSalary') ?></h3>
                    <p><?=$this->lang->line('competitiveSalaryParagraph') ?></p>
                </div>    
             </div>  
             <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="bigger-inside">
                    <h4><img src="<?php echo FRONTSTATIC; ?>/images/tra.png"></h4>
                    <h3><?=$this->lang->line('freeTraining') ?></h3>
                    <p><?=$this->lang->line('freeTrainingParagraph') ?></p>
                </div>   
             </div>  
             <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="bigger-inside">
                    <h4><img src="<?php echo FRONTSTATIC; ?>/images/equ.png"></h4>
                    <h3><?=$this->lang->line('allEquipmentYouNeed') ?></h3>
                    <p><?=$this->lang->line('allEquipmentYouNeedParagraph') ?> </p>
                </div>    
             </div> 
        </div>
        <div class="bigger-items">
              <div class="col-md-4 col-sm-12 col-xs-12">
                 <div class="bigger-inside">
                   <h4><img src="<?php echo FRONTSTATIC; ?>/images/brth.png"></h4>
                    <h3><?=$this->lang->line('happyBirthday') ?></h3>
                    <p><?=$this->lang->line('happyBirthdayParagraph') ?></p>
                 </div>   
             </div>  
             <div class="col-md-4 col-sm-12 col-xs-12">
               <div class="bigger-inside">
                    <h4><img src="<?php echo FRONTSTATIC; ?>/images/trav.png"></h4>
                    <h3><?=$this->lang->line('paidForTravel') ?></h3>
                    <p><?=$this->lang->line('paidForTravelParagraph') ?></p>
               </div> 
             </div>  
             <div class="col-md-4 col-sm-12 col-xs-12">
               <div class="bigger-inside">  
                    <h4><img src="<?php echo FRONTSTATIC; ?>/images/cher.png"></h4>
                    <h3><?=$this->lang->line('cheers') ?></h3>
                    <p><?=$this->lang->line('cheersParagraph') ?> </p>
               </div> 
             </div> 
        </div>
 </div>
</div> <!--===========end we get main===============-->

<div class="vacancie-main">
 <div class="container">
     <h1><?=$this->lang->line('ourVacancies') ?></h1>
   <div class="col-md-6 col-sm-6 col-xs-12">
        <h2><?=$this->lang->line('ourVacancies1Name') ?> <br><?=$this->lang->line('ourVacancies1Position') ?> </h2>
        <img src="<?php echo FRONTSTATIC; ?>/images/event.jpg">
   </div>

    <div class="col-md-6 col-sm-6 col-xs-12">
        <h2><?=$this->lang->line('ourVacancies2Name') ?> <br><?=$this->lang->line('ourVacancies2Position') ?></h2>
        <img src="<?php echo FRONTSTATIC; ?>/images/sales.jpg">
   </div>

   

 </div> 

</div> <!--===========end vacancie main===============-->

<?php include 'inc/footer.php';?>