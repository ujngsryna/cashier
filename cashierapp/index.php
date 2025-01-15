<?php
require_once('db/db-connection.php');
require_once('db/db-login.php')
?>

<link href='img/beans.png' rel='shortcut icon'>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="container">
    <img style="width: 150px; margin-bottom: 2rem;" src="img/logo.png" alt="Morcoffee Logo">
    <form method="POST">
        <h3>Enigmachino Coffee</h3>
        
        <?php if (isset($error_message)): ?>
            <div id="loginAlert" class="alert alert-danger" role="alert">
                            <?php echo $error_message; ?>
                        </div>
        <?php endif; ?>

        <div class="form-field">
            <input id="username" name="username" type="text" placeholder="Username" required>
            </div>

        <div class="form-field">
            <input id="password" name="password" type="password" placeholder="Password" required>
        </div>

        <div class="form-field">
            <button type="submit">Login</button>
        </div>

    </form>
</div>

<style>
 @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Poppins:wght@400;500;600;700&display=swap');

:root {
  --primary-color: #292929;
  --secondary-color: #303134;
  --button-color: #FF8C00;
  --button-hover-color: #f79920;
  --white: #ffffff;
  --border:#555555;
  --label: #292929;
  --error-color: #f44336;
}

body {
  background-color: var(--primary-color);
  margin: 0;
  font-family: 'Lato', sans-serif;
  overflow: hidden;
  
}

.container {
  width: 30%;
  background-color: var(--secondary-color);
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  margin: 10% auto;
  padding: 1rem;
  text-align: center;
  color: var(--primary-color);
}

.container img {
    display: block;
    margin: 0 auto;
    margin-top: 5px;
    border-radius: 5px;
}

.container h3 {
  color: var(--white);
  font-family: 'Lato', sans-serif;
}

.container form {
  margin-top: 1rem;
}

.container label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--white);
}

.container input[type="text"],
.container input[type="password"] {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid var(--border);
  border-radius: 6px;
  background-color: var(--label);
  color: var(--white);
  font-size: 14px;
}

.container button[type="submit"] {
  background-color: var(--button-color);
  color: var(--white);
  padding: 0.5rem 1.5rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 1rem;
  font-family: 'Poppins', sans-serif;
}

.container button[type="submit"]:hover {
  background-color: var(--button-hover-color);
}

.error-message {
  background-color: var(--error-color);
  color: var(--white);
  padding: 1rem 2rem;
  border-radius: 8px;
  margin-top: 1rem;
}

.form-field {
  margin-bottom: 1rem;
}
