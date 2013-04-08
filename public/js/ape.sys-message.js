function sysMessage(ape, debug){
	// we call this function once APE has finished loading
	this.initialize = function(){
		// once a new user joins (pipe created), call setup()
		ape.addEvent('pipeCreate', this.setup);

		// when a user joins, update the user list
		ape.addEvent('userJoin', this.createUser);

		// when a user leaves, destroy them with mighty thunder!
		ape.addEvent('userLeft', this.deleteUser);

		// when we want to send data
		ape.onCmd('send', this.cmdSend);

		// and when we recieve data
		ape.onRaw('data', this.rawData);

		// start the session with a random name!
		// note: you'll need the chat plugin loaded
		ape.start(String((new Date()).getTime()).replace(/D/gi,''));
	}

	this.setup = function(type, pipe, options){
		// add an event listener on our selectbox
		$("#button").click(function(){
			// get the select box value
			if ($("#chat").val())
			{
				// send the message to the APE server
				pipe.send($("#chat").val());
			}
		});
	}

	this.cmdSend = function(pipe, sessid, pubid, message){
		if(debug)
			$("<span>    " + ape.user.properties.name + " sent new message: " + message + "</span><br />").prependTo("#debug");
	}

	this.rawData = function(raw, pipe){
		// data has been received by the APE server so do the following...
		if(debug)
			$("<span>   Received message from  " + raw.datas.sender.properties.name + " saying: " + raw.datas.msg + "</span><br />").prependTo("#debug");

		// set the selectboxes value to match other clients
		$('<span>' + raw.datas.msg + '</span>').prependTo("#wrapper");
	}

	this.createUser = function(user, pipe){
		// a user has joined so prepend them to the debug window
		user.element = $("<span>" + user.properties.name + " has joined</span><br />").prependTo("#debug");
	}

	this.deleteUser = function(user, pipe){
		// a user has left so update the debug window
		$(user.element).text(user.properties.name + " has left").css("color", "#666666").prependTo("#debug");
	}
}