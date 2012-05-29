var cognosys = {
	init: function() {
		humane.timeout = -1
		humane.clickToClose = true
	},
	alert: function(messages, type) {
		humane.create({timeout: -1,clickToClose: true})
		      .log(messages, {addnCls: 'humane-jackedup-'+type})
	}
}
