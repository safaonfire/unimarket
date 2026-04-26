<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Inscription - UniMarket</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <style>
        body {
            padding-top: 90px;
            padding-bottom: 40px;
            background-color: #f8f9fa;
        }
        .form-container {
            background: white;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <form action="../controller/user/add.php" method="POST" onsubmit="showSpinner()" class="form-container border p-4 shadow-sm">
                    <h3 class="text-center mb-4 fw-bold">S’inscrire</h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" name="prenom" id="prenom" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tel" class="form-label">Téléphone *</label>
                        <input type="text" name="tel" id="tel" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" name="adresse" id="adresse" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe *</label>
                        <input type="password" name="mdp" id="mdp" class="form-control" required>
                        <div class="form-text">Min. 6 caractères, avec lettre et chiffre.</div>
                    </div>

                    <div class="mb-4">
                        <label for="c_mdp" class="form-label">Confirmer mot de passe *</label>
                        <input type="password" name="c_mdp" id="c_mdp" class="form-control" required>
                    </div>

                    <button type="submit" name="ok" class="btn btn-dark w-100 py-2">Créer un compte</button>
                    
                    <p class="text-center mt-3 mb-0">
                        Déjà un compte ? <a href="login.php" class="text-decoration-none fw-bold text-dark">Se connecter</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    
    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; text-align:center;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);">
            <div class="spinner-border text-dark" style="width:4rem; height:4rem;"></div>
        </div>
    </div>

    <div id="successCheck" style="display:none; position:fixed; top:30%; left:50%; transform:translateX(-50%); z-index:10000;">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-check-circle-fill text-success" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
        </svg>
    </div>

<script>
function showSpinner(){
    document.getElementById('loadingOverlay').style.display = 'block';
}

window.onload = function(){
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('success') === '1'){
        document.getElementById('successCheck').style.display = 'block';
        setTimeout(()=> document.getElementById('successCheck').style.display='none', 4000); 
    }
}
</script>

</body>
</html>





