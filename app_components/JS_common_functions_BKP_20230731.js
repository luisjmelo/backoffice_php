// #######################################################################################
// function to CONFIRM RECORD DELETE
// #######################################################################################
function confirm_delete() {
	return(confirm("Tem a certeza que quer apagar?"));
}

// #######################################################################################
// function to TOGGLE SHOW/HIDE PAGE SECTIONS
// #######################################################################################
function toggle_show_hide(elem_act, elem_dsp) {
  var curr_elem_act = document.getElementsByName(elem_act);
  var curr_elem_dsp = document.getElementsByName(elem_dsp);
  for (var i = 0; i < curr_elem_dsp.length; i++) {
    if (curr_elem_act[i].style.display === "none") {
      curr_elem_act[i].style.display = "block";
      curr_elem_dsp[i].style.display = "none";
    } else {
      curr_elem_act[i].style.display = "none";
      curr_elem_dsp[i].style.display = "block";
    }
  }
}

// #######################################################################################
// function to compare 2 fields (eg. password) and return an error if not the same
// #######################################################################################
function check_if_equal(elmt_one, elmt_two, error_message) {
  if (elmt_one != elmt_two){
    alert(error_message);
    return false;
  } else {
    return true;
  }
}

// #######################################################################################
// function to show or hide a section and all its form fields so theu will not be validated
/* #######################################################################################
function show_hide_section(sect_name, act_name) {
  // Initialize variables
  var section = document.getElementById(sect_name), // this is a div that displays or hides content, including FORM FIELDS
      sect_name_1 = '',
      sect_name_2 = '';

  if (section) {
    section.style.display = act_name; // Hide the div that contains the form elements
    var formElements = section.querySelectorAll('input, select'); // Get all form elements of type input and select

    // Loop through form fields
    for (var i = 0; i < formElements.length; i++) {
      // If hiding the form elements
      if (act_name == 'none'){
        if (formElements[i].field_tag == "INPUT" && field_type.match(/^(text|textarea|password|number)$/)) {
          // Reset the values
          formElements[i].value = formElements[i].defaultValue; // defaultValue text, password and textarea
        } else if (formElements[i].field_tag == "INPUT" && field_type.match(/^(radio|checkbox)$/)) {
          formElements[i].checked = formElements[i].defaultChecked; // defaultChecked checkbox/radio 
        } else if (formElements[i].field_tag == "SELECT") {
          // Loop through the options to find the selected option
          for (let x = 0; x < formElements[i].options.length; x++) {
            const option = formElements[i].options[x];
            // Check if the current option is selected
            if (option.defaultSelected) {
              option.selected = true;
              break;
            }
          }
        }
        // Second, display or hide the form field
        formElements[i].style.display = act_name;
      }
    }

    // If action is to hide and the section is 'CAPT_section' (the mother section)
    if (act_name == 'none' && sect_name == 'CAPT_section'){
      // Also hide the children (Congelagos and Other Buyers) sections
      sect_name_1 = 'CGL_section';
      var section = document.getElementById(sect_name_1);
      section.style.display = act_name;
      sect_name_2 = 'OTB_section';
      var section = document.getElementById(sect_name_2);
      section.style.display = act_name;
    }
  }
}*/

// #######################################################################################
// function to show or hide a section and all its form fields so theu will not be validated
/* #######################################################################################
function show_hide_section(sect_name, act_name) {
  var section = document.getElementById(sect_name); // this is a div that displays or hides content, including FORM FIELDS

  if (section) {
    section.style.display = act_name; // Hide the div that contains the form elements
    var formElements = section.querySelectorAll('input, select'); // Get all form elements of type input and select

    // Loop through form fields
    for (var i = 0; i < formElements.length; i++) {
      // If hiding the form elements
      if (act_name == 'none'){
        // Reset the values
        formElements[i].value = formElements[i].defaultValue; // defaultValue text, password and textarea
        formElements[i].checked = formElements[i].defaultChecked; // defaultChecked checkbox/radio 
        formElements[i].selectedIndex = formElements[i].defaultSelected; // defaultSelected option
      }
      // Second, display or hide the form field
      formElements[i].style.display = act_name;
    }

    // If action is to hide and the section is 'CAPT_section' (the mother section)
    if (act_name == 'none' && sect_name == 'CAPT_section'){
      // Also hide the children (Congelagos and Other Buyers) sections
      sect_name_1 = 'CGL_section';
      var section = document.getElementById(sect_name_1);
      section.style.display = act_name;
      sect_name_2 = 'OTB_section';
      var section = document.getElementById(sect_name_2);
      section.style.display = act_name;
    }
  }
}*/

// #######################################################################################
// function to show or hide a section and all its form fields so theu will not be validated
// #######################################################################################
function show_hide_sections(sect_name, act_name, sect_id) {
  var section_name = sect_name + sect_id;
  show_hide_section(section_name, act_name, sect_id);

  // If section is 'CAPT_section' (the mother section)
  if (sect_name == 'CAPT_section'){
    // If action is to hide, hide both the CGL and The OTHER BUYERS sections
    if (act_name == 'none'){
      section_name = 'CGL_section' + sect_id;
      show_hide_section(section_name, act_name);
      section_name = 'OTB_section' + sect_id;
      show_hide_section(section_name, act_name);
    }
  }
}

function show_hide_section(sect_name, act_name) {
  var section = document.getElementById(sect_name); // Displays or hides content, including FORM FIELDS
  if (section) { // if the DIV section exists
    section.style.display = act_name; // Show/Hide the DIV that contains the form elements
    var inp_ckb_rad_elements = section.querySelectorAll('input'); // Get all form elements of type input
    var slct_elements = section.querySelectorAll('select'); // Get all form elements of type select

    // Loop through TEXT, CHECK BOX, and RADIO BUTTON form fields
    for (var i = 0; i < inp_ckb_rad_elements.length; i++) {
      if (act_name == 'none'){
        // Reset the values
        inp_ckb_rad_elements[i].value = inp_ckb_rad_elements[i].defaultValue; // defaultValue text, password and textarea
        inp_ckb_rad_elements[i].checked = inp_ckb_rad_elements[i].defaultChecked; // defaultChecked checkbox/radio 
      }
      inp_ckb_rad_elements[i].style.display = act_name;
    }

    // Loop through SELECT form fields
    for (var i = 0; i < slct_elements.length; i++) {
      // Loop through the options and get the index of the one that has the DEFAULTSELECTED option
      for (let x = 0; x < slct_elements[i].options.length; x++) {
        const option = slct_elements[i].options[x];
    
        // Check if the current option is default selected
        if (option.defaultSelected) {
          defaultSelectedIndex = x;
          break; // Exit the loop as we found the default selected option
        }
      }
      if (act_name == 'none'){
        // alert ('OBJ: ' + slct_elements[i].name + '; DEFAULT_SELECTED_INDEX: ' + defaultSelectedIndex + '; OPTION VALUE: ' + slct_elements[i].options[defaultSelectedIndex].value);
        // select the option of the select box that was originally selected
        slct_elements[i].options[defaultSelectedIndex].selected = true;
        // If the select box is
        if (slct_elements[i].name == 'drs_cgl_bought'){
          show_hide_sections('CGL_section', act_name, '_new')
        }
      }
      slct_elements[i].style.display = act_name;
    }
  }
}

// #######################################################################################
// function to format numbers with thousand separators as the user enters it
// #######################################################################################
function formatNumber(form_field) {
  const numberInput = document.getElementById(form_field);
  const value = numberInput.value.replace(/[^\d.]/g, ''); // Remove all non-digit and non-decimal-point characters
  const formattedValue = new Intl.NumberFormat().format(value.replace(/\./g, ''));
  numberInput.value = formattedValue;
}