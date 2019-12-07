<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
            <div class="">
              <div class="order_status">
                <h2><?=$this->lang->line('userMembershipInfo')?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table" id="sampleTable">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('count')?></th> 
                    <th><?=$this->lang->line('membership')?></th>
                    <th><?=$this->lang->line('membership').' '.$this->lang->line('status')?></th>
                    <th><?=$this->lang->line('userAppliedCoupon')?></th>
                    <th><?=$this->lang->line('startDate')?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
              </table>
            </div>
        <?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
        <script type="text/javascript">

          $(document).ready(function(){
        
            $('#sampleTable').DataTable({
        
                "processing": true,
        
                "serverSide": true,
                
                "pageLength": 10,
                "searching": false,
        
                "ajax":{
        
                    "url": DASHURL+'/admin/commonajax',
        
                    "dataType": "json",
        
                    "type": "POST",
        
                    "data":{'action' : 'getUserMembershipLog', 'userId':  '<?php echo $profile->userId;?>'}
        
                },
        
                "columns": [
        
                      {"data": "count"},
        
                      {"data": "membership"},
                      
                      {"data": "membershipStatus"},
                      {"data": "userAppliedCoupon"},
                      {"data": "startDate"},
        
                   ],
        
                   "order": [[0, 'asc']]
        
        
        
            });
            
          });
        
        </script>
    </body>
</html>