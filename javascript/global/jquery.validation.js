/**
 ** Copyright © Larry Wakeman - 2012
 **
 ** All rights reserved. No part of this publication may be reproduced, stored in a retrieval system,
 ** or transmitted in any form or by any means without the prior written permission of the copyright
 ** owner and must contain the avove copyright notice.
 **
 ** This script is licensed nder the GPL license..
 */
/*
 This script can be used to validate a form or a control.

 This script requires jQuery.

 To load this script, use the following:

 <script language="JavaScript" src="validation.js"></script>
 <script language="JavaScript">
 var valid = new validate();
 </script>

 The form definitions should be as follows:

 <form method="POST" action="targetPage" name="FormName" id="FormName" onsubmit="return valid.validateForm(this);">

 Items to be validated should be described as follows:

 <label for="FacilityState">Facility State</label>
 <input type="text" name="FacilityState" id="FacilityState" class="a list of validations and other classes" size="3" onchange="javascript: valid.validateInput(this);">
 <div id="FacilityStateError" class="validationError" style="display:none;"></div>

 You might be able to use onblur events but if you display a popup, that gets to be problematic and by default, this script creates a popup.

 You can also put a div as follows on your page to display any validation errors from the form:

 <div id="ValidationError" class="validationError" style="display: none;"></div>

 Valid validations are:

 required
 email
 date - in dd-mon-yyyy,  dd-mm-yyyy, yyyy-mon-dd or yyyy-mm-dd formats
 time
 currency
 numeric
 alphanumeric
 alpha
 phone
 state
 zipcode
 urlentry

 Valid controls are:

 text
 testarea
 select - Required only, value not '' or 0
 radio buttons - one must be checked

 */

function validate() {

    /**
     * Method to setup to validate an input
     */
    this.validateInput = function(ctrl) {
        this.lastChecked = '';
        var id = (jQuery(ctrl).attr('id'));
        jQuery('validationError').hide();
        jQuery('validationError').html('');
        return this.checkInput(ctrl);
    };

    /**
     * Method to validate an input
     */
    this.checkInput = function (ctrl) {
        var valid =true;
        var id = (jQuery(ctrl).attr('id'));
        if (jQuery(ctrl).attr('name') != this.lastChecked) {
            jQuery('#' + id + 'Error').hide();
            jQuery('#' + id + 'Error').html('');
        }
        var label = jQuery('label[for="' + id + '"]').text();
        if (jQuery(ctrl).hasClass('required')) {
            if (!this.isRequired(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'This field is required<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is required<br>');
                jQuery('#validationError').show();
                return false;
            }
        }
        if (jQuery(ctrl).hasClass('email')) {
            if (!this.isEmail(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid email address.<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' has an invalid email address.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('date')) {
            if (!this.isDate(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid date.<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is an invalid date.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('time')) {
            if (!this.isTime(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid time.<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is an invalid time.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('currency')) {
            if (!this.isCurrency(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid currency.<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is an invalid currency.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('numeric')) {
            if (!this.isNumeric(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Non numeric value<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is a non numeric value.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('alphanumeric')) {
            if (!this.isAlphaNumeric(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Non alpha-numeric value<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is a non alpha-numeric value.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('alpha')) {
            if (!this.isAlpha(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Non alphabetic value<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is a non alphabetic value.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('phone')) {
            if (!this.isPhone(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid phone number<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is as invalid phone number.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('state')) {
            if (!this.isState(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid State<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is as invalid State.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('zipcode')) {
            if (!this.isZipcode(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid Zip Code<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is as invalid Zip Code.<br>');
                valid = false;
            }
        }
        if (jQuery(ctrl).hasClass('urlentry')) {
            if (!this.isInternetURL(ctrl)) {
                jQuery('#' + id + 'Error').html(jQuery('#' + id + 'Error').html() + 'Invalid URL<br>');
                jQuery('#' + id + 'Error').show();
                jQuery('#validationError').html(jQuery('#validationError').html() + label + ' is as invalid URL.<br>');
                valid = false;
            }
        }
        if (!valid) jQuery('#' + id + 'Error').show();
        return valid;
    };

    /**
     * Method to find all the descendants of a form that can be validated
     */
    this.checkChild  = function (ctrl) {
        var children = jQuery(ctrl).children();
        var valid =true;
        for (var i = 0; i < children.size(); i++) {
            if (jQuery(children[i]).is('input') ||jQuery(children[i]).is('textarea') ||jQuery(children[i]).is('select') || jQuery(children[i]).is('input:radio')) {
                valid = valid & this.checkInput(jQuery(children[i]));
            } else {
                valid = valid & this.checkChild(children[i]);
            }
        }
        return valid;
    };

    /**
     * Method to validate a form
     */
    this.validateForm = function(ctrl) {
        this.lastChecked = '';
        jQuery('.validationError').html('');
        if (this.checkChild(ctrl)) return true;
        jQuery('#validationError').show();
        return false;
    };

    /**
     * Method to check that required field is not empty
     */
    this.lastChecked = '';

    this.isRequired = function (ctrl) {
        if (jQuery(ctrl).is('input:radio')) {
            if (this.lastChecked != jQuery(ctrl).attr('name')) {
                this.lastChecked = jQuery(ctrl).attr('name');
                if (!jQuery("input[@name='" + jQuery(ctrl).attr('name') + "']:checked").val()) {
                    return false;
                }
            }
        } else {
            if (jQuery(ctrl).val().length == 0) return false;
            if (jQuery(ctrl).val() == "") return false;
            if (jQuery(ctrl).is('select') && jQuery(ctrl).val() == 0) return false;
        }
        return true;
    };

    /**
     * Method to check that email field is formatted properly
     */
    this.isEmail = function (ctrl) {
        if (this.isRequired(ctrl)) {
            if (jQuery(ctrl).val().lastIndexOf(".") > 2) {
                if (jQuery(ctrl).val().lastIndexOf("@") > 0) {
                    if (jQuery(ctrl).val().lastIndexOf(".") > jQuery(ctrl).val().indexOf("@")) {
                        return true;
                    }
                }
            }
        }
        return false;
    };

    /**
     * Method to check that field is numeric
     */
    this.isNumeric = function (ctrl) {
        if (this.isRequired(ctrl)) {
            if (isNaN(jQuery(ctrl).val())) {
                return false;
            }
        }
        return true;
    };

    /**
     * Method to check that field is a decimal value
     */
    this.isDecimal = function (number) {
        if (this.isRequired(value)) {
            var stripped = value.replace(/[\.]/g, '');
            if (isNaN(number)) {
                return false;
            }
        }
        return true;
    };

    /**
     * Method to check that field is an unsigned number
     */
    this.isUnsignedNumber = function (number) {
        if (this.isRequired(value)) {
            if (isNaN(number)) {
                return false;
            }
            if ((number.indexOf("-") != 0) || (number.indexOf("+") != 0)) {
                return false;
            }
        }
        return true;
    };

    /**
     * Method to check that field is Alpha numeric
     */
    this.isAlphaNumeric = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var reAlphanumeric = /^[a-zA-Z0-9]+$/
            return reAlphanumeric.test(jQuery(ctrl).val());
        }
        return true;
    };

    /**
     * Method to check that required field is Alphabetic
     */
    this.isAlpha = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var reAlphabetic = /^[a-zA-Z]+$/
            return reAlphabetic.test(jQuery(ctrl).val());
        }
        return true;
    };

    /**
     * Method to check that field is a date
     */
    this.isDate = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var value = jQuery(ctrl).val();
            var leapYear = false;
            var days;
            var dateParts = new Array();
            dateParts = value.split('-');

            for(var i = 0; i < 3; ++i){
                dateParts[i] = parseInt(dateParts[i]);
            }

            if ((dateParts[0] % 4) == 0) {
                if ((dateParts[0] % 100) != 0) {
                    leapYear = true;
                } else if ((dateParts[0] % 400) == 0) {
                    leapYear = true;
                } else {
                    leapYear = true;
                }
            }

            switch(dateParts[1]){
                case 1: //JAN
                case 3: //MAR
                case 5: //MAY
                case 7: //JUL
                case 8: //AUG
                case 10: //OCT
                case 12: //DEC
                    if (dateParts[2] < 32) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 4: //APR
                case 6: //JUN
                case 9: //SEP
                case 11: //NNOV
                    if (dateParts[2] < 31) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                case 2: //FEB
                    if (leapYear) {
                        days = 29;
                    } else {
                        days = 28;
                    }
                    if (dateParts[2] < days) {
                        return true;
                    } else {
                        return false;
                    }
                    break;
                default:
                    return false;
            }
            return false;
        }
        return true;
    };

    /**
     * Method to check that field is a valid state
     */
    this.isState = function (ctrl) {
        var states =",AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,GA,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,NC,ND,OH,OK,OR,PA,RI,SC,SD,TN,TX,UT,VT,VA,WA,WV,WI,WY,";
        if (this.isRequired(ctrl)) {
            if (jQuery(ctrl).val().length != 2) {
                return false;
            }
            if (states.indexOf(jQuery(ctrl).val()+",") == -1) {
                return false;
            }
        }
        return true;
    };

    /**
     * Method to check that field is a URL
     */
    this.isInternetURL = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var temp = jQuery(ctrl).val().toLowerCase();
            if (temp.substring(0,4) != 'http') return false;
            temp = temp.substring(4);
            if (temp.substring(0, 1) == 's') temp = temp.substring(1);
            if (temp.substring(0, 3) != '://') return false;
            temp = temp.substring(3);
            if (temp.indexOf('/') ==  -1) {
                if (temp.indexOf('.') ==  -1) return false;
            } else {
                if (temp.indexOf('.') >  temp.inindexOfdex('/')) return false;
            }
        }
        return true;
    };

    /**
     * Method to check that field is a tme
     */
    this.isTime = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var timePat = /^(\d{1,2}):(\d{2})?(\s?(AM|am|PM|pm))?$/;
            var matchArray = jQuery(ctrl).val().match(timePat);
            if (matchArray == null) {
                return false;
            }
            hour = matchArray[1];
            minute = matchArray[2];
            ampm = matchArray[4];

            if (ampm=="") {
                return false;
            }
            if (hour < 0  || hour > 12) {
                return false;
            }
            if (minute<0 || minute > 59) {
                return false;
            }
            return true;
        }
        return true;
    };

    /**
     * Method to check that field is currency
     */
    this.isCurrency = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var value = jQuery(ctrl).val();
            if (value.substring(0,1) == '$') value = value.substring(1);
            var parts = value.split('.');
            if (!isNaN(parts[0]) && !isNaN(parts[1])) {
                return true;
            } else {
                return false;
            }
        }
    };

    /**
     * Method to check that field is a sip code
     */
    this.isZipcode  = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var stripped = jQuery(ctrl).val().replace(/[\-]/g, '');
            if (isNaN(stripped)) {
                return false;
            }
            if ((stripped.length == 5)) {
                return true;
            }
            if ((stripped.length == 9)) {
                return true;
            }
            return false;
        }
        return true;
    };

    /**
     * Method to check that field is a phone number
     */
    this.isPhone  = function (ctrl) {
        if (this.isRequired(ctrl)) {
            var stripped = jQuery(ctrl).val().replace(/[\-\ ]/g, '');
            if (isNaN(stripped)) {
                stripped = stripped.replace(/[ext]/g, '');
                if (isNaN(stripped)) {
                    return false;
                }
            } else {
                if (stripped.length != 10) {
                    return false;
                }
            }
        }
        return true;
    };
}
