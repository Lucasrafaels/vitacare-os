<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaCare OS · Entrar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#f6f8fb;min-height:100vh;display:flex;color:#0f172a;-webkit-font-smoothing:antialiased}
        .split{display:grid;grid-template-columns:1.05fr 1fr;min-height:100vh;width:100%}
        .brand{position:relative;background:linear-gradient(135deg,#0b1424 0%,#0f766e 65%,#14b8a6 140%);color:#fff;padding:48px 56px;display:flex;flex-direction:column;justify-content:space-between;overflow:hidden}
        .brand::before,.brand::after{content:'';position:absolute;border-radius:50%;filter:blur(70px);pointer-events:none}
        .brand::before{width:360px;height:360px;background:#22d3ee;opacity:.35;top:-90px;right:-90px}
        .brand::after{width:300px;height:300px;background:#5eead4;opacity:.28;bottom:-110px;left:-70px}
        .brand .mark{position:relative;z-index:2;font-family:'Plus Jakarta Sans',sans-serif;font-size:22px;font-weight:800;letter-spacing:-.4px}
        .brand .mark em{background:linear-gradient(90deg,#5eead4,#22d3ee);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:normal}
        .brand h2{position:relative;z-index:2;font-family:'Plus Jakarta Sans',sans-serif;font-size:40px;font-weight:800;line-height:1.08;letter-spacing:-.6px;max-width:480px;margin-top:auto}
        .brand h2 span{background:linear-gradient(90deg,#5eead4,#22d3ee);-webkit-background-clip:text;background-clip:text;color:transparent}
        .brand p.lead{position:relative;z-index:2;margin-top:18px;color:rgba(255,255,255,.78);font-size:15px;max-width:440px;line-height:1.6}
        .brand .meta{position:relative;z-index:2;display:flex;gap:28px;margin-top:36px;font-size:11.5px;color:rgba(255,255,255,.65);text-transform:uppercase;letter-spacing:.1em;font-weight:600}
        .brand .meta div strong{display:block;font-family:'Plus Jakarta Sans',sans-serif;font-size:22px;color:#fff;font-weight:800;letter-spacing:-.4px;margin-bottom:4px;text-transform:none}
        .panel{display:flex;align-items:center;justify-content:center;padding:48px 28px}
        .login-box{background:#fff;border-radius:18px;padding:42px;width:100%;max-width:420px;box-shadow:0 1px 2px rgba(15,23,42,.04),0 30px 60px -20px rgba(15,23,42,.18);border:1px solid #eef2f7}
        .logo{margin-bottom:28px}
        .logo h1{font-family:'Plus Jakarta Sans',sans-serif;font-size:24px;font-weight:800;color:#0f172a;letter-spacing:-.4px}
        .logo h1 em{background:linear-gradient(90deg,#0d9488,#06b6d4);-webkit-background-clip:text;background-clip:text;color:transparent;font-style:normal}
        .logo p{color:#64748b;font-size:14px;margin-top:6px}
        .form-group{margin-bottom:16px}
        label{display:block;font-size:11.5px;font-weight:700;color:#475569;margin-bottom:6px;text-transform:uppercase;letter-spacing:.07em}
        input{width:100%;padding:11px 13px;border:1px solid #d3dce6;border-radius:9px;font-size:14px;color:#0f172a;font-family:inherit;transition:border-color .15s,box-shadow .15s}
        input:hover{border-color:#9fb0c2}
        input:focus{outline:none;border-color:#0d9488;box-shadow:0 0 0 4px rgba(13,148,136,.12)}
        .btn{width:100%;padding:12px;background:linear-gradient(135deg,#0d9488,#14b8a6 60%,#06b6d4);color:#fff;border:none;border-radius:10px;font-size:14.5px;font-weight:700;cursor:pointer;font-family:inherit;letter-spacing:-.01em;box-shadow:0 8px 20px -8px rgba(13,148,136,.55),inset 0 1px 0 rgba(255,255,255,.2);transition:filter .15s,transform .1s;margin-top:6px}
        .btn:hover{filter:brightness(1.05)}
        .btn:active{transform:translateY(1px)}
        .alert{background:#fee2e2;color:#b91c1c;padding:10px 12px;border-radius:8px;font-size:13px;margin-bottom:16px;border:1px solid #fecaca}
        .demo-box{background:#f0fdfa;border:1px solid #cffaf3;border-radius:10px;padding:14px;margin-top:22px;font-size:12.5px;color:#0f766e;line-height:1.7}
        .demo-box strong{display:block;color:#0d9488;margin-bottom:6px;font-size:11.5px;text-transform:uppercase;letter-spacing:.07em}
        .footer-link{text-align:center;margin-top:18px;font-size:12.5px}
        .footer-link a{color:#64748b;text-decoration:none}
        .footer-link a:hover{color:#0d9488}
        @media(max-width:880px){.split{grid-template-columns:1fr}.brand{display:none}}
    </style>
</head>
<body>
<div class="split">
    <aside class="brand">
        <div class="mark">Vita<em>Care</em> OS</div>
        <div>
            <h2>Gestão de OS da <span>atenção primária</span>, ponta a ponta.</h2>
            <p class="lead">Acompanhe o plano de trabalho de cada facilitador, audite o ciclo das visitas e reduza o tempo gasto com papel — em uma única plataforma.</p>
            <div class="meta">
                <div><strong>+12</strong>Unidades</div>
                <div><strong>100%</strong>Rastreável</div>
                <div><strong>0</strong>Papel</div>
            </div>
        </div>
    </aside>
    <div class="panel">
        <div class="login-box">
            <div class="logo">
                <h1>Vita<em>Care</em> OS</h1>
                <p>Entre com sua conta institucional</p>
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
                <strong>Contas de demonstração · senha: vitacare123</strong>
                gestor@vitacare.dev — Gestor<br>
                carlos@vitacare.dev — Facilitador<br>
                ana@vitacare.dev — Facilitador
            </div>
            <div class="footer-link">
                <a href="/">&laquo; Voltar para a página inicial</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
