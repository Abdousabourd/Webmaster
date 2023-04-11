
<?php

// Vérifier si le code d'activation est valide
if (isset($_GET['code'])) {
	$code_activation = test_input($_GET['code']);

	// Se connecter à la base de données
	$connexion = mysqli_connect("localhost", "utilisateur", "motdepasse", "ma_base_de_donnees");

	// Vérifier si le code d'activation correspond à un utilisateur dans la base de données
	$requete = "SELECT * FROM utilisateurs WHERE code_activation = '$code_activation'";
	$resultat = mysqli_query($connexion, $requete);

	if (mysqli_num_rows($resultat) == 1) {
		// Récupérer les données de l'utilisateur
		$row = mysqli_fetch_assoc($resultat);
		$nom = $row['nom'];
		$prenom = $row['prenom'];
		$email = $row['email'];
		$nom_utilisateur = $row['nom_utilisateur'];

		// Afficher les informations de confirmation à l'utilisateur
		echo "<h1>Bienvenue sur notre site web, $prenom !</h1>";
		echo "<p>Votre compte a été activé avec succès.</p>";
		echo "<p>Voici vos informations d'utilisateur :</p>";
		echo "<ul>";
		echo "<li>Nom : $nom</li>";
		echo "<li>Prénom : $prenom</li>";
		echo "<li>Nom d'utilisateur : $nom_utilisateur</li>";
		echo "<li>Adresse e-mail : $email</li>";
		echo "</ul>";

		// Mettre à jour la base de données pour indiquer que le compte a été activé
		$requete = "UPDATE utilisateurs SET compte_active = 1 WHERE code_activation = '$code_activation'";
		mysqli_query($connexion, $requete);

		// Fermer la connexion à la base de données
		mysqli_close($connexion);
	} else {
		echo "<p>Le code d'activation n'est pas valide.</p>";
	}
} else {
	echo "<p>Le code d'activation est manquant.</p>";
}
