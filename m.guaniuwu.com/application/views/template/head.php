<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars($title); ?></title>
<meta name="keywords" content="<?php echo htmlspecialchars($keywords); ?>" />
<meta name="description" content="<?php echo htmlspecialchars($description); ?>" />
<link rel="icon" href="<?php echo $GLOBALS['HOST'];?>/favicon.ico" type="image/x-icon" />
<?php foreach ($styles as $file): ?> 
    <link rel="stylesheet" type="text/css" href="<?php echo _script($file); ?>" media="all" />
<?php endforeach; ?>
<script type="text/javascript">
<?php 
    $GLOBALS['WEBPROFILE']['userid']= empty($login_user) ? null : idtourl($login_user['uid']);
	foreach($GLOBALS['WEBPROFILE'] as $k=>&$v){
        $v="{$k}:\"".htmlspecialchars($v)."\""; 
    }
?>
    WEBPROFILE = { <?php echo implode(',', $GLOBALS['WEBPROFILE']); ?> }
</script>
<?php  foreach ($scripts as $file): ?>
        <script type="text/javascript" src="<?php echo _script($file); ?>"></script>
<?php endforeach; ?> 
</head>
