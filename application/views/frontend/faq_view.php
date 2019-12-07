
<?php include 'inc/header.php';?>
 
<div class="faq-hero">
   <div class="container">
     <div class="faq-inside wow flipInX">
        <h1><?=$this->lang->line('frequentlyAsked') ?> <br><?=$this->lang->line('questions') ?></h1> 
    </div>
  </div>
</div> <!--===========end faq slide===============-->  

<div class="question-main">
  <div class="container">  
    <div class="panel-group question-inside" id="accordion" role="tablist" aria-multiselectable="true">

<?php if($this->sessLang == 'french'){  ?>
        <div class="panel panel-default">
            <div class="panel-heading pactive" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Combien coûte l'adhésion?</b>
                   </a>                
                </h4>
          </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                     <p>The App is free to download and try. If you decide to become a member, you receive one drink a day for CHF 29.90 a month, it’s like the price of two cocktails.</p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>What types of venues are on Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <p>We partner with top bars and restaurants around Switzerland. Currently we are live in 3 cities, and we are constantly expanding.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How soon after subscribing can I order my first cocktail with Vedmir?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <p>As soon as you activate the subscription on the app, you’re good to go. Immediately.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Can I go to multiple Vedmir venues and get drinks from every venue?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    <p>You get One Drink Every Day. You select the venue, but once you redeem your drink for the day, you cannot get another one until the next day. But you can come back to the same venue the next day, or be adventurous and try something new, up to you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Who is behind Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    <p>Vedmir was founded by a group of friends with extensive knowledge and background in nightlife, business and tech.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>I have a bar/restaurant and want to partner with Vedmir; how do I go about that?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    <p><a href="<?=BASEURL?>/signup" target="_blank">Click Here</a> to apply to have your venue partnered with us.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How do I cancel my Vedmir membership?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    <p>Easy. Go in the App and click Cancel Subscription under Plans & Billing from the main menu and follow the instructions, or you may open a cancellation ticket by e-mailing info@vedmir.com. Deleting the App itself does NOT automatically unsubscribe you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEight">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Where can I find more resources on responsible drinking?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                <div class="panel-body">
                    <p>Vedmir encourages drinking socially but never irresponsibly. Here are some sites to visit: <br> <a href="https://www.responsibility.org/" target="_blank">Responsibility.org</a> <br>  <a href="http://gettips.com/" target="_blank">GetTips.com</a></p>
                </div>
            </div>
        </div>


<?php }else if($this->sessLang == 'german'){  ?>
        <div class="panel panel-default">
            <div class="panel-heading pactive" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Wie viel ist Mitgliedschaft??</b>
                   </a>                
                </h4>
          </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                     <p>The App is free to download and try. If you decide to become a member, you receive one drink a day for CHF 29.90 a month, it’s like the price of two cocktails.</p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>What types of venues are on Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <p>We partner with top bars and restaurants around Switzerland. Currently we are live in 3 cities, and we are constantly expanding.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How soon after subscribing can I order my first cocktail with Vedmir?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <p>As soon as you activate the subscription on the app, you’re good to go. Immediately.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Can I go to multiple Vedmir venues and get drinks from every venue?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    <p>You get One Drink Every Day. You select the venue, but once you redeem your drink for the day, you cannot get another one until the next day. But you can come back to the same venue the next day, or be adventurous and try something new, up to you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Who is behind Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    <p>Vedmir was founded by a group of friends with extensive knowledge and background in nightlife, business and tech.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>I have a bar/restaurant and want to partner with Vedmir; how do I go about that?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    <p><a href="<?=BASEURL?>/signup" target="_blank">Click Here</a> to apply to have your venue partnered with us.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How do I cancel my Vedmir membership?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    <p>Easy. Go in the App and click Cancel Subscription under Plans & Billing from the main menu and follow the instructions, or you may open a cancellation ticket by e-mailing support@vedmir.com. Deleting the App itself does NOT automatically unsubscribe you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEight">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Where can I find more resources on responsible drinking?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                <div class="panel-body">
                    <p>Vedmir encourages drinking socially but never irresponsibly. Here are some sites to visit: <br> <a href="https://www.responsibility.org/" target="_blank">Responsibility.org</a> <br>  <a href="http://gettips.com/" target="_blank">GetTips.com</a></p>
                </div>
            </div>
        </div>


<?php }else if($this->sessLang == 'italian'){  ?>
        <div class="panel panel-default">
            <div class="panel-heading pactive" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Quanto costa l'iscrizione?</b>
                   </a>                
                </h4>
          </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                     <p>The App is free to download and try. If you decide to become a member, you receive one drink a day for CHF 29.90 a month, it’s like the price of two cocktails.</p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>What types of venues are on Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <p>We partner with top bars and restaurants around Switzerland. Currently we are live in 3 cities, and we are constantly expanding.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How soon after subscribing can I order my first cocktail with Vedmir?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <p>As soon as you activate the subscription on the app, you’re good to go. Immediately.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Can I go to multiple Vedmir venues and get drinks from every venue?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    <p>You get One Drink Every Day. You select the venue, but once you redeem your drink for the day, you cannot get another one until the next day. But you can come back to the same venue the next day, or be adventurous and try something new, up to you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Who is behind Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    <p>Vedmir was founded by a group of friends with extensive knowledge and background in nightlife, business and tech.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>I have a bar/restaurant and want to partner with Vedmir; how do I go about that?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    <p><a href="<?=BASEURL?>/signup" target="_blank">Click Here</a> to apply to have your venue partnered with us.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How do I cancel my Vedmir membership?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    <p>Easy. Go in the App and click Cancel Subscription under Plans & Billing from the main menu and follow the instructions, or you may open a cancellation ticket by e-mailing support@vedmir.com. Deleting the App itself does NOT automatically unsubscribe you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEight">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Where can I find more resources on responsible drinking?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                <div class="panel-body">
                    <p>Vedmir encourages drinking socially but never irresponsibly. Here are some sites to visit: <br> <a href="https://www.responsibility.org/" target="_blank">Responsibility.org</a> <br>  <a href="http://gettips.com/" target="_blank">GetTips.com</a></p>
                </div>
            </div>
        </div>


<?php }else { ?>
        <div class="panel panel-default">
            <div class="panel-heading pactive" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How much is membership?</b>
                   </a>                
                </h4>
          </div>
            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                     <p>The App is free to download and try. If you decide to become a member, you receive one drink a day for CHF 29.90 a month, it’s like the price of two cocktails.</p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>What types of venues are on Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                    <p>We partner with top bars and restaurants around Switzerland. Currently we are live in 3 cities, and we are constantly expanding.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingThree">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How soon after subscribing can I order my first cocktail with Vedmir?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                <div class="panel-body">
                    <p>As soon as you activate the subscription on the app, you’re good to go. Immediately.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFour">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Can I go to multiple Vedmir venues and get drinks from every venue?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                <div class="panel-body">
                    <p>You get One Drink Every Day. You select the venue, but once you redeem your drink for the day, you cannot get another one until the next day. But you can come back to the same venue the next day, or be adventurous and try something new, up to you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingFive">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Who is behind Vedmir?</b>
                   </a>   
                </h4>
          </div>
            <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                <div class="panel-body">
                    <p>Vedmir was founded by a group of friends with extensive knowledge and background in nightlife, business and tech.</p>

                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSix">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>I have a bar/restaurant and want to partner with Vedmir; how do I go about that?</b>  
                    </a>
                </h4>
          </div>
            <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                <div class="panel-body">
                    <p><a href="<?=BASEURL?>/signup" target="_blank">Click Here</a> to apply to have your venue partnered with us.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingSeven">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>How do I cancel my Vedmir membership?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                <div class="panel-body">
                    <p>Easy. Go in the App and click Cancel Subscription under Plans & Billing from the main menu and follow the instructions, or you may open a cancellation ticket by e-mailing support@vedmir.com. Deleting the App itself does NOT automatically unsubscribe you.</p>
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingEight">
                <h4 class="panel-title">
                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseFour">
                        <i class="more-less glyphicon glyphicon-plus myicon"></i>
                         <b>Where can I find more resources on responsible drinking?</b>  
                    </a>  
               </h4>
          </div>
            <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                <div class="panel-body">
                    <p>Vedmir encourages drinking socially but never irresponsibly. Here are some sites to visit: <br> <a href="https://www.responsibility.org/" target="_blank">Responsibility.org</a> <br>  <a href="http://gettips.com/" target="_blank">GetTips.com</a></p>
                </div>
            </div>
        </div>


<?php }  ?>

        
    </div>

 </div>
</div> <!--===========end question main===============-->


<?php include 'inc/footer.php';?>