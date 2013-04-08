$(document).ready(function() {
	
	var topPanelCollapsed = true;
	var currentTab = false;
	var currentPanel = false;
	var fadeInterval = 250;
	var fadeTransitionInterval = 500;
	var mainPanel = '#panelMainContent';
	var panelSegments = ["one", "two", "three"];
	
	// Panel open-close and content swap
	$(".top-panel-toggle").click(function () {
		$("#toggle a").toggle();
		if (topPanelCollapsed) {
			// Open the panel
			currentTab = $(this).attr('id');
			currentPanel = $(this).attr('panel');
			if ('#panelMainContent' != currentPanel)
				$('#panelMainContent').html($(currentPanel).html());
			$("div#panel").slideDown("slow");
			topPanelCollapsed = false;
		} else {
			if (currentTab != $(this).attr('id')) {
				// Panel content swap
				var panel = $(this).attr('panel');
				var source = [];
				var sourceA = [];
				var targetA = [];
				for (var i = 0; i < panelSegments.length; i++) {
					source.push(mainPanel + ' .' + panelSegments[i]);
					sourceA.push(mainPanel + ' .' + panelSegments[i]);
					targetA.push(panel + ' .' + panelSegments[i]);
				}
				var mySource = source.shift();
				$(mySource).fadeTo(fadeTransitionInterval, 0.01, function () {
					var mySourceA = sourceA.shift();
					var myTargetA = targetA.shift();
					$(mySourceA).html($(myTargetA).html());
					$(mySourceA).fadeTo(fadeTransitionInterval, 1.0);
				});
				if (panelSegments.length > 1) {
					for (var i = 1; i < panelSegments.length; i++) {
						setTimeout(function () {
							var mySource = source.shift();
							$(mySource).fadeTo(fadeTransitionInterval, 0.01, function () {
								var mySourceA = sourceA.shift();
								var myTargetA = targetA.shift();
								$(mySourceA).html($(myTargetA).html());
								$(mySourceA).fadeTo(fadeTransitionInterval, 1.0);
							});
							}, i * fadeInterval);
					}
				}
				currentTab = $(this).attr('id');
			} else {
				// Close the panel
				$("div#panel").slideUp("slow");	
				topPanelCollapsed = true;
			}
		}
	});
		
});