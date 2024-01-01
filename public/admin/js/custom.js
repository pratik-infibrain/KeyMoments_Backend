function alertconfirm(str){
	if (confirm(str)){
		return true;
	}
	return false;
}
function onlyAlphabets(e, t) {
	try {
	
		if (window.event) {
			var charCode = window.event.keyCode;
		}else if (e) {
			var charCode = e.which;
		}else { 
			return true; 
		}
		if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode==32 || charCode==08 || charCode==13 || charCode==63)
			return true;
		else
			return false;
	}catch (err) {
		//alert(err.Description);
	}
}
$(document).on('keydown', '.only_number', function(e) {
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [32,46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			// Allow: Ctrl+A, Command+A
		(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
			// Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
				// let it happen, don't do anything
				return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
 }); 
$(document).on('keydown', '.only_character', function(e) {	
  if (e.shiftKey || e.ctrlKey || e.altKey) {
	  e.preventDefault();
  } else {
	  var key = e.keyCode;
	  if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
		  e.preventDefault();
	  }
  }
}); 
$(function() {
    $("body").delegate(".datepicker_field", "focusin", function(){
        jQuery('.datepicker_field').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth: true,
			changeYear: true,
			//yearRange: "-100:+0", // last hundred years
			//inline: true,
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
		});
    });
	$("body").delegate(".datepicker_field_max", "focusin", function(){
        jQuery('.datepicker_field_max').datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0", // last hundred years
			//inline: true,
			maxDate: new Date(),
			prevText : '<i class="fa fa-chevron-left"></i>',
			nextText : '<i class="fa fa-chevron-right"></i>',
		});
    });
});
/*
var enforceModalFocusFn = $.fn.modal.Constructor.prototype.enforceFocus;
$.fn.modal.Constructor.prototype.enforceFocus = function() {};
$confModal.on('hidden', function() {
    $.fn.modal.Constructor.prototype.enforceFocus = enforceModalFocusFn;
});
$confModal.modal({ backdrop : false });
*/
jQuery('.datepicker_field').datepicker({
	dateFormat: 'dd/mm/yy',
	changeMonth: true,
	changeYear: true,
	//yearRange: "-100:+0", // last hundred years
	//inline: true,
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
});
jQuery('.datepicker_field_max').datepicker({
	dateFormat: 'dd/mm/yy',
	changeMonth: true,
	changeYear: true,
	yearRange: "-100:+0", // last hundred years
	//inline: true,
	maxDate: new Date(),
	prevText : '<i class="fa fa-chevron-left"></i>',
	nextText : '<i class="fa fa-chevron-right"></i>',
});
