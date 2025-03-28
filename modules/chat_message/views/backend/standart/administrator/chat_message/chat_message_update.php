

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
        Chat Message        <small>Edit Chat Message</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="<?= admin_site_url('/chat_message'); ?>">Chat Message</a></li>
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
                            <h3 class="widget-user-username">Chat Message</h3>
                            <h5 class="widget-user-desc">Edit Chat Message</h5>
                            <hr>
                        </div>
                        <?= form_open(admin_base_url('/chat_message/edit_save/'.$this->uri->segment(4)), [
                            'name' => 'form_chat_message',
                            'class' => 'form-horizontal form-step',
                            'id' => 'form_chat_message',
                            'method' => 'POST'
                        ]); ?>

                        <?php
                        $user_groups = $this->model_group->get_user_group_ids();
                        ?>

                                                    
                        
                        <div class="form-group group-message_user_id  ">
                                <label for="message_user_id" class="col-sm-2 control-label">Message User Id                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" name="message_user_id" id="message_user_id" placeholder="" value="<?= set_value('message_user_id', $chat_message->message_user_id); ?>">
                                    <small class="info help-block">
                                        <b>Input Message User Id</b> Max Length : 11.</small>
                                </div>
                            </div>
                        
                        
                                                    
                        
                        <div class="form-group group-chat_id  ">
                                <label for="chat_id" class="col-sm-2 control-label">Chat Id                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="chat_id" id="chat_id" placeholder="" value="<?= set_value('chat_id', $chat_message->chat_id); ?>">
                                    <small class="info help-block">
                                        <b>Input Chat Id</b> Max Length : 100.</small>
                                </div>
                            </div>
                        
                        
                                                    
                        
                        <div class="form-group group-uid  ">
                                <label for="uid" class="col-sm-2 control-label">Uid                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="uid" id="uid" placeholder="" value="<?= set_value('uid', $chat_message->uid); ?>">
                                    <small class="info help-block">
                                        <b>Input Uid</b> Max Length : 100.</small>
                                </div>
                            </div>
                        
                        
                                                    
                        
                        <div class="form-group group-message  ">
                                <label for="message" class="col-sm-2 control-label">Message                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-8">
                                    <textarea id="message" name="message" rows="10" cols="80"> <?= set_value('message', $chat_message->message); ?></textarea>
                                    <small class="info help-block">
                                        </small>
                                </div>
                            </div>
                        
                        
                                                    
                        
                        <div class="form-group group-status  ">
                                <label for="status" class="col-sm-2 control-label">Status                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="status" id="status" placeholder="" value="<?= set_value('status', $chat_message->status); ?>">
                                    <small class="info help-block">
                                        <b>Input Status</b> Max Length : 100.</small>
                                </div>
                            </div>
                        
                        
                                                    
                        
                        <div class="form-group group-created_at  ">
                                <label for="created_at" class="col-sm-2 control-label">Created At                                    <i class="required">*</i>
                                    </label>
                                <div class="col-sm-6">
                                    <div class="input-group date col-sm-8">
                                        <input type="text" class="form-control pull-right datetimepicker" name="created_at" placeholder="" id="created_at" value="<?= set_value('created_at', $chat_message->created_at); ?>">
                                    </div>
                                    <small class="info help-block">
                                        </small>
                                </div>
                            </div>
                        
                        
                        
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

    <script src="<?= BASE_ASSET; ?>ckeditor/ckeditor.js"></script>

<script>
    $(document).ready(function() {

        "use strict";
        
    window.event_submit_and_action = '';
            
    
      
      
      
        
        
    CKEDITOR.replace('message');
    var message = CKEDITOR.instances.message;
        
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
                    window.location.href = ADMIN_BASE_URL + '/chat_message';
                }
            });

        return false;
    }); /*end btn cancel*/

    $('.btn_save').click(function() {
        $('.message').fadeOut();
        $('#message').val(message.getData());
        
    var form_chat_message = $('#form_chat_message');
    var data_post = form_chat_message.serializeArray();
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
            url: form_chat_message.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: data_post,
        })
        .done(function(res) {
            $('form').find('.form-group').removeClass('has-error');
            $('form').find('.error-input').remove();
            $('.steps li').removeClass('error');
            if (res.success) {
                var id = $('#chat_message_image_galery').find('li').attr('qq-file-id');
                if (save_type == 'back') {
                    window.location.href = res.redirect;
                    return;
                }

                $('.message').printMessage({
                    message: res.message
                });
                $('.message').fadeIn();
                $('.data_file_uuid').val('');

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