<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaCare OS · Entrar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f766e 0%,#1e293b 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;}
        .login-box{background:#fff;border-radius:12px;padding:40px;width:100%;max-width:380px;box-shadow:0 25px 50px rgba(0,0,0,.25);}
        .logo{text-align:center;margin-bottom:28px}
        .logo h1{font-size:22px;font-weight:700;color:#1e293b;letter-spacing:-.3px}
        .logo h1 em{color:#0d9488;font-style:normal}
        .logo p{color:#64748b;font-size:13px;margin-top:4px}
        .form-group{margin-bottom:16px}
        label{display:block;font-size:12.5px;font-weight:600;color:#475569;margin-bottom:5px}
        input{width:100%;padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;font-size:14px;color:#1e293b;font-family:inherit;transition:border-color .15s,box-shadow .15s}
        input:focus{outline:none;border-color:#0d9488;box-shadow:0 0 0 3px rgba(13,148,136,.1)}
        .btn{width:100%;padding:10px;background:#0d9488;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:background .15s;margin-top:4px}
        .btn:hover{background:#0f766e}
        .alert{padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:16px;background:#fee2e2;color:#b91c1c;border:1px solid #fecaca}
        .footer-link{text-align:center;margin-top:20px;font-size:12.5px;color:#64748b}
        .footer-link a{color:#0d9488;font-weight:500}
        .demo-box{background:#f0fdfa;border:1px solid #ccfbf1;border-radius:8px;padding:12px;margin-top:16px;font-size:12px;color:#0f766e}
        .demo-box strong{display:block;margin-bottom:4px;color:#0d9488}
    </style>
</head>
<body>
<div class="login-box">
    <div class="logo">
        <h1>Vita<em>Care</em> OS</h1>
        <p>Sistema de Gestão de Ordens de Serviço</p>
    </div>

    @if ($errors->any())
    <div class="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="seu@email.com" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Entrar no sistema</button>
    </form>
    <div class="demo-box">
        <strong>Contas de demonstração (senha: vitacare123)</strong>
        gestor@vitacare.dev — Gestor<br>
        carlos@vitacare.dev — Facilitador<br>
        ana@vitacare.dev — Facilitador
    </div>
    <div class="footer-link">
        <a href="/">Voltar para a página inicial</a>
    </div>
</div>
</body>
</html>
