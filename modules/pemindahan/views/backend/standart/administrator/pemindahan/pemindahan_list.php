<script type="text/javascript">
function domo(){
 
   $('*').bind('keydown', 'Ctrl+a', function() {
       window.location.href = ADMIN_BASE_URL + '/Pemindahan/add';
       return false;
   });

   $('*').bind('keydown', 'Ctrl+f', function() {
       $('#sbtn').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+x', function() {
       $('#reset').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+b', function() {

       $('#reset').trigger('click');
       return false;
   });
}

jQuery(document).ready(domo);
</script>
<section class="content-header">
   <h1>
      <?= cclang('pemindahan') ?><small><?= cclang('list_all'); ?></small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?= cclang('pemindahan') ?></li>
   </ol>
</section>
<!-- Main content -->
<section class="content">
   <div class="row" >
      
      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body ">
               <div class="box box-widget widget-user-2">
                  <div class="widget-user-header ">
                     <div class="row pull-right">
                        <?php is_allowed('pemindahan_add', function(){?>
                        <a class="btn btn-flat btn-success btn_add_new" id="btn_add_new" title="<?= cclang('add_new_button', [cclang('pemindahan')]); ?>  (Ctrl+a)" href="<?=  admin_site_url('/pemindahan/add'); ?>"><i class="fa fa-plus-square-o" ></i> <?= cclang('add_new_button', [cclang('pemindahan')]); ?></a>
                        <?php }) ?>
                        <?php is_allowed('pemindahan_export', function(){?>
                        <a class="btn btn-flat btn-success" title="<?= cclang('export'); ?> <?= cclang('pemindahan') ?> " href="<?= admin_site_url('/pemindahan/export?q='.$this->input->get('q').'&f='.$this->input->get('f')); ?>"><i class="fa fa-file-excel-o" ></i> <?= cclang('export'); ?> XLS</a>
                        <?php }) ?>
                                                <?php is_allowed('pemindahan_export', function(){?>
                        <a class="btn btn-flat btn-success" title="<?= cclang('export'); ?> pdf <?= cclang('pemindahan') ?> " href="<?= admin_site_url('/pemindahan/export_pdf?q='.$this->input->get('q').'&f='.$this->input->get('f')); ?>"><i class="fa fa-file-pdf-o" ></i> <?= cclang('export'); ?> PDF</a>
                        <?php }) ?>
                                             </div>
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET; ?>/img/list.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username"><?= cclang('pemindahan') ?></h3>
                     <h5 class="widget-user-desc"><?= cclang('list_all', [cclang('pemindahan')]); ?>  <i class="label bg-yellow"><span class="total-rows"><?= $pemindahan_counts; ?></span>  <?= cclang('items'); ?></i></h5>
                  </div>

                  <form name="form_pemindahan" id="form_pemindahan" action="<?= admin_base_url('/pemindahan/index'); ?>">
                  


                     <!-- /.widget-user -->
                  <div class="row">
                     <div class="col-md-8">
                                                <!-- <div class="col-sm-2 padd-left-0 " >
                           <select type="text" class="form-control chosen chosen-select" name="bulk" id="bulk" placeholder="Site Email" >
                                                         <option value="delete">Delete</option>
                                                      </select>
                        </div>
                        <div class="col-sm-2 padd-left-0 ">
                           <button type="button" class="btn btn-flat" name="apply" id="apply" title="<?= cclang('apply_bulk_action'); ?>"><?= cclang('apply_button'); ?></button>
                        </div> -->
                                                <div class="col-sm-3 padd-left-0  " >
                           <input type="text" class="form-control" name="q" id="filter" placeholder="<?= cclang('filter'); ?>" value="<?= $this->input->get('q'); ?>">
                        </div>
                        <div class="col-sm-3 padd-left-0 " >
                           <select type="text" class="form-control chosen chosen-select" name="f" id="field" >
                              <option value=""><?= cclang('all'); ?></option>
                              <option <?= $this->input->get('f') == 'tipe_transaksi' ? 'selected' :''; ?> value="tipe_transaksi">Tipe</option>
                              <option <?= $this->input->get('f') == 'status_transaksi' ? 'selected' :''; ?> value="status_transaksi">Status Pemindahan</option>
                              <option <?= $this->input->get('f') == 'tgl_awal_transaksi' ? 'selected' :''; ?> value="tgl_awal_transaksi">Tanggal</option>
                              <option <?= $this->input->get('f') == 'ket_transaksi' ? 'selected' :''; ?> value="ket_transaksi">Keterangan</option>
                              <option <?= $this->input->get('f') == 'id_area' ? 'selected' :''; ?> value="id_area">Area Asal</option>
                              <option <?= $this->input->get('f') == 'id_gedung' ? 'selected' :''; ?> value="id_gedung">Gedung Asal</option>
                              <option <?= $this->input->get('f') == 'id_ruangan' ? 'selected' :''; ?> value="id_ruangan">Ruangan Asal</option>
                              <option <?= $this->input->get('f') == 'id_area2' ? 'selected' :''; ?> value="id_area">Area Tujuan</option>
                              <option <?= $this->input->get('f') == 'id_gedung2' ? 'selected' :''; ?> value="id_gedung">Gedung Tujuan</option>
                              <option <?= $this->input->get('f') == 'id_ruangan2' ? 'selected' :''; ?> value="id_ruangan">Ruangan Tujuan</option>
                           </select>
                        </div>
                        <div class="col-sm-1 padd-left-0 ">
                           <button type="submit" class="btn btn-flat" name="sbtn" id="sbtn" value="Apply" title="<?= cclang('filter_search'); ?>">
                           Filter
                           </button>
                        </div>
                        <div class="col-sm-1 padd-left-0 ">
                           <a class="btn btn-default btn-flat" name="reset" id="reset" value="Apply" href="<?= admin_base_url('/pemindahan');?>" title="<?= cclang('reset_filter'); ?>">
                           <i class="fa fa-undo"></i>
                           </a>
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="dataTables_paginate paging_simple_numbers pull-right" id="example2_paginate" >
                           <div class="table-pagination"><?= $pagination; ?></div>
                        </div>
                     </div>
                  </div>
                  <div class="table-responsive"> 

                  <br>
                  <table class="table table-bordered table-striped dataTable">
                     <thead>
                        <tr class="">                             
                           <th style="text-align: center">
                              <input type="checkbox" class="flat-red toltip" id="check_all" name="check_all" title="check all">
                           </th>
                           <th style="text-align: center" data-field="tipe_transaksi"data-sort="1" data-primary-key="0"> <?= cclang('Tipe') ?></th>
                           <th style="text-align: center" data-field="tgl_awal_transaksi"data-sort="1" data-primary-key="0"> <?= cclang('Tanggal') ?></th>
                           <th style="text-align: center" data-field="ket_transaksi"data-sort="1" data-primary-key="0"> <?= cclang('Keterangan') ?></th>
                           <!-- <th data-field="id_pegawai_input"data-sort="1" data-primary-key="0"> <?= cclang('id_pegawai_input') ?></th>
                           <th data-field="nama_pegawai_input"data-sort="1" data-primary-key="0"> <?= cclang('nama_pegawai_input') ?></th> -->
                           <th style="text-align: center" data-field="id_area"data-sort="1" data-primary-key="0"> <?= cclang('Area Asal') ?></th>
                           <th style="text-align: center" data-field="id_gedung"data-sort="1" data-primary-key="0"> <?= cclang('Gedung Asal') ?></th>
                           <th style="text-align: center" data-field="id_ruangan"data-sort="1" data-primary-key="0"> <?= cclang('Ruangan Asal') ?></th>   
                           <th style="text-align: center" data-field="id_area2"data-sort="1" data-primary-key="0"> <?= cclang('Area Tujuan') ?></th>
                           <th style="text-align: center" data-field="id_gedung2"data-sort="1" data-primary-key="0"> <?= cclang('Gedung Tujuan') ?></th>
                           <th style="text-align: center" data-field="id_ruangan2"data-sort="1" data-primary-key="0"> <?= cclang('Ruangan Tujuan') ?></th>                           
                           <th data-field="status_transaksi"data-sort="1" data-primary-key="0"> <?= cclang('Status Pemindahan') ?></th>
                           <th style="text-align: center">Action</th>                        
                        </tr>
                     </thead>
                     <tbody id="tbody_pemindahan">
                            <?= $tables ?>
                      </tbody>
                  </table>
                  </div>
               </div>
               <hr>
             
            </div>
            </form>            
         </div>
      </div>
   </div>
</section>
<script>
  var module_name = "pemindahan"
  var use_ajax_crud = false  
</script>
<script src="<?= BASE_ASSET ?>js/filter.js"></script>


<script>
  $(document).ready(function(){

    "use strict";
   
    
      
      if (use_ajax_crud ==false) {

         $(document).on('click', 'a.remove-data', function(){
   
         var url = $(this).attr('data-href');
   
         swal({
             title: "<?= cclang('are_you_sure'); ?>",
             text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
             cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
             closeOnConfirm: true,
             closeOnCancel: true
           },
           function(isConfirm){
             if (isConfirm) {
               document.location.href = url;            
             }
           });
   
         return false;
       });
      }



    $(document).on('click', '#apply', function(){

      var bulk = $('#bulk');
      var serialize_bulk = $('#form_pemindahan').serialize();

      if (bulk.val() == 'delete') {
         swal({
            title: "<?= cclang('are_you_sure'); ?>",
            text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "<?= cclang('yes_delete_it'); ?>",
            cancelButtonText: "<?= cclang('no_cancel_plx'); ?>",
            closeOnConfirm: true,
            closeOnCancel: true
          },
          function(isConfirm){
            if (isConfirm) {
               document.location.href = ADMIN_BASE_URL + '/pemindahan/delete?' + serialize_bulk;      
            }
          });

        return false;

      } else if(bulk.val() == '')  {
          swal({
            title: "Upss",
            text: "<?= cclang('please_choose_bulk_action_first'); ?>",
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Okay!",
            closeOnConfirm: true,
            closeOnCancel: true
          });

        return false;
      }

      return false;

    });/*end appliy click*/


    //check all
    var checkAll = $('#check_all');
    var checkboxes = $('input.check');

    checkAll.on('ifChecked ifUnchecked', function(event) {   
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
    initSortableAjax('pemindahan', $('table.dataTable'));
  }); /*end doc ready*/
</script>