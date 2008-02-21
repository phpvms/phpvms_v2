<form name='form1' method='post' action='index.php'>
  <table width='100%' border='0'>
    <tr>
      <td>Name:</td>
      <td><label>
        <input type='text' name='name' id='name'>
      </label></td>
    </tr>
    <tr>
      <td>E-Mail Address:</td>
      <td><label>
        <input type='text' name='email' id='email'>
      </label></td>
    </tr>
    <tr>
      <td>Message:</td>
      <td><label>
        <textarea name='message' id='message' cols='45' rows='5'></textarea>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'><label>
        <div align='center'>
          <input type='submit' name='button' id='button' value='Send Message'>
        </div>
      </label></td>
    </tr>
  </table><input name='contact' type='hidden' value='contact'>
</form>