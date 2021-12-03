jQuery(function ($) {
	// TODO: Add IPFS icon to uploaded attachment.
	
	// Single media upload event
	$(document).on("click", ".ipup-upload", function ($) {
		const post_id = jQuery(this).attr("data-post-id");
		jQuery("#ipup-" + post_id).attr("disabled", true);
		jQuery("#ipup-" + post_id).html(ipup.textWait);
		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: ipup.endpoint + 'ipup/v1/ipfs-upload/',
			data: {
				attachment_id: post_id
			},
			post_id: post_id,
			success: function (msg) {
				jQuery("#ipup-" + this.post_id).hide();
				var result = msg.result
				if (result==='ERROR') {
					jQuery("#ipup-upload-result-" + this.post_id).html( msg.message );
					jQuery("#ipup-" + post_id).removeAttr('disabled');
					jQuery("#ipup-" + post_id).html(ipup.textPrepare);
					jQuery("#ipup-" + this.post_id).show();
				} else {
					let html = `
					<input type="text" class="attachment-details-copy-link" id="attachment-details-copy-ipfs-link" value="${msg.hash}" readonly="">
					<span class="copy-to-clipboard-container" style="margin: 0;">
						<button type="button" class="button button-small copy-attachment-url" data-clipboard-target="#attachment-details-copy-ipfs-link">複製網址至剪貼簿</button>
						<span class="success hidden" aria-hidden="true">已完成複製！</span>
					</span>
					`
					jQuery("#ipup-upload-result-" + this.post_id).html( html );
				}
			}
		})
	});

	// Add bulk upload button.
	const btnUpload = $('.ipup-selected-upload-button');
	if( btnUpload.length === 0 ) {
		let button = `
		<button type="button" class="button media-button button-primary button-large ipup-selected-upload-button" disabled="disabled">${ipup.textPrepare}</button>
		`
		$('.delete-selected-button').after(button);
	}

	// Bulk upload event.
	$(document).on('click','.media-frame.mode-grid.mode-select .attachment',function(){
		var selectedAttachmentId = [];
		if( $('.media-frame.mode-grid.mode-select .attachment.selected').length > 0 ){
			$('.ipup-selected-upload-button').prop('disabled',false)
		} else {
			$('.ipup-selected-upload-button').prop('disabled',true)
		}
		$('.media-frame.mode-grid.mode-select .attachment.selected').each(function(){
			selectedAttachmentId.push( $(this).data('id') )	
		})
		console.log(selectedAttachmentId);

		$(document).on('click','.ipup-selected-upload-button',function(){
			$(this).attr("disabled", true);
			$(this).text(ipup.textWait);
			for (let index = 0; index < selectedAttachmentId.length; index++) {
				if($(`.attachments-wrapper li[data-id="${selectedAttachmentId[index]}"] .centered .lds-roller`).length === 0){
					$(`.attachments-wrapper li[data-id="${selectedAttachmentId[index]}"] .centered`).append('<div class="lds-roller"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>');
				}
			}
			jQuery.ajax({
				type: "post",
				dataType: "json",
				url: ipup.endpoint + 'ipup/v1/ipfs-upload/',
				data: {
					attachment_id: selectedAttachmentId
				},
				success: function (msg) {
					console.log(msg);
					postId = msg.post_id;
					$(`.attachments-wrapper li[data-id="${postId}"] .centered .lds-roller`).remove();
					$(`.attachments-wrapper li[data-id="${postId}"]`).removeClass('selected');
					$('.ipup-selected-upload-button').attr("disabled", false);
					$('.ipup-selected-upload-button').text(ipup.textPrepare);
				}
			})
		})
	})
	
	$('.select-mode-toggle-button').click(function(){
		$('.ipup-selected-upload-button').prop('disabled',true)
	})
})