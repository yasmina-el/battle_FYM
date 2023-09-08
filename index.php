<?php

function checkFormData(array $data, array $requireds = []): array
{
    //ajouter le nom des input dans le requireds si tous les champs ne sont pas obligatoires
    $errors = [];
    unset($_SESSION['errors']);
    foreach($data as $key => $value)
    { 
        if($requireds == [] || in_array($key, $requireds))
        {
            $value=trim($value);
            if(empty($value))
            {
                $errors[$key] = "Ce champ ne doit pas être vide";
                $_SESSION['errors'][$key]="border-red";
            }else{
                $_SESSION['values'][$key]=$value;
            }
        }
    }

    return $errors;
}

function insertUser(array $data)
{
    $dbh = $GLOBALS['dbh'];

    $data['firstname'] = htmlspecialchars($data['firstname']);
    $data['lastname'] = htmlspecialchars($data['lastname']);
    $data['email'] = htmlspecialchars($data['email']);

    $sql = "INSERT INTO user (firstname, lastname, email) VALUES (:firstname, :lastname, :email)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $form = $dbh->lastInsertId();

    return $form;
}

if (isset($_POST['submit'])) {
    // Vérification du champ qui ne doit pas être vide
    $errors = checkFormData($_POST['user'], [
        'firstname',
        'lastname',
        'email'
    ]);

    if (count($errors) == 0) {
        $form = insertUser($_POST['user']);
    }
}

?>

<form>
  <div class="form-group">
    <label for="firstname">Prénom</label>
    <input type="text" class="form-control" id="firstname">
  </div>
  <div class="form-group">
    <label for="lastname">Nom</label>
    <input type="text" class="form-control" id="lastname">
  </div>
  <div class="form-group">
    <label for="email">Adresse mail</label>
    <input type="email" class="form-control" id="email">
  </div>
  <button type="submit" class="btn btn-primary">Envoyer</button>
</form>