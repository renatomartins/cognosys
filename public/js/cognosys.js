var cognosys = {
	init: function() {
		
	},
	alert: function(messages, type) {
		humane.create({timeout: -1,clickToClose: true})
		      .log(messages, {addnCls: 'humane-jackedup-'+type})
	}
}
