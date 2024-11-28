<div id="header">
	<a class="no-decoration" href="./index.php">
		<h1 class="title">
			<span class="red">Photo A</span>
			<span class="blue">B</span>
			<span class="yellow">C</span>
			<span class="green">D</span>
		</h1>
	</a>
	<div class="inline-buttons-right" id="nav-box">
		<form class="account-form" action=".">
			<input class="hidden" type="submit" id="index"/>
			<label for="index" class="form-button">Blogs</label>
		</form>
		<form class="account-form" action="books">
			<input class="hidden" type="submit" id="books"/>
			<label for="books" class="form-button">Alphabet Books</label>
		</form>
	</div>
	<div class="inline-buttons" id="account-box">
		<p>
			<?php
				include_once "account.php";
				echo getWelcomeText();
			?>
		</p>
		
		<?php
			if(isset($_SESSION["account"])){
				echo '
					<form class="account-form" action="inc/signout">
						<input class="hidden" type="submit" id="logout"/>
						<label for="logout" class="form-button">Logout</label>
					</form>
					';
			}
			else{
				echo '
					<form class="account-form" action="login">
						<input class="hidden" type="submit" id="login"/>
						<label for="login" class="form-button">Login</label>
					</form>
					<form class="account-form" action="register">
						<input class="hidden" type="submit" id="register"/>
						<label for="register" class="form-button">Register</label>
					</form>
					';
			}
		?>
	</div>
</div>