<script type="text/javascript">
    function close_window(){
		if(confirm("Logout and Close Window?")){
            window.location = "logout.php"
        }
    }
</script>
<?php
//	require_once 'json/cekping.php';
?>


<div data-role="header" data-theme="b">
	<a href="#" onclick="return goIndex();" class="ui-btn-left ui-corner-all ui-shadow" data-role="button" data-icon="home">Home</a>
	<h1>Nymph Mobile</h1>
	<a href="#" onclick="return goLogout();" class="ui-btn-right ui-corner-all ui-shadow"data-role="button" data-icon="forward" title="Logout">Logout</a>
</div>

