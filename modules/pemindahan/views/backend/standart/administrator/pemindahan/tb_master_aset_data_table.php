<?php foreach($pemindahans as $tb_master_transaksi): ?>
    <tr>
                
        <td width="5">
            <input type="checkbox" class="flat-red check" name="id[]" value="<?= $tb_master_transaksi->id; ?>">
        </td>
                
        <td><span class="list_group-kode_transaksi"><?= _ent($tb_master_transaksi->id); ?></span></td> 
        <td><span class="list_group-status_transaksi"><?= _ent($tb_master_transaksi->nama_aset); ?></span></td> 
        <td><span class="list_group-tgl_awal_transaksi"><?= _ent($tb_master_transaksi->kode_aset); ?></span></td> 
        <td><span class="list_group-ket_transaksi"><?= _ent($tb_master_transaksi->nup); ?></span></td>
         
    </tr>
    <?php endforeach; ?>
    <?php if ($pemindahan_counts == 0) :?>
        <tr>
        <td colspan="100">
        data is not available
        </td>
        </tr>
    <?php endif; ?>