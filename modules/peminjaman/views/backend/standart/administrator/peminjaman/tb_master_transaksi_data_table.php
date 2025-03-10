<?php foreach($peminjamans as $tb_master_transaksi): ?>
    <tr>
                <td width="5">
            <input type="checkbox" class="flat-red check" name="id[]" value="<?= $tb_master_transaksi->id; ?>">
        </td>
                
        <!-- <td><span class="list_group-kode_transaksi"><?= _ent($tb_master_transaksi->kode_transaksi); ?></span></td>  -->
        <td style="text-align: center"><?php if ($tb_master_transaksi->tipe_transaksi) {
            echo $tb_master_transaksi->tb_master_type_transaksi_tipe_transaksi; }?></td>
        <!-- <td><span class="list_group-status_transaksi"><?= _ent($tb_master_transaksi->status_transaksi); ?></span></td>  -->
         
        <td style="text-align: center"><span class="list_group-tgl_awal_transaksi"><?= _ent(date('d-m-Y', strtotime($tb_master_transaksi->tgl_awal_transaksi))); ?></span></td>
        <td style="text-align: center"><span class="list_group-tgl_akhir_transaksi"><?= _ent(date('d-m-Y', strtotime($tb_master_transaksi->tgl_akhir_transaksi))); ?></span></td>
        <td><span class="list_group-ket_transaksi"><?= _ent($tb_master_transaksi->ket_transaksi); ?></span></td> 
        
        <td style="text-align: center"><?php if ($tb_master_transaksi->id_pegawai) {
            echo $tb_master_transaksi->tb_master_pegawai_nama;
        } ?></td>
        
        <!-- <td><span class="list_group-id_pegawai_input"><?= _ent($tb_master_transaksi->id_pegawai); ?></span></td> 
        <td><span class="list_group-nama_pegawai_input"><?= _ent($tb_master_transaksi->nama_pegawai_input); ?></span></td>  -->
        <!-- <td><span class="list_group-id_pegawai_input"><?= _ent($tb_master_transaksi->id_pegawai_input); ?></span></td> 
        <td><span class="list_group-nama_pegawai_input"><?= _ent($tb_master_transaksi->nama_pegawai_input); ?></span></td> 
        <td style="text-align: center"><?php if ($tb_master_transaksi->id_area) {
            echo $tb_master_transaksi->tb_master_area_area;
        } ?></td>
         
         <td style="text-align: center"><?php if ($tb_master_transaksi->id_gedung) {
            echo $tb_master_transaksi->tb_master_gedung_gedung;
        } ?></td>
         
         <td style="text-align: center"><?php if ($tb_master_transaksi->id_ruangan) {
            echo $tb_master_transaksi->tb_master_ruangan_ruangan;
        } ?></td> -->

        <!-- Status Transaksi -->
        <td style="text-align: center">
            <?php 
                switch ($tb_master_transaksi->status_transaksi) {
                    case 1:
                        echo "Open";
                        break;
                    case 2:
                        echo "Progress";
                        break;
                    case 3:
                        echo "Complete";
                        break;
                    case 4:
                        echo "Batal";
                        break;
                    default:
                        echo "Unknown";
                }
            ?>
        </td>
        <td width="200" style="text-align: center">
        
            <?php is_allowed('peminjaman_view', function() use ($tb_master_transaksi){?>
                <a href="<?= admin_site_url('/peminjaman/view/' . $tb_master_transaksi->id); ?>" data-id="<?= $tb_master_transaksi->id ?>" class="label-default btn-act-view"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
            <?php }) ?>
            
            <!-- <?php is_allowed('tb_master_transaksi_update', function() use ($tb_master_transaksi){?>
            <a href="<?= admin_site_url('/peminjaman/edit/' . $tb_master_transaksi->id); ?>" data-id="<?= $tb_master_transaksi->id ?>" class="label-default btn-act-edit"><i class="fa fa-edit "></i> <?= cclang('update_button'); ?></a>
            <?php }) ?> -->
            <!-- <?php is_allowed('tb_master_transaksi_delete', function() use ($tb_master_transaksi){?>
                <a href="javascript:void(0);" data-href="<?= admin_site_url('/peminjaman/delete/' . $tb_master_transaksi->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
            <?php }) ?> -->

        </td>    
    </tr>
<?php endforeach; ?>
<?php if ($peminjaman_counts == 0) :?>
        <tr>
        <td colspan="100">
        Peminjaman data is not available
        </td>
    </tr>
<?php endif; ?>