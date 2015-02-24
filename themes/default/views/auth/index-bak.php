
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('index_heading'); ?></h2>
        <div class="box-icon">
            <a class="btn-add" href="<?= site_url('auth/create_user') ?>"><i class="fa fa-plus"></i></a>
            <!--<a class="btn-minimize" href="#"><i class="fa fa-chevron-up"></i></a>-->
        </div>
    </div>
    <div class="box-content">  
        <div class="row">            
            <div class="col-lg-12">

                <p class="introtext"><?= lang('index_subheading'); ?></p>
                <div class="table-responsive">
                    <table cellpadding=0 cellspacing=10 class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?php echo lang('index_fname_th'); ?></th>
                                <th><?php echo lang('index_lname_th'); ?></th>
                                <th><?php echo lang('index_email_th'); ?></th>
                                <th><?php echo lang('group'); ?></th>
                                <th><?php echo lang('index_status_th'); ?></th>
                                <th><?php echo lang('index_action_th'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo $user->first_name; ?></td>
                                    <td><?php echo $user->last_name; ?></td>
                                    <td><?php echo $user->email; ?></td>
                                    <td>
                                        <?php foreach ($user->groups as $group): ?>
                                            <?= $group->name; ?> <?php //anchor("auth/edit_group/" . $group->id, $group->name);  ?>
                                            <br />
                                        <?php endforeach ?>
                                    </td>
                                    <td style="width: 55px;  text-align: center;"><?= ($user->active) ? anchor("auth/deactivate/" . $user->id, '<span class="label label-success"><i class="fa fa-check"> '.lang('index_active_link').'</span>', 'data-toggle="modal" data-target="#myModal"') : anchor("auth/activate/" . $user->id, '<span class="label label-danger"><i class="fa fa-times"> '.lang('index_inactive_link').'</span>'); ?></td>
                                    <td style="width: 55px; text-align: center;"><?= anchor("auth/profile/" . $user->id, '<i class="fa fa-edit">'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p><?= anchor('auth/create_user', lang('index_create_user_link'), 'class="btn btn-primary"') ?> <?php /* echo anchor('auth/create_group', lang('index_create_group_link'), 'class="btn btn-primary"') */ ?></p>
            </div><!--/row -->                           

        </div>
    </div>
</div>
