<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_multifeed_category; ?></td>
      <td class="text-left"><?php echo $column_category; ?></td>
      <td class="text-right"><?php echo $column_action; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($multifeed_categories) { ?>
    <?php foreach ($multifeed_categories as $multifeed_category) { ?>
    <tr>
      <td class="text-left"><?php echo $multifeed_category['multifeed_category']; ?></td>
      <td class="text-left"><?php echo $multifeed_category['category']; ?></td>
      <td class="text-right"><button type="button" id="kategoriyiSil-<?php echo $multifeed_category['platform']; ?>" 
	  onClick="removeCategory(<?php echo $multifeed_category['multifeed_category_id']; ?>, '<?php echo $multifeed_category['category_id']; ?>', '<?php echo $multifeed_category['platform']; ?>')"  
	  data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
