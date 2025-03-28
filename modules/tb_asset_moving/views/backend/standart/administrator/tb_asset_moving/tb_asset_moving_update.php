

<script type="text/javascript">
    function domo() {

        $('*').bind('keydown', 'Ctrl+s', function() {
            $('#btn_save').trigger('click');
            return false;
        });

        $('*').bind('keydown', 'Ctrl+x', function() {
            $('#btn_cancel').trigger('click');
            return false;
        });

        $('*').bind('keydown', 'Ctrl+d', function() {
            $('.btn_save_back').trigger('click');
            return false;
        });

    }

    jQuery(document).ready(domo);
</script>
<section class="content-header">
    <h1>
        Tb Asset Moving        <small>Edit Tb Asset Moving</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="<?= admin_site_url('/tb_asset_moving'); ?>">Tb Asset Moving</a></li>
        <li class="active">Edit</li>
    </ol>
</section>

<style>
    </style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-warning">
                <div class="box-body ">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header ">
                            <div class="widget-user-image">
                                <img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
                            </div>
                            <h3 class="widget-user-username">Tb Asset Moving</h3>
                            <h5 class="widget-user-desc">Edit Tb Asset Moving</h5>
                            <hr>
                        </div>
                        <?= form_open(admin_base_url('/tb_asset_moving/edit_save/'.$this->uri->segment(4)), [
                        'name' => 'form_tb_asset_moving_edit',
                        'class' => 'form-horizontal form-step',
                        'id' => 'form_tb_asset_moving_edit',
                        'method' => 'POST'
                        ]); ?>

                        <?php
                        $user_groups = $this->model_group->get_user_group_ids();
                        ?>

                                                    

<div class="form-group group-tanggal  ">
        <label for="tanggal" class="col-sm-2 control-label">Tanggal            <i class="required">*</i>
            </label>
        <div class="col-sm-6">
            <div class="input-group date col-sm-8">
                <input type="text" class="form-control pull-right datepicker" name="tanggal" placeholder="" id="tanggal" value="<?= set_value('tb_asset_moving_tanggal_name', $tb_asset_moving->tanggal); ?>">
            </div>
            <small class="info help-block">
                </small>
        </div>
    </div>



                            

<div class="form-group group-waktu  ">
        <label for="waktu" class="col-sm-2 control-label">Waktu            <i class="required">*</i>
            </label>
        <div class="col-sm-6">
            <div class="input-group date col-sm-8">
                <input type="text" class="form-control pull-right timepicker" name="waktu" id="waktu" value="<?= set_value('tb_asset_moving_waktu_name', $tb_asset_moving->waktu); ?>">
            </div>
            <small class="info help-block">
                </small>
        </div>
    </div>


                            

<div class="form-group group-reader_id">
<<<<<<< HEAD
        <label for="reader_id" class="col-sm-2 control-label">Reader Id            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="reader_id" id="reader_id" data-placeholder="Select Reader Id">
=======
        <label for="reader_id" class="col-sm-2 control-label">Reader            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="reader_id" id="reader_id" data-placeholder="Select Reader">
>>>>>>> 8b0d86583f8ade3ce48095c5863c622d05cbbdc5
                <option value=""></option>
                <?php
                $conditions = [
                ];
                ?>
                <?php foreach (db_get_all_data('tag_reader', $conditions) as $row): ?>
                <option <?= $row->reader_id == $tb_asset_moving->reader_id ? 'selected' : ''; ?> value="<?= $row->reader_id ?>"><?= $row->reader_name; ?></option>
                <?php endforeach; ?>
            </select>
            <small class="info help-block">
                </small>
        </div>
    </div>




                            

<div class="form-group group-room_id">
<<<<<<< HEAD
        <label for="room_id" class="col-sm-2 control-label">Room Id            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="room_id" id="room_id" data-placeholder="Select Room Id">
=======
        <label for="room_id" class="col-sm-2 control-label">Ruangan            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="room_id" id="room_id" data-placeholder="Select Ruangan">
>>>>>>> 8b0d86583f8ade3ce48095c5863c622d05cbbdc5
                <option value=""></option>
                <?php
                $conditions = [
                ];
                ?>
                <?php foreach (db_get_all_data('tb_room_master', $conditions) as $row): ?>
                <option <?= $row->id_room == $tb_asset_moving->room_id ? 'selected' : ''; ?> value="<?= $row->id_room ?>"><?= $row->name_room; ?></option>
                <?php endforeach; ?>
            </select>
            <small class="info help-block">
                </small>
        </div>
    </div>


<<<<<<< HEAD


                            

<div class="form-group group-tag_code">
        <label for="tag_code" class="col-sm-2 control-label">Tag Code            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="tag_code" id="tag_code" data-placeholder="Select Tag Code">
                <option value=""></option>
                <?php
                $conditions = [
                ];
                ?>
                <?php foreach (db_get_all_data('tb_asset_master', $conditions) as $row): ?>
                <option <?= $row->tag_code == $tb_asset_moving->tag_code ? 'selected' : ''; ?> value="<?= $row->tag_code ?>"><?= $row->nama_brg; ?></option>
                <?php endforeach; ?>
            </select>
            <small class="info help-block">
                <b>Input Tag Code</b> Max Length : 96.</small>
        </div>
    </div>




                            

	<div class="form-group group-status_moving  ">
		<label for="status_moving" class="col-sm-2 control-label">Status Moving			</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="status_moving" id="status_moving" placeholder="" value="<?= set_value('status_moving', $tb_asset_moving->status_moving); ?>">
			<small class="info help-block">
				</small>
		</div>
	</div>


=======


                            

<div class="form-group group-tag_code">
        <label for="tag_code" class="col-sm-2 control-label">Aset            <i class="required">*</i>
            </label>
        <div class="col-sm-8">
            <select class="form-control chosen chosen-select-deselect" name="tag_code" id="tag_code" data-placeholder="Select Aset">
                <option value=""></option>
                <?php
                $conditions = [
                ];
                ?>
                <?php foreach (db_get_all_data('tb_asset_master', $conditions) as $row): ?>
                <option <?= $row->tag_code == $tb_asset_moving->tag_code ? 'selected' : ''; ?> value="<?= $row->tag_code ?>"><?= $row->nama_brg; ?></option>
                <?php endforeach; ?>
            </select>
            <small class="info help-block">
                <b>Input Tag Code</b> Max Length : 96.</small>
        </div>
    </div>




                            

	<div class="form-group group-status_moving  ">
		<label for="status_moving" class="col-sm-2 control-label">Status Moving			</label>
		<div class="col-sm-8">
			<input type="text" class="form-control" name="status_moving" id="status_moving" placeholder="" value="<?= set_value('status_moving', $tb_asset_moving->status_moving); ?>">
			<small class="info help-block">
				</small>
		</div>
	</div>


>>>>>>> 8b0d86583f8ade3ce48095c5863c622d05cbbdc5

    <div class="message"></div>
<div class="row-fluid col-md-7 container-button-bottom">
    <button class="btn btn-flat btn-primary btn_save btn_action" id="btn_save" data-stype='stay' title="<?= cclang('save_button'); ?> (Ctrl+s)">
        <i class="fa fa-save"></i> <?= cclang('save_button'); ?>
    </button>
    <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
        <i class="ion ion-ios-list-outline"></i> <?= cclang('save_and_go_the_list_button'); ?>
    </a>

    <div class="custom-button-wrapper">

            </div>
    <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
        <i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?>
    </a>
    <span class="loading loading-hide">
        <img src="<?= BASE_ASSET; ?>/img/loading-spin-primary.svg">
        <i><?= cclang('loading_saving_data'); ?></i>
    </span>
</div>
<?= form_close(); ?>
</div>
</div>
<!--/box body -->
</div>
<!--/box -->
</div>
</div>
</section>

<script>
  var module_name = "tb_asset_moving"
  var use_ajax_crud = false

</script>


<script>
    $(document).ready(function() {

        "use strict";

        window.event_submit_and_action = '';

        


        
        
        
    
    $('#btn_cancel').click(function() {
        swal({
                title: "Are you sure?",
                text: "the data that you have created will be in the exhaust!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes!",
                cancelButtonText: "No!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    window.location.href = ADMIN_BASE_URL + '/tb_asset_moving';
                }
            });

        return false;
    }); /*end btn cancel*/

    $('.btn_save').click(function() {
        $('.message').fadeOut();
        
    var form_tb_asset_moving = $('#form_tb_asset_moving_edit');
    var data_post = form_tb_asset_moving.serializeArray();
    var save_type = $(this).attr('data-stype');
    data_post.push({
        name: 'save_type',
        value: save_type
    });

    

    data_post.push({
        name: 'event_submit_and_action',
        value: window.event_submit_and_action
    });

    $('.loading').show();

    $.ajax({
            url: form_tb_asset_moving.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: data_post,
        })
        .done(function(res) {
            $('form').find('.form-group').removeClass('has-error');
            $('form').find('.error-input').remove();
            $('.steps li').removeClass('error');
            if (res.success) {
                var id = $('#tb_asset_moving_image_galery').find('li').attr('qq-file-id');
                if (save_type == 'back') {
                    window.location.href = res.redirect;
                    return;
                }

                
                if (use_ajax_crud) {
                    toastr['success'](res.message)
                } else {

                    $('.message').printMessage({
                        message: res.message
                    });
                    $('.message').fadeIn();
                }
                $('.data_file_uuid').val('');
                showPopup(false)
                
                if (use_ajax_crud == true) {

                    var url = BASE_URL + ADMIN_NAMESPACE_URL + '/tb_asset_moving/index/?ajax=1'
                    reloadDataTable(url);
                }



            } else {
                if (res.errors) {
                    parseErrorField(res.errors);
                }
                $('.message').printMessage({
                    message: res.message,
                    type: 'warning'
                });
            }

        })
        .fail(function() {
            $('.message').printMessage({
                message: 'Error save data',
                type: 'warning'
            });
        })
        .always(function() {
            $('.loading').hide();
            $('html, body').animate({
                scrollTop: $(document).height()
            }, 2000);
        });

    return false;
    }); /*end btn save*/

    

    

    async function chain() {
            }

    chain();




    }); /*end doc ready*/
</script>