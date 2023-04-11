    <?php
    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupérer les données du formulaire
    $nom = test_input($_POST["nom"]);
    $prenom = test_input($_POST["prenom"]);
    $nom_utilisateur = test_input($_POST["nom_utilisateur"]);
    $email = test_input($_POST["email"]);
    $mot_de_passe = test_input($_POST["mot_de_passe"]);
    $confirmer_mot_de_passe = test_input($_POST["confirmer_mot_de_passe"]);
    
    // Vérifier la validité des champs
    $erreurs = array();
    if (empty($nom)) {
        $erreurs["nom"] = "Le nom est obligatoire.";
    } else if (!preg_match("/^[a-zA-Z ]*$/",$nom)) {
        $erreurs["nom"] = "Seuls les lettres et les espaces sont autorisés.";
    }
    
    if (empty($prenom)) {
        $erreurs["prenom"] = "Le prénom est obligatoire.";
    } else if (!preg_match("/^[a-zA-Z ]*$/",$prenom)) {
        $erreurs["prenom"] = "Seuls les lettres et les espaces sont autorisés.";
    }
    
    if (empty($nom_utilisateur)) {
        $erreurs["nom_utilisateur"] = "Le nom d'utilisateur est obligatoire.";
    } else if (!preg_match("/^[a-zA-Z0-9]*$/",$nom_utilisateur)) {
        $erreurs["nom_utilisateur"] = "Seuls les lettres et les chiffres sont autorisés.";
    }
    
    if (empty($email)) {
        $erreurs["email"] = "L'adresse e-mail est obligatoire.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs["email"] = "L'adresse e-mail n'est pas valide.";
    }
    
    if (empty($mot_de_passe)) {
        $erreurs["mot_de_passe"] = "Le mot de passe est obligatoire.";
    } else if (strlen($mot_de_passe) < 8) {
        $erreurs["mot_de_passe"] = "Le mot de passe doit comporter au moins 8 caractères.";
    }
    
    if (empty($confirmer_mot_de_passe)) {
        $erreurs["confirmer_mot_de_passe"] = "Veuillez confirmer votre mot de passe.";
    } else if ($mot_de_passe != $confirmer_mot_de_passe) {
        $erreurs["confirmer_mot_de_passe"] = "Les mots de passe ne correspondent pas.";
    }
    
    // Si des erreurs ont été détectées, les afficher
    if (count($erreurs) > 0) {
        foreach ($erreurs as $erreur) {
        echo "<p>$erreur</p>";
        }
    } else {
        // Générer un code d'activation unique
        $code_activation = md5(uniqid(rand(), true));
        // Envoyer un e-mail de confirmation avec le code d'activation
        $destinataire = $email;
        $sujet = "Confirmation d'inscription";
        $message = "Bonjour $prenom,\n\n";
        $message .= "Merci de vous être inscrit sur notre site web.\n\n";
        $message .= "Veuillez cliquer sur le lien ci-dessous pour activer votre compte :\n";
        $message .= "http://www.example.com/activation.php?code=$code_activation\n\n";
        $message .= "Si vous n'avez pas demandé cette inscription, veuillez ignorer cet e-mail.\n\n";
        $message .= "Cordialement,\n";
        $message .= "L'équipe de notre site web";
        
        $headers = "From: mrcharisme@gmail.com" . "\r\n" .
                "Reply-To: mrcharisme@gmail.com" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();
        
        if (mail($destinataire, $sujet, $message, $headers)) {

            
        // Enregistrer les données dans la base de données
        $connexion = mysqli_connect("localhost", "root", "", "gesco_ecole");
        
        $mot_de_passe_hashe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        
        $requete = "INSERT INTO utilisateurs (nom, prenom, nom_utilisateur, email, mot_de_passe, code_activation) ";
        $requete .= "VALUES ('$nom', '$prenom', '$nom_utilisateur', '$email', '$mot_de_passe_hashe', '$code_activation')";
        
        mysqli_query($connexion, $requete);
        
        mysqli_close($connexion);
        
        // Rediriger l'utilisateur vers la page de confirmation
        header("Location: confirmation.php");
        exit();
        } else {
        echo "<p>Une erreur s'est produite lors de l'envoi de l'e-mail de confirmation. Veuillez réessayer plus tard.</p>";
        }
    }
    }

    

    // Fonction de nettoyage des données
    function test_input($donnees) {
    $donnees = trim($donnees);
    $donnees = stripslashes($donnees);
    $donnees = htmlspecialchars($donnees);
    return $donnees;
    }

    