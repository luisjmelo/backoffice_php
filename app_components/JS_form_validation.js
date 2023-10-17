var regexPatterns = {
	rx_allowAll: /^.*$/,
	rx_allowAllAndLineBreaks: /^(.*\n)*$/,
	rx_allowAllIncAndLineBreaks: /^(.*|\n|\r|\s)*$/,
	rx_allowAlphaNumeric: /^[a-zA-Z0-9]*$/,
	rx_colorHex: /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/,
	rx_colorRgba: /(#(?:[\da-f]{3}){1,2}|rgb\((?:\d{1,3},\s*){2}\d{1,3}\)|rgba\((?:\d{1,3},\s*){3}\d*\.?\d+\)|hsl\(\d{1,3}(?:,\s*\d{1,3}%){2}\)|hsla\(\d{1,3}(?:,\s*\d{1,3}%){2},\s*\d*\.?\d+\))/gi,
	rx_date: /^(0[1-9]|1\d|2\d|3[01])-(0[1-9]|1[0-2])-\d{4}$/, /* checks for date in the dd-mm-yyyy format */
	rx_email: /^[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*@([a-zA-Z0-9_\-]+\.)+([a-zA-Z]{2,6})$/,
	rx_emailList: /(^([a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*@([a-zA-Z0-9_\-]+\.)+([a-zA-Z]{2,6})){1}(\s?(,|;)\s?[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*@([a-zA-Z0-9_\-]+\.)+([a-zA-Z]{2,6}))*)$/,
	rx_hexColor: /^\#[a-fA-F0-9]{6}$/,
	rx_idList: /^\d{1,8}(\,\d{1,8})*$/,
	rx_ip: /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/,
	rx_nbr: /^\d+$/,
	rx_nbrInteger: /(^[0]$)|(^-?[1-9](\d{1,8})?$)/,
	rx_nbrIntegerNotNeg: /^\d+$/,
	rx_nbrNotNegNotZero: /^[1-9][0-9]*$/,
	rx_nbrSmallInt: /(^[0]$)|(^-?[1-9]{1}(\d{1,3})?$)|(^-?[1-2]{1}\d{4}$)|(^-?[3]{1}[0-1]{1}\d{3}$)|(^-?[3]{1}[2]{1}[0-6]{1}\d{2}$)|(^-?[3]{1}[2]{1}[7]{1}[0-5]{1}\d{1}$)|(^-?[3]{1}[2]{1}[7]{1}[6]{1}[0-7]{1}$)/,
	rx_nbrTinyInt: /(^[0]$)|(^[1-9]{1}(\d{1})?$)|(^[1]{1}(\d{1,2})?$)|(^[2]{1}[0-4]{1}\d{1}$)|(^[2]{1}[5]{1}[0-5]{1}$)/,
	// OLD rx_nbrDecimal: /^-?((([1-9]{1}(\d{1,5})?)(\,\d{1,3})?)|\,\d{1,3})$/,
	// OLD 2 NEW rx_nbrDecimal: /^-?(((\d{1,6}?)(\,\d{1,2})?)|\,\d{1,2})$/,
	rx_nbrDecimal: /^-?\d{0,9}([,.])\d{1,2}/,
	// OLD rx_nbrDecimalNotNeg: /^((\d{1,18}(\,\d{1,3})?)|\,\d{1,3})$/,
	// OLD 2 rx_nbrDecimalNotNeg: /^((\d{1,6}(\,\d{1,2})?)|\,\d{1,2})$/,
	rx_nbrDecimalNotNeg: /^\d{0,9}([,.])\d{1,2}/,
	rx_nbrPercent: /^[1-9]{1}([0-9]{1})?$|100/,
	rx_nbrFloat: /^-?([1-9][0-9]{0,5})(\.\d{1,3})?|[0]\.([0-9])?([0-9])?([1-9])$/,
	rx_nbrOnly: /[^0-9]+/g,
	rx_password: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])[^,~\^]{8,30}$/,
	rx_ptPhone: /^[9]{1}\d{8}$/, /* checks for phone in the followin 9######## format */
	rx_intPhone: /^[1-9]{1}\d{5,6}$/,
	rx_time: /^(((0|1){1}\d{1})|(2{1}[0-3]{1}))\:{1}[0-5]{1}\d{1}$/,
	rx_url: /^((ht|f)tp(s?)\:\/\/){0,1}[a-zA-Z0-9\-\._]+(\.[a-zA-Z0-9\-\._]+)+(\/?)([a-zA-Z0-9\-\.\=\?\,\@\'\/\\\+&%\$#_]*)?$/,
	/*
	 * this regex finds the card which is corresponds to
	 * VISA - ?:4[0-9]{12}(?:[0-9]{3})?
	 * Master card - ^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$
	 * American Express - 3[47][0-9]{13}
	 * Diners Club - 3(?:0[0-5]|[68][0-9])[0-9]{11}
	 * Discover - 6(?:011|5[0-9]{2})[0-9]{12}
	 * JCB - (?:2131|1800|35\d{3})\d{11}
	 */
	rx_creditCardNumberRegex: /^(?:4[0-9]{12}(?:[0-9]{3})?|(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|6(?:011|5[0-9]{2})[0-9]{12}|(?:2131|1800|35\d{3})\d{11})$/
};

/* function checkpage_2(form) {
	// debug message
	alert('Entered checkpage_2:: Nº OF FORM ELEMENTS: ' + document.forms[form].length);
	// Initialize the variables
} */

function checkpage(form) {
	/* debug message
	alert('Entered checkpage:: Nº OF FORM ELEMENTS: ' + document.forms[form].length); */
	// Initialize the variables
	var form_error = 0,
		form_obj = document.forms[form],
		field = '',
		field_name = '',
		field_value = '',
		field_tag = '',
		field_type = '',
		field_required = '',
		field_regEx_name = '',
		field_regEx = '',
		field_error_message = '',
		field_ckb_rdb = '', // variable to contain the radio button or checkbox objects
		field_isAnyChecked = '';
		
	for (var i = 0; i < form_obj.elements.length; i++) {
		// populate the variables with current field information
		field = form_obj.elements[i];
		field_name = field.name;
		field_tag = field.tagName;
		field_type = field.type;
		field_value = field.value;
		field_required = field.getAttribute('fld_req');
		field_regEx_name = field.getAttribute('fld_regEx');
		field_regEx = RegExp(regexPatterns[field_regEx_name]);
		field_error_message = field.getAttribute('fld_error_message');
		field_isAnyChecked = 0;
		// If the field has a name
		if (field_name != ''){
			// Skip hidden fields
			if (field.style.display === 'none') {
				continue;
			}
			// If it is an input field of type "text", "textarea" or "password"
			else if (field_tag == "INPUT" && field_type.match(/^(text|textarea|password|number)$/)) {
				// if required and field is empty
				if (field_required == 'true' && field_value.trim() === ""){
					form_error++;
				}
				// if field is not empty, a regEx is defined and field value DOES NOT match the regEx patern
				else if (field_value.trim() !== "" && field_regEx != '' && !(regexPatterns[field_regEx_name].test(field_value))) {
					form_error++;
				}
				// If there was an error, set the error message to the message specified in the field
				if(form_error != 0){
					field_error_message = field_error_message;
				}
			}
			// If it is an input field of type "radio" or "checkbox" and it is required
			else if (field_tag == "INPUT" && field_type.match(/^(radio|checkbox)$/) && field_required == 'true'){
				field_ckb_rdb = document.getElementsByName(field_name);
				// loop through "checkboxes"/"radio buttons" and look for one that is checked
				for (var x = 0; x < field_ckb_rdb.length; x++) {
					if (field_ckb_rdb[x].checked) {
						field_isAnyChecked ++;
						break;
					}
				}
				// If no checkbox or radio button is checked set the "field_error_message" and the "form_error" flag.
				if(field_isAnyChecked == 0){
					form_error++;
					field_error_message = field_error_message;
				// if one of the checkboxes or radio buttons was checked
				} else {
					// increment "i" by the (current number of elements (radiobutton/checkbox) -1) so 
					// it moves on to the next form element without checking others with the same name
					// i += field_ckb_rdb.length--;
				}
			}
			
			// If it is a select field and it is required
			else if (field_tag == "SELECT" && field_required == 'true' && field_value.trim() == '---'){
				form_error++;
				// Set the error message for the select box
				field_error_message = field_error_message;
			}
		}
		// if there was a validation error, return false and do not submitt the form and set focus to the problem field
		if(form_error > 0){
			alert(field_error_message);
			field.focus();
			return false;
		}
	}
	// No problems were found, return true
	return true;
}