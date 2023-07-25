<!DOCTYPE html>
<html>
<head>
    <title>Vérification de l'email</title>
</head>
<body>
<div style="margin: 0 auto; max-width: 600px; padding: 20px; font-family: Arial, sans-serif;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 5px; border: 1px solid #e9ecef;">
        <h1 style="color: #343a40;">Vérification de l'email</h1>
        <p style="color: #6c757d;">Veuillez cliquer sur le bouton ci-dessous pour vérifier votre adresse e-mail.</p>
        <a href="{{ $verificationUrl }}" style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block; margin: 10px 0;">Vérifier l'email</a>
        <p style="color: #6c757d;">Si vous n'avez pas créé de compte, aucune autre action n'est nécessaire.</p>
        <p style="color: #6c757d;">Merci,</p>
        <p style="color: #6c757d;"><strong>{{ config('app.name') }}</strong></p>
    </div>
    <p style="color: #6c757d; text-align: center; margin-top: 20px;">© 2023 Votre Service. Tous les droits sont réservés.</p>
</div>
</body>
</html>
