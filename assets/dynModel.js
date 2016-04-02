/**
 * @author Dmitrij "m00nk" Sheremetjev <m00nk1975@gmail.com>
 * Date: 01.04.16, Time: 1:31
 */

var dynModel = {
	ajaxUrl: '',
	settings: {
		model: '',
		fieldId : '',
		messages: {
			titleError: '',

			btnClose: '',

			wrongIndex : '',
			cantMoveUp: '',
			cantMoveDown : ''
		}
	},

	init: function(initSettings)
	{
		dynModel.ajaxUrl = initSettings.ajaxUrl;
		delete initSettings.ajaxUrl;
		dynModel.settings = initSettings;

		$('body')
			.on('click', '.js_dynmodel_link_add_field', dynModel._addFieldHandler)
			.on('click', '.js_dynmodel_link_edit_field', dynModel._editFieldHandler)
			.on('click', '.js_dynmodel_link_move_up_field', dynModel._moveFieldUpHandler)
			.on('click', '.js_dynmodel_link_move_down_field', dynModel._moveFieldDownHandler)
			.on('click', '.js_dynmodel_link_delete_field', dynModel._deleteFieldHandler)
		;
	},

	_addFieldHandler: function(e)
	{
		e.preventDefault();

		var type = $(this).attr('data-id');

		$.ajax({
			url: dynModel.ajaxUrl,
			type: 'post',
			data: {
				cmd: 'getNewFieldEditor',
				params: dynModel.settings,
				type: type
			},
			dataType: 'json',
			success: function(respond)
			{
				if(respond.status == 'ok')
					dynModel.__showDialog(respond);
				else
					alert(respond.message);
			}
		});
	},

	_editFieldHandler: function(e)
	{
		e.preventDefault();

		var id = $($(this).parents('tr')[0]).attr('data-id');

		$.ajax({
			url: dynModel.ajaxUrl,
			type: 'post',
			data: {
				cmd: 'getFieldEditor',
				params: dynModel.settings,
				id: id
			},
			dataType: 'json',
			success: function(respond)
			{
				if(respond.status == 'ok')
					dynModel.__showDialog(respond);
				else
					alert(respond.message);
			}
		});
	},

	_deleteFieldHandler: function(e)
	{
		e.preventDefault();

		var row = $($(this).parents('tr')[0]);
		var id = row.attr('data-id');

		var index = dynModel.__getIndexById(id);
		if(index == -1)
			dynModel._showMessage(dynModel.settings.messages.wrongIndex, dynModel.settings.messages.titleError);
		else
		{
			row.animate({opacity: 0}, 300, function(){
				row.remove();
				dynModel.settings.model.splice(index,1);
				dynModel.__updateJson();
			});
		}
	},

	_moveFieldUpHandler: function(e)
	{
		e.preventDefault();

		var row = $($(this).parents('tr')[0]);
		var id = row.attr('data-id');

		var index = dynModel.__getIndexById(id);
		if(index == -1)
			dynModel._showMessage(dynModel.settings.messages.wrongIndex, dynModel.settings.messages.titleError);
		else
		{
			if(index == 0)
				dynModel._showMessage(dynModel.settings.messages.cantMoveUp, dynModel.settings.messages.titleError);
			else
			{
				var z = dynModel.settings.model[index];
				dynModel.settings.model[index] = dynModel.settings.model[index-1];
				dynModel.settings.model[index-1] = z;
				dynModel.__updateJson();

				var row2 = row.prev();
				row.after(row2);
			}
		}
	},

	_moveFieldDownHandler : function(e)
	{
		e.preventDefault();

		var row = $($(this).parents('tr')[0]);
		var id = row.attr('data-id');

		var index = dynModel.__getIndexById(id);
		if(index == -1)
			dynModel._showMessage(dynModel.settings.messages.wrongIndex, dynModel.settings.messages.titleError);
		else
		{
			if(index == dynModel.settings.model.length-1)
				dynModel._showMessage(dynModel.settings.messages.cantMoveDown, dynModel.settings.messages.titleError);
			else
			{
				var z = dynModel.settings.model[index];
				dynModel.settings.model[index] = dynModel.settings.model[index+1];
				dynModel.settings.model[index+1] = z;
				dynModel.__updateJson();

				var row2 = row.next();
				row2.after(row);
			}
		}
	},

	__showDialog: function(respond)
	{
		var dlg = dynModel.__renderUiDialog(respond.html, respond.title /*, {width:600} */);
		dlg.on('click', '.js_dynmodel_link_submit_dlg', function(e)
		{
			var formData = dynModel.__formToArray(dlg.find('form')[0]);
			dlg.dialog('close');

			$.ajax({
				url: dynModel.ajaxUrl,
				type: 'post',
				data: {
					cmd: 'storeField',
					params: dynModel.settings,
					data: formData
				},
				dataType: 'json',
				success: function(respond)
				{
					if(respond.status == 'reload_form')
						dynModel.__showDialog(respond);
					else
					{
						if(respond.status == 'ok')
						{
							dynModel.settings.model = respond.model;
							dynModel.__updateJson();
							$('#dynmodel_fields_table').after($(respond.html).find('#dynmodel_fields_table')).remove();
						}
						else
							alert(respond.message);
					}
				}
			});
		});
	},

	__renderUiDialog: function(content, title, options)
	{
		var d = $('<div id="dyn-model-editor">' + content + '</div>').appendTo($('body'));

		options = $.extend({
			title: title || '',
			resizable: true,
			modal: true,
			closeOnEscape: false,
			close: function(){ $(this).dialog('destroy').remove();}
		}, options || {});

		d.dialog(options);

		d.on('click', '.js_dynmodel_link_close_dlg', function(e)
		{
			e.preventDefault();
			d.dialog('close');
		});

		return d;
	},

	__getIndexById: function(id)
	{
		var index = -1;
		$.each(dynModel.settings.model, function(i, o){
			if(o.id == id)
			{
				index = i;
				return false;
			}
		});
		return index;
	},

	_showMessage: function(content, title, options)
	{
		var d = $('<div id="dyn-model-error">' +
			content +
				'<div class="text-center" style="margin-top: 16px;"><a href="#" class="btn btn-sm btn-default js_dynmodel_link_close_dlg">'+dynModel.settings.messages.btnClose+'</a></div>' +
			'</div>').appendTo($('body'));

		options = $.extend({
			title: title || '',
			resizable: true,
			modal: true,
			closeOnEscape: false,
			close: function(){ $(this).dialog('destroy').remove();}
		}, options || {});

		d.dialog(options);

		d.on('click', '.js_dynmodel_link_close_dlg', function(e){ e.preventDefault(); d.dialog('close');});

		return d;
	},

	/** из контролов формы создает массив, пригодный для пересылки постом (для аякс-запросов) */
	__formToArray: function(formSelector)
	{
		var data = {};

		jQuery.each($(formSelector).serializeArray(), function()
		{
			var p, arr, name = this.name;
			p = name.search(/\[\]/);
			if(p != -1)
			{ // поле с множественным выбором типа SomeClass[SomeField][]
				name = name.substring(0, name.length - 2);

				p = name.search(/\[/);
				if(p == -1)
					data[name] = this.value;
				else
				{
					arr = name.match(/(.*)\[(.*)\]/);
					if(typeof data[arr[1]] == 'undefined') data[arr[1]] = {};

					if(typeof data[arr[1]][arr[2]] == 'undefined')
						data[arr[1]][arr[2]] = this.value;
					else
						data[arr[1]][arr[2]] += ',' + this.value; // собираем массив значений
				}
			}
			else
			{
				p = name.search(/\[/);
				if(p == -1)
					data[name] = this.value;
				else
				{
					arr = name.match(/(.*)\[(.*)\]/);
					if(typeof data[arr[1]] == 'undefined') data[arr[1]] = {};

					data[arr[1]][arr[2]] = this.value;
				}
			}
		});
		return data;
	},

	__updateJson: function()
	{
		$('#'+dynModel.settings.fieldId).val(JSON.stringify(dynModel.settings.model));
	}
};