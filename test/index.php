<?php 

require '../src/FileUpload.php';


if ( isset($_FILES['my-file']) ){
	// var_dump($_FILES['my-file']);

	$file = new FileUpload($_FILES['my-file']['tmp_name']);
	$file = $file->setUploadedFileInfo($_FILES['my-file']);


	$info = $file->validate(['size' => 10 * 1024 * 1024, 'ext' => 'jpg,png'])->move('../uploads');

	if ( !$info ){
		var_dump($file->getError());
	}
}


?>

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