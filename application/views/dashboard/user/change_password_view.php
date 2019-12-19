<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
          
    <form action="" method="POST" id="frmEditPassword" enctype="multipart/form-data">
        <div class="you_requested">
            <h3><?=$this->lang->line('change')?><span> <?=$this->lang->line('password')?></span></h3>
          
            <div class="expert_breif">

            </div>
            <div class="expert_contact">
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?=$this->lang->line('old').' '.$this->lang->line('password')?><span class="asterisk">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control input-sm" required id="txtCurrentPassword" name="txtCurrentPassword">
                    </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?=$this->lang->line('new').' '.$this->lang->line('password')?><span class="asterisk">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control input-sm" required id="txtNewPassword" name="txtNewPassword">
                    </div>
                </div><!-- /.form-group -->
                <div class="form-group">
                    <label class="col-sm-4 control-label"><?=$this->lang->line('confirm').' '.$this->lang->line('password')?><span class="asterisk">*</span></label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control input-sm" required id="txtConfirmPassword" name="txtConfirmPassword" onchange='check();'>
                    </div>
                </div><!-- /.form-group -->
				 <br>
                <div class=" col-sm-12 form-group" style=" margin-top: 12px;">
                    <button type="submit" id="btnChangePassword" name="btnChangePassword" class="btn btn-sm btn-primary"><?=$this->lang->line('buttonUpdate').' '.$this->lang->line('password')?></button>
                 </div>
            </div>
        </div><!--you_requested-->
    </form>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>

	<script>
	
		$("#frmEditPassword").validate({
                    rules: {
                        txtNewPassword: {
                            required: true
                        },
                        txtConfirmPassword: {
                            required: true,
                            equalTo: "#txtNewPassword"
                        }
                    },
                    messages: {
                            txtNewPassword: {
                            required: "Provide a password",
                            rangelength: jQuery.validator.format("Enter at least {0} characters")
                        },
                        txtConfirmPassword: {
                            required: "Repeat your password",
                            minlength: jQuery.validator.format("Enter at least {0} characters"),
                            equalTo: "Enter the same password as above"
                        }
                    },
                    highlight:function(element) {
                        $(element).parents('.form-group').addClass('has-error has-feedback');
                    },
                    unhighlight: function(element) {
                        $(element).parents('.form-group').removeClass('has-error');
                    },
                    submitHandler: function() {
					   ('#frmEditPassword').submit();
                    }
                });
	
			</script>
    </body>
    <!--/ END BODY -->

</html>