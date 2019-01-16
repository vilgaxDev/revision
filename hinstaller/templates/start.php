
<script>
$('#step1').attr('class', 'active');
</script>


<p>
  Dear User,<br />
  thank you for purchasing FileBear. This installer will help you, to configure your database and initial user settings.<br />
  Please do not hesistate to contact us if you need any asistance with our product at any time.<br />
</p><br />

<p class="alert alert-danger">
  <strong>Important Notice</strong><br />
  You as administrator of the webspace this system is running on, are solely responsible for any illegal actions taken with this platform (e.g. illegal file sharing).
</p>
<form method="post" action="index.php">
  <label>
    <input type="checkbox" value="1" name="agree"  />
    I've read and fully understood the above notice
  </label>
  <input type="text" name="submit" value="1" style="display: none;"  hidden/>
  <input type="submit" class="btn btn-primary pull-right" value="Continue" />
</form>
