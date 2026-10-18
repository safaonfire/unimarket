<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Connexion - UniMarket</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <style>
        body {
            padding-top: 100px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <?php include __DIR__ . '/navbar.php'; ?>
<br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <form action="/controller/user/login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>" method="POST" class="bg-white border p-4 shadow-sm rounded">
                    <h3 class="text-center mb-4">Connexion</h3>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe *</label>
                        <input type="password" name="mdp" id="mdp" class="form-control" required>
                    </div>

                    <button type="submit" name="ok" class="btn btn-dark w-100" onclick="showSpinner()">
                        Se connecter
                    </button>

                    <p class="text-center mt-3 mb-0">
                        Pas encore de compte ? <a href="/view/inscription.php" class="text-decoration-none fw-bold text-dark">S'inscrire</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; text-align:center;">
        <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);">
            <div class="spinner-border text-success" style="width:4rem; height:4rem;"></div>
        </div>
    </div>

    <script>
        function showSpinner(){
            document.getElementById('loadingOverlay').style.display = 'block';
        }
    </script>
</body>
</html>





