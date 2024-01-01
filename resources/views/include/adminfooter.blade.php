<!-- Control Sidebar -->
  <input type="hidden" name="baseurl" id="baseurl" value="<?php echo url('/');?>">
  <aside class="control-sidebar control-sidebar-dark">
    
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <!-- Main Footer -->
 <script> 
 
$(document).ready(function() 
{
    $( "#CategoryId" ).change(function() {
     var value = $(this).val();
     var url = $('#baseurl').val()+'/backend/getparentcat/'+value;
     $.ajax({
        type: "GET",
        url: url,
        success: function(response){
           $( "#subCategoryId" ).html(response);
        }
      });
    });
    $( "#subCategoryId" ).change(function() {
     var value = $(this).val();
     var url = $('#baseurl').val()+'/backend/getparentcat/'+value;
     $.ajax({
        type: "GET",
        url: url,
        success: function(response){
           $( "#subSubCategoryId" ).html(response);
        }
      });
    });
});
  function pagevlidation()
  {
      /*var page_title = $('#page_title').val();
      if(page_title == "")
      {
          alert('Page title can not be blank!');
          $('#page_title').focus();
          return false;
      }
      var menu_title = $('#menu_title').val();
      if(menu_title == "")
      {
          alert('Menu title can not be blank!');
          $('#menu_title').focus();
          return false;
      }
      var exampledropUp = $('#example-dropUp').val();
      if(exampledropUp == "")
      {
          alert('Website can not be blank!');
          $('#example-dropUp').focus();
          return false;
      }
      var meta_keyword = $('#meta_keyword').val();
      if(meta_keyword == "")
      {
          alert('Meta keyword can not be blank!');
          $('#meta_keyword').focus();
          return false;
      }
      var meta_description = $('#meta_description').val();
      if(meta_description == "")
      {
          alert('Meta escription can not be blank!');
          $('#meta_description').focus();
          return false;
      }
      */
      return true;
  }
 function tutorvlidation()
  {
      /*var FirstName = $('#FirstName').val();
      if(FirstName == "")
      {
          alert('First name can not be blank!');
          $('#FirstName').focus();
          return false;
      }
      var LastName = $('#LastName').val();
      if(LastName == "")
      {
          alert('Last name can not be blank!');
          $('#LastName').focus();
          return false;
      }
      var username = $('#username').val();
      if(username == "")
      {
          alert('User name can not be blank!');
          $('#username').focus();
          return false;
      }
      var Email = $('#Email').val();
      if(Email == "")
      {
          alert('Email can not be blank!');
          $('#Email').focus();
          return false;
      }
      else
      {
          regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if(!regex.test(Email))
          {
            alert('Invalid Email!');
            $('#Email').focus();
            return false;
          }
      }
      var Mobile = $('#Mobile').val();
      if(Mobile == "")
      {
          alert('Mobile can not be blank!');
          $('#Mobile').focus();
          return false;
      }
      else
      {
        intRegex = /[0-9 -()+]+$/;
        if((Mobile.length < 10) || !intRegex.test(Mobile))
        {
             alert('Please enter a valid mobile number.');
             $('#Mobile').focus();
             return false;
        }
      }
      var Address = $('#Address').val();
      if(Address == "")
      {
          alert('Address can not be blank!');
          $('#Address').focus();
          return false;
      }
      var BirthDate = $('#BirthDate').val();
      if(BirthDate == "")
      {
          alert('Birth date can not be blank!');
          $('#BirthDate').focus();
          return false;
      }
      */
      return true;
  }
  function studentvlidation()
  {
      /* var FirstName = $('#FirstName').val();
      if(FirstName == "")
      {
          alert('First name can not be blank!');
          $('#FirstName').focus();
          return false;
      }
      var LastName = $('#LastName').val();
      if(LastName == "")
      {
          alert('Last name can not be blank!');
          $('#LastName').focus();
          return false;
      }
      var username = $('#username').val();
      if(username == "")
      {
          alert('User name can not be blank!');
          $('#username').focus();
          return false;
      }
      var Email = $('#Email').val();
      if(Email == "")
      {
          alert('Email can not be blank!');
          $('#Email').focus();
          return false;
      }
      else
      {
          regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if(!regex.test(Email))
          {
            alert('Invalid Email!');
            $('#Email').focus();
            return false;
          }
      }
      var Mobile = $('#Mobile').val();
      if(Mobile == "")
      {
          alert('Mobile can not be blank!');
          $('#Mobile').focus();
          return false;
      }
      else
      {
        intRegex = /[0-9 -()+]+$/;
        if((Mobile.length < 10) || !intRegex.test(Mobile))
        {
             alert('Please enter a valid mobile number.');
             $('#Mobile').focus();
             return false;
        }
      }
      var Address = $('#Address').val();
      if(Address == "")
      {
          alert('Address can not be blank!');
          $('#Address').focus();
          return false;
      }
      var BirthDate = $('#BirthDate').val();
      if(BirthDate == "")
      {
          alert('Birth date can not be blank!');
          $('#BirthDate').focus();
          return false;
      }
      */
      return true;

  }
  function coursevlidation()
  {
      /*var Name = $('#Name').val();
      if(Name == "")
      {
          alert('Course name can not be blank!');
          $('#Name').focus();
          return false;
      }
      */
      return true;
  }
   function subjectvlidation()
  {
      /*var name = $('#name').val();
      if(name == "")
      {
          alert('Subject name can not be blank!');
          $('#name').focus();
          return false;
      }
      var description = $('#description').val();
      if(description == "")
      {
          alert('Description can not be blank!');
          $('#description').focus();
          return false;
      }
      var courseid = $('#courseid').val();
      if(courseid == "")
      {
          alert('Course can not be blank!');
          $('#courseid').focus();
          return false;
      }
      */
      return true;
  }
  function uservlidation()
  {
      /*var name = $('#name').val();
      if(name == "")
      {
          alert('Name can not be blank!');
          $('#name').focus();
          return false;
      }
      var email = $('#email').val();
      if(email == "")
      {
          alert('Email can not be blank!');
          $('#email').focus();
          return false;
      }
      else
      {
          regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if(!regex.test(email))
          {
            alert('Invalid email!');
            $('#email').focus();
            return false;
          }
      }
      var role_id = $('#role_id').val();
      if(role_id == "")
      {
          alert('Role id can not be blank!');
          $('#role_id').focus();
          return false;
      }
      var password = $('#password').val();
      if(password == "")
      {
          alert('Password can not be blank!');
          $('#password').focus();
          return false;
      }
       var image = $('#image').val();
      if(image == "")
      {
          alert('Image can not be blank!');
          $('#image').focus();
          return false;
      }
      */
      return true;
  }

  function documentvalidation()
  {
      /*var tutorid = $('#tutorid').val();
      if(tutorid == "")
      {
          alert('Please select any one tutor!');
          $('#tutorid').focus();
          return false;
      }
      var myaction = $('#myaction').val();
      if(myaction.trim() == 'edit')
      {
          var documentname = $('#documentname').val();
          if(documentname == "")
          {
            var documenthidden = $('#documenthidden').val();
            if(documenthidden == ""){
              alert('Please upload documents!');
              $('#documentname').focus();
              return false;
            }

          }
      }
      else
      {
         var documentname = $('#documentname').val();
          if(documentname == "")
          {
              alert('Please upload documents!');
              $('#documentname').focus();
              return false;
          }
      }
      */
      return true;

  }

 </script> 
<footer class="main-footer" style="display: none;">
    <strong>Copyright &copy; {{date('Y')}} </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.5
    </div>
  </footer>