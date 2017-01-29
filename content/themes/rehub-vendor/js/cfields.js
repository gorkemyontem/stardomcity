jQuery(document).ready(function($){

  var companyFields = ['billing_company_field', 'billing_tax_office_city_field', 'billing_tax_office_field', 'billing_tax_no_field'];
  var individualFields = ['billing_tc_no_field'];

  function hideFields(fields){
    for (let i = 0; i < fields.length ; i++) {
      let field = fields[i];

      $('#' + field ).hide(function(){
          $(this).removeClass("validate-required");
          $("label[for='" + field.replace("_field","") +"']").find('abbr').remove();
      });
    }
  }
  function showFields(fields){
    for (let j = 0; j < fields.length; j++) {
      let field = fields[j];
      $('#' + field).show(function(){
          $(this).addClass("validate-required");
          $("label[for='" + field.replace("_field","") +"']").append('<abbr class="required" title="required"> *</abbr>');

      });
    }
  }

  function setDefaultFields(){
    if($("#billing_status option:selected").val() == "2"){
       hideFields(individualFields);
       showFields(companyFields);
    } else if($("#billing_status option:selected").val() == "1"){
       hideFields(companyFields);
       showFields(individualFields);
    }
  }

  setDefaultFields();

  $("#billing_status").change(function(){
      setDefaultFields();
  });

});
