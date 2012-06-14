var cognosys = {
	init: function() {
		this.confirm.init()
	},
	alert: function(messages, type) {
		humane.create({timeout: -1, clickToClose: true})
		      .log(messages, {addnCls: 'humane-jackedup-'+type})
	},
	confirm: {
		init: function() {
			$('a[rel="confirm"]').each(function(){
				$(this).click(function(e) {
					e.preventDefault();
					cognosys.confirm.popup($(this).attr('href'), $(this).attr('confirm'))
				})
			})
		},
		popup: function(url, text) {
			var id = 'modal-confirm'
			var modal_start = '<div id="'+id+'" class="modal hide fade">'
			var modal_header = '<div class="modal-header"><h3>Confirm</h3></div>'
			var modal_body = '<div class="modal-body">'+text+'</div>'
			var modal_footer = '<div class="modal-footer"><a class="btn btn-primary" href="'+url+'">OK</a><a class="btn" data-dismiss="modal">Cancel</a></div>'
			var modal_end = '</div>'
			$(modal_start + modal_header + modal_body + modal_footer + modal_end).appendTo(document.body)
			$('#'+id).modal()
		}
	}
}
