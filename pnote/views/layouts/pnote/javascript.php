<script>
	function loadEventShortcut(){
		$('.button-shortcut').off('click').on('click', function(){
			var link = $(this).data('link');
			var name_shortcut = $(this).data('name_shortcut');
			var link_shortcut = $(this).data('link_shortcut');
			var group_shortcut = $(this).data('group_shortcut');
			$('#name_shortcut').val(name_shortcut);
			$('#link_shortcut').val(link_shortcut);
			$('#group_shortcut').val(group_shortcut);
			if (name_shortcut)
			{
				$('#form_shortcut ._method').val('PUT');
			}
			else
			{
				$('#form_shortcut ._method').val('POST');
			}

			$('#form_shortcut').attr('action', link);
		});

		$('.remove-shortcut').off('click').on('click', function(e){
			e.preventDefault();
			var id = $(this).data('id');
            var result = confirm("You are going to delete 1 record(s). Are you sure ?");
            if (result) {
				var form = new FormData();
				form.append('_method', 'DELETE');
                $.ajax({
					url: `<?php echo $this->link_shortcut_form ?>/${id}`,
					type: 'POST',
					processData: false,
					contentType: false,
					data: form,
					success: function(result) {
						if (result.status != 'done') {
							var message = result.message ? result.message : 'Save Failed';
							alert(result.message);
						} 
						else{
							loadShortcut();
							location.reload();
						}
					}
				});
            }
            else
            {
                return false;
            }
		});
	};
	$(document).ready(function(){
		loadEventShortcut();
	})
</script>