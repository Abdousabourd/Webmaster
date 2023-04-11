    <?php

    // Informations de connexion à la base de données
    $serveur = "localhost";
    $utilisateur = "root";
    $mot_de_passe = "";
    $base_de_donnees = "gesco_ecole";

    // Connexion à la base de données
    $connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérification de la connexion
    if (!$connexion) {
        die("La connexion a échoué : " . mysqli_connect_error());
    }

    echo "Connexion réussie à la base de données.";


    // Étape 1 : Vérifier si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Étape 2 : Valider les données saisies
    $errors = array();

    // Validation du champ nom
    $nom = $_POST['nom'];
    if (empty($nom)) {
        $errors['nom'] = "Le nom est obligatoire.";
    }
    if (!preg_match('/^[A-Za-zéèêëîïôöùûüç-]+$/', $nom)) {
        $errors['nom'] = "Le nom doit contenir uniquement des lettres et des tirets.";
    }

    // Validation du champ prénom
    $prenom = $_POST['prenom'];
    if (empty($prenom)) {
        $errors['prenom'] = "Le prénom est obligatoire.";
    }
    if (!preg_match('/^[A-Za-zéèêëîïôöùûüç-]+$/', $prenom)) {
        $errors['prenom'] = "Le prénom doit contenir uniquement des lettres et des tirets.";
    }

    // Validation du champ nom_utilisateur
    $nom_utilisateur = $_POST['nom_utilisateur'];
    if (empty($nom_utilisateur)) {
        $errors['nom_utilisateur'] = "Le nom d'utilisateur est obligatoire.";
    }
    if (!preg_match('/^[A-Za-z0-9_]+$/', $nom_utilisateur)) {
        $errors['nom_utilisateur'] = "Le nom d'utilisateur doit contenir uniquement des lettres, des chiffres et des underscores.";
    }

    // Validation du champ courriel
    $courriel = $_POST['courriel'];
    if (empty($courriel)) {
        $errors['courriel'] = "L'adresse e-mail est obligatoire.";
    }
    if (!filter_var($courriel, FILTER_VALIDATE_EMAIL)) {
        $errors['courriel'] = "L'adresse e-mail est invalide.";
    }

    // Validation du champ mot_de_passe
    $mot_de_passe = $_POST['mot_de_passe'];
    if (empty($mot_de_passe)) {
        $errors['mot_de_passe'] = "Le mot de passe est obligatoire.";
    }
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $mot_de_passe)) {
        $errors['mot_de_passe'] = "Le mot de passe doit contenir au moins 8 caractères, dont une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
    }

    // Validation du champ confirm_mot_de_passe
    $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];
    if (empty($confirm_mot_de_passe)) {
        $errors['confirm_mot_de_passe'] = "La confirmation du mot de passe est obligatoire.";
    }
    if ($confirm_mot_de_passe != $mot_de_passe) {
        $errors['confirm_mot_de_passe'] = "La confirmation du mot de passe ne correspond pas.";
    }

    // Validation du champ conditions
    $conditions = $_POST['conditions'];
    if (empty($conditions)) {
        $errors['conditions'] = "Vous devez accepter les termes et conditions.";
    }

    // Étape 3 : Inscription de l'utilisateur dans la base de données
    // Connexion à la base de données
    $db = new PDO('mysql:host=localhost;dbname=gesco_ecole', 'root', '');

    // Préparation de la requête SQL pour insérer les données de l'utilisateur
    $stmt = $db->prepare("INSERT INTO utilisateurs (nom, prenom, nom_utilisateur, courriel, mot_de_passe) VALUES (:nom, :prenom, :nom_utilisateur, :courriel, :mot_de_passe)");

    // Association des valeurs saisies aux paramètres de la requête SQL
    $stmt->bindParam(':nom', $_POST['nom']);
    $stmt->bindParam(':prenom', $_POST['prenom']);
    $stmt->bindParam(':nom_utilisateur', $_POST['nom_utilisateur']);
    $stmt->bindParam(':courriel', $_POST['courriel']);
    $stmt->bindParam(':mot_de_passe', password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT));

    // Validation de la confirmation du mot de passe
    $confirm_mot_de_passe = $_POST['confirm_mot_de_passe'];
    if ($confirm_mot_de_passe != $_POST['mot_de_passe']) {
        $errors['confirm_mot_de_passe'] = "La confirmation du mot de passe ne correspond pas.";
    }

    // Si des erreurs ont été détectées, renvoyer l'utilisateur vers le formulaire avec un message d'erreur.
    if (!empty($errors)) {
        // Afficher les erreurs et renvoyer l'utilisateur vers le formulaire avec les valeurs saisies précédemment.
    } else {
        // Exécution de la requête SQL
        $stmt->execute();
        // Redirection de l'utilisateur vers une page de confirmation d'inscription
        if (!empty($errors)) {
            // Si des erreurs ont été détectées, renvoyer l'utilisateur vers le formulaire avec un message d'erreur.
            // Afficher les erreurs à l'utilisateur
            echo "<div class='alert alert-danger'>";
            echo "<strong>Erreurs:</strong><br>";
            foreach ($errors as $error) {
                echo "- $error<br>";
            }
            echo "</div>";
        
            // Renvoyer l'utilisateur vers le formulaire avec les valeurs saisies précédemment
            echo "<form method='post' action='formulaire.php'>";
            echo "<label for='nom'>Nom:</label>";
            echo "<input type='text' name='nom' value='$nom'><br>";
            echo "<label for='prenom'>Prénom:</label>";
            echo "<input type='text' name='prenom' value='$prenom'><br>";
            echo "<label for='nom_utilisateur'>Nom d'utilisateur:</label>";
            echo "<input type='text' name='nom_utilisateur' value='$nom_utilisateur'><br>";
            echo "<label for='courriel'>Courriel:</label>";
            echo "<input type='email' name='courriel' value='$courriel'><br>";
            echo "<label for='mot_de_passe'>Mot de passe:</label>";
            echo "<input type='password' name='mot_de_passe'><br>";
            echo "<label for='confirm_mot_de_passe'>Confirmer le mot de passe:</label>";
            echo "<input type='password' name='confirm_mot_de_passe'><br>";
            echo "<label for='conditions'>Accepter les termes et conditions:</label>";
            echo "<input type='checkbox' name='conditions' value='oui' checked><br>";
            echo "<input type='submit' value='S'inscrire'>";
            echo "</form>";
        }

        /// Redirection vers une page de confirmation
    header("Location: confirmation.php");
    exit();

        // Autre traitement des données
    }
    }

    ?>
