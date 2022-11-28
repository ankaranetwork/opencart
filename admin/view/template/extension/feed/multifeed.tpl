<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-multifeed" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
        <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
    </div>
    <div class="panel-body">
		<?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-multi-feed" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
			<li><a href="#tab-storexml" data-toggle="tab"><?php echo $text_storexml; ?></a></li>
			<li><a href="#tab-google"  onClick="getCategories('Google')" data-toggle="tab"><?php echo $text_googlexml; ?></a></li>
            <li><a href="#tab-facebook" onClick="getCategories('Facebook')" data-toggle="tab"><?php echo $text_facebookxml; ?></a></li>
          </ul>			
		 <div class="tab-content">
		 
            <div class="tab-pane active" id="tab-general">
 
          <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $tab_store; ?></label>
            <div class="col-sm-10">
             <select name="multifeed_store" class="form-control">
			 <?php  var_dump($stores); if ($stores) { ?>
			 <?php foreach ($stores as $store) { ?>
             <option value="<?php echo $store['store_id']; ?>" <?php if($store['store_id']== $multifeed_store) echo 'selected="selected"';?> >
			 <?php echo $store['name']; ?></option>
			  <?php } ?>
			  <?php } else { ?>
			 <option value="0"><?php echo $text_default; ?></option>
			 <?php } ?>
            </select>
            </div>
          </div>
	
	
	
        <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $text_language; ?></label>
            <div class="col-sm-10">
             <select name="multifeed_language" class="form-control">
			 <?php if ($languages) { ?>
			 <?php foreach ($languages as $language) { ?>
             <option value="<?php echo $language['language_id']; ?>" <?php if($language['language_id']== $multifeed_language) echo 'selected="selected"';?> >
			 <?php echo $language['name']; ?></option>
			  <?php } ?>
			  <?php }  ?>
            </select>
            </div>
          </div>	
	
 	
		  
		 <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $text_password; ?></label>
            <div class="col-sm-10">
            <input type="text" name="multifeed_password" value="<?php echo $multifeed_password; ?>" class="form-control">
            </div>
          </div>
		
		
		
		  <div class="well">
          <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $text_width; ?></label>
            <div class="col-sm-10">
            <input type="text" name="multifeed_width" value="<?php echo $multifeed_width ? $multifeed_width :500; ?>" placeholder="<?php echo $text_width; ?>" class="form-control">
            </div>
          </div>		
		
		
		
		
          <div class="form-group">
            <label class="col-sm-2 control-label" ><?php echo $text_height; ?></label>
            <div class="col-sm-10">
            <input type="text" name="multifeed_height" value="<?php echo $multifeed_height ? $multifeed_height : 500; ?>" placeholder="<?php echo $text_height; ?>" class="form-control">
            </div>
          </div>
		  		
		</div>
		
		  
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="multifeed_status" id="input-status" class="form-control">
                <?php if ($multifeed_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>		  
		  
		  
		  
		  
		  
         </div>
			
			
			
			<div class="tab-pane" id="tab-storexml">
			<div class="well well-xs">
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-multi-feed"><?php echo $entry_data_feed; ?></label>
            <div class="col-sm-10">
            <input readonly id="input-data-multi-feed" class="form-control" value="<?php echo $data_feed; ?>&sifre=<?php echo $multifeed_password; ?>&platform=Store" />
            </div>
			</div>
            </div>		
			
			
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-options"><?php echo $text_options; ?></label>
            <div class="col-sm-10">
              <select name="multifeed_options" id="input-options" class="form-control">
                <?php if ($multifeed_options) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>			
			
			
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-attributes"><?php echo $text_attributes; ?></label>
            <div class="col-sm-10">
              <select name="multifeed_attributes" id="input-attributes" class="form-control">
                <?php if ($multifeed_attributes) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>			
				
			</div>
			
			
	       <div class="tab-pane" id="tab-google">
	 <button type="button" id="button-import-Google" onClick="importCategories('Google')" class="btn btn-success"><i class="fa fa fa-upload"></i> <?php echo $button_import; ?></button>
			 <?php echo $text_google; ?>
			 <hr />
			<div class="well well-xs">
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-multi-feed"><?php echo $entry_data_feed; ?></label>
            <div class="col-sm-10">
            <input readonly id="input-data-multi-feed"  class="form-control" value="<?php echo $data_feed; ?>&sifre=<?php echo $multifeed_password; ?>&platform=Google" />
            </div>
			</div>
            </div>			
			
			
			
				
			<div id="Google-Categories"></div>
            <br />
            <div class="form-group">
              <div class="col-sm-12">
                  <input type="text" name="multifeed_category" value="" data-multifeed="Google" placeholder="Google" id="input-multifeed-category" class="form-control" />
                  <input type="hidden" name="multifeed_category_id" value="" />
                  <div class="input-group">
                    <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category"  class="form-control" />
                    <input type="hidden" name="category_id" value="" />
                    <span class="input-group-btn"><button type="button"  id="Google" onClick="addCategory('Google')" class="btn btn-primary"><?php echo $button_category_add; ?> <i class="fa fa-plus"></i></button></span>
                  </div>
              </div>
            </div>	

	  
		    </div>		
			
			
			
			
			<!-- Kategoriler Start -->
			<div class="tab-pane" id="tab-facebook">
			 <button type="button" id="button-import-Facebook" onClick="importCategories('Facebook')" class="btn btn-success"><i class="fa fa fa-upload"></i> <?php echo $button_import; ?></button>
			 <?php echo $text_facebook; ?>
			 <hr />
			<div class="well well-xs">
			<div class="form-group">
            <label class="col-sm-2 control-label" for="input-data-multi-feed"><?php echo $entry_data_feed; ?></label>
            <div class="col-sm-10">
            <input readonly id="input-data-multi-feed" class="form-control" value="<?php echo $data_feed; ?>&sifre=<?php echo $multifeed_password; ?>&platform=Facebook" />
            </div>
			</div>
            </div>			
			
			
			
				
			<div id="Facebook-Categories"></div>
            <br />
            <div class="form-group">
              <div class="col-sm-12">
                  <input type="text" name="multifeed_category" value="" data-multifeed="Facebook" placeholder="Facebook" id="input-multifeed-category" class="form-control" />
                  <input type="hidden" name="multifeed_category_id" value="" />
                  <div class="input-group">
                    <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                    <input type="hidden" name="category_id" value="" />
                    <span class="input-group-btn"><button type="button" id="Facebook" onClick="addCategory('Facebook')" class="btn btn-primary"><?php echo $button_category_add; ?> <i class="fa fa-plus"></i></button></span>
                  </div>
              </div>
            </div>	
			
			
			
          			
			
			
				  
		    </div>
			<!-- Kategoriler End -->
		   </div>
        </form>
    </div>
  </div>
</div>





<script type="text/javascript"><!--
//  Categories
$('input[name=\'multifeed_category\']').autocomplete({
    
	
		
    'source': function(request, response) {
	
	  var CategoryFeed = $(this).attr("data-multifeed");
	  
      $.ajax({
        url: 'index.php?route=extension/feed/multifeed/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request) + '&getcategory=' + CategoryFeed,
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['multifeed_category_id']
            }
          }));
        },
        error: function(xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    },
    'select': function(item) {
      $(this).val(item['label']);
      $('input[name=\'multifeed_category_id\']').val(item['value']);
  }
});

// Category
$('input[name=\'category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			},
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
	},
	'select': function(item) {
      $(this).val(item['label']);
      $('input[name="category_id"]').val(item['value']);
    }
});




$('#Google-Categories').delegate('.pagination a', 'click', function(e) {

	e.preventDefault();

	$('#Google-Categories').load(this.href);
	
});


$('#Facebook-Categories').delegate('.pagination a', 'click', function(e) {

	e.preventDefault();

	$('#Facebook-Categories').load(this.href);
	
});



function getCategories(categories) {
 
 
 $.ajax({
    url: 'index.php?route=extension/feed/multifeed/category&token=<?php echo $token; ?>&getcategory=' + categories, 
	
	success: function(result){
         $('#' + categories + '-Categories').load('index.php?route=extension/feed/multifeed/category&token=<?php echo $token; ?>&getcategory=' + categories);
     	}
	  }
	  
	 );
 

}


function addCategory(multiFeed) {

   $.ajax({
		url: 'index.php?route=extension/feed/multifeed/addcategory&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'multifeed_category_id=' + $('input[name=\'multifeed_category_id\']').val() + '&category_id=' + $('input[name=\'category_id\']').val() + '&platform=' + multiFeed,
		beforeSend: function() {
			$('#button-category-add').button('loading');
		},
		complete: function() {
			$('#button-category-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#' + multiFeed + '-Categories').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
 
 
			if (json['success']) {
 				$('#' + multiFeed + '-Categories').load('index.php?route=extension/feed/multifeed/category&token=<?php echo $token; ?>&getcategory=' + multiFeed);

				$('#' + multiFeed + '-Categories').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

		  $('input[name=\'category\']').val('');
          $('input[name=\'category_id\']').val('');
          $('input[name=\'multifeed_category\']').val('');
          $('input[name=\'multifeed_category_id\']').val('');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function removeCategory(_removeFeedCategory, _removeCategory, _platform) {
	
	 var removeFeedCategory = _removeFeedCategory;
     var removeCategory = _removeCategory;
     var platform = _platform;
 
	$.ajax({
		url: 'index.php?route=extension/feed/multifeed/removecategory&token=<?php echo $token; ?>',
		type: 'post',
		data: 'multifeed_category_id=' + removeFeedCategory + '&category_id=' + removeCategory + '&platform=' + platform,
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#kategoriyiSil-'+ platform).button('loading');
		},
		complete: function() {
			$('#kategoriyiSil-'+ platform).button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			// Check for errors
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

      if (json['success']) {
				$('#' + platform + '-Categories').load('index.php?route=extension/feed/multifeed/category&token=<?php echo $token; ?>&getcategory=' + platform);

				$('#' + platform + '-Categories').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	
	
}

function importCategories(categoryPlatform) {

 
	$('#form-upload').remove();

 
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
        clearInterval(timer);
	}
 

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);
			$.ajax({
				url: 'index.php?route=extension/feed/multifeed/import&token=<?php echo $token; ?>&platform=' + categoryPlatform,
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-import-'+categoryPlatform).button('loading');
				},
				complete: function() {
					$('#button-import-'+categoryPlatform).button('reset');
				},
				success: function(json) {
					$('.alert').remove();

          if (json['error']) {
        		$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        	}

        	if (json['success']) {
        		$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        	}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
}
//--></script>
</div>
<?php echo $footer; ?>