var cognosys = {
	init: function() {
		this.confirm.init()

		$('a[rel="tooltip"]').tooltip({animation: false, delay: 500})
	},
	alert: function(messages, type) {
		humane.create({timeout: -1, clickToClose: true})
		      .log(messages, {addnCls: 'humane-jackedup-'+type})
	},
	confirm: {
		init: function() {
			$('a[confirm]').each(function(){
				$(this).click(function(e) {
					e.preventDefault();
					cognosys.confirm.popup($(this).attr('href'), $(this).attr('confirm'))
				})
			})
		},
		popup: function(url, text) {
			var id = 'modal-confirm'
			var m_start = '<div id="'+id+'" class="modal hide fade">'
			var m_header = '<div class="modal-header"><h3>Confirm</h3></div>'
			var m_body = '<div class="modal-body">'+text+'</div>'
			var m_footer = '<div class="modal-footer"><a class="btn btn-primary" href="'+url+'">OK</a><a class="btn" data-dismiss="modal">Cancel</a></div>'
			var m_end = '</div>'

			if ($('#'+id).length) {
				$('#'+id).html(m_header + m_body + m_footer)
			} else {
				$(m_start + m_header + m_body + m_footer + m_end).appendTo(document.body)
			}
			$('#'+id).modal()
		}
	},
	hash: {
		get: function() {
			var index = window.location.href.indexOf('#')
			return (index == -1 ? '' : window.location.href.substr(index+1))
		}
	}
}
