<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
        <form action="" method="POST" enctype="multipart/form-data"  id="paymentFrm" novalidate="">
          <div class="you_requested">
            <h3><?= $this->lang->line('addMoneyToWallet');?></h3>
          

            <div class="">

                  <div class="paymentoption">
                    <div class="form-group">
                      <label><?=$this->lang->line('amount');?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="amount" size="20" autocomplete="off" name="amount" value="" onkeypress="return OnlyFloat()" required="required">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('cardType');?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="card_type" size="20" autocomplete="off" name="card_type" value="" onkeypress="return OnlyAlphabet()" required="required">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('cardHolderName');?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="card_holder_name" size="20" autocomplete="off" name="card_holder_name" value="" onkeypress="return OnlyAlphabet()" required="required">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('cardNumber');?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="card_num" maxlength="20" autocomplete="off" name="card_num" value="" onkeypress="return OnlyInteger(event)" required="required">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('cvv');?> <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="cvc" autocomplete="off" name="cvc" value="" onkeypress="return checkCardCVV()" required="required">
                    </div>
                    <div class="form-group">
                      <label style="width: 100%;float: left;"><?=$this->lang->line('expiration');?> (MM/YY) <span class="text-danger">*</span></label>
                      <select class="form-control col-md-1" name="exp_month" id="exp_month" required="required" style="width: 100px;" onchange="return checkCardMonthYear('#exp_month', '#exp_year')">
                        <?php
                        $months = array('01','02','03','04','05','06','07','08','09','10','11','12');
                            $current =  date('m');
                            foreach ( $months as $month ) {
                              $class = ($month < date('m'))?' class="hide"':'';
                              echo '<option value="'.$month.'" '.(($current == $month)?"selected":"").' '.$class.'>'.$month .'</option>';
                            }
                        ?>
                        
                      </select>
                      <div class ="col-md-1" style="font-size: 32px;margin-left: 11px;"> / </div>
                      <select class="form-control col-md-1" name="exp_year" id="exp_year" required="required" style="width: 100px;" onchange="return checkCardMonthYear('#exp_month', '#exp_year')" >
                        <?php
                            $first =  substr(date('Y'), -2) ;
                            for ($i=$first; $i < $first+10; $i++) {
                              echo '<option value="'.$i.'" >'.$i .'</option>';
                            }
                        ?>
                        
                      </select>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                  <input type="hidden" name="actionval" id="actionval" value="addevent">
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                   <button type="button" name="btnAddProduct" class="btn btn-primary btnAddProduct"><?= $this->lang->line('addMoney'); ?></button>
                    
                </div>
         
            </div>
          </div><!--you_requested-->

      </form>
      <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
      <?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>


      <script type="text/javascript">
        Stripe.setPublishableKey('pk_test_njXCUkc2mXGPJvCa8A0j6YYI');

      //callback to handle the response from stripe
      function stripeResponseHandler(status, response) {
          if (response.error) {
              //enable the submit button
              $('.create-event').removeAttr("disabled");
              //display the errors on the form
              $(".payment-errors").html(response.error.message);
          } else {
              var form = $("#paymentFrm");
              //get token id
              var token = response['id;']
              //insert the token into the form
              form.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
              //submit form to the server
              $("#paymentFrm").submit();
          }
      }
      </script>
    </body>
    <!--/ END BODY -->

</html>