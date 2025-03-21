<section class="content-header">
  <h1>
    Database <small><?= cclang('new', ['Database']); ?> </small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?= admin_site_url('/database'); ?>">Database</a></li>
    <li class="active"><?= cclang('new'); ?></li>
  </ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-body ">

          <div class="box box-widget widget-user-2">

            <div class="widget-user-header ">
              <div class="widget-user-image">
                <img class="img-circle" src="<?= BASE_ASSET ?>img/add2.png" alt="User Avatar">
              </div>

              <h3 class="widget-user-username">Database</h3>
              <h5 class="widget-user-desc"><?= cclang('new', ['Database']); ?></h5>
              <hr>
            </div>
            <div class="field-wrapper-template display-none">
              <table>
                <tr class="table-field-item">
                  <td><input type="text" name="table[{id}][name]" class="name form-control field-auto-name"></td>
                  <td>
                    <select class="form-control field-auto-type" name="table[{id}][type]" id="">
                      <?php foreach ($field_type as $group => $types) : ?>
                        <optgroup label="<?= ucwords($group) ?>">
                          <?php foreach ($types as $type) : ?>
                            <option value="<?= $type ?>"><?= $type ?></option>
                          <?php endforeach ?>
                        </optgroup>
                      <?php endforeach ?>
                    </select>
                  </td>
                  <td><input type="text" name="table[{id}][length]" class="form-control field-auto-length"></td>
                  <td>
                    <select class="form-control table-default" name="table[{id}][default]" id="default" name="table[{id}][default]" class="field-auto-default">
                      <option value="">none</option>
                      <option value="null">null</option>
                      <option value="as_defined">as defined</option>
                    </select>
                    <div class="defined-value-wrapper display-none">
                      <input type="text" name="table[{id}][defined_value]" class="defined-value form-control">
                    </div>
                  </td>
                  <td>
                    <input name="table[{id}][null]" type="checkbox" class="field-auto-null" value="true">
                  </td>
                  <td>
                    <input name="table[{id}][auto_increment]" class="field-auto-increment" type="checkbox" value="true">
                  </td>
                  <td>
                    <a href="" class="btn-remove-field"><i class="fa fa-trash"></i></a>
                  </td>
                </tr>
              </table>
            </div>

            <?= form_open('', [
              'name'    => 'form_database',
              'class'   => 'form-horizontal form-step',
              'id'      => 'form_database',
              'enctype' => 'multipart/form-data',
              'method'  => 'POST'
            ]); ?>


            <input type="hidden" value="<?= ccencrypt($table_name) ? 'yes' : '' ?>" name="new_field">
            <input type="hidden" value="<?= $this->input->get('position') ?>" name="position">
            <input type="hidden" value="<?= $this->input->get('field') ?>" name="field">

            <div class="form-group ">
              <label for="table_name" class="col-sm-2 control-label">Table Name
                <i class="required">*</i>
              </label>
              <div class="col-sm-4">
                <input <?= ccencrypt($table_name) ? 'readonly' : '' ?> value="<?= ccdecrypt($table_name) ?>" type="text" class="form-control" name="table_name" id="table_name" placeholder="Table Name">
                <small class="info help-block">
                </small>
              </div>
            </div>

            <div class="form-group ">

              <div class="col-md-12">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Type</th>
                      <th>Length / Value</th>
                      <th>Default</th>
                      <th>Null</th>
                      <th>Auto Increment / Primary Key</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody class="table-field-wrapper">

                  </tbody>
                </table>
              </div>
            </div>


            <div class="message"></div>
            <div class="">

              <a class="btn btn-flat btn-info btn_save btn_action btn_save_back" id="btn_save" data-stype='back' title="<?= cclang('save_and_go_the_list_button'); ?> (Ctrl+d)">
                <i class="ion ion-ios-list-outline"></i> <?= cclang('save_button'); ?>
              </a>
              <a class="btn btn-flat btn-default btn_action" id="btn_cancel" title="<?= cclang('cancel_button'); ?> (Ctrl+x)">
                <i class="fa fa-undo"></i> <?= cclang('cancel_button'); ?>
              </a>
              <span class="loading loading-hide">
                <img src="<?= BASE_ASSET ?>img/loading-spin-primary.svg">
                <i><?= cclang('loading_saving_data'); ?></i>
              </span>
            </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="<?= BASE_ASSET ?>js/database-manager.js"></script>
<script src="<?= BASE_ASSET ?>js/page/database/database-add.js"></script>