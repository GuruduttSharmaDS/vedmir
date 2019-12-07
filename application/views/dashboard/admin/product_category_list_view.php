<?php $this->load->viewD($this->sessRole.'/inc/header'); ?>
<?php $this->load->viewD($this->sessRole.'/inc/sidebar'); ?>
            <div class="man_table">
              <div class="order_status">
                <h2><?=$this->lang->line('product').' '.$this->lang->line('categoryList') ?><span><i class="fa fa-gear" aria-hidden="true"></i></span></h2>
              </div>
              <table class="table">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('name')?></th>
                    <th><?=$this->lang->line('addedOn')?></th>
                    <th><?=$this->lang->line('status')?></th>
                    <th><?=$this->lang->line('action')?></th>
                  </tr>
                </thead>
                <tbody class="tablebody">



                </tbody>
              </table>
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

                                </select> <?=$this->lang->line('recordsPerPage')?>
                                </select> 

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
            </div>
<?php $this->load->viewD($this->sessRole.'/inc/footer'); ?>
    </body>
    <!--/ END BODY -->

</html>