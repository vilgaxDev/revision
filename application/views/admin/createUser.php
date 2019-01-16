
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>User Management</h3>
      </div>

    </div>
  </div>

<div class="clearfix"></div>



<div class="row">
  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Create new user</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <?php
        if(!empty($errorMessage)){
          foreach($errorMessage as $message){
            ?>
            <p class="alert alert-danger"><?php echo $message; ?></p>
            <?php
          }
        }
        if(!empty($successMessage)){
          foreach($successMessage as $message){
            ?>
            <p class="alert alert-success"><?php echo $message; ?></p>
            <?php
          }
        }
         ?>

         <?php echo form_open('admin/user/edit/'.$user_details['id']); ?>
         <div class="row">
           <div class="col-md-12">
             <h4>Contact Information</h4>
             <div class="row">
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Firstname</label>
                   <input class="form-control" type="text" name="firstname" value="" />
                 </div>
               </div>
               <div class="col-md-6">
                 <div class="form-group">
                   <label>Lastname</label>
                   <input class="form-control" type="text" name="lastname" value="" />
                 </div>
               </div>
             </div>
             <div class="form-group">
               <label>Email Address</label>
               <input class="form-control" type="text" name="email" value="" />
             </div>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
             <br/>
             <h4>Premium Account</h4>
             <div class="row">
               <div id="sandbox-container" class="form-group col-md-6">
                 <label>Premium Until</label>
                 <input type="text" name="premium_until" class="form-control" value="">
               </div>
               <div class="col-md-6">
                 <strong>Information</strong><br/>
                 <p>
                   Please select a date in the future to set this user as a premium user.
                   As soon as the date expires the user will be degrated to a default user again.
                 </p>
                 <input type="checkbox" name="deletePremium" value="1"/> <label class="red">Delete current premium subscription</label>
               </div>
             </div>
             <br/>
             <h4>User Permissions</h4>
             <div class="row">
               <div class="col-md-6">
                 <select class="form-control" name="userGroup">
                   <?php
                   foreach($user_groups as $user_group){
                     ?>
                     <option value="<?php echo $user_group['id']; ?>" <?php if($user_details['group_id'] == $user_group['id']){echo "selected";} ?>>
                       <?php echo $user_group['name']; ?>
                     </option>
                     <?php
                   }
                    ?>
                 </select>
               </div>
               <div class="col-md-6">
                 <strong>Administrator</strong><br/>
                 All permissions<br/>
                 <strong>Staff</strong><br/>
                 View statistics, edit users, delete files<br/>
                 <strong>Customer</strong><br/>
                 Mange its own account
               </div>
             </div>
             <script type="text/javascript">
                 $(function () {
                   $('#sandbox-container input').datepicker({
                   });
                 });
             </script>

             <br/>
             <h4>Account Password</h4>
             <div class="form-group">
               <label>New Password</label>
               <input class="form-control" type="password" name="new_password" placeholder="Unchanged" />
             </div>
             <div class="form-group">
               <label>Repeat New Password</label>
               <input class="form-control" type="password" name="new_password_repeat" placeholder="Unchanged" />
             </div>
             <input type="submit" class="btn btn-success pull-right" value="Save changes"/>
           </div>
         </div>

       </div>
     </div>
   </div>
 </div>
</div>
<script type="text/javascript">
$('.right_col').css("min-height", $(window).height());
</script>
