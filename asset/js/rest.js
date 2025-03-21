"use strict";

/*replace all string*/
String.prototype.replaceAll = function (search, replacement) {
	var target = this;
	return target.split(search).join(replacement);
};

function updateValidation(target) {
	var validation_group = target.find('option:selected').attr('title');

	var options = $('.validation_rules option.' + validation_group).clone().filter(function (index, elem) {
		return elem;
	});
	target.parents('tr').find('.box-validation').each(function (index, el) {
		if (!$(this).hasClass(validation_group)) {
			$(this).remove();
		}
	});
	target.parents('tr').find('.validation').html(options).trigger('chosen:updated');
}

function addValidation(target, id, name, type, input_able, input_value, input_placeholder) {
	if (!target.parents('td').find('.box-validation.' + type).length) {

		if (typeof input_value == 'undefined') {
			var input_value = '';
		}

		if (typeof input_placeholder == 'undefined') {
			var input_placeholder = '';
		}

		var group_validation = target.find('option:selected').attr('class');

		if (input_able == 'yes') {
			var input = '<input value="' + input_value + '" placeholder="' + input_placeholder + '" class="input_validation" name="rest[' + id + '][' + name + '][validation][rules][' + type + ']" >';
			var class_validation_name = 'validation-name';
		} else {
			var input = '<input type="hidden" class="input_validation" name="rest[' + id + '][' + name + '][validation][rules][' + type + ']" >';
			var class_validation_name = 'validation-name-max';
		}


		target.parents('td').append('<div class="box-validation ' + group_validation + ' ' + type + '"> <label><div class="' + class_validation_name + '">' + type.replaceAll('_', ' ') + '</div> ' + input + '</label> <a class="delete fa fa-trash"></a> </div>');
		target.parents('td').find('.box-validation.' + type + ' input').focus();
	}
	target.val('').trigger('chosen:updated');
}

$(document).ready(function () {

	$(document).on('change', 'table tr .validation', function () {
		var type = $(this).val();
		var input_able = $(this).find('option:selected').attr('title');
		var input_placeholder = $(this).find('option:selected').attr('data-placeholder');
		var id = $(this).parents('tr').find('#rest-id').val();
		var name = $(this).parents('tr').find('#rest-name').val();

		addValidation($(this), id, name, type, input_able, '', input_placeholder);
	}); /*end validation change*/

	$(document).on('click', 'table tr a.delete', function () {
		$(this).parents('.box-validation').remove();
	});/*end delte click*/

	/*update validation rules on input type change*/
	$(document).on('change', 'table tr .input_type', function () {
		updateValidation($(this));

		var relation = $(this).find('option:selected').attr('relation');
		var table_relation = $(this).parents('td').find('.relation_table');
		if (relation == 1) {
			table_relation.val('').trigger('chosen:updated').parents('.form-group').show();

		} else {
			$(this).parents('td').find('.relation_field').parents('.form-group').hide();
			$(this).parents('td').find('.relation_field').val('');
		}

	});


	$(document).on('change', 'table tr .relation_table', function () {
		var relation_value = $(this).parents('td').find('.relation_value');
		var relation_label = $(this).parents('td').find('.relation_label');
		var table_name = $(this).val();

		relation_value.parents('.form-group').show();
		relation_label.parents('.form-group').show();

		$.get(ADMIN_BASE_URL + '/rest/get_list_field_id/' + table_name, function (data) {
			var res = (data);

			if (res.success) {
				relation_value.html(res.html);
				relation_value.trigger('chosen:updated');

				relation_label.html(res.html);
				relation_label.trigger('chosen:updated');
			} else {
				$('.message').printMessage({ message: res.message, type: 'warning' });
				$('.message').fadeIn();
			}
		}).fail(function () {
			$('.message').printMessage({ message: 'Error getting data', type: 'warning' });
		})
			.always(function () {
				$('.loading').hide();
			});


		$.get(ADMIN_BASE_URL + '/rest/get_list_field_label/' + table_name, function (data) {
			var res = (data);

			if (res.success) {
				relation_label.html(res.html);
				relation_label.trigger('chosen:updated');
			} else {
				$('.message').printMessage({ message: res.message, type: 'warning' });
				$('.message').fadeIn();
			}
		}).fail(function () {
			$('.message').printMessage({ message: 'Error getting data', type: 'warning' });
		})
			.always(function () {
				$('.loading').hide();
			});

	});

	$('#btn_cancel').on('click', function () {
		swal({
			title: "Are you sure?",
			text: "the data that you have created will be in the exhaust!",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes!",
			cancelButtonText: "No!",
			closeOnConfirm: true,
			closeOnCancel: true
		},
			function (isConfirm) {
				if (isConfirm) {
					window.location.href = HTTP_REFERER;
				}
			});

		return false;
	}); /*end btn cancel*/
});
