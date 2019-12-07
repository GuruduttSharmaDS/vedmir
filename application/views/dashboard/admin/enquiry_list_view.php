<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('user').' '.$this->lang->line('enquiryList');?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('email')?></th>
                    <th><?=$this->lang->line('subject')?></th>
                    <th><?=$this->lang->line('message')?></th>
                    <th><?=$this->lang->line('addedOn')?></th>                    
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
              </table>
            </div>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
    </body>
    <!--/ END BODY -->

</html>