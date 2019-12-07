<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>

            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('stripeWalletHistory')?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('amount')?></th>
                    <th><?=$this->lang->line('transactionId')?></th>
                    <th><?=$this->lang->line('addedOn')?></th>
                    <th><?=$this->lang->line('status')?></th>
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