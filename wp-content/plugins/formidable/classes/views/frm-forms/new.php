<div class="wrap">
    <div class="frmicon icon32"><br/></div>    
    <h2><?php _e('Add New Form', 'formidable') ?></h2>
    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <?php if (!$values['is_template']){ ?>
        <div class="alignleft">
            <?php FrmAppController::get_form_nav($id, true); ?>
        </div>
    <?php } ?>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    <?php require('add_field_links.php'); ?>
    <div id="post-body">
    <div id="post-body-content">
    <div class="frm_form_builder<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>">
        <form method="post" >
            <p style="margin-top:0;">
                <input type="submit" value="<?php _e('Create', 'formidable') ?>" class="button-primary" />
                <?php _e('or', 'formidable') ?>
                <a class="button-secondary cancel" href="?page=formidable&amp;frm_action=destroy&amp;id=<?php echo $id; ?>"><?php _e('Cancel', 'formidable') ?></a>
            </p>
            
            <input type="hidden" name="frm_action" value="create" />
            <input type="hidden" name="action" value="create" />
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <?php wp_nonce_field('update-options'); ?>

            <?php require(FRM_VIEWS_PATH.'/frm-forms/form.php'); ?>

            <p>
                <input type="submit" value="<?php _e('Create', 'formidable') ?>" class="button-primary" />
                <?php _e('or', 'formidable') ?>
                <a class="button-secondary cancel" href="?page=formidable&amp;frm_action=destroy&amp;id=<?php echo $id; ?>"><?php _e('Cancel', 'formidable') ?></a>
            </p>
        </form>
    </div>
    </div>
    </div>
    </div>
</div> 
<?php require('footer.php'); ?>