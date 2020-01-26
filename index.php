<?php
/*
 * Md. Imran Hosen
 * Web Developer
 * Dhaka, Bangladesh
 * www.github.com/MdImranHosen
 */
include 'crud.php';
$object = new Crud();
?>
  <!DOCTYPE html>
   <html lang="en">
  <head>
  <meta charset="utf-8">
  <!-- Responsive -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <title> PHP Mysql Ajax Crud using OOPS - Pagination </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <style>
   body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
   .box
   {
    width:1270px;
    padding:20px;
    background-color:#fff;
    border:1px solid #ccc;
    border-radius:5px;
    margin-top:10px;
   }
  </style>
 </head>
 <body>
  <div class="container box">
   <h3 align="center"> PHP Mysql Ajax Crud using OOPS - Pagination </h3><br /><br />
   <div class="col-md-8">
    <button type="button" name="add" id="add" class="btn btn-success" data-toggle="collapse" data-target="#user_collapse">Add</button>
   </div>
   <div class="col-md-4">
    <input type="text" name="search" id="search" placeholder="Search" class="form-control" />
   </div>
   <br />
   <br />
   <div id="user_collapse" class="collapse">
    <form method="post" id="user_form">
     <label>Enter First Name</label>
     <input type="text" name="first_name" id="first_name" class="form-control" />
     <br />
     <label>Enter Last Name</label>
     <input type="text" name="last_name" id="last_name" class="form-control" />
     <br />
     <label>Select User Image</label>
     <input type="file" name="user_image" id="user_image" />
     <input type="hidden" name="hidden_user_image" id="hidden_user_image" />
     <span id="uploaded_image"></span>
     <br />
     <div align="center">
      <input type="hidden" name="action" id="action" />
      <input type="hidden" name="user_id" id="user_id" />
      <input type="submit" name="button_action" id="button_action" class="btn btn-default" value="Insert" />
     </div>
    </form>
   </div>
   <br /><br />
   <div id="user_table" class="table-responsive">
   </div>
  </div>
 </body>
</html>

<script type="text/javascript">
 $(document).ready(function(){

  load_data();

  $('#action').val("Insert");

  $('#add').click(function(){
   $('#user_form')[0].reset();
   $('#uploaded_image').html('');
   $('#button_action').val("Insert");
  });

  function load_data(page)
  {
   var action = "Load";
   $.ajax({
    url:"action.php",
    method:"POST",
    data:{action:action, page:page},
    success:function(data)
    {
     $('#user_table').html(data);
    }
   });
  }

  $(document).on('click', '.pagination_link', function(){
   var page = $(this).attr("id");
   load_data(page);
  });

  $('#user_form').on('submit', function(event){
   event.preventDefault();
   var firstName = $('#first_name').val();
   var lastName = $('#last_name').val();
   var extension = $('#user_image').val().split('.').pop().toLowerCase();
   if(extension != '')
   {
    if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
    {
     alert("Invalid Image File");
     $('#user_image').val('');
     return false;
    }
   }
   if(firstName != '' && lastName != '')
   {
    $.ajax({
     url:"action.php",
     method:"POST",
     data:new FormData(this),
     contentType:false,
     processData:false,
     success:function(data)
     {
      alert(data);
      $('#user_form')[0].reset();
      load_data();      
      $('#action').val("Insert");
      $('#button_action').val("Insert");
      $('#uploaded_image').html('');
     }
    })
   }
   else
   {
    alert("Both Fields are Required");
   }
  });

  $(document).on('click', '.update', function(){
   var user_id = $(this).attr("id");
   var action = "Fetch Single Data";
   $.ajax({
    url:"action.php",
    method:"POST",
    data:{user_id:user_id, action:action},
    dataType:"json",
    success:function(data)
    {
     $('.collapse').collapse("show");
     $('#first_name').val(data.first_name);
     $('#last_name').val(data.last_name);
     $('#uploaded_image').html(data.image);
     $('#hidden_user_image').val(data.user_image);
     $('#button_action').val("Edit");
     $('#action').val("Edit");
     $('#user_id').val(user_id);
    }
   });
  });
  
  $(document).on('click', '.delete', function(){
   var user_id = $(this).attr("id");
   var action = "Delete";
   if(confirm("Are you sure you want to delete this?"))
   {
    $.ajax({
     url:"action.php",
     method:"POST",
     data:{user_id:user_id, action:action},
     success:function(data)
     {
      alert(data);
      load_data();
     }
    });
   }
   else
   {
    return false;
   }
  });
  
  $('#search').keyup(function(){
   var query = $('#search').val();
   var action = "Search";
   if(query != '')
   {
    $.ajax({
     url:"action.php",
     method:"POST",
     data:{query:query, action:action},
     success:function(data)
     {
      $('#user_table').html(data);
     }
    });
   }
   else
   {
    load_data();
   }
  });
  
 });
</script>