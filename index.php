<?php
function __autoload($class_name){
	
			$class_name.='.php';
			if (file_exists('classes/'.$class_name)) include 'classes/'.$class_name;
			else if (file_exists('classes/model/'.$class_name)) include 'classes/model/'.$class_name;
			else if (file_exists('classes/controller/'.$class_name)) include 'classes/controller/'.$class_name;
			else if (file_exists('classes/view/'.$class_name)) include 'classes/view/'.$class_name;
	}
	
	


SessionManager::start();

?>

<html>
<head>
<title>Simple notice</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<script type="text/javascript" src="resources/plugins/tiny_mce/tiny_mce.js" ></script >
<script type="text/javascript" >
tinyMCE.init({
        mode : "textareas",
        theme : "simple" ,
				content_css: "/resources/tinymce.css"
});
</script >

<link rel="stylesheet" type="text/css" href="resources/style.css" />
</head>
<body>
<div class="filler">
	<img src='resources/blocknote.png'/>
	<span id='logo'>SimpleNotice</span>
	<span id='logo_help'>SimpleNotice</span>
	<span id='page_description'>Projektni zadatak za Računarski praktikum 2 - Stanko Arbutina, lipanj 2011.</span>
</div>
<?php
	//$kolegij->Studij()->createReference($studij,array('godina'=>3));
	
	list($page,$action)=Dispatcher::parseRequest();
	$out=Dispatcher::getPage($page,$action);
	echo Dispatcher::getMenu();
	echo "<div class='main'>\n";
	echo $out;
	echo "</div>\n";
	DB::disconnect_if_connected();
	
	
?>
</body>
</html>

