<!DOCTYPE html>
<?php
	include_once "inc/account.php";
	if(!isset($_SESSION["account"])){
		header('Location: login.php');
	}
	
	$blog_id = "";
	$account = "";
	$blog = "";
	$creator = "";
	$editing = false;
	
	if(isset($_GET["id"])){
		//editing existing post
		include_once "inc/blog_data.php";
		
		if(!isset($_SESSION["account"])){
			header('Location: login.php');
			exit;
		}

		if (!isset($_GET['id'])) {
			header('Location: index.php');
			exit;
		}
		
		$blog_id = $_GET['id'];
		$account = getSessionAccount();
		$blog = getBlogById($blog_id);

		if (!$blog || !$account || $account["email"] !== $blog["creator_email"]) {
			header('Location: index.php');
			exit;
		}
		
		$editing = true;
		$creator = getUser($blog["creator_email"]);
	}
?>
<html>
	<head>
		<title>Photo ABCD</title>
		<link rel="stylesheet" type="text/css" href="styles.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="global-wrapper">
			<?php include "inc/header.php"; ?>
			<div id="body">
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
									case 'title':
										echo "Title must begin with a capital letter.";
										break;
								}
							}
						?>
					</p>
					
					<form class="blogpost" action="inc/makepost.php" method="post" enctype="multipart/form-data">
						<?php 
						if($editing){
							echo "<input class='hidden' type='text' name='id' value='". $blog_id . "'/>";
						}
						?>
						<div class="post-header">
							<select id="title-letter" name="title-letter" class="form-select">
								<option value="A">A</option>
								<option value="B">B</option>
								<option value="C">C</option>
								<option value="D">D</option>
								<option value="E">E</option>
								<option value="F">F</option>
								<option value="G">G</option>
								<option value="H">H</option>
								<option value="I">I</option>
								<option value="J">J</option>
								<option value="K">K</option>
								<option value="L">L</option>
								<option value="M">M</option>
								<option value="N">N</option>
								<option value="O">O</option>
								<option value="P">P</option>
								<option value="Q">Q</option>
								<option value="R">R</option>
								<option value="S">S</option>
								<option value="T">T</option>
								<option value="U">U</option>
								<option value="V">V</option>
								<option value="W">W</option>
								<option value="X">X</option>
								<option value="Y">Y</option>
								<option value="Z">Z</option>
							</select>
							<span class="form-span"> for </span>
							<input id="title" class="form-text" type="text" name="title" placeholder="Post Title" value="<?php
								//fill default if set
								if(isset($_SESSION["title"])){
									echo htmlspecialchars($_SESSION["title"]);
									unset($_SESSION["title"]);
								}elseif($editing){
									echo $blog["title"];
								}
							?>" required></input>
							<input id="date" class="form-date" type="text" placeholder="<?php echo date("d-m-Y") ?>" readonly="readonly"></input>
							
							<div class="form-button-container">
								<label for="image" class="form-button-inline" id="upload"><?php echo $editing ? "Replace Image" : "Choose Image"; ?> </label>				
								<input class="hidden" type="file" id="image" name="image"></input>
							</div>
						
							<input id="visibility" class="hidden" name="public" type="checkbox" <?php 
								if($editing && $blog["privacy_filter"] != "private"){
									echo "checked";
								}
							?>></input>
							
							<label id="visibility-label" for="visibility" class="form-toggle"></label>
							
							<input class="hidden" type="submit" name="submit" id="post"/>
							<label for="post" class="form-button"><?php echo $editing ? "Update" : "Post"?></label>
						</div>
						<div class="post-body">
							<textarea id="post-content" name="post-content" rows="30" required><?php
								//get default if Set
								if(isset($_SESSION["content"])){
									echo htmlspecialchars($_SESSION["content"]);
									unset($_SESSION["content"]);
								}elseif($editing){
									echo $blog["description"];
								}
							?></textarea>
						</div>
					</form>
				</div>
			</div>
			<div id="footer">
				<p>Fall 2024 ICS 325-50 Team Cougars</p>
			</div>
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
				var button = document.getElementById('upload');

				fileInput.addEventListener('change', function () {
					if (fileInput.files.length > 0) {
					var fileName = fileInput.files[0].name;
						button.textContent = 'Image Selected';
						
					} else {
						button.textContent = '<?php echo $editing ? "Replace Image" : "Choose Image"; ?>';
					}
				});
			});
		</script>
		
	</body>
</html>