$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//validation update Customer
    $('#updateCustomerForm').on('submit',function (e) {
        e.preventDefault();
        var name = $('#e_name').val();
        var email = $('#e_email').val();
        var phone = $('#e_phone').val();
        var address = $('#e_address').val();

        if(name == ''){
            $('#e_nameError').text('Field Must not be Empty!');
            $('#e_name').addClass('border-danger');
        }else{
            $('#e_nameError').text('');
            $('#e_name').removeClass('border-danger');
        }

        if(email == ''){
            $('#e_emailError').text('Field Must not be Empty!');
            $('#e_email').addClass('border-danger');
            flag = false;
        }else{
            $('#e_emailError').text('');
            $('#e_email').removeClass('border-danger');
        }

        if(address == ''){
            $('#e_addressError').text('Field Must not be Empty!');
            $('#e_address').addClass('border-danger');
        }else{
            $('#e_addressError').text('');
            $('#e_address').removeClass('border-danger');
        }

        if(phone == ''){
            $('#e_phoneError').text('Field Must not be Empty!');
            $('#e_phone').addClass('border-danger');
        }else{
            $('#e_phoneError').text('');
            $('#e_phone').removeClass('border-danger');
        }
    });

    $('#addProductForm').on('submit',function (e) {
        e.preventDefault();
        var category = $('#category_id').val();
        var unit = $('#unit_id').val();
        var supplier = $('#supplier_id').val();
        var name = $('#product').val();

        if(name == ''){
            $('#productError').text('Field Must not be Empty!');
            $('#product').addClass('border-danger');
        }else{
            $('#productError').text('');
            $('#product').removeClass('border-danger');
        }

        if(category == ''){
            $('#categoryError').text('Field Must not be Empty!');
            $('#category_id').addClass('border-danger');
        }else{
            $('#categoryError').text('');
            $('#category_id').removeClass('border-danger');
        }

        if(unit == ''){
            $('#unitError').text('Field Must not be Empty!');
            $('#unit_id').addClass('border-danger');
        }else{
            $('#unitError').text('');
            $('#unit_id').removeClass('border-danger');
        }

        if(supplier == ''){
            $('#supplierError').text('Field Must not be Empty!');
            $('#supplier_id').addClass('border-danger');
        }else{
            $('#supplierError').text('');
            $('#supplier_id').removeClass('border-danger');
        }
    });

});
function setSwalAlert(mode,title,text) {
    Swal.fire({
        icon: mode,
        title: title,
        text: text,
    })
}