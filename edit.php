<?php
require 'db.php';

// UPDATE

// Si l'id est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // $id = $_GET['id'];
        // $query = "SELECT * FROM user WHERE id = $id";
        // $result = $db->query($query);
        // $user = $result->fetch();
        $id = $_GET['id'];
        $stmt = $db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();

        // + sécurisé contre injections :
        // $stmt = $db->prepare(...) = je prépare ma requête SQL avec un trou (?)
        // $stmt->execute([$id]) = je dis quelle valeur mettre dans ce trou
        // $user = $stmt->fetch()n = je récupère le résultat : un utilisateur précis

        if (!$user) {
            echo "Utilisateur non trouvé.";
            exit();
        }

        $firstName = $user['firstName'];
        $lastName = $user['lastName'];
        $mail = $user['mail'];
        $zipCode = $user['zipCode'];
    } catch (Exception $e) {
        echo "Erreur lors de la récupération des informations de l'utilisateur : " . $e->getMessage();
    }
} else {
    echo "ID manquant.";
    exit();
}

// Si le form de modification est soumis
if (isset($_POST['updateUser'])) {
    // je récupère les données
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $mail = trim($_POST['mail']);
    $zipCode = trim($_POST['zipCode']);

    // Regex
    if (!preg_match("/^[a-zA-ZÀ-ÿ' -]+$/", $firstName)) {
        $errors['firstName'] = "Le prénom ne peut contenir que des lettres, des apostrophes ou des tirets.";
    }

    if (!preg_match("/^[a-zA-ZÀ-ÿ' -]+$/", $lastName)) {
        $errors['lastName'] = "Le nom ne peut contenir que des lettres, des apostrophes ou des tirets.";
    }

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $mail)) {
        $errors['mail'] = "Email invalide. Format attendu : exemple@domaine.com";
    }

    if (!preg_match("/^[0-9]{5}$/", $zipCode)) {
        $errors['zipCode'] = "Code postal invalide, il doit contenir 5 chiffres.";
    }

    if (empty($errors)) {
        try {
            // Requête de màj des données
            // $query = "UPDATE user SET firstName = '$firstName', lastName = '$lastName', mail = '$mail', zipCode = '$zipCode' WHERE id = $id";
            // $result = $db->query($query);
            $stmt = $db->prepare("UPDATE user SET firstName = ?, lastName = ?, mail = ?, zipCode = ? WHERE id = ?");
            $stmt->execute([$firstName, $lastName, $mail, $zipCode, $id]);
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <header>
        <h1>ManagEasy</h1>
    </header>
    <main>
        <section class="updateForm">
            <h3>Modifier un utilisateur</h3>
            <form method="POST" action="edit.php?id=<?= htmlspecialchars($id); ?>">

                <label for="firstName">Prénom :</label><br>
                <input type="text" name="firstName" value="<?= htmlspecialchars($firstName); ?>"><br>
                <?php if (isset($errors['firstName'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['firstName']); ?></div>
                <?php endif; ?>

                <label for="lastName">Nom :</label><br>
                <input type="text" name="lastName" value="<?= htmlspecialchars($lastName); ?>"><br>
                <?php if (isset($errors['lastName'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['lastName']); ?></div>
                <?php endif; ?>

                <label for="mail">Email :</label><br>
                <input type="text" name="mail" value="<?= htmlspecialchars($mail); ?>"><br>
                <?php if (isset($errors['mail'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['mail']); ?></div>
                <?php endif; ?>

                <label for="zipCode">Code postal :</label><br>
                <input type="text" name="zipCode" value="<?= htmlspecialchars($zipCode); ?>"><br>
                <?php if (isset($errors['zipCode'])): ?>
                    <div class="error"><?= htmlspecialchars($errors['zipCode']); ?></div>
                <?php endif; ?>

                <button type="submit" name="updateUser">Mettre à jour</button>
            </form>

            <a href="index.php">Retour à la liste des utilisateurs</a>
        </section>
    </main>
</body>

</html>