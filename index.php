<?php
	require('init.php');
	$uri = array_key_exists('connection_uri', $_SESSION) ? $_SESSION['connection_uri'] : 'null';
	$lg = array_key_exists('connection_logging', $_SESSION) ? $_SESSION['connection_logging'] : false;

	if ($lg == '')
		$lg = false;

	if ($lg && LOGDIR)
		$lg = LOGDIR.'/'.$lg;

	$errmsg = false;
	$lv = new Libvirt($uri, $lg, $lang_str);
	if ($lv->get_last_error()) {
		$page = 'overview';
		$name = false;
		$errmsg = $lang->get('cannot_connect');
	}
	else {
		$name = array_key_exists('name', $_GET) ? $_GET['name'] : false;
		$res = $lv->get_domain_by_name($name);
		$page = array_key_exists('page', $_GET) ? $_GET['page'] : 'overview';
	}
?>
<html>
<head>
 <title>php-virt-control - <?= $lang->get('title_vmc') ?></title>
 <link rel="STYLESHEET" type="text/css" href="manager.css"> 
</head>
<body>
  <div id="header">
    <div id="headerLogo"></div>
  </div>

<?php
	include('main-menu.php');
	if ($name):
?>
	<h2 id="vm-name"><?= $lang->get('vm_title').' '.$name ?></h2>
<?php
	include('menu.php');
	if (File_Exists('./pages/details/'.$page.'.php'))
		include('./pages/details/'.$page.'.php');
	else
		include('error.php');
	else:
	if (File_Exists('./pages/'.$page.'.php'))
		include('./pages/'.$page.'.php');
	else
		include('error.php');
	endif;
?>

<?
	if (DEBUG) {
		echo '<div id="content">';
		echo '<div class="section">Debug - Libvirt-php resources</div>';

		$resources = $lv->print_resources();
		for ($i = 0; $i < sizeof($resources); $i++) {
			echo '<div class="item">';
			echo '        <div class="label">Resource #'.($i + 1).'</div>';
			echo '        <div class="value">'.$resources[$i].'</div>';
			echo '        <div class="nl">';
			echo '</div>';
		}

		echo '</div>';
	}
?>

</body>
</html>
