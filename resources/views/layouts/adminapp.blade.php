
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
    
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{$site_title}}</title>
    <link rel="shortcut icon" href="{{ asset('public/admin/img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('public/admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('public/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
   <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/admin/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/admin/css/custom.css') }}">
  <link href="{{ asset('public/admin/css/jquery-ui.css') }}" rel="stylesheet" />
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('public/admin/css/toaster.css') }}" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
	<script type="text/javascript">
    	var base_url = "{{URL::to('/').'/'}}";
    </script> 
    <style type="text/css">
        .btn-group{
            width: 100%;
        }
        .multiselect-container{
            width: 100%;
            margin-top: 0 !important;
            max-height: 150px !important;
        }
        .multiselect-container>li>a>label {
            padding: 2px 16px 2px 15px !important;
        }
        .errors_msg{
            color: red;
        }
        .required{
            color: red;
            padding-left: 2px;
        }
        .left_box{
            float: left;
        }
        .right_box{
            float: right;
        }
        .col-form-label {
            text-align: right;
        }

    /*.pagination > li > a,
    .pagination > li > span {
        width: 100%;
        margin: 0;
        line-height: 30px;
        padding: 0;
        border-radius: 0px!important;
    }
    .pagination > li {
        float: left;
        width: 4%;
        text-align: center;
        border: 1px solid #dee2e6;
    }
    .pagination > .active {
        color: white;
        background-color: #007bff !Important;
        border: solid 1px #007bff !Important;
        cursor: no-drop;
    }
    .pagination > .disabled {
        cursor: no-drop;
    }*/
    .brand-link .brand-image {
    max-height: 36px !important;
    }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
		@include('include.adminsidebar')
		@include('include.adminheader')
		@yield('content')
		@include('include.adminfooter')
	</div>
	@include('include.adminfooterscripts')
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery -->
    <script src="{{ asset('public/admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/1_12_1jquery-ui.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
<script src="{{ asset('public/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('public/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('public/admin/js/adminlte.js') }}"></script>

    <script src="{{ asset('public/admin/js/toaster.js') }}"></script>
    @if (Session::has('error_message')) 
    <script type="text/javascript">
    $.toast({
        heading: 'Error',
        showHideTransition: 'slide', 
        text:"{{ Session::get('error_message') }}", 
        icon: 'error'             
    })
    </script>
    @endif
    @if (Session::has('success_message'))   
    <script type="text/javascript">
       $.toast({
        heading: 'Success',
        showHideTransition: 'slide', 
        text: "{{ Session::get('success_message') }}",
        icon: 'success',
        position: 'mid-center',
        
    })
    </script>
    @endif
    <script type="text/javascript" src="{{ asset('public/admin/ckeditor/ckeditor.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/admin/css/bootstrap-multiselect.css') }}" type="text/css">
    <script type="text/javascript" src="{{ asset('public/admin/js/bootstrap-multiselect.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#example-dropUp').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            maxHeight: 400,
            dropUp: true
        });
    });
    </script>
    
    <script src="{{ asset('public/admin/js/custom.js') }}"></script>
    <!-- OPTIONAL SCRIPTS -->
    <script src="{{ asset('public/admin/js/demo.js') }}"></script>


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.form-date').datepicker({
                format: 'mm/dd/yyyy',
                startDate: '-3d'
            });
           
        })
    </script>
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true,
      "autoWidth": true,
      'columnDefs': [{ orderable: false, targets: -1 }]
    });
   
  });
</script>
<?php
   if($page_condition == 'role_page'):
    ?>
    <script>
     function rolevalidation()
     {
        $('.error').text('');
        var name = $('#name').val();
        if(name.trim()=='')
        {
            $('#errorname').text('Name is required.');
            $('#name').focus();
            return false;
        }
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'enum_page'):
    ?>
    <script>
    function enumvalidation()
    {
        $('.error').text('');
        var enumname = $('#enumname').val();
        var error = 'No';
        if(enumname.trim()=='')
        {
            $('#errorenumname').text('Enum name is required.');
            $('#enumname').focus();
             var error = 'Yes';
        }
        var enumvalue = $('#enumvalue').val();
        if(enumvalue.trim()=='')
        {
            $('#errorenumvalue').text('Enum value is required.');
            $('#enumvalue').focus();
             var error = 'Yes';
        }
        if(error == 'Yes')
        {
        	return false;
        }
     }
     function getparentvalue(value)
     {
        $('#parentvalue').val($('#parentname').find(':selected').attr('data-values'));
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'Package_page'):
    ?>
    <script>
    function Packagevalidation()
    {
        $('.error').text('');
        var package_name = $('#package_name').val();
        var error = 'No';
        if(package_name.trim()=='')
        {
            $('#errorPackagename').text('Package name is required.');
            $('#package_name').focus();
            var error = 'Yes';
        }
        var package_price = $('#package_price').val();
        if(package_price.trim()=='')
        {
            $('#errorPackageprice').text('Package price is required.');
            $('#package_price').focus();
          	 var error = 'Yes';
        }
		var number_of_notes = $('#number_of_notes').val();
        if(number_of_notes.trim()=='')
        {
            $('#errorPackagenotes').text('Number of notes is required.');
            $('#number_of_notes').focus();
            var error = 'Yes';
        }
		var number_of_photos = $('#number_of_photos').val();
        if(number_of_photos.trim()=='')
        {
            $('#errorPackagephotos').text('Number of photos is required.');
            $('#number_of_photos').focus();
            var error = 'Yes';
        }
		var number_of_videos = $('#number_of_videos').val();
        if(number_of_videos.trim()=='')
        {
            $('#errorPackagevideos').text('Number of videos is required.');
            $('#number_of_videos').focus();
            var error = 'Yes';
        }
		var data_limit = $('#data_limit').val();
        if(data_limit.trim()=='')
        {
            $('#errorPackagelimit').text('Data limit is required.');
            $('#data_limit').focus();
            var error = 'Yes';
        }
        if(error == 'Yes'){
        	return false;
        }
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'notification_page'):
    ?>
    <script>
    function notificationvalidation()
    {
        $('.error').text('');
        var notification_title = $('#notification_title').val();
        var error = 'No';
        if(notification_title.trim()=='')
        {
            $('#errornotificationtitle').text('Notification title is required.');
            $('#notification_title').focus();
            var error = 'Yes';
        }
        var notification_content = $('#notification_content').val();
        if(notification_content.trim()=='')
        {
            $('#errornotificationcontent').text('Notification content is required.');
            $('#notification_content').focus();
            var error = 'Yes';
        }
		var notification_img = $('#notification_img').val();
        var hiddenimage = $('#hiddenimage').val();
        var myaction = $('#myaction').val();
        
		if(error == 'Yes')
		{
			return false;
		}
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'promotion_page'):
    ?>
    <script>
    function promotionvalidation()
    {
        $('.error').text('');
        var promotion_code = $('#promotion_code').val();
        var error = 'No';
        if(promotion_code.trim()=='')
        {
            $('#errorpromotioncode').text('Promotion code is required.');
            $('#promotion_code').focus();
            var error = 'Yes';
        }
		var valid_form_date = $('#valid_form_date').val();
        if(valid_form_date.trim()=='')
        {
            $('#errorpromotionfromdate').text('Valid from date is required.');
            var error = 'Yes';
        }
		var valid_to_date = $('#valid_to_date').val();
        if(valid_to_date.trim()=='')
        {
            $('#errorpromotiontodate').text('Valid to date is required.');
            var error = 'Yes';
        }
        
        var from = valid_form_date.split("/");
        var f = new Date(from[2], from[1] - 1, from[0]);
        var to = valid_to_date.split("/");
        var t = new Date(to[2], to[1] - 1, to[0]);
        
        if(valid_to_date!= '' && valid_form_date!= '' && f > t)
        {
            $('#errorpromotiontodate').text("Please ensure that the valid to date is greater than or equal to the valid from date.");
            var error = 'Yes';
        }
        if($('input[name="type"]:checked').length == 0)
        {
            $('#errorpromotiontype').text('Type is required.');
            var error = 'Yes';
        }
		var value_percentage = $('#value_percentage').val();
        if(value_percentage.trim()=='')
        {
            $('#errorpromotionpercentage').text('Value is required.');
            $('#value_percentage').focus();
            var error = 'Yes';
        }
        else{
            var promotionValue = $("input[name='type']:checked").val();
            if(promotionValue.trim() == 'Percentage')
            {
                if(value_percentage < 1 ||  value_percentage > 100){
                    $('#errorpromotionpercentage').text('Value will be between 1 to 100!');
                    var error = 'Yes';
                }
            }
            else if(promotionValue.trim() == 'Amount')
            {
                if(value_percentage < 1){
                    $('#errorpromotionpercentage').text('Value will be greater than 0!');
                    var error = 'Yes';
                }
            }
            else if(promotionValue.trim() == 'Free Trial Days'){
                if(value_percentage <= 1){
                    $('#errorpromotionpercentage').text('Value will be greater than 1!');
                    var error = 'Yes';
                }
                else{
                    filter = /^[0-9-+]+$/;
                    if (!filter.test(value_percentage)) {
                         $('#errorpromotionpercentage').text('Value mast be integer!');
                        var error = 'Yes';
                    }
                    
                }
            }
        }
        /*var promotion_content = $('#promotion_content').val();
        if(promotion_content.trim()=='')
        {
            $('#errorpromotioncontent').text('Promotion content is required.');
            $('#promotion_content').focus();
            var error = 'Yes';
        }*/
        if(error == 'Yes')
        {
        	return false;
        }
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'mobileapp_page'):
    ?>
    <script>
    function mobileappvalidation()
    {
        $('.error').text('');
        var email = $('#email').val();
        var error = 'No';
        if(email.trim()=='')
        {
            $('#errormobileappemail').text('Email is required.');
            $('#email').focus();
            var error = 'Yes';
        }
        else
        {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(!regex.test(email))
            {
                $('#errormobileappemail').text('Email is invalid.');
                $('#email').focus();
                 var error = 'Yes';
            }
        }
        var act = $("#act").val();
        if (act == 'create') {
            var password = $('#password').val();
            if(password.trim()=='')
            {
                $('#errormobileapppass').text('Password is required.');
                $('#password').focus();
                var error = 'Yes';
            }
            var cpassword = $('#cpassword').val();
            if(cpassword.trim()=='')
            {
                $('#errormobileappcpass').text('Confirm Password is required.');
                $('#cpassword').focus();
                var error = 'Yes';
            }
            if(cpassword.trim() != password.trim())
            {
                $('#errormobileappcpass').text('Password and confirm password must be same.');
                $('#cpassword').focus();
                var error = 'Yes';
            }
        }
		var full_name = $('#full_name').val();
        if(full_name.trim()=='')
        {
            $('#errormobileappfullname').text('Full name is required.');
            $('#full_name').focus();
            var error = 'Yes';
        }
		var mobile_number = $('#mobile_number').val();
        
        if(mobile_number.trim()=='')
        {
            $('#errormobileappmno').text('Mobile number is required.');
            $('#mobile_number').focus();
            var error = 'Yes';
        }
        else
        {
            var filter = /^[0-9][0-9]{9}$/;
            if (!filter.test(mobile_number)) 
            {
                $('#errormobileappmno').text('Mobile number is invalid.');
                $('#mobile_number').focus();
                var error = 'Yes';
            }
        }
        if($('input[name="gender"]:checked').length == 0)
        {
            $('#errormobileappgender').text('Gender is required.');
            var error = 'Yes';
        }
		var age = $('#age').val();
        if(age.trim()=='')
        {
            $('#errormobileappage').text('Age is required.');
            $('#age').focus();
            var error = 'Yes';
        }
        if($('input[name="marital_status"]:checked').length == 0)
        {
            $('#errormobileappmaritals').text('Marital status is required.');
            var error = 'Yes';
        }
		/*var children = $('#children').val();
        if(children.trim()=='')
        {
            $('#errormobileappchildren').text('Children is required.');
            $('#children').focus();
            var error = 'Yes';
        }*/
		var education = $('#education').val();
        if(education.trim()=='')
        {
            $('#errormobileappeducation').text('Education is required.');
            $('#education').focus();
            var error = 'Yes';
        }
		var military_status = $('#military_status').val();
        if($('input[name="military_status"]:checked').length == 0)
        {
            $('#errormobileappmilitary').text('Military status is required.');
            var error = 'Yes';
        }
		var employment = $('#employment').val();
        if(employment.trim()=='')
        {
            $('#errormobileappemployment').text('Employment is required.');
            $('#employment').focus();
            var error = 'Yes';
        }
		var package = $('#package').val();
        if(package.trim()=='')
        {
            $('#errormobileapppname').text('Package is required.');
            $('#package').focus();
            var error = 'Yes';
        }
        if(error == 'Yes')
        {
        	return false;
        }
     }
    </script>
    <?php
   endif; 
?>
<?php
   if($page_condition == 'tutorial_page'):
    ?>
    <script>
     function tutorialvalidation()
     {
        $('.error').text('');
        var tutorialname = $('#tutorialname').val();
        var error = 'No';
        if(tutorialname.trim()=='')
        {
            $('#errortutorialname').text('Tutorial name is required.');
            $('#tutorialname').focus();
            var error = 'Yes';
        }
        var tutorialvideo = $('#tutorialvideo').val();
        var tutorialvideohidden = $('#tutorialvideohidden').val();
        
        if(tutorialvideohidden=="No")
        {
            if(tutorialvideo.trim()=='')
            {
                $('#errortutorialvideo').text('Tutorial video is required.');
                $('#tutorialvideo').focus();
                var error = 'Yes';
            }
            else
            {
                var exts = ['mp4','mov','ogg','qt'];
                var get_ext = tutorialvideo.split('.');
                get_ext = get_ext.reverse();
                if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                  
                }
                else 
                {
                    $('#errortutorialvideo').text('Tutorial video is invlid format.');
                    $('#tutorialvideo').focus();
                    
                    var error = 'Yes';
                }
            }
        }
        else
        {
           if(tutorialvideo!='')
            {
                 var exts = ['mp4','mov','ogg','qt'];
                var get_ext = tutorialvideo.split('.');
                get_ext = get_ext.reverse();
                if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
                  
                }
                else 
                {
                    $('#errortutorialvideo').text('Tutorial video is invlid format.');
                    $('#tutorialvideo').focus();
                    
                    var error = 'Yes';
                }
                
            }
        }
        if(error == 'Yes'){
        	return false;
        }
     }
    </script>
    <?php
   endif; 
?>
<script>
    <?php 
if($page_condition == 'admin_user_page'):
    ?>
    $(document).ready(function() {
      $("#phone").bind("keypress", function (e) {
          var keyCode = e.which ? e.which : e.keyCode
               
          if (!(keyCode >= 48 && keyCode <= 57)) {
            $("#errorphonenumeric").css("display", "inline");
            return false;
          }else{
            $("#errorphonenumeric").css("display", "none");
          }
      });
    });
    <?php
endif;
?>
function uservlidation()
{
    $('.error').text(''); 
    //var name = $('#name').val();
    var error = 'No';
    /*if(name.trim()=='')
    {
        $('#errorname').text('Username is required.');
        $('#name').focus();
        var error = 'Yes';
    }*/
    var firstname = $('#firstname').val();
    if(firstname.trim()=='')
    {
        $('#errorfirstname').text('First name is required.');
        $('#firstname').focus();
        var error = 'Yes';
    }
    var lastname = $('#lastname').val();
    if(lastname.trim()=='')
    {
        $('#errorlastname').text('Last name is required.');
        $('#lastname').focus();
        var error = 'Yes';
    }
    var phone = $('#phone').val();
    if(phone.trim()=='')
    {
        $('#errorphone').text('Phone is required.');
        $('#phone').focus();
        var error = 'Yes';
    }
    else
    {
    	var filter = /^[0-9][0-9]{9}$/;
        if (!filter.test(phone)) 
        {
            $('#errorphone').text('Phone is invalid.');
            $('#phone').focus();
            var error = 'Yes';
        }
    }
    var email = $('#email').val();
    if(email.trim()=='')
    {
        $('#erroremail').text('Email is required.');
        $('#email').focus();
        var error = 'Yes';
    }
    else
    {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email))
        {
            $('#erroremail').text('Email is invalid.');
            $('#email').focus();
            var error = 'Yes';
        }
    }
    var myaction = $('#myaction').val();
    if(myaction!='edit')
    {
        var role_id = $('#role_id').val();
        if(role_id.trim()=='')
        {
            $('#errorrole_id').text('Role is required.');
            $('#role_id').focus();
            var error = 'Yes';
        }
        var password = $('#password').val();
        if(password.trim()=='')
        {
            $('#errorpassword').text('Password is required.');
            $('#password').focus();
            var error = 'Yes';
        }
        var cpassword = $('#cpassword').val();
        if(cpassword.trim()=='')
        {
            $('#errorcpassword').text('Confirm password is required.');
            $('#cpassword').focus();
            var error = 'Yes';
        }
        if(password.trim() != cpassword.trim())
        {
            $('#errorcpassword').text('Confirm password and password does not match.');
            $('#cpassword').focus();
            var error = 'Yes';
        }
    }
    if(error == 'Yes'){
    	return false;
    }
 }
 function changepasswordvalidation()
 {
 	$('.error').text('');
    var oldpassword = $('#oldpassword').val();
    var error = 'No';
    if(oldpassword.trim()=='')
    {
        $('#erroroldpassword').text('Old password is required.');
        $('#oldpassword').focus();
        var error = 'Yes';
    }
    var password = $('#password').val();
    if(password.trim()=='')
    {
        $('#errorpassword').text('Password is required.');
        $('#password').focus();
        var error = 'Yes';
    }
    var password_confirmation = $('#password_confirmation').val();
    if(password_confirmation.trim()=='')
    {
        $('#errorpassword_confirmation').text('Retype new password is required.');
        $('#password_confirmation').focus();
        var error = 'Yes';
    }
    if(error == 'Yes')
    {
    	return false;
    }
 }
</script>
<script>
function getpermission(value)
{
    $('.error').text('');
    if(value == '')
    {
        $('#errorrole').text('Please select role!');
        $('#role').focus();
        $('#tablecheck').html('');
        return false;
    }
    else
    {   
        var url = $('#baseurl').val()+'/backend/getpermission/'+value;
         $.ajax({
            type: "GET",
            url: url,
            success: function(response){
                console.log(response);
               $('#tablecheck').html(response);
            }
          });
    }
}
function permissionvaliadation(){
    var roel = $('#role').val();
    $('.error').text('');
    if(roel ==''){
        $('#errorrole').text('Please select role!');
        $('#role').focus();
        $('#tablecheck').html('');
        return false;
    }
    if($("input[type=checkbox]:checked").length == 0)
    {
        $('#moduleerror').text('Please select atleast one checkbox');
        return false;
    }
}
</script>
</body>
</html>
