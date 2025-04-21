        $('#btn_pilih_aset').click(function(e) {

            e.preventDefault();
            
            // Menentukan tabel yang aktif
            var activeTab = $('#myTab .active a').attr('href'); // Mendapatkan ID tab yang aktif
            var myTable = $(activeTab).find('table'); // Mengambil tabel dari tab yang aktif

            console.log("ridwan", myTable.DataTable().rows('.selected').data().toArray().length);

            if (myTable.DataTable().rows('.selected').data().toArray().length == 0) {

                $('#total_aset_checklist').html(0);
                
                swal({
                    title: "Perhatian !",
                    text: "Silahkan pilih Aset yang ingin di Daftarkan RFID!",
                    type: "error"
                });

                return false;

            } else {
                
                $('#total_aset_checklist').html(myTable.DataTable().rows('.selected').data().toArray().length);

                arrayObj = myTable.DataTable().rows('.selected').data().toArray().map(item => {
                    return {
                        id: item[1],
                        kode_aset: item[3],
                        nama_aset: item[2],
                        nup: item[4],
                        kode_tid: item[5]
                    };
                });

                $('#data_array_aset').val(JSON.stringify(arrayObj)); // Menyimpan array data ke hidden input
                return true;

            }
            
        }); 