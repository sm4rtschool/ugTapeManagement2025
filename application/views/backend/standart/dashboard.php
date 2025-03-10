<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
<?php
// Di awal skrip PHP Anda
ini_set('display_errors', 0); // Menyembunyikan pesan error dari tampilan publik
ini_set('log_errors', 1); // Mengaktifkan logging error ke file log PHP
ini_set('error_log', '/lokasi/penyimpanan/error.log'); // Menentukan lokasi penyimpanan file log error

// Memuat CI instance
$CI = &get_instance();

?>

<style type="text/css">
  .c-dashboardInfo {
    margin-bottom: 15px;
  }

  .c-dashboardInfo .wrap {
    background: #ffffff;
    box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
    border-radius: 7px;
    text-align: center;
    position: relative;
    overflow: hidden;
    padding: 40px 25px 20px;
    height: 100%;
  }

  .c-dashboardInfo__title,
  .c-dashboardInfo__subInfo {
    font-size: 1.18em;
  }

  .c-dashboardInfo span {
    display: block;
  }

  .c-dashboardInfo__count {
    font-weight: 600;
    font-size: 3.5em;
    line-height: 64px;
  }

  .c-dashboardInfo .wrap:after {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 10px;
    content: "";
  }

  .c-dashboardInfo:nth-child(1) .wrap:after {
    background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
  }

  .c-dashboardInfo:nth-child(2) .wrap:after {
    background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
  }

  .c-dashboardInfo:nth-child(3) .wrap:after {
    background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
  }

  .c-dashboardInfo:nth-child(4) .wrap:after {
    background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
  }

  .c-dashboardInfo:nth-child(5) .wrap:after {
    background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
  }

  .c-dashboardInfo:nth-child(6) .wrap:after {
    background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
  }


  .c-dashboardInfo:nth-child(7) .wrap:after {
    background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
  }


  .c-dashboardInfo__title svg {
    color: #d7d7d7;
    margin-left: 5px;
  }

  .MuiSvgIcon-root-19 {
    fill: currentColor;
    width: 1em;
    height: 1em;
    display: inline-block;
    font-size: 24px;
    transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    user-select: none;
    flex-shrink: 0;
  }

  .bg-totalaset {
    background-color: rgb(255, 255, 255) !important;
  }

  .bg-tersedia {
    color: white;
    background-color: #266317 !important;
  }

  .bg-peminjaman {
    color: white;
    background-color: #1b304a !important;
  }

  .bg-legal {
    color: white;
    background-color: #939c91 !important;
  }

  /* blink ilegal */
  @keyframes flash-bg {

    0%,
    100% {
      background-color: #ff4500;
    }

    50% {
      background-color: transparent;
    }
  }

  .blink-ilegal {
    color: white;
    animation: flash-bg 0.8s infinite;
  }

  .bg-ilegal {
    color: white;
    background-color: #ff4500 !important;
  }

  /* blink overdue */

  @keyframes flash-bgo {

    0%,
    100% {
      background-color: #eba834;
    }

    50% {
      background-color: transparent;
    }
  }

  .blink-overdue {
    color: white;
    animation: flash-bgo 0.8s infinite;
  }

  .bg-overdue {
    color: white;
    background-color: #eba834 !important;
  }

  .bg-perbaikan {
    color: white;
    background-color: #c2860e !important;
  }

  .tooltip-tr {
    position: relative;
    cursor: pointer;
  }

  .tooltip-tr::after {
    content: attr(data-tooltip);
    background-color: black;
    color: white;
    text-align: center;
    padding: 5px;
    border-radius: 5px;
    position: absolute;
    left: 60%;
    transform: translateX(-50%);
    white-space: nowrap;
    opacity: 1;
    /* Selalu terlihat */
    visibility: visible;
    /* Pastikan terlihat */
  }


  /* batas */
</style>

<link rel="stylesheet" href="<?php BASE_ASSET; ?>admin-lte/plugins/morris/morris.css">

<section class="content-header">
  <!-- <h1>
    <?php cclang('DASHBOARD INVENTORY ASET') ?>
    <small>

      <?php cclang('Dashboard') ?>
    </small>
  </h1> -->
  <!-- <ol class="breadcrumb">
    <li>
      <a href="#">
        <i class="fa fa-dashboard">
        </i>
        <?php cclang('home') ?>
      </a>
    </li>
    <li class="active">
      <?php cclang('dashboard') ?>
    </li>
  </ol> -->
</section>

<section class="content">
  <div class="row">

    <div class="box-body chart-responsive">

      <!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-body" role="document">
          <div class="modal-content">
            <!-- Konten modal akan ditampilkan di sini -->
          </div>
        </div>
      </div>
      <div class="row">

        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">ACTIVITY ASSETS</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <!-- Modal -->
              <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <!-- Konten modal akan ditampilkan di sini -->
                  </div>
                </div>
              </div>
              <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-2">
                  <div id="ta12" class="wrap" style="padding-top: 20px;">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Total Aset <br><small>(12 bulan terakhir)</small>
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='aset_teregister' class="mt-0 info-box-icon hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='aset_teregister' style="cursor:pointer;">click for detail</a>
                  </div>
                </div>

                <div class="c-dashboardInfo col-lg-2">
                  <div id="ta" class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Total Aset
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='aset_total_pantau' class="hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='aset_total_pantau' style="cursor:pointer;">click for detail</a>

                  </div>
                </div>
                <div class="c-dashboardInfo col-lg-2">
                  <div id="ava" class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Aset Tersedia
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='avalaible' class="hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='avalaible' style="cursor:pointer; color:white;">click for detail</a>

                  </div>
                </div>
                <div class="c-dashboardInfo col-lg-2">
                  <div id="pem" class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Peminjaman
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='peminjaman' class="hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='peminjaman' style="cursor:pointer;">click for detail</a>

                  </div>
                </div>
                <div class="c-dashboardInfo col-lg-2">
                  <div id="perp" class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Perpindahan
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='perpindahan' class="hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='perpindahan' style="cursor:pointer;">click for detail</a>

                  </div>
                </div>
                <div class="c-dashboardInfo col-lg-2">
                  <div id="perb" class="wrap">
                    <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">Perbaikan
                      <!-- <svg class="MuiSvgIcon-root-19" focusable="false" viewBox="0 0 24 24" aria-hidden="true" role="presentation">
                          <path fill="none" d="M0 0h24v24H0z"></path>
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z">
                          </path>
                        </svg> -->
                    </h4><span id='perbaikan' class="hind-font caption-12 c-dashboardInfo__count">loading...</span>
                    <a id='perbaikan' style="cursor:pointer;">click for detail</a>

                  </div>
                </div>
              </div>






            </div>

          </div>


        </div>

      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">STATUS ASET</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <canvas id="myChartSIMAN"></canvas>

            </div>

          </div>

        </div>
        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">ASET PADA RUANGAN</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <canvas id="myRoom"></canvas>

            </div>

          </div>

        </div>
        <div class="col-md-4">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">KATEGORI ASET</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <canvas id="myCategory" width="150px" height="150px"></canvas>
            </div>

          </div>

        </div>



      </div>
    </div>

  </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.3/socket.io.js"></script>

<!-- Skrip JavaScript -->
<script>
  // $('#ta12').addClass('bg-totalaset');
  // $('#ta').addClass('bg-totalaset');

  $(document).ready(function() {

    // const ws = new WebSocket("ws://localhost:3000");

    // ws.onopen = function() {
    //   console.log("Connected to WebSocket server");
    // };

    // ws.onmessage = function(event) {
    //   const message = JSON.parse(event.data);
    //   newLibraraian();
    //   console.log(message);
    //   // updateDashboard(message);
    // };
    // console.log("ugmandiri");
    // ws.onclose = function() {
    //   console.log("Connection closed");
    // };

    // Fungsi untuk menampilkan modal saat div dengan ID tertentu diklik
    $('#aset_teregister, #sectiondashboard, #aset_total_pantau, #tape_total,#perbaikan,#perpindahan,#peminjaman,#avalaible').click(function() {
      var divId = $(this).attr('id'); // Mendapatkan ID div yang diklik
      var title = '';
      // Menggunakan ID div untuk memilih endpoint yang sesuai
      var endpoint = '';
      switch (divId) {
        case 'aset_teregister':
          endpoint = BASE_URL + '/administrator/dashboard/abc/gettotalpantau';
          title = 'TOTAL ASET (12 Bulan Terakhir)';
          topic = '';
          break;
        case 'aset_total_pantau':
          endpoint = BASE_URL + '/administrator/dashboard/abc/total';
          title = 'TOTAL ASET';
          topic = '';
          break;
        case 'avalaible':
          endpoint = BASE_URL + '/administrator/dashboard/abc/anomali';
          title = 'ASET TERSEDIA';
          topic = 'avalaible';
          break;
        case 'peminjaman':
          endpoint = BASE_URL + '/administrator/dashboard/abc/mutation';
          title = 'ASET DIPINJAM';
          topic = 'borrow';
          break;
        case 'perpindahan':
          endpoint = BASE_URL + '/administrator/dashboard/abc/moving';
          title = 'ASET BERGERAK';
          topic = 'moving';
          break;
        case 'perbaikan':
          endpoint = BASE_URL + '/administrator/dashboard/abc/maintenance';
          title = 'ASET PERBAIKAN';
          topic = 'mainten';
          break;
        case 'tape_overdue':
          endpoint = BASE_URL + '/administrator/dashboard/abc/overdue';
          title = 'TAPE OVER DUE';
          break;
        case 'tape_borrow':
          endpoint = BASE_URL + '/administrator/dashboard/abc/borrow';
          title = 'TAPE DIPINJAM';
          break;
        case 'tape_broken':
          endpoint = BASE_URL + '/administrator/dashboard/abc/broken';
          title = 'TAPE RUSAK';
          break;
        case 'tape_anomaly':
          endpoint = BASE_URL + '/administrator/dashboard/abc/koreksi';
          title = 'KOREKSI ASET';
          break;
        default:
          endpoint = '';
      }

      // Memanggil fungsi untuk menampilkan modal dengan konten dari endpoint yang sesuai
      showModalWithPagination(endpoint, title, topic);
    });


    // Fungsi untuk menampilkan modal dengan konten dari endpoint yang diberikan
    function showModalWithPagination(endpoint, title, topic) {

      // Lakukan request AJAX ke endpoint yang diberikan
      $.ajax({
        url: endpoint,
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          // Proses data dan tampilkan dalam modal
          // Misalnya, Anda dapat membuat HTML untuk menampilkan data dalam bentuk tabel dan menambahkan pagination di dalamnya

          var modalContent = '<div class="modal-header"> <span class="close-btn" id="closeModal">&times;</span><h1>' + title + '</h1></div>'; // Contoh pembuatan konten modal
          modalContent += '<div class="modal-body">';
          // Misalnya, tampilkan data dalam bentuk tabel
          modalContent += '<table class="table table-bordered dataTable responsive">';

          if (topic == 'avalaible') {

            modalContent += '<tr><th>No</th><th>RFID kode</th><th>Kode Aset</th><th>NUP</th><th>Nama Aset</th><th>Ruangan</th></tr>';

          } else if (topic == 'borrow') {
            modalContent += '<tr><th>No</th><th>RFID kode</th><th>Kode Aset</th><th>NUP</th><th>Nama Aset</th><th>Asal Ruangan</th><th>Peminjaman</th><th>Pengembalian</th></tr>';
          } else if (topic == 'mainten') {
            modalContent += '<tr><th>No</th><th>RFID kode</th><th>Kode Aset</th><th>NUP</th><th>Nama Aset</th><th>Asal Ruangan</th></tr>';

          } else if (topic == 'moving') {
            modalContent += '<tr><th>No</th><th>RFID kode</th><th>Kode Aset</th><th>NUP</th><th>Nama Aset</th><th>Asal Ruangan</th><th>Posisi Terakhir</th></tr>';


          } else {
            modalContent += '<tr><th>No</th><th>RFID kode</th><th>Kode Aset</th><th>NUP</th><th>Nama Aset</th><th>Tgl Inventarisasi</th></tr>';

          }
          // Proses data dari respons JSON dan tambahkan ke dalam tabel
          // Misalnya, untuk setiap item dalam data, tambahkan baris baru ke tabel
          data.forEach(function(item, index) {
            let rowClass = "";

            // Tambahkan class berdasarkan kondisi
            if (item.status == 4 && item.tipe_moving == 0) {
              rowClass = "bg-red tooltip-tr";
            }

            var no = index + 1;
            // Misalnya, tambahkan baris baru dengan data item ke dalam tabel
            modalContent += `<tr data-tooltip="Pergerakan Ilegal!" class="${rowClass}">`;
            modalContent += '<td>' + no + '</td>';
            modalContent += '<td>' + item.kode_tid + '</td>'; // Misalnya, ambil field1 dari item
            modalContent += '<td>' + item.kode_aset + '</td>'; // Misalnya, ambil field2 dari item
            modalContent += '<td>' + item.nup + '</td>'; // Misalnya, ambil field2 dari item
            modalContent += '<td>' + item.nama_aset + '</td>'; // Misalnya, ambil field2 dari item

            if (topic == 'avalaible') {

              modalContent += '<td>' + item.ruangan + '</td>'; // Misalnya, ambil field2 dari item

            } else if (topic == 'borrow') {
              modalContent += '<td>' + item.ruangan + '</td>'; // Misalnya, ambil field2 dari item
              modalContent += '<td>' + item.pinjam + '</td>'; // Misalnya, ambil field2 dari item
              modalContent += '<td>' + item.kembali + '</td>'; // Misalnya, ambil field2 dari item
            } else if (topic == 'mainten') {
              modalContent += '<td>' + item.ruangan + '</td>'; // Misalnya, ambil field2 dari item

            } else if (topic == 'moving') {
              modalContent += '<td>' + item.ruangan + '</td>'; // Misalnya, ambil field2 dari item
              modalContent += '<td>' + item.ruanganterakhir + '</td>'; // Misalnya, ambil field2 dari item


            } else {
              modalContent += '<td>' + item.tgl_inventarisasi + '</td>'; // Misalnya, ambil field2 dari item

            }


            // Misalnya, ambil field2 dari item
            // Lanjutkan untuk setiap field yang diperlukan
            modalContent += '</tr>';
          });
          modalContent += '</table>';
          modalContent += '</div>';
          // Tambahkan tombol pagination di bagian bawah modal jika diperlukan
          // Misalnya, Anda dapat menambahkan tombol Next dan Previous untuk pagination
          modalContent += '<div class="modal-footer"><button id="closeBtn">Tutup</button>';
          // modalContent += '<button type="button" class="btn btn-secondary">Previous</button>';
          // modalContent += '<button type="button" class="btn btn-secondary">Next</button>';
          modalContent += '</div>';

          // Tampilkan modal dengan konten yang telah dibuat
          $('#myModal').modal('show');
          $('.modal-content').html(modalContent);

          var closeModal = document.getElementById("closeModal");
          var closeBtn = document.getElementById("closeBtn");

          closeModal.onclick = function() {
            $('#myModal').modal('hide');
          }

          // Event listener untuk tombol tutup di footer
          closeBtn.onclick = function() {
            $('#myModal').modal('hide');
          }

        },
        error: function(xhr, status, error) {
          console.error("Failed to fetch data:", error);
        }
      });
    }

    function updateDashboard(data) {
      $('#aset_teregister').text(data.totalpantau);
      if (data.totalpantau > 0) {
        $('#ta12').addClass('bg-totalaset');
      } else {
        $('#ta12').removeClass('bg-totalaset');
      }
      $('#aset_total_pantau').text(data.total);
      if (data.total > 0) {
        $('#ta').addClass('bg-totalaset');
      } else {
        $('#ta').removeClass('bg-tersedia');
      }
      $('#avalaible').text(data.avalaible);
      if (data.avalaible > 0) {
        $('#ava').addClass('bg-tersedia');
      } else {
        $('#ava').removeClass('bg-tersedia');
      }

      $('#peminjaman').text(data.peminjaman);
      if (data.peminjaman > 0 && data.pinjamlewathari == 0) {
        $('#pem').addClass('bg-peminjaman');
      } else if (data.peminjaman > 0 && data.pinjamlewathari > 0) {
        $('#pem').removeClass('bg-peminjaman');

        $('#pem').addClass('blink-overdue');

      } else {
        $('#pem').removeClass('bg-peminjaman');
        $('#pem').removeClass('blink-overdue');

      }

      $('#perpindahan').text(parseInt(data.ilegal) + parseInt(data.legal));
      if (data.ilegal > 0) {
        $('#perp').addClass('blink-ilegal');
      } else {
        $('#perp').addClass('bg-legal');
        $('#perp').removeClass('blink-ilegal');

      }
      if (data.ilegal == 0 && data.legal == 0) {
        $('#perp').removeClass('bg-ilegal');
        $('#perp').removeClass('bg-legal');
      }
      $('#perbaikan').text(data.perbaikan);
      if (data.perbaikan > 0) {
        $('#perb').addClass('bg-perbaikan');
      } else {
        $('#perb').removeClass('bg-perbaikan');
      }
      $('#tape_overdue').text(data.overdue);
      $('#tape_borrow').text(data.borrow);
      $('#tape_broken').text(data.broken);
      $('#tape_anomaly').text(data.anomaly);
    }

    $(document).on('click', '.xxx', function() {
      var divId = event.target.getAttribute("data-el");
      var roomName = decodeURI(event.target.getAttribute("name-room")).replace(/"/g, "");


      var title = '';
      // Menggunakan ID div untuk memilih endpoint yang sesuai
      var endpoint = '';

      endpoint = BASE_URL + '/administrator/dashboard/abc/' + divId;
      title = 'ASET DI ' + roomName;
      topic = 'ruangan';

      // showModalWithPagination(endpoint, title, topic);
    });

    function librarian(data) {
      var output = '';

      // Variabel untuk menyimpan nama bangunan terakhir
      var current_building = '';

      // Iterasi melalui setiap item dalam array 'librarian' di respons JSON
      data.librarian.forEach(function(item) {
        // Jika nama bangunan tidak sama dengan nama bangunan saat ini, tambahkan pemisah (div row)
        if (item.building_name !== current_building) {
          if (current_building !== '') {
            output += "</div>"; // Menutup row sebelumnya
          }
          output += "<div class='row'>";
          // output += "<div class='col-md-12'><h2>" + item.building_name + "</h2></div>"; // Tambahkan pemisah
          current_building = item.building_name; // Perbarui nama bangunan saat ini
        }

        // Tambahkan informasi librarian ke output HTML
        output += "<div class='col-md-12 col-sm-8 col-xs-12'>";
        output += "<div class='bg-grey info-box'>";
        output += "<span id='sectiondashboard' class='xxx info-box-icon' data-el=" + item.id_room + " name-room=" + encodeURIComponent(JSON.stringify(item.building_name)) + ">" + item.total_rfid + "</span>";
        output += "<div class='info-box-content'>";
        output += "<span class='info-box-text'>" + item.name_room + "</span>";
        output += "<div class='progress'>";
        output += "<div class='progress-bar' style='width: 95%'></div>";
        output += "</div>";
        output += "<span class='progress-description'>" + item.building_name + "</span>";

        output += "</div></div></div>";
      });

      // Menutup div terakhir jika ada
      if (current_building !== '') {
        output += "</div>"; // Menutup row terakhir
      }

      // Memasukkan output HTML ke dalam elemen dengan ID tertentu
      $('#librarian').html(output);
    }

    function readerradar(data) {
      var output = '';

      // Variabel untuk menyimpan nama bangunan terakhir
      var current_building = '';

      // Iterasi melalui setiap item dalam array 'librarian' di respons JSON
      data.librarian.forEach(function(item) {
        // Jika nama bangunan tidak sama dengan nama bangunan saat ini, tambahkan pemisah (div row)
        if (item.building_name !== current_building) {
          if (current_building !== '') {
            output += "</div>"; // Menutup row sebelumnya
          }
          output += "<div class='row'>";
          // output += "<div class='col-md-12'><h2>" + item.building_name + "</h2></div>"; // Tambahkan pemisah
          current_building = item.building_name; // Perbarui nama bangunan saat ini
        }

        // Tambahkan informasi librarian ke output HTML
        output += "<div class='col-md-4 col-xs-12'>";
        output += "<div class='bgcard info-box'>";

        output += "<span id='" + item.room_id + "' class='readerOn info-box-icon' style='font-size:14px'>" + "Reader A" + "</span>";
        output += "<span id='" + item.room_id + "' class='warning2 info-box-icon' style='font-size:14px'>" + "Reader B" + "</span>";
        output += "<div class='info-box-content'>";
        output += "<span class='info-box-text' style='text-wrap: wrap; text-align: center'>" + item.name_room + "</span>";

        output += "</div></div></div>";
      });

      // Menutup div terakhir jika ada
      if (current_building !== '') {
        output += "</div>"; // Menutup row terakhir
      }

      // Memasukkan output HTML ke dalam elemen dengan ID tertentu
      $('#librarian2').html(output);
    }

    function newLibraraian() {
      $.ajax({
        url: BASE_URL + '/administrator/dashboard/getSumAsetRoom',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          updateDashboard(data);
          // librarian(data);
          dChart.data.labels = data.labelcateg.map(item => [item.key_status]); // Mengganti labels
          dChart.data.datasets[0].data = data.labelcateg.map(item => item.total); // Mengganti data
          dChart.data.datasets[0].backgroundColor = data.labelcateg.map(item => [item.color]);

          myChart1.data.labels = data.label.map(item => [item.ruangan]); // Mengganti labels
          myChart1.data.datasets[0].data = data.label.map(item => item.total); // Mengganti data

          myChart3.data.labels = data.labelkat.map(item => [item.kategori]); // Mengganti labels
          myChart3.data.datasets[0].data = data.labelkat.map(item => item.total); // Mengganti data

          dChart.update();
          myChart1.update();
          myChart3.update();
        },
        error: function(xhr, status, error) {
          console.error("Failed to fetch data:", error);
        }
      });
    }

    function updateStatus() {
      $.ajax({
        url: BASE_URL + '/administrator/dashboard/getSumAsetRoom',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
          updateDashboard(data);

          dChart.data.labels = data.labelcateg.map(item => [item.key_status]); // Mengganti labels
          dChart.data.datasets[0].data = data.labelcateg.map(item => item.total); // Mengganti data
          dChart.data.datasets[0].backgroundColor = data.labelcateg.map(item => [item.color]);

          myChart1.data.labels = data.label.map(item => [item.ruangan]); // Mengganti labels
          myChart1.data.datasets[0].data = data.label.map(item => item.total); // Mengganti data

          myChart3.data.labels = data.labelkat.map(item => [item.kategori]); // Mengganti labels
          myChart3.data.datasets[0].data = data.labelkat.map(item => item.total); // Mengganti data

          dChart.update();
          myChart1.update();
          myChart3.update();


        },
        error: function(xhr, status, error) {
          console.error("Failed to fetch data:", error);
        }
      });
    }

    // Jalankan setiap 5 detik
    setInterval(updateStatus, 2000);
    var ctx2 = document.getElementById('myChartSIMAN').getContext('2d');
    var dChart = new Chart(ctx2, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [],
          backgroundColor: [],
        }, ],
        labels: [],
      },
      options: {
        legend: {
          position: 'top',
          labels: {
            fontColor: "black",
            boxWidth: 20,
            padding: 20,
            fontSize: 18,

          }
        },
        plugins: {
          datalabels: {
            formatter: (value) => {
              return value + '%';
            },
          },
        },
      },
    });

    var ctx1 = document.getElementById('myRoom').getContext('2d');
    var myChart1 = new Chart(ctx1, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [],
          backgroundColor: [
            '#0d1d4a',
            '#916306',
            '#939c91',
          ],
        }, ],
        labels: [],
      },
      options: {
        legend: {
          position: 'top',
          labels: {
            fontColor: "black",
            boxWidth: 20,
            padding: 20,
            fontSize: 18,

          }
        },
        plugins: {
          datalabels: {
            formatter: (value) => {
              return value + '%';
            },
          },
        },
      },
    });

    var ctx3 = document.getElementById('myCategory').getContext('2d');
    var myChart3 = new Chart(ctx3, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [],
          backgroundColor: [

            '#0d1d4a',
            '#916306',
          ],
        }, ],
        labels: [],
      },
      options: {
        legend: {
          position: 'top',
          labels: {
            fontSize: 18,
            fontColor: "black",
            boxWidth: 20,
            padding: 20
          }
        },
        plugins: {
          datalabels: {
            formatter: (value) => {
              return value + '%';
            },
          },
        },
      },
    });

    // Inisialisasi chart dengan data dari PHP


    Chart.plugins.register({
      afterDatasetsDraw: function(chart, easing) {
        // To only draw at the end of animation, check for easing === 1
        var ctx = chart.ctx;
        if (chart.data.datasets[0].data.length < 1) {
          let ctx = chart.ctx;
          let width = chart.width;
          let height = chart.height;
          ctx.textAlign = "center";
          ctx.textBaseline = "middle";
          ctx.font = "26px Arial";
          ctx.fillText("No data to display", width / 2, height / 2);
          ctx.restore();
        }

        chart.data.datasets.forEach(function(dataset, i) {
          var meta = chart.getDatasetMeta(i);

          if (!meta.hidden) {
            meta.data.forEach(function(element, index) {
              // Draw the text in black, with the specified font
              ctx.fillStyle = '#FFF';

              var fontSize = 16;
              var fontStyle = 'bold';
              ctx.font = Chart.helpers.fontString(fontSize, fontStyle);

              // Just naively convert to string for now
              var dataString = dataset.data[index].toString() + ' Aset';

              // Make sure alignment settings are correct
              ctx.textAlign = 'center';
              ctx.textBaseline = 'middle';

              var padding = -10;
              var position = element.tooltipPosition();
              ctx.fillText(dataString, position.x - (fontSize / 2) + 10, position.y - (fontSize / 2) - padding);
            });
          }
        });
      }
    });

    setInterval(newLibraraian, 1000);

    $(document).ready(function() {

      // Setup - add a text input to each footer cell
      $('#exampleas thead tr').clone(true).appendTo('#exampleas thead');
      $('#exampleas thead tr:eq(1) th').each(function(i) {

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

      var table = $('#exampleas').DataTable({
        bInfo: true,
        orderCellsTop: true,
        fixedHeader: true,
        bPaginate: false,
        searching: true,
      });
    });
  });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>