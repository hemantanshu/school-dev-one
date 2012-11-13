$(document)
    .ready(
    function () {
        var employeeId = $('#employeeId_main').val();
        var salary = $('#salary_main').val();
        $('#'+employeeId).html(salary);
    });