<?php foreach($ug_mstags as $ug_mstag): ?>
    <tr>
        <td width="5" style="text-align: center;">
            <input type="checkbox" class="flat-red check" name="id[]" value="<?= $ug_mstag->id; ?>">
        </td>
                
        <td style="text-align: center;"><span class="list_group-kode_tid"><?= _ent($ug_mstag->kode_tid); ?></span></td> 
        <td style="text-align: center;"><span class="list_group-kode_epc"><?= _ent($ug_mstag->kode_epc); ?></span></td> 
        <td style="text-align: center;">
            <span class="list_group-status_tag">
            <?= $ug_mstag->status_tag == 'Y' ? 'Available' : 'Not Available'; ?>
            </span>
        </td> 
        <td style="text-align: center;">
            <span class="list_group-status_tag">
            <?php 
            if ($ug_mstag->kategori_tag == '0') {
                echo 'Not Used';
            } elseif ($ug_mstag->kategori_tag == '1') {
                echo 'Aset';
            } elseif ($ug_mstag->kategori_tag == '2') {
                echo 'People';
            }
            ?>
            </span>
        </td> 
        <td width="200" style="text-align: center;">
        
            <?php is_allowed('ug_mstag_view', function() use ($ug_mstag){?>
            <a href="<?= admin_site_url('/ug_mstag/view/' . $ug_mstag->id); ?>" data-id="<?= $ug_mstag->id ?>" class="label-default btn-act-view"><i class="fa fa-newspaper-o"></i> <?= cclang('view_button'); ?>
            <?php }) ?>
            <!-- <?php is_allowed('ug_mstag_update', function() use ($ug_mstag){?>
            <a href="<?= admin_site_url('/ug_mstag/edit/' . $ug_mstag->id); ?>" data-id="<?= $ug_mstag->id ?>" class="label-default btn-act-edit"><i class="fa fa-edit "></i> <?= cclang('update_button'); ?></a>
            <?php }) ?> -->
            <!-- <?php is_allowed('ug_mstag_delete', function() use ($ug_mstag){?>
            <a href="javascript:void(0);" data-href="<?= admin_site_url('/ug_mstag/delete/' . $ug_mstag->id); ?>" class="label-default remove-data"><i class="fa fa-close"></i> <?= cclang('remove_button'); ?></a>
            <?php }) ?> -->

        </td>
    </tr>
<?php endforeach; ?>
<?php if ($ug_mstag_counts == 0) :?>
    <tr>
        <td colspan="100">
            Data is not available
        </td>
    </tr>
<?php endif; ?>