<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('feedbackList')?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('restaurant')?></th>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('userMessage')?></th>
                    <th><?=$this->lang->line('priceRating')?></th>
                    <th><?=$this->lang->line('qualityRating')?></th>
                    <th><?=$this->lang->line('serviceRating')?></th>
                    <th><?=$this->lang->line('ambienceRating')?></th>                    
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