//the script to generate the tooltip in the forms
var tooltipObj = new DHTMLgoodies_formTooltip();
tooltipObj.setTooltipPosition('right');
tooltipObj.setPageBgColor('#EEEEEE');
tooltipObj.setTooltipCornerSize(15);
tooltipObj.initFormFieldTooltip();


//the script to generate the datepicker 
datePickerController.createDatePicker({ 
    //date picker for the first date
    formelements:{
        "sDate":"%Y-%m-%d"
    }
}); 

datePickerController.createDatePicker({ 
    //date picker for the first date
    formelements:{
        "eDate":"%Y-%m-%d"
    }
}); 
datePickerController.createDatePicker({ 
    //date picker for the first date
    formelements:{
        "sDate_u":"%Y-%m-%d"
    }
}); 

datePickerController.createDatePicker({ 
    //date picker for the first date
    formelements:{
        "eDate_u":"%Y-%m-%d"
    }
});