<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="settingsModalLabel">Advanced Settings</h4>
      </div>
      <div class="modal-body">
      	<div class="alert alert-danger">These are the Opencart events that are linked with the Facebook Marketing analytics extension.<br/>
      	<b>Making changes here can disrupt and/or break the functionality of the conversion tracking!!</b><br/>
      	Make sure you know what you are doing before adding events here.</div>
        <?php if ($oc_events) { ?>
        	<?php $oc_rows = 0; ?>
			<table class="table table-responsive table-condensed table-hover" id="table-oc-events">
				<thead>
					<th>Trigger</th>
					<th>Action</th>
				</thead>
				<tbody>
				<?php foreach ($oc_events as $oc_event) { ?>
					<tr id="oc-event<?php echo $oc_rows; ?>">
						<td><?php echo $oc_event['trigger']; ?></td>
						<td><?php echo $oc_event['action']; ?></td>
					</tr>
					<?php $oc_rows++; ?>
				<?php } ?>
					<tr>
					<td></td>
					<td><button type="button" class="btn btn-primary pull-right" title="Add Opencart Event" id="add-oc-event"><i class="fa fa-plus"></i></button></td>
					</tr>
				</tbody>
			</table>
        <?php } else { ?>
        	<div class="alert alert-danger">No Opencart events found! Try reinstalling the extension.</div>
        <?php } ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
    var oc_event_row = <?php echo $oc_rows; ?>;
	
	$('#add-oc-event').on('click', function() {
		
		html = '<tr id="oc-event' + oc_event_row + '">';
		html += '<td><input type="text"  placeholder="Trigger" name="oc_event_trigger" class="form-control"></td>';
		html += '<td><div class="row"><div class="col-lg-9 col-md-9 col-sm-9"><input type="text" placeholder="Action" name="oc_event_action" class="form-control"></div>';
		html += '<div class="col-lg-3 col-md-3 col-sm-3"><button type="button" class="btn btn-sm btn-danger" title="Remove" onclick="$(this).parents(\'tr\').remove();"><i class="fa fa-minus"></i></button>';
		html += '<button type="button" class="btn btn-sm btn-success" title="Save"><i class="fa fa-save" onclick="saveOCEvent(' + oc_event_row + ');"></i></button></div>';
		html += '</div></td>';
		html += '</tr>';
		$('#table-oc-events tbody').append(html);
	})
	
	function saveOCEvent(row) {
		<?php if ($permission) { ?>
			$.ajax({
				url:'index.php?route=<?php echo $controller_path; ?>/saveOCEvent&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: { 
					trigger : $('#oc-event' + row + ' input[name="oc_event_trigger"]').val(),
					action :  $('#oc-event' + row + ' input[name="oc_event_action"]').val(),
				},
				success: function(json) {
					alertJson('alert alert-success', json);
				},
				error: function(json) {
					alertJson('alert alert-warning', json);
				}
			});
			return false;
	    <?php } else { ?>	    
	    	$('.alert').remove();
			$(".panel-default").before('<div class="alert alert-warning"><?php echo $error_permission; ?></div>');
		 <?php } ?>
	}
	
	
//--></script>