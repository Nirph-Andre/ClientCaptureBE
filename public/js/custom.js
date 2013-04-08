var updateAutoData = {};
var refreshAutoData = {};
var lastUpdatedId = false;
var postUpdateAction = [];
var postRefreshAction = [];
var postCollectAction = [];
var searchHandler = {};
var searchStack = {};
var mode = '';

new function($) {
  $.fn.setCursorPosition = function(pos) {
    if ($(this).get(0).setSelectionRange) {
      $(this).get(0).setSelectionRange(pos, pos);
    } else if ($(this).get(0).createTextRange) {
      var range = $(this).get(0).createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  }
}(jQuery);

function hasPlaceholderSupport() {
	var input = document.createElement('input');
	return ('placeholder' in input);
}

function generalNotice(header,body)
{
	$('#modalGeneralNotice h3').html(header);
	$('#modalGeneralNotice .modal-body').html(body);
	$('#modalGeneralNotice').modal('show');
}



$(document).ready(function() {
	//$.prettyLoader();
  $('.nav-vertical .nav-list:even').addClass('handy');
	$('.nav-vertical .nav-list:even').click(function () {
		$(this).next().slideToggle();
		$(this).find("i").toggleClass('icon-circle-arrow-down');
	});
	//$("input:checkbox, input:radio").uniform();
	$("[rel=tooltip]").tooltip({
		placement:"top", 
		delay: 250
	});
	$(".alert").alert();
	$(".popover").popover();
	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	setInterval('updateClock()', 1000);
	jQuery.validator.messages.required = "";
	jQuery.validator.messages.email = "";
});

$("#deleteForm").submit(function () {
	$("[rel=tooltip]").tooltip('hide');
	doUpdate('delete',
		$('#delete_namespace').val(),
		$('#delete_remove_action').val(),
		$('#delete_refresh_container').val(),
		$('#delete_refresh_action').val(),
		false, true);
	return false;
});

function loadUrl(url) {
  window.location = url;
}

function loadPage(page, options) {
	$("[rel=tooltip]").tooltip('hide');
	$('#adminMainContent').fadeTo(300, 0.5);
	$('#adminMainContent').load(
		'/admin/' + page, options,
		function () {
			$('#adminMainContent').fadeTo(200, 1);
			$("[rel=tooltip]").tooltip({
				placement:"top", 
				delay: 250
			});
			$(".alert").alert();
			$(".popover").popover();
			$(".datepicker").datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
}

function pagerPage(section, action, pagenum) {
	$("[rel=tooltip]").tooltip('hide');
	$('#' + section).fadeTo(300, 0.5);
	$('#' + section).load(
		'/' + theme + '/' + action,
		{
			pg_page: pagenum
		},
		function () {
			$('#' + section).fadeTo(200, 1);
		});
};

function pagerRecords(section, action, numrecords) {
	$("[rel=tooltip]").tooltip('hide');
	$('#' + section).fadeTo(300, 0.5);
	$('#' + section).load(
		'/' + theme + '/' + action,
		{
			pg_records: numrecords, 
			pg_page:1
		},
		function () {
			$('#' + section).fadeTo(200, 1);
		});
};

function search(namespace) {
	prepend = namespace.replace('-', '_') + '_';
	meta = searchHandler[namespace];
	$("[rel=tooltip]").tooltip('hide');
	$('#' + meta['container']).fadeTo(300, 0.5);
	data = {};
	data["x"] = "x";
	inputs = $('input.' + namespace + ', select.' + namespace).toArray();
	for (index in inputs) {
		item = inputs[index];
		if ($(item).attr('name') != undefined) {
			value = $(item).val();
			if (value.length) {
				name = $(item).attr('name');
				name = name.replace(prepend, '');
				data[name] = value;
			}
		}
	}
	refreshContent(meta['container'], meta['action'], false, false, {
		pg_filter: data, 
		pg_page:1
	});
};

function order(namespace, field, direction) {
	meta = searchHandler['srch-' + namespace];
	$("[rel=tooltip]").tooltip('hide');
	$('#' + meta['container']).fadeTo(300, 0.5);
	data = {};
	data[field] = direction;
	refreshContent(meta['container'], meta['action'], false, false, {
		pg_order: data, 
		pg_page:1
	});
}

function flagItem(field, id, label, refreshContainer, refreshAction) {
	$("[rel=tooltip]").tooltip('hide');
	$('#' + refreshContainer).fadeTo(300, 0.5);
	$.ajax({
		type: "POST",
		url: '/util/utility/set-data-flag',
		data: {
			fieldname: field, 
			filter: id, 
			filterlabel: label
		},
		success: function(data, textStatus, jqXHR) {
			$('#' + refreshContainer).fadeTo(300, 1);
			try {
				response = typeof(data)=='string'
				? $.parseJSON(data)
				: data;
			} catch (e) {
				alert('Could not flag data entry!');
			}
			refreshContent(refreshContainer, refreshAction);
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('#' + refreshContainer).fadeTo(300, 1);
			alert('Oops: Could not flag data entry!');
		}
	});
		
}

function collectData(dataAction, requestData) {
	$("[rel=tooltip]").tooltip('hide');
	if (!requestData) {
		requestData = {};
	}
	$.ajax({
		type: "POST",
		url: '/' + dataAction,
		data: requestData,
		success: function(data, textStatus, jqXHR) {
			try {
				response = typeof(data)=='string'
				? $.parseJSON(data)
				: data;
			} catch (e) {
				alert('Oops, incorrect data format received from the server!');
			}
			while (postCollectAction.length) {
				(postCollectAction.shift())(response['Data']);
			}
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			alert('Oops: ' + errorThrown);
			return false;
		}
	});
};

function refreshContent(section, action, titleId, title, data) {
	$("[rel=tooltip]").tooltip('hide');
	$('#' + section).fadeTo(300, 0.5);
	if (!data) {
		data = {};
	}
	$('#' + section).load(
		'/' + action,
		data,
		function () {
			$('#' + section).fadeTo(200, 1);
			while (postRefreshAction.length) {
				(postRefreshAction.shift())();
			}
		});
	if (titleId) {
		$('#' + titleId).html(title);
	}
};

function clearForm(namespace, modalElement) {
	$("[rel=tooltip]").tooltip('hide');
	$("[form-id=" + namespace + "]").each(function() {
		if (this.name) {
			if ($(this).is(':checkbox')) {
				$(this).prop("checked", false);
			} else if ($(this).is('textarea')) {
				$(this).val('');
				$('.wysihtml5-sandbox').contents().find('body').html('');
			} else if ($(this).hasClass('data-dependant-chain') || $(this).hasClass('data-dependant-child')) {
				meta = $(this).metadata({
					type:'attr', 
					name:'data'
				});
				meta = $('#' + meta['parent']).metadata({
					type:'attr', 
					name:'data'
				});
				$(this).html('<option value="">--- Select ' + meta['title'] + ' ---</option>');
			} else {
				$(this).val('');
			}
		}
	});
	if (modalElement) {
		$('#' + modalElement).modal('toggle');
	}
	mode = 'create';
};

function populateForm(namespace, context, dataItem, modalElement) {
	mode = 'populate';
	$("[rel=tooltip]").tooltip('hide');
	depParentValue = 0;
	$('[id^=' + namespace + '_]').each(function (){
		field = $(this).attr('id');
		field = field.replace(namespace + '_', '');
		element = $(this);

		if (typeof(dataItem[field]) == "undefined")
		{
			bits = field.split("_");
			sub = bits[bits.length-1];
			bits = bits.splice(0, bits.length-1);
			dataValue = (dataItem[bits.join('_')])
			? dataItem[bits.join('_')][sub]
			: "";
		}
		else
		{
			dataValue = dataItem[field];
		}
		if (typeof(dataValue) != "undefined")
		{
			if (element.is(':checkbox')) {
				if ('1' == dataValue) {
					element.prop("checked", true);
				} else {
					element.prop("checked", false);
				}
			} else if (element.is('textarea')) {
				element.val(dataValue);
				$('.wysihtml5-sandbox').contents().find('body').html(dataValue);
			} else if (element.hasClass('data-dependant-chain') || element.hasClass('data-dependant-child')) {
				meta = element.metadata({
					type:'attr', 
					name:'data'
				});
				parentDataField = context + '_' + meta['parent'].replace(namespace + '_', '')
				meta = $('#' + meta['parent']).metadata({
					type:'attr', 
					name:'data'
				});
				element.load(
					'/' + theme + '/' + 'list-dependancy-select',
					{
						dep_filter:{
							item:meta['item'], 
							field:meta['filterField'], 
							value:dataItem[parentDataField], 
							selected:dataValue
						}
					}
					);
			} else {
				element.val(dataValue);
			}
		}
	});
	
	if (modalElement) {
		$('#' + modalElement).modal('toggle');
	}
	mode = 'update';
};

function collectFormData(selector) {
	values = {};
	$(selector).each(function() {
		if (this.name) {
			shortname = this.name;
			if ($(this).is(':checkbox'))
			{
				values[$(this).val()] = $(this).is(':checked') ? 1 : 0;
			}
			else if ($(this).is(':radio'))
			{
				values[shortname] = $('input[name=' + this.name + ']:checked').val();
			}
			else
			{
				values[shortname] = $(this).val();
			}
      
		}
	});
	return values;
}

function doUpdate(namespace, datanamespace, updateAction, refreshContainer, refreshAction,
	warningContainer, closeOnSave, redirect, addon) {
	$("[rel=tooltip]").tooltip('hide');
	if (refreshContainer) {
		$('#' + refreshContainer).fadeTo(300, 0.5);
	}
	$('.btn-save').button('loading');
	values = {};
	if (namespace) {
		values = collectFormData("[form-id=" + namespace + "]");
	}
	if (addon) {
		for (index in addon) {
			values[index] = addon[index];
		}
	}
	if (updateAutoData[updateAction]) {
		for (index in updateAutoData[updateAction]) {
			values[index] = updateAutoData[updateAction][index];
		}
	}
	if (datanamespace) {
		var postData = {};
		postData[datanamespace] = values;
	} else {
		var postData = values;
	}
	$.ajax({
		type: "POST",
		url: '/' + updateAction,
		data: postData,
		success: function(data, textStatus, jqXHR) {
			/*try {
						response = $.parseJSON(data);
					} catch (e) {
						$('.btn-save').button('complete');
						alert('Oops, incorrect data format received from the server!');
					}*/
			response = data;
			if ('Success' == response['Status']) {
				if ('RecordId' in response) {
					lastUpdatedId = response['RecordId'];
				} else {
					lastUpdatedId = false;
				}
				if ((refreshContainer) && (refreshAction)) {
					if (refreshAutoData[refreshContainer]) {
						refreshContent(refreshContainer, refreshAction, false, false, refreshAutoData[refreshContainer]);
					} else {
						refreshContent(refreshContainer, refreshAction);
					}
				}
				if (closeOnSave) {
					if ('boolean' == typeof(closeOnSave)) {
						modalElement = '#modal' + ucfirst(namespace);
					} else {
						modalElement = '#' + closeOnSave;
					}
					$(modalElement).modal('toggle');
				}
				while (postUpdateAction.length) {
					(postUpdateAction.shift())(response);
				}
				if (redirect) {
					$('.btn-save').button('complete');
					window.location = redirect;
				}
			} else if ('Error' == response['Status']) {
				if (warningContainer) {
          flashPopover(
              warningContainer.element,
              (warningContainer.placement) ? warningContainer.placement : "right",
              (warningContainer.title) ? warningContainer.title : "Oops",
              response['Message'],
              (warningContainer.length) ? warningContainer.length : 3000
            );
				} else {
					alert('Service Error: ' + response['Message']);
				}
			} else {
				alert(data);
			}
			$('.btn-save').button('complete');
			return false;
		},
		error: function(jqXHR, textStatus, errorThrown) {
			$('.btn-save').button('complete');
			alert('Oops: ' + errorThrown);
			return false;
		}
	});
}

function confirmDelete(namespace, removeAction, id, refreshContainer, refreshAction) {
	$('#delete_id').val(id);
	$('#delete_namespace').val(namespace);
	$('#delete_remove_action').val(removeAction);
	$('#delete_refresh_container').val(refreshContainer);
	$('#delete_refresh_action').val(refreshAction);
	$('#modalDelete').modal('toggle');
};

function flashPopover(element, placement, title, content, length) {
  $('#' + element).popover({
    "placement": placement,
    "title": title,
    "content": content,
    "trigger": "manual"
  });
  $('#btnLogin').popover('show');
  if (!length) {
    length = 3000;
  }
  setTimeout(function () {
    $('#' + element).popover('hide');
  }, length);
}

/* 
 *	Function wrapping code.
 *	fn - reference to function.
 *	context - what you want "this" to be.
 *	params - array of parameters to pass to function.
 *	Example usage:
 *	 var sayStuff = function(str) { alert(str); };
 *	 var fun1 = wrapFunction(sayStuff, this, ["Hello, world!"]);
 *	 funqueue.push(fun1);
 */
var wrapFunction = function(fn, context, params) {
	return function() {
		fn.apply(context, params);
	};
};

function resetSaveButton() {
	$('.btn-save').button('complete');
};

function resetTooltips() {
	$(".tooltip").remove();
	$("[rel=tooltip]").tooltip({
		placement:"top", 
		delay: 250
	});
	setTimeout(function() {
		$(".icon-fade").fadeTo(300, 0.35);
	}, 200);
}

function populateSelect(target, instruction, data, selected) {
	var opts = '<option value="">-- ' + instruction + ' --</option>';
	for (var i in data) {
		var chosen = (selected == i) ? ' selected' : '';
		opts += '<option value="' + i + '"' + chosen + '>' + data[i] + '</option>';
	}
	$('#' + target).html(opts);
}

function ucfirst(string)
{
	return string.charAt(0).toUpperCase() + string.slice(1);
};

function updateClock()
{
	var currentTime = new Date ( );
	var currentHours = currentTime.getHours ( );
	var currentMinutes = currentTime.getMinutes ( );
	var currentSeconds = currentTime.getSeconds ( );
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;
	$("#clock").html(currentTimeString);
}

function calculateTimeLeft(value)
{
	var dateTime = value.split(' '),
	date     = dateTime[0],
	time     = dateTime[1],

	dateParts = date.split('-').map(function(part){
		return parseInt(part, 10);
	}),
	timeParts = time.split(':').map(function(part){
		return parseInt(part, 10);
	}),
	expieDateTime = new Date(dateParts[0], dateParts[1]-1, dateParts[2], timeParts[0], timeParts[1], timeParts[2] ),
	currentDateTime = new Date(),
	difference = expieDateTime.getTime() - currentDateTime.getTime(),
	daysDifference, hoursDifference, minutesDifference, secondsDifference;

	daysDifference = Math.floor(difference/1000/60/60/24);
	difference -= daysDifference*1000*60*60*24;
	hoursDifference = Math.floor(difference/1000/60/60);
	difference -= hoursDifference*1000*60*60;
	minutesDifference = Math.floor(difference/1000/60);
	difference -= minutesDifference*1000*60;
	secondsDifference = Math.floor(difference/1000);

	return daysDifference > 0
	  ? daysDifference + 'd, ' + hoursDifference + 'h, ' + minutesDifference + 'm'
	  : hoursDifference + 'h, ' + minutesDifference + 'm';
}