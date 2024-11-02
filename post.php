<!DOCTYPE html>
<?php
	include_once "inc/account.php";
	if(!isset($_SESSION["account"])){
		header('Location: login.php');
	}
?>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
	
		<?php include "inc/header.php"; ?>
		
		<div class="content-box" id="content-box">
			<h1 class="section-title">Post Blog</h1>
			<p id="form-result">
				<?php
					if(isset($_GET["error"])){
						switch($_GET["error"]){
							case 'format':
								echo "Invalid image format.";
								break;
							case 'size':
								echo "Maximum image size 50Mb.";
								break;
						}
					}
				?>
			</p>
			
			<form class="blogpost" action="inc/makepost.php" method="post" enctype="multipart/form-data">
				<div class="post-header">
					<input id="title" class="form-text" type="text" name="title" placeholder="Post Title" value="<?php
						//fill default if set
						if(isset($_SESSION["title"])){
							echo htmlspecialchars($_SESSION["title"]);
							unset($_SESSION["title"]);
						}
					?>" required></input>
					<input id="date" class="form-date" type="text" placeholder="<?php echo date("d-m-Y") ?>" readonly="readonly"></input>
					
					<div class="form-button-container">
						<label for="image" class="form-button-inline">Choose File</label>		
						<span id="file-name">No file chosen.</span>					
						<input class="hidden" type="file" id="image" name="image"></input>
					</div>
				
					<input id="visibility" class="hidden" name="public" type="checkbox"></input>
					<label id="visibility-label" for="visibility" class="form-toggle"></label>
					
					<input class="hidden" type="submit" name="submit" id="post"/>
					<label for="post" class="form-button">Post</label>
				</div>
				<div class="post-body">
					<textarea id="post-content" name="post-content" rows="30" required><?php
						//get default if Set
						if(isset($_SESSION["content"])){
							echo htmlspecialchars($_SESSION["content"]);
							unset($_SESSION["content"]);
						}
					?></textarea>
				</div>
			</form>
		</div>
		<div id="footer">
			<p>Fall 2024 ICS 325-50 Team Cougars</p>
		</div>
		
		<script>
			const textarea = document.getElementById('post-content');
			const content = document.getElementById('content-box');
			function update(){
				textarea.style.height = 'auto'; // Reset height
				textarea.style.height = `${textarea.scrollHeight}px`; // Set height to scrollHeight
				content.style.height= `${textarea.scrollHeight + 205}px`;
			}
			
			textarea.addEventListener('input', update);
			update();
		</script>
		
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				var fileInput = document.getElementById('image');
				var fileNameSpan = document.getElementById('file-name');

				fileInput.addEventListener('change', function () {
					if (fileInput.files.length > 0) {
					var fileName = fileInput.files[0].name;
						fileNameSpan.textContent = fileName;
						//$('#upload').removeAttr('disabled');
						
					} else {
						//$('#upload').attr('disabled','disabled');
						fileNameSpan.textContent = 'No file chosen';
					}
				});
			});
		</script>
		
	</body>
</html>