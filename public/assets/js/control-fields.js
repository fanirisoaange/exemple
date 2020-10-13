
/**
 * @type {[string,string]}
 */
var arrayFieldEmail = [
];

/**
 * @type {[string,string,string]}
 */
var arrayFieldPhone = [
];

/**
 * @type {[string,string,string]}
 */
var arrayFieldInteger = [
    'qty'
];

/**
 * @type {[string,string,string]}
 */
var arrayFieldDecimal = [
    'cpm'
];

/**
 * @type {[string,string]}
 */
var arrayFieldDate = [
];

var arrayFieldSiret = [
];

var excludeFieldRequire = [
];

/**
 * @param field
 * @returns {boolean}
 */
function checkFormatField(field)
{
    if (arrayFieldEmail.indexOf($(field).attr('name')) != -1) {
        if (/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test($(field).val())) {
            return true;
        }

        return false;
    }

    if (arrayFieldPhone.indexOf($(field).attr('name')) != -1) {
        var valueField = $(field).val().replace(/ /g, "");
        var firstCharInvalueField = valueField.substring(0, 1);
        var restCharInvalueField = valueField.substring(1);
        var motifForFirstChar = /[0-9]|\+/;
        var motifForRestChar = /^[0-9]{9,11}$/;
        if (motifForFirstChar.test(firstCharInvalueField) && motifForRestChar.test(restCharInvalueField)) {
            return true;
        }

        return false;
    }

    if(arrayFieldInteger.indexOf($(field).attr('name')) != -1)
    {
        var valueField = $(field).val().replace(/ /g, "");
        return isInt(valueField);
    }

    if (arrayFieldDecimal.indexOf($(field).attr('name')) != -1) {
        var valField = $(field).val().replace(/,/g, ".");
        return isDecimal(valField);
    }

    if(arrayFieldSiret.indexOf($(field).attr('name')) != -1)
    {
        var valueField = $(field).val().replace(/ /g, "");
        return isSiretValid(valueField);
    }

    return true;
}

/**
 * @param siret
 * @returns {*}
 */
function isSiretValid(siret) {
    var isValid;
    if ( (siret.length != 14) || (isNaN(siret)) )
        isValid = false;
    else {
        var somme = 0;
        var tmp;
        for (var cpt = 0; cpt<siret.length; cpt++) {
            if ((cpt % 2) == 0) {
                tmp = siret.charAt(cpt) * 2;
                if (tmp > 9)
                    tmp -= 9;
            }
            else
                tmp = siret.charAt(cpt);
            somme += parseInt(tmp);
        }
        if ((somme % 10) == 0)
            isValid = true;
        else
            isValid = false;
    }
    return isValid;
}

function isInt(value){
    if (value.length > 1) {
        if (value.indexOf('0') == 0)
            return false;
    }
    if((parseFloat(value) == parseInt(value)) && !isNaN(value) && parseInt(value) >= 0){
        return true;
    } else
        return false;
}

function isDecimal(value){
    if (value.length > 1) {
        if (value.indexOf('0') == 0)
            return false;
    }
    if (!isNaN(parseFloat(value)) && isFinite(value) && parseFloat(value) >= 0) {
        return true;
    }

    return false;
}
