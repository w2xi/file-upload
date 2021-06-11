<html>
	<head>
		<meta charset="utf-8"/>
		<title>File Upload</title>
	</head>
	<body>
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" name="my-file" />
			<input type="submit" value="submit" />
		</form>
	</body>
</html>

<?php 

if ( isset($_FILES['my-file']) ){
	require '../src/FileUpload.php';

	$file = new FileUpload($_FILES['my-file']['tmp_name']);
	$file = $file->setUploadedFileInfo($_FILES['my-file']);

	$info = $file->validate(['size' => 10 * 1024 * 1024, 'ext' => 'jpg,png'])->move(dirname(__DIR__) . '\\uploads');

	if ( !$info ){
	    var_dump($file->getError());
	}

	echo $info->getExtension() . '<br />';
	echo $info->getSaveName() . '<br />';
	echo $info->getFilename() . '<br />';

	echo 'upload file successfully!';
} 