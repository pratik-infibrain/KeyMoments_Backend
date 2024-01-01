<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Replace Textareas by Class Name &mdash; CKEditor Sample</title>
	<meta content="text/html; charset=utf-8" http-equiv="content-type" />
	<script type="text/javascript" src="ckeditor.js"></script>
	<script src="sample.js" type="text/javascript"></script>
	<link href="sample.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<form action="get_name.php" method="post">
		<p>
			<label for="editor1">
				Editor 1:</label>
			<textarea class="ckeditor" cols="80" id="editor1" name="editor1" rows="10">
			
			Hello
			</textarea>
			
			<br>
			
			Editor 2:</label>
			<textarea class="ckeditor" cols="80" id="editor2" name="editor2" rows="10">
			
			Hello world
			</textarea>
			
		</p>
		<p>
			<input type="submit" value="Submit" />
		</p>
	</form>
	
</body>
</html>
