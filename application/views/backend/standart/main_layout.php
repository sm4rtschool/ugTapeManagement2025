<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="<?= get_option('site_description'); ?>">
  <meta name="keywords" content="<?= get_option('keywords'); ?>">
  <meta name="author" content="<?= get_option('author'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <meta http-equiv="refresh" content="1800;url=<?= admin_site_url('/auth/logout/1' . get_user_data('id')); ?>" /> -->
  <title><?= get_option('site_name'); ?> | <?= $template['title']; ?></title>
  <link rel="icon" href="<?= BASE_URL ?>/asset/img/icon/logosekneg.png" type="image/x-icon" />
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <?php if (basename(dirname($_SERVER['REQUEST_URI'])) != 'registrasi_aset' && basename(dirname($_SERVER['REQUEST_URI'])) != 'registrasi_people' && basename(dirname($_SERVER['REQUEST_URI'])) != 'penggantian_tag') { ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.7.2/css/all.min.css" rel="stylesheet" type="text/css" />
  <?php } ?>

  <!-- <?php if (get_user_data('oauth_uid') == '') { ?>
    <?php
    // header("Location: http://localhost/rfid_monitoring/administrator/auth/logout/1");
    // die();
    ?>
  <?php } ?> -->

  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.0/css/dataTables.dataTables.css">

  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>font-awesome-4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/dist/css/AdminLTE.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/iCheck/flat/blue.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/morris/morris.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/datepicker/datepicker3.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>admin-lte/plugins/iCheck/all.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>sweet-alert/sweetalert.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>toastr/build/toastr.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>fancy-box/source/jquery.fancybox.css?v=2.1.5" media="screen" />
  <link rel="stylesheet" href="<?= BASE_ASSET ?>chosen/chosen.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>css/custom.css?timestamp=202127080855">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>datetimepicker/jquery.datetimepicker.css" />
  <link rel="stylesheet" href="<?= BASE_ASSET ?>js-scroll/style/jquery.jscrollpane.css" rel="stylesheet" media="all" />
  <link rel="stylesheet" href="<?= BASE_ASSET ?>flag-icon/css/flag-icon.css" rel="stylesheet" media="all" />
  <link rel="stylesheet" href="<?= BASE_ASSET ?>css/font.css">
  <link rel="stylesheet" href="<?= BASE_ASSET ?>stepper/css/jquery.steps.css">

  <?= $this->cc_html->getCssFileTop(); ?>

  <script src="<?= BASE_ASSET ?>admin-lte/plugins/jQuery/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/iCheck/icheck.min.js"></script>
  <script src="<?= BASE_ASSET ?>sweet-alert/sweetalert-dev.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/input-mask/jquery.inputmask.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <script src="<?= BASE_ASSET ?>toastr/toastr.js"></script>
  <script src="<?= BASE_ASSET ?>fancy-box/source/jquery.fancybox.js?v=2.1.5"></script>
  <script src="<?= BASE_ASSET ?>datetimepicker/build/jquery.datetimepicker.full.js"></script>
  <script src="<?= BASE_ASSET ?>editor/dist/js/medium-editor.js"></script>
  <script src="<?= BASE_ASSET ?>js/cc-extension.js"></script>
  <script src="<?= BASE_ASSET ?>js/cc-page-element.js"></script>
  <script src="<?= BASE_ASSET ?>stepper/jquery.steps.min.js"></script>
  <script src="<?= BASE_ASSET ?>js/jquery.hotkeys.js"></script>

  <script>
    "use strict";

    var BASE_URL = "<?= base_url(); ?>";
    var ADMIN_BASE_URL = "<?= base_url(ADMIN_NAMESPACE_URL); ?>";
    var ADMIN_NAMESPACE_URL = "<?= ADMIN_NAMESPACE_URL ?>";
    var HTTP_REFERER = "<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>";
    var csrf = '<?= $this->security->get_csrf_token_name(); ?>';
    var token = '<?= $this->security->get_csrf_hash(); ?>';
    var _lang = [];


    <?php
    include(APPPATH . 'language/' . get_cookie('language') . '/web_lang.php');
    if ($this->uri->segment('2') == 'irrigation') {
      include(APPPATH . 'language/' . get_cookie('language') . '/irrigation_lang.php');
    }
    foreach ($lang as $key => $value) {
    ?>
      _lang['<?= $key ?>'] = `<?= $value ?>`;
    <?php
    }
    ?>

    var AdminLTEOptions = {
      sidebarExpandOnHover: false,
      navbarMenuSlimscroll: false,
    };

    $(document).ready(function() {

      toastr.options = {
        "positionClass": "toast-top-center",
      }

      var f_message = '<?= $this->session->flashdata('f_message'); ?>';
      var f_type = '<?= $this->session->flashdata('f_type'); ?>';

      if (f_message.length > 0) {
        toastr[f_type](f_message);
      }

    });
  </script>
  <script src="<?= BASE_ASSET ?>js/page/main/top-script.js"></script>

  <?= $this->cc_html->getScriptFileTop(); ?>
</head>
<style>
  .custom-file-upload {
    background: #f7f7f7;
    padding: 8px;
    border: 1px solid #e3e3e3;
    border-radius: 5px;
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
  }
</style>

<body class="sidebar-mini skin-black fixed web-body">
  <div class="wrapper" id="app">

    <header class="main-header">
      <?php
      $logo =  get_option('logo');
      if ($logo) {
        $logo = 'uploads/setting/' . get_option('logo');
      } else {
        $logo = 'asset/img/icon-wide.png';
      }
      if (!is_file(FCPATH . '/' . $logo)) {
        $logo = 'asset/img/icon-wide.png';
      }
      ?>

      <a href="<?= site_url('/'); ?>" class="logo">
        <span class="logo-mini"><b><img src="<?= BASE_ASSET ?>img/icon-small.png" height="40px"></b></span>
        <span class="logo-lg"><b><img src="<?= base_url($logo) ?>" height="40px"></b></span>
      </a>
      <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <?php if ($this->aauth->get_user()) : ?>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?= BASE_URL . 'uploads/user/' . (!empty(get_user_data('avatar')) ? get_user_data('avatar') : 'default.png'); ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?= _ent(ucwords(clean_snake_case(get_user_data('full_name')))); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="user-header">
                    <img src="<?= BASE_URL . 'uploads/user/' . (!empty(get_user_data('avatar')) ? get_user_data('avatar') : 'default.png'); ?>" class="img-circle" alt="User Image">

                    <p>
                      <?= _ent(ucwords(clean_snake_case($this->aauth->get_user()->full_name))); ?>
                      <small>Last Login, <?= date('Y-M-D', strtotime(get_user_data('last_login'))); ?></small>
                    </p>
                  </li>

                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?= admin_site_url('/user/profile'); ?>" class="btn btn-default btn-flat"><?= cclang('profile'); ?></a>
                    </div>
                    <div class="pull-right">

                      <a href="<?= admin_site_url('/auth/logout/' . get_user_data('id')); ?>" class="btn btn-default btn-flat"><?= cclang('sign_out'); ?></a>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="dropdown ">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <span class="flag-icon <?= get_current_initial_lang(); ?>"></span> <?= get_current_lang(); ?> </a>
                <ul class="dropdown-menu" role="menu">
                  <?php foreach (get_langs() as $lang) : ?>
                    <li><a href="<?= site_url('web/switch_lang/' . $lang['folder_name']); ?>"><span class="flag-icon <?= $lang['icon_name']; ?>"></span> <?= $lang['name']; ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </li>
            </ul>
          </div>
        <?php endif ?>
      </nav>
    </header>

    <aside class="main-sidebar">
      <section class="sidebar sidebar-admin">
        <ul class="sidebar-menu  sidebar-admin tree" data-widget="tree">
          <?= display_menu_admin(_ent(ucwords(clean_snake_case(get_user_data('oauth_uid')))), 0, 1); ?>
        </ul>
      </section>
    </aside>

    <div class="content-wrapper">
      <?php cicool()->eventListen('backend_content_top'); ?>
      <?= $template['partials']['content']; ?>
      <?php cicool()->eventListen('backend_content_bottom'); ?>

      <div class="modal" id="modalPopUp">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b><?= cclang('version') ?></b> <?= VERSION ?>
      </div>
      <strong>Powered by
        <a href="#">
          <img src="<?= BASE_ASSET ?>img/icon/vektorUg.png" alt="AdminLTE Logo" width="100" style="opacity: .8">
        </a>
      </strong>
      &copy; 2024 All rights reserved.


      <!-- <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 1.0.0
      </div> -->
    </footer>

    <div class="control-sidebar-bg"></div>
  </div>

  <?= $this->cc_html->getHtmlFileBottom(); ?>
  <?= $this->cc_html->getCssFileBottom(); ?>
  <?= $this->cc_html->getScriptFileBottom(); ?>

  <script src="<?= BASE_ASSET ?>js/chosen.jquery.min.js" type="text/javascript"></script>
  <script src="<?= BASE_ASSET ?>jquery-ui/jquery-ui.js"></script>
  <script src="<?= BASE_ASSET ?>jquery-switch-button/jquery.switchButton.js"></script>
  <script src="<?= BASE_ASSET ?>js/jquery.ui.touch-punch.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/plugins/fastclick/fastclick.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/dist/js/app.min.js"></script>
  <script src="<?= BASE_ASSET ?>admin-lte/dist/js/adminlte.js"></script>
  <script src="<?= BASE_ASSET ?>js-scroll/script/jquery.jscrollpane.min.js"></script>
  <script src="<?= BASE_ASSET ?>jquery-switch-button/jquery.switchButton.js"></script>
  <script src="<?= BASE_ASSET ?>js/custom.js"></script>

  <!-- http://localhost/rfid_monitoring/administrator/manual_sensus/add -->

  <?php if (basename(dirname($_SERVER['REQUEST_URI'])) != 'registrasi_aset' && basename(dirname($_SERVER['REQUEST_URI'])) != 'registrasi_people' && basename(dirname($_SERVER['REQUEST_URI'])) != 'penggantian_tag') { ?>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.0/js/dataTables.js"></script>
  <?php } ?>

  <!-- <?php if (basename(dirname($_SERVER['REQUEST_URI'])) != 'manual_sensus/add') { ?>

    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.0/js/dataTables.js"></script>

  <?php } ?> -->

  <script>
    $(document).ready(function() {
      $('#choose-file').change(function() {
        var i = $(this).prev('label').clone();
        var file = $('#choose-file')[0].files[0].name;
        $(this).prev('label').text(file);
      });
    });



    $(document).ready(function() {
      // new DataTable('#masterdata');
      new DataTable('#masterdata', {

        order: [
          [0, 'desc']
        ] // Kolom kedua (index 1) diurutkan secara descending
      });
    });
  </script>


  <script>
    $(document).ready(function() {
      DataTable.ext.errMode = 'none';

      // Setup - add a text input to each footer cell
      $('#tabledetail thead tr').clone(true).appendTo('#tabledetail thead');
      $('#tabledetail thead tr:eq(1) th').each(function(i) {

        var title = $(this).text();
        if (title != 'Action') {
          $(this).html('<input type="text" placeholder="Search ' + title + '" />');

          $('input', this).on('keyup change', function() {
            if (table.column(i).search() !== this.value) {
              table
                .column(i)
                .search(this.value)
                .draw();
            }
          });
        }
      });

      var table = $('#tabledetail').DataTable({
        "order": [
          [1, 'desc']
        ],
        paging: false,
        scrollCollapse: true,
        scrollY: '250px',
        bInfo: true,
        orderCellsTop: true,
        fixedHeader: true,
        bPaginate: false,
        searching: false,
      });


    });
  </script>
  <script>
    $(document).ready(function() {
      DataTable.ext.errMode = 'none';

      // Setup - add a text input to each footer cell
      $('#tabledetailevent thead tr').clone(true).appendTo('#tabledetailevent thead');
      $('#tabledetailevent thead tr:eq(1) th').each(function(i) {

        var title = $(this).text();
        if (title != 'Action') {
          $(this).html('<input type="text" placeholder="Search ' + title + '" />');

          $('input', this).on('keyup change', function() {
            if (table.column(i).search() !== this.value) {
              table
                .column(i)
                .search(this.value)
                .draw();
            }
          });
        }
      });

      var table = $('#tabledetailevent').DataTable({
        "order": [
          [1, 'desc']
        ],
        paging: false,
        scrollCollapse: true,
        scrollY: '200px',
        bInfo: true,
        orderCellsTop: true,
        fixedHeader: true,
        bPaginate: false,
        searching: false,
      });


    });
  </script>
</body>

</html>