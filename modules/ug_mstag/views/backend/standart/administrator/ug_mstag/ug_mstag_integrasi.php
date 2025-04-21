<script src="<?= BASE_ASSET; ?>js/loadingoverlay.min.js"></script>

<script type="text/javascript">

    function setDropdownValue(idDropdown, storageKey) {
        let value = localStorage.getItem(storageKey);
        if (value) {
            // console.log(`${storageKey} tersimpan:`, value);

            // Cek apakah opsi sudah tersedia
            let interval = setInterval(function() {
                let $dropdown = $('#' + idDropdown);
                if ($dropdown.find(`option[value="${value}"]`).length) {
                    $dropdown.val(value).trigger('change');
                    $dropdown.trigger('chosen:updated'); // Update Chosen
                    // console.log(`${storageKey} berhasil di trigger dan diupdate`);
                    clearInterval(interval); // Stop interval setelah berhasil
                }
            }, 200); // Cek setiap 200ms
        }
    }

    // function domo() {

    //     $('*').bind('keydown', 'Ctrl+s', function() {
    //         $('#btn_save').trigger('click');
    //         return false;
    //     });

    //     $('*').bind('keydown', 'Ctrl+x', function() {
    //         $('#btn_cancel').trigger('click');
    //         return false;
    //     });

    //     $('*').bind('keydown', 'Ctrl+d', function() {
    //         $('.btn_save_back').trigger('click');
    //         return false;
    //     });

    // }

    // jQuery(document).ready(domo);

    function get_check_unique_data(uniqueDataArray) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: ADMIN_BASE_URL + '/registrasi_aset/check_unique_data',
                type: 'GET',
                dataType: 'json', 
                data: {
                    uniqueData: JSON.stringify(uniqueDataArray)
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error);
                }
            });
        });
    }

    function get_check_unique_single_tag(tid) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: ADMIN_BASE_URL + '/registrasi_aset/check_unique_single_tag',
                type: 'GET',
                dataType: 'json',
                data: {
                    tid: tid
                },
                success: function(response) {
                    resolve(response);
                },
                error: function(xhr, status, error) {
                    reject(error); 
                }
            });
        });
    }
    
</script>

<style>
    
</style>

<section class="content-header">
    <h1>
        Integrasi Tag RFID<small><?= cclang('new', ['Integrasi Tag RFID']); ?> </small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class=""><a href="<?= admin_site_url('/ug_mstag'); ?>">Integrasi Tag RFID</a></li>
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
                                <img class="img-circle" src="<?= BASE_ASSET; ?>/img/add2.png" alt="User Avatar">
                            </div>
                            <h3 class="widget-user-username">Integrasi Tag RFID</h3>
                            <h5 class="widget-user-desc"><?= cclang('new', ['Integrasi Tag RFID']); ?></h5>
                            <hr>
                        </div>
                        
                        <?= form_open('', [
                            'name' => 'form_ug_mstag_add',
                            'class' => 'form-horizontal form-step',
                            'id' => 'form_ug_mstag_add',
                            'enctype' => 'multipart/form-data',
                            'method' => 'POST'
                        ]); ?>
                        
                        <?php
                        $user_groups = $this->model_group->get_user_group_ids();
                        ?>

                        <div class="row">

                            <div class="col-md-3">
                                <input type="hidden" id="ip_address_server" name="ip_address_server" value="<?= $pengaturan_sistem->ip_address_server; ?>">
                                <input type="hidden" id="port_ws_server" name="port_ws_server" value="<?= $pengaturan_sistem->port_ws_server; ?>">
                                <input type="hidden" id="flag_alarm_register_tag" name="flag_alarm_register_tag" value="<?= $pengaturan_sistem->flag_alarm_register_tag; ?>">
                                <input type="hidden" id="deras_status_default" name="deras_status_default" value="<?= $pengaturan_sistem->deras_status_default; ?>">
                                <input type="hidden" id="deras_description" name="deras_description" value="<?= $pengaturan_sistem->deras_description; ?>">
                                <input type="hidden" id="deras_category_default" name="deras_category_default" value="<?= $pengaturan_sistem->deras_category_default; ?>">
                                <input type="hidden" id="protocol_ws_server" name="protocol_ws_server" value="<?= $pengaturan_sistem->protocol_ws_server; ?>">
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Jenis Integrasi</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="jenis_integrasi" name="jenis_integrasi">
                                            <option value="existing" selected>Existing</option>
                                            <option value="new">Baru</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="is_integrasi_baru">
                                
                                    <div class="form-group group-merk ">
                                        <label for="merk" class="col-sm-2 control-label">Merk
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="merk" id="merk" placeholder="Merk" value="<?= set_value('merk'); ?>">
                                            <small class="info help-block">
                                                <b>Input Merk</b> Max Length : 50.</small>
                                        </div>
                                    </div>

                                    <div class="form-group group-tipe ">
                                        <label for="tipe" class="col-sm-2 control-label">Tipe
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="tipe" id="tipe" placeholder="Tipe" value="<?= set_value('tipe'); ?>">
                                            <small class="info help-block">
                                                <b>Input Tipe</b> Max Length : 50.</small>
                                        </div>
                                    </div>

                                    <div class="form-group group-kategori">

                                        <label for="kategori" class="col-sm-2 control-label">Penanggung Jawab
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control chosen chosen-select" name="pic" id="pic" tabi-ndex="5" placeholder="Select Kategori">
                                                <option value="0">Pilih PIC</option>

                                                <?php foreach (db_get_all_data('tb_master_pegawai') as $row) : ?>
                                                    <option value="<?= $row->id; ?>"><?= ucwords($row->nama); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="info help-block">
                                                Pilih PIC.
                                            </small>
                                        </div>

                                    </div>

                                </div>

                                <!-- <h3 style="text-decoration: underline;">Pilih Area</h3> -->
                                <!-- <hr> -->

                                <!-- <section> -->
                                <fieldset>

                                    <div class="form-group group-id_area ">
                                        <label for="id_area" class="col-sm-2 control-label">Area<i class="required">*</i>
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control chosen chosen-select-deselect" name="id_area" id="id_area" data-placeholder="Pilih Area">
                                                <option value=""></option>
                                                <?php foreach (db_get_all_data('tb_master_area') as $row): ?>
                                                    <option value="<?= $row->id ?>"><?= $row->area; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="info help-block">
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group group-id_gedung ">
                                        <label for="id_gedung" class="col-sm-2 control-label">Gedung<i class="required">*</i>
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control chosen chosen-select-deselect" name="id_gedung" id="id_gedung" data-placeholder="Pilih Gedung">
                                                <option value=""></option>
                                            </select>
                                            <small class="info help-block">
                                            </small>
                                        </div>
                                    </div>

                                    <div class="form-group group-id_ruangan ">
                                        <label for="id_ruangan" class="col-sm-2 control-label">Ruangan<i class="required">*</i>
                                        </label>
                                        <div class="col-sm-8">
                                            <select class="form-control chosen chosen-select-deselect" name="id_ruangan" id="id_ruangan" data-placeholder="Pilih Ruangan">
                                                <option value=""></option>
                                            </select>
                                            <small class="info help-block">
                                            </small>
                                        </div>
                                    </div>

                                    <!-- </section> -->
                                </fieldset>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">IP Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="ip_address" name="ip_address" placeholder="IP Address">
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-3"></div>
                        
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3"></div>
                            
                            <div class="col-md-6">
                                <div class="d-flex justify-content-center">
                                    
                                    <a class="btn btn-flat btn-info btn_search btn_action btn_search_back btn-block" id="btn_search" data-stype='back' title="Search">
                                        <i class="ion ion-ios-list-outline"></i> Search
                                    </a>

                                </div>
                                <small class="info help-block"><b>Status:</b> <div id="status"></div></small>&nbsp;&nbsp;<small class="info help-block"><b>Total RFID Tag:</b> <div id="total_rfid_tag"></div></small>
                            </div>

                            <div class="col-md-3"></div>
                        </div>


                    <div class="table-responsive" style="margin-bottom: 15px;"> 

                        <br>
                        <table class="table table-bordered table-striped dataTable" id="your_table_id">
                            <thead>
                            <tr class="">                            
                                <th style="text-align: center">
                                    <?= cclang('No') ?>
                                </th>
                                <th style="text-align: center" data-field="kode_tid" data-sort="1" data-primary-key="0"> <?= cclang('kode_tid') ?></th>
                                <th style="text-align: center" data-field="kode_epc" data-sort="1" data-primary-key="0"> <?= cclang('kode_epc') ?></th>
                                <th style="text-align: center" data-field="status_tag" data-sort="1" data-primary-key="0"> <?= cclang('status_tag') ?></th>
                            </tr>
                            </thead>
                            <tbody id="tbody_ug_mstag">
                            </tbody>
                        </table>

                    </div>

    <div class="row-fluid col-md-7 container-button-bottom" style="margin-top: 15px; margin-bottom: 15px;">
    
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
            <i id="data_processing"></i>
        </span>

    </div>

    <div class="message"></div>

<?= form_close(); ?>
</div>
</div>
</div>
</div>
</div>

</section>

<style>
    .table thead th {
        border-bottom: 1px solid #dee2e6 !important;
        /* Pakai !important agar override */
        border-top: none !important;
        /* Hilangkan border atas */
    }

    .table tbody td {
        border-top: 1px solid #dee2e6 !important;
        /* Pakai !important di baris data */
    }

    .table tfoot td {
        border-top: 1px solid #dee2e6 !important;
        /* Pakai !important di footer */
        border-bottom: none !important;
        /* Hilangkan border bawah */
    }

    .table tfoot td {
        border-bottom: none !important;
        /* Hilangkan border bawah */
    }

    #asetTable tbody td {
        border-bottom: none !important;
        /* Hilangkan border bawah */
    }
</style>

<script src="<?php echo base_url(); ?>asset/js/socket.io.js"></script>

<script>
  var module_name = "ug_mstag"
  var use_ajax_crud = false
</script>

<script>
    $(document).ready(function() {

        $('#is_integrasi_baru').hide();

        $('#jenis_integrasi').change(function() {
            if ($(this).val() === 'new') {
                $('#is_integrasi_baru').slideDown();
            } else {
                $('#is_integrasi_baru').slideUp();
            }
        });

        setDropdownValue('id_area', 'id_area');
        setDropdownValue('id_gedung', 'id_gedung');
        setDropdownValue('id_ruangan', 'id_ruangan');

        $('#status').html('Disconnected');
        $('#ip_address').attr('placeholder', 'Masukkan IP Address');
        // $('#ip_address').val('');

        var stored_ip_address = localStorage.getItem('ip_address');

        if (stored_ip_address) {
            $('#ip_address').val(stored_ip_address);
            console.log('IP Address yang tersimpan:', stored_ip_address);
            // alert('IP Address tersimpan: ' + stored_ip_address);
        } else {
            // localStorage.setItem('ip_address', ip_address);
            console.log('Tidak ada IP Address yang tersimpan.');
            // alert('Tidak ada IP Address yang tersimpan.');
        }

        $('#id_ruangan').change(function() {
            // console.log('ID Ruangan:', $(this).val());
            localStorage.setItem('id_ruangan', $(this).val());
        });
        
        "use strict";
        window.event_submit_and_action = '';

        // Deklarasi array global untuk menyimpan data unik berdasarkan TID
        var uniqueDataArray = [];

        $('#btn_cancel').click(function() {
            swal({
                    title: "<?= cclang('are_you_sure'); ?>",
                    text: "<?= cclang('data_to_be_deleted_can_not_be_restored'); ?>",
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
                        window.location.href = ADMIN_BASE_URL + '/ug_mstag';
                    }
                });

            return false;
        }); /*end btn cancel*/

        $('#btn_search').click(async function() {

            // Reset uniqueDataArray
            uniqueDataArray = [];

            var ip_address = $('#ip_address').val();

            if (ip_address == '') {
                swal({
                    title: "Error",
                    text: "IP Address tidak boleh kosong!",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Okay!",
                    closeOnConfirm: true
                });
                return false;
            }

            var protocol_ws_server = $('#protocol_ws_server').val();
            localStorage.setItem('ip_address', ip_address);
            
            // const socket = new WebSocket(protocol_ws_server + '://' + ip_address + ':3030');
            const socket = new WebSocket('ws://' + ip_address + ':3030');

            $('#your_table_id tbody tr').remove();

            socket.onopen = function(event) {

                // console.log('Your System Connected to WebSocket server');
                $('#status').html('Connected');
                //   const messageArea = document.getElementById('messageArea');
                //   messageArea.innerHTML = '';
                //   messageArea.innerHTML += 'Status : Connected to Server';
                //socket.send('refresh');

            };

            socket.onclose = function(event) {
                if (event.wasClean) {
                    console.log('WebSocket connection closed');
                } else {
                    console.log('WebSocket connection died');
                }
                $('#status').html('Not Connected to Server');
            };


            var postTimeout = null; // Timer untuk mendeteksi tidak ada data baru
            var timeoutDuration = 2000; // Waktu tunggu (ms) untuk memposting data ke database

            socket.onmessage = function (event) {

                var parsedData = JSON.parse(event.data);
                var event_name = parsedData.event;

                if (event_name == 'scan-rfid-result') {

                    // const messageArea = document.getElementById('messageArea');
                    // messageArea.innerHTML = '';
                    // messageArea.innerHTML += 'Receive Data from Server';

                    // $('.loading').show();

                    try {
                        
                        var tid = parsedData.data_tid;
                        var epc = parsedData.data;
                        var alias_antenna = 'handheld';
                        var status_tag = 'OK';
                        var description = 'OK';

                        var count_tag = 0;

                        // Cek apakah data dengan TID dan alias_antenna tersebut sudah ada
                        var isExisting = uniqueDataArray.some(data => data.tid === tid);

                        if (!isExisting) {

                            var is_unique_single_tag = '0';

                            // Cek apakah data dengan TID tersebut sudah ada
                            get_check_unique_single_tag(tid).then(async function(response) {
                                
                                is_unique_single_tag = response.check;

                                if (is_unique_single_tag == 0) {
                                    
                                    isExisting = uniqueDataArray.some(data => data.tid === tid);

                                    if (!isExisting) {
                                        
                                        console.log('your tid: ' + tid, 'is available');

                                        count_tag++;

                                        var waktu = new Date().toISOString();

                                        // Tambahkan data baru ke array jika TID belum ada
                                        uniqueDataArray.push({
                                            tid: tid,
                                            epc: epc,
                                            status: status_tag,
                                            // waktu: waktu,
                                            description: description
                                        });

                                        // Tambahkan data baru ke tabel HTML
                                        $('#your_table_id tbody').append(`
                                            <tr>
                                                <td style="text-align: center;">${uniqueDataArray.length}</td>
                                                <td style="text-align: center;">${tid}</td>
                                                <td style="text-align: center;">${epc}</td>
                                                <td style="text-align: center;">${status_tag}</td>
                                            </tr>
                                        `);

                                        // $('#array_tag_code').val(JSON.stringify(uniqueDataArray));
                                        $('#total_rfid_tag').html(uniqueDataArray.length);

                                        console.log('Data baru ditambahkan:', tid);

                                    }

                                }

                            }); 

                        } else {
                            console.log('Data dengan TID ini sudah ada:', tid);
                        }

                        // Reset timer setiap kali data baru diterima
                        // resetPostTimer();

                    } catch (error) {
                        console.error('Error parsing JSON data:', error);
                    }
                } else if (event_name == 'response-scan-rfid-on') {
                    $('#data_processing').html('Searching RFID Tag...');
                    $('.loading').show();
                } else if (event_name == 'response-scan-rfid-off') {
                    $('#data_processing').html('');
                    $('.loading').hide();
                }
            };

            return false;
        });

        $('.btn_save').click(async function(e) {

            try {
                e.preventDefault();

                // Validasi id_area, id_gedung, id_ruangan
                if (!$('#id_area').val()) {
                    await swal({
                        title: "Error",
                        text: "Area tidak boleh kosong!",
                        type: "error",
                        confirmButtonText: "Okay!"
                    });
                    return false;
                }

                if (!$('#id_gedung').val()) {
                    await swal({
                        title: "Error",
                        text: "Gedung tidak boleh kosong!",
                        type: "error",
                        confirmButtonText: "Okay!"
                    });
                    return false;
                }

                if (!$('#id_ruangan').val()) {
                    await swal({
                        title: "Error",
                        text: "Ruangan tidak boleh kosong!",
                        type: "error",
                        confirmButtonText: "Okay!"
                    });
                    return false;
                }


                $('.message').fadeOut();
                $('#data_processing').html('Saving RFID Tag...');
                $('.loading').show();

                var flag_alarm_register_tag = $('#flag_alarm_register_tag').val();
                var deras_status_default = $('#deras_status_default').val();
                var deras_description = $('#deras_description').val();
                var deras_category_default = $('#deras_category_default').val();

                // 1. Validasi input
                const ip_address_server = $('#ip_address_server').val();
                const port_ws_server = $('#port_ws_server').val();

                if (!ip_address_server) {
                    swal({
                        title: "Error",
                        text: "IP Address tidak boleh kosong!",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Okay!",
                        closeOnConfirm: true
                    });
                    return false;
                }

                if (!port_ws_server) {
                    swal({
                        title: "Error", 
                        text: "Port Web Socket tidak boleh kosong!",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Okay!",
                        closeOnConfirm: true
                    });
                    return false;
                }

                if (uniqueDataArray.length === 0) {
                    swal({
                        title: "Error",
                        text: "Tidak ada data untuk diposting.",
                        type: "error", 
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Okay!",
                        closeOnConfirm: true
                    });
                    return false;
                }

                // 2. Cek data unik di database
                const uniqueCheck = await get_check_unique_data(uniqueDataArray);
                console.log(uniqueCheck);

                if (uniqueCheck.exists) {
                    swal({
                        title: "Error",
                        text: "RFID Tag sudah terdaftar di database!",
                        type: "error",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Okay!",
                        closeOnConfirm: true
                    });
                    return false;
                }

                // 3. Koneksi WebSocket dan kirim data

                let wsConnected = false;

                try {

                    const socket = new WebSocket(`ws://${ip_address_server}:${port_ws_server}`);
                    
                    await new Promise((resolve, reject) => {

                        socket.onopen = async () => {

                            try {
                                wsConnected = true;
                                console.log('Connected to WebSocket server');
                                
                                // Kirim data satu per satu
                                for (const item of uniqueDataArray) {
                                    const data = {
                                        event: "db-storage-insert-rfid-list",
                                        value: {
                                            tid: item.tid,
                                            epc: item.epc,
                                            status: deras_status_default,
                                            description: deras_description,
                                            flag_alarm: flag_alarm_register_tag,
                                            category: deras_category_default
                                        }
                                    };
                                    socket.send(JSON.stringify(data));
                                    await new Promise(resolve => setTimeout(resolve, 100)); // Delay antar pengiriman
                                }

                                resolve();

                            } catch (error) {
                                reject(error);
                            }

                        };

                        socket.onerror = (error) => {
                            wsConnected = false;
                            reject(new Error('WebSocket connection failed'));
                        };

                        socket.onclose = (event) => {
                            // When the connection is closed, show an alert
                            if (!event.wasClean) {
                                swal("Web Socket Server Tidak Aktif !!", "Periksa koneksi Web Socket.", "error");
                            }
                        };

                    });

                } catch (wsError) {
                    console.warn('WebSocket error:', wsError.message);
                }

                // 4. Simpan ke database lokal
                const form_ug_mstag = $('#form_ug_mstag_add');
                const data_post = form_ug_mstag.serializeArray();
                const save_type = $(this).attr('data-stype');

                data_post.push(
                    { name: 'save_type', value: save_type },
                    { name: 'event_submit_and_action', value: window.event_submit_and_action }
                );

                let jenis_integrasi = $('#jenis_integrasi').val();
                let endpoint = '';

                if (jenis_integrasi == 'existing') {
                    endpoint = 'add_integrasi_existing';
                } else {
                    endpoint = 'add_new';
                }

                const response = await $.ajax({
                    url: ADMIN_BASE_URL + '/ug_mstag/' + endpoint,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ data_post, data: uniqueDataArray })
                });

                // 5. Handle response
                if (response.success) {

                    if (save_type == 'back') {
                        window.location.href = response.redirect;
                        return;
                    }
                    
                    $('.message').printMessage({ message: response.message });
                    $('.message').fadeIn();
                    resetForm();
                    $('#your_table_id tbody tr').remove();
                    $('#total_rfid_tag').html(0);
                    $('#data_processing').html('');
                    $('.chosen option').prop('selected', false).trigger('chosen:updated');

                }

            } catch (error) {
                swal({
                    title: "Error",
                    text: error.message,
                    type: "error",
                    confirmButtonText: "Okay!"
                });
            } finally {
                $('#data_processing').html('');
                $('.loading').hide();
            }

        });

        $('#id_area').change(function(event) {
            var val = $(this).val();
            $.LoadingOverlay('show')
            localStorage.setItem('id_area', val);
            $.ajax({
                    url: ADMIN_BASE_URL + '/registrasi_aset/ajax_id_gedung/' + val,
                    dataType: 'JSON',
                })
                .done(function(res) {
                    var html = '<option value=""></option>';
                    $.each(res, function(index, val) {
                        html += '<option value="' + val.id + '">' + val.gedung + '</option>'
                    });
                    $('#id_gedung').html(html);
                    $('#id_gedung').trigger('chosen:updated');

                })
                .fail(function() {
                    toastr['error']('Error', 'Getting data fail')
                })
                .always(function() {
                    $.LoadingOverlay('hide')
                });

        });

        $('#id_gedung').change(function(event) {
            var val = $(this).val();
            $.LoadingOverlay('show')
            localStorage.setItem('id_gedung', val);
            $.ajax({
                    url: ADMIN_BASE_URL + '/registrasi_aset/ajax_id_ruangan/' + val,
                    dataType: 'JSON',
                })
                .done(function(res) {
                    var html = '<option value=""></option>';
                    $.each(res, function(index, val) {
                        html += '<option value="' + val.id + '">' + val.ruangan + '</option>'
                    });
                    $('#id_ruangan').html(html);
                    $('#id_ruangan').trigger('chosen:updated');

                })
                .fail(function() {
                    toastr['error']('Error', 'Getting data fail')
                })
                .always(function() {
                    $.LoadingOverlay('hide')
                });

        });

    }); /*end doc ready*/
</script>