<?php

$dsn = 'mysql:dbname=test;host=127.0.0.1;charset=utf8mb4';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password);

function insertUser(array $data)
{
    $dbh = $GLOBALS['dbh'];

    $data['firstname'] = htmlspecialchars($data['firstname']);
    $data['lastname'] = htmlspecialchars($data['lastname']);
    $data['email'] = htmlspecialchars($data['email']);
    $data['moves_number']=htmlspecialchars($_GET['resp']);

    $sql = "INSERT INTO user (firstname, lastname, email, moves_number) VALUES (:firstname, :lastname, :email, :moves_number)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $form = $dbh->lastInsertId();

    return $form;
}

$errors = [];

if (isset($_POST['submit'])) {
    // Récupération des données du formulaire
    $formData = $_POST['user'];

    // Validation des champs
    if (empty($formData['firstname'])) {
        $errors['firstname'] = "Veuillez renseigner votre prénom";
    }

    if (empty($formData['lastname'])) {
        $errors['lastname'] = "Veuillez renseigner votre nom";
    }

    if (empty($formData['email'])) {
        $errors['email'] = "Veuillez renseigner votre adresse mail";
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'adresse mail n'est pas valide";
    }

    // Si les données sont valides, insérez-les dans la base de données
    if (empty($errors)) {
        $form = insertUser($formData);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Flip Card Memory Game</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Concert+One|Nova+Slim">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="display:flex; justify-content:center; align-items:center; width:100%; height:100%;">
        <div class="mb-5">
            <h4>Veuillez vous enregistrer pour connaitre votre score</h4>
        </div>

        <form method="POST">
        <div class="form-group mb-3">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="user[firstname]" style="width:300px">
            <?php if (!empty($errors['firstname'])) : ?>
                <p class="text-danger"><?php echo $errors['firstname']; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-group mb-3">
            <label for="lastname">Nom</label>
            <input type="text" class="form-control" id="lastname" name="user[lastname]">
            <?php if (!empty($errors['lastname'])) : ?>
                <p class="text-danger"><?php echo $errors['lastname']; ?></p>
            <?php endif; ?>
        </div>
        <div class="form-group mb-3">
            <label for="email">Adresse mail</label>
            <input type="email" class="form-control" id="email" name="user[email]">
            <?php if (!empty($errors['email'])) : ?>
                <p class="text-danger"><?php echo $errors['email']; ?></p>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <button type="submit" name="submit" class="btn btn-outline-dark w-100 h-100">Enregistrer</button>
        </div>
        </form>
    </div>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>