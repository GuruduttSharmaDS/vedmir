<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('blocked').' '.$this->lang->line('userList')?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('image')?></th>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('email')?></th>
                    <th><?=$this->lang->line('country')?></th>
                    <th><?=$this->lang->line('membership')?></th>
                    <th><?=$this->lang->line('addedOn')?></th>
                    <th><?=$this->lang->line('status')?></th>
                    <th><?=$this->lang->line('action')?></th>
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