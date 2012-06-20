var cognosys = {
	init: function() {
		this.ajax.init()
		this.confirm.init()

		this.tooltips()
		this.tabs()
		this.datepicker()
	},
	ajax: {
		init: function() {
			var ajax = this
			$('ajax').on('load', function() {
				var $this = $(this)
				ajax.send($this.attr('href'), function() {
					$this.html('<div class="center progress progress-warning progress-striped active" style="width:80px"><div class="bar" style="width:100%">loading...</div></div>')
				}, function(data) {
					$this.html(data)
					$this.removeAttr('load')
					$this.attr('loaded', '')
					//TODO: how to execute all inits in this content?
				}, function() {
					$this.html('<div class="center">There was an error in the request</div>')
				})
			})
			$('ajax[load]').trigger('load')
		},
		activate: function(parent) {
			$(parent).children('ajax').not('[loaded]').each(function() {
				$(this).trigger('load')
			})
		},
		send: function(url, before, success, error) {
			$.ajax({url:url,beforeSend:before,success:success,error:error})
		}
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
	datepicker: function() {
		$('.date').datepicker().on('changeDate', function(e) {
			$this = $(this)
			$this.children(':first').html($this.data('date'))
			$this.datepicker('hide')
		})
	},
	hash: {
		get: function() {
			var index = window.location.href.indexOf('#')
			return (index == -1 ? '' : window.location.href.substr(index+1))
		}
	},
	tabs: function() {
		$('[data-toggle="tab"]').on('shown', function() {
			cognosys.ajax.activate($($(this).attr('href')))
		})
	},
	tooltips: function() {
		$('a[title]').tooltip({animation: false})
	}
}
