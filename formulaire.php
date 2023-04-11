    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = array();
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $nom_utilisateur = $_POST['nom_utilisateur'] ?? '';
        $mot_de_passe = $_POST['mot_de_passe'] ?? '';
        $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'] ?? '';
        
        // Vérification du nom
        if (empty($nom)) {
            $errors[] = "Le nom est obligatoire.";
        }
        
        // Vérification du prénom
        if (empty($prenom)) {
            $errors[] = "Le prénom est obligatoire.";
        }
        
        // Vérification de l'adresse e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse e-mail n'est pas valide.";
        }
        
        // Vérification du nom d'utilisateur
        if (empty($nom_utilisateur)) {
            $errors[] = "Le nom d'utilisateur est obligatoire.";
        }
        
        // Vérification du mot de passe
        if (empty($mot_de_passe)) {
            $errors[] = "Le mot de passe est obligatoire.";
        } elseif (strlen($mot_de_passe) < 8) {
            $errors[] = "Le mot de passe doit comporter au moins 8 caractères.";
        }
        
        // Vérification de la confirmation du mot de passe
        if (empty($confirmer_mot_de_passe)) {
            $errors[] = "Veuillez confirmer votre mot de passe.";
        } elseif ($confirmer_mot_de_passe !== $mot_de_passe) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }
        
        // Si le formulaire ne contient pas d'erreurs, on peut traiter les données
        if (count($errors) === 0) {
            // Traitement des données...
            echo "Le formulaire a été soumis avec succès !";
            exit;
        }
    }
    ?>

    <form class="row g-3 needs-validation" action="formulaire.php" method="post" novalidate>
        <?php if (count($errors) > 0): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="col-12">
            <label for="nom" class="form-label">Votre nom</label>
            <input type="text" name="nom" class="form-control" id="nom" required>
            <div class="invalid-feedback">Svp, entrer votre nom !</div>
        </div>

        <div class="col-12">
            <label for="email" class="form-label">Votre adresse e-mail</label>
            <input type="email" name="email" class="form-control" id="email" required>
            <div class="invalid-feedback">Svp, entrer votre</div>

    <div class="col-12">
        <label for="prenom" class="form-label">Votre prénom</label>
        <input type="text" name="prenom" class="form-control" id="prenom" required>
        <div class="invalid-feedback">Svp, entrer votre prénom !</div>
    </div>

    <div class="col-12">
        <label for="nom_utilisateur" class="form-label">Votre nom d'utilisateur</label>
        <input type="text" name="nom_utilisateur" class="form-control" id="nom_utilisateur" required>
        <div class="invalid-feedback">Svp, entrer votre nom d'utilisateur !</div>
    </div>

    <div class="col-12">
        <label for="mot_de_passe" class="form-label">Votre mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" required>
        <div class="invalid-feedback">Svp, entrer votre mot de passe !</div>
    </div>

    <div class="col-12">
        <label for="confirmer_mot_de_passe" class="form-label">Confirmer votre mot de passe</label>
        <input type="password" name="confirmer_mot_de_passe" class="form-control" id="confirmer_mot_de_passe" required>
        <div class="invalid-feedback">Svp, confirmer votre mot de passe !</div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>

