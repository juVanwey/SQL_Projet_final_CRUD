<?php

session_start();

require 'db.php';

$errors = [];
$firstName = $lastName = $mail = $zipCode = '';

// CREATE / INSERT INTO

// Si le form est soumis
if (isset($_POST['addUser'])) {
    // je récupère les données
    $firstName = trim($_POST['firstName']); // trim() pour supprimer les espaces, permet ainsi comparaison, recherche...
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

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zAZ0-9.-]+\.[a-zA-Z]{2,}$/", $mail)) {
        $errors['mail'] = "Email invalide. Format attendu : exemple@domaine.com";
    }

    if (!preg_match("/^[0-9]{5}$/", $zipCode)) {
        $errors['zipCode'] = "Code postal invalide, il doit contenir 5 chiffres.";
    }

    // Si aucune erreur :
    if (empty($errors)) {
        try {
            // Requête d'insertion INSERT INTO
            $stmt = $db->prepare("INSERT INTO user (firstName, lastName, mail, zipCode) VALUES (?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $mail, $zipCode]);

            // Message de confirmation
            $_SESSION['confirmationAddMessage'] = "Utilisateur ajouté avec succès !";

            // Redirection vers la page d'accueil
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            // catch l'erreur et l'affiche
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }
}

// READ / SELECT

try {
    $users = $db->query("SELECT * FROM user ORDER BY id DESC")->fetchAll();
} catch (Exception $e) {
    echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion utilisateurs</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <header>
        <h1>ManagEasy</h1>
    </header>

    <main>
        <!-- Formm pour ajouter un user -->
        <h2>Gestion des utilisateurs</h2>
        <div class="addFormAndTable">
            <section class="addForm">
                <h3>Ajouter un utilisateur</h3>
                <form action="index.php" method="POST">
                    <label for="firstName">Prénom:</label><br>
                    <input type="text" name="firstName" value="<?= htmlspecialchars($firstName); ?>"><br>
                    <?php if (isset($errors['firstName'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['firstName']); ?></div>
                    <?php endif; ?>

                    <label for="lastName">Nom:</label><br>
                    <input type="text" name="lastName" value="<?= htmlspecialchars($lastName); ?>"><br>
                    <?php if (isset($errors['lastName'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['lastName']); ?></div>
                    <?php endif; ?>

                    <label for="mail">Email:</label><br>
                    <input type="email" name="mail" value="<?= htmlspecialchars($mail); ?>"><br>
                    <?php if (isset($errors['mail'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['mail']); ?></div>
                    <?php endif; ?>

                    <label for="zipCode">Code postal:</label><br>
                    <input type="text" name="zipCode" value="<?= htmlspecialchars($zipCode); ?>"><br>
                    <?php if (isset($errors['zipCode'])): ?>
                        <div class="error"><?= htmlspecialchars($errors['zipCode']); ?></div>
                    <?php endif; ?>

                    <button type="submit" name="addUser">Ajouter</button>
                </form>

                <!-- Message de confirmation d'ajout -->
                <?php
                if (isset($_SESSION['confirmationAddMessage'])): ?>
                    <div class="message"><?= $_SESSION['confirmationAddMessage']; ?></div>
                <?php unset($_SESSION['confirmationAddMessage']); // supprimer après affichage
                endif;
                ?>

            </section>

            <!-- Tableau pour afficher les users et les btns modifier et supprimer -->
            <section class="table">

                <!-- Message de confirmation de modfification -->
                <?php
                if (isset($_SESSION['confirmationUpdateMessage'])): ?>
                    <div class="message"><?= $_SESSION['confirmationUpdateMessage']; ?></div>
                <?php unset($_SESSION['confirmationUpdateMessage']); // supprimer après affichage
                endif;
                ?>

                <h3>Liste des utilisateurs</h3>
                <table>
                    <tr>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Mail</th>
                        <th>Code postal</th>
                    </tr>
                    <?php foreach ($users as $entry): ?>
                        <tr>
                            <td><?= $entry['firstName']; ?></td>
                            <td><?= $entry['lastName']; ?></td>
                            <td><?= $entry['mail']; ?></td>
                            <td><?= $entry['zipCode']; ?></td>
                            <td><a href="edit.php?id=<?= $entry['id']; ?>">Modifier</a></td>
                            <td><a href="delete.php?id=<?= $entry['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>
        </div>
    </main>

    <footer>
        <p>Crée par Julie V. &copy; 2025 ManagEasy. Tous droits réservés.</p>
    </footer>
</body>

</html>