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

    $('#addAdminForm').on('submit',function (e) {
        e.preventDefault();
        var name = $('#name').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var password = $('#password').val();
        var password = $('#password').val();
        var confirm_password = $('#confirm_password').val();
        var role = $('#status').val();
        var image = $('#image').val();

        if(name == ''){
            $('#nameError').text('Field Must not be Empty!');
            $('#name').addClass('border-danger');
        }else{
            $('#nameError').text('');
            $('#name').removeClass('border-danger');
        }

        if(email == ''){
            $('#emailError').text('Field Must not be Empty!');
            $('#email').addClass('border-danger');
            flag = false;
        }else{
            $('#emailError').text('');
            $('#email').removeClass('border-danger');
        }

        if(image == ''){
            $('#imageError').text('Field Must not be Empty!');
            $('#image').addClass('border-danger');
        }else{
            $('#imageError').text('');
            $('#image').removeClass('border-danger');
        }

        if(phone == ''){
            $('#phoneError').text('Field Must not be Empty!');
            $('#phone').addClass('border-danger');
        }else{
            $('#phoneError').text('');
            $('#phone').removeClass('border-danger');
        }

        if(role == ''){
            $('#statusError').text('Field Must not be Empty!');
            $('#status').addClass('border-danger');
        }else{
            $('#statusError').text('');
            $('#status').removeClass('border-danger');
        }

        if(password == ''){
            $('#passwordError').text('Field Must not be Empty!');
            $('#password').addClass('border-danger');
        }else{
            $('#passwordError').text('');
            $('#password').removeClass('border-danger');
        }

        if(confirm_password == ''){
            $('#confirm_passwordError').text('Field Must not be Empty!');
            $('#confirm_password').addClass('border-danger');
        }else{
            $('#confirm_passwordError').text('');
            $('#confirm_password').removeClass('border-danger');
        }

        if(password != confirm_password){
            $('#confirm_passwordError').text('Password Doesnt match!');
            $('#confirm_password').addClass('border-danger');
        }else{
            $('#confirm_passwordError').text('');
            $('#confirm_password').removeClass('border-danger');
        }
    });
    $('.select2').select2();
});
function setSwalAlert(mode,title,text) {
    Swal.fire({
        icon: mode,
        title: title,
        text: text,
    })
}

function setNotifyAlert(msg,mode) {
    $.notify(msg, mode);
}

