<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaCare OS · Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#f8fafc;color:#1e293b}
        header{background:linear-gradient(135deg,#0f766e,#1e293b);padding:20px 40px;display:flex;align-items:center;justify-content:space-between}
        .logo{color:#fff;font-size:20px;font-weight:700}
        .logo em{color:#5eead4;font-style:normal}
        .header-link{background:rgba(255,255,255,.15);color:#fff;padding:7px 16px;border-radius:8px;font-size:13.5px;font-weight:500;text-decoration:none;transition:background .15s}
        .header-link:hover{background:rgba(255,255,255,.25)}
        .hero{background:linear-gradient(135deg,#0f766e,#1e293b);padding:60px 40px;text-align:center;color:#fff}
        .hero h1{font-size:36px;font-weight:700;letter-spacing:-.5px;margin-bottom:12px}
        .hero h1 em{color:#5eead4;font-style:normal}
        .hero p{font-size:16px;opacity:.8;max-width:520px;margin:0 auto 32px}
        .search-bar{display:flex;gap:0;max-width:520px;margin:0 auto;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.2)}
        .search-bar input{flex:1;padding:14px 18px;border:none;font-size:14px;font-family:inherit;color:#1e293b}
        .search-bar input:focus{outline:none}
        .search-bar button{padding:14px 22px;background:#0d9488;color:#fff;border:none;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit}
        .search-bar button:hover{background:#0f766e}
        .container{max-width:900px;margin:0 auto;padding:40px}
        .section-title{font-size:15px;font-weight:700;color:#1e293b;margin-bottom:16px}
        table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.08)}
        th{padding:10px 16px;text-align:left;font-size:11.5px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.05em;background:#f8fafc;border-bottom:1px solid #e2e8f0}
        td{padding:12px 16px;border-bottom:1px solid #f1f5f9;font-size:13.5px}
        tr:last-child td{border-bottom:none}
        .badge{display:inline-flex;padding:2px 9px;border-radius:20px;font-size:11.5px;font-weight:600}
        .badge-gray{background:#f1f5f9;color:#64748b}
        .badge-amber{background:#fef3c7;color:#92400e}
        .badge-green{background:#dcfce7;color:#15803d}
        .badge-red{background:#fee2e2;color:#b91c1c}
        .empty{text-align:center;padding:40px;color:#94a3b8;font-size:14px}
        .features{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-top:40px}
        .feature{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
        .feature h3{font-size:14px;font-weight:600;color:#1e293b;margin-bottom:6px}
        .feature p{font-size:13px;color:#64748b}
        .feature-icon{width:36px;height:36px;border-radius:8px;background:#f0fdfa;display:flex;align-items:center;justify-content:center;margin-bottom:10px}
        footer{text-align:center;padding:24px;color:#94a3b8;font-size:12px;border-top:1px solid #e2e8f0;background:#fff;margin-top:40px}
    </style>
</head>
<body>
<header>
    <div class="logo">Vita<em>Care</em> OS</div>
    <a href="/login" class="header-link">Entrar no sistema →</a>
</header>

<div class="hero">
    <h1>Gestão de <em>Ordens de Serviço</em></h1>
    <p>Acompanhe visitas e atendimentos das unidades de saúde em tempo real.</p>

    <form method="GET" action="/pesquisa" class="search-bar">
        <input type="text" name="q" placeholder="Busque por código da OS, unidade ou profissional…"
               value="{{ $q ?? '' }}">
        <button type="submit">Pesquisar</button>
    </form>
</div>

<div class="container">
    @if (isset($visitas) && count($visitas))
    <div class="section-title">Resultados para "{{ $q }}"</div>
    <table>
        <thead>
            <tr>
                <th>Código</th><th>Profissional</th><th>Unidade</th><th>Atividade</th><th>Data</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
        @php
            $statusMap = ['nao_iniciado'=>['gray','Não iniciado'],'iniciado'=>['amber','Em andamento'],'concluido'=>['green','Concluído'],'nao_executado'=>['red','Não executado']];
        @endphp
        @foreach ($visitas as $v)
        @php [$cls, $label] = $statusMap[$v['status']] ?? ['gray', $v['status']]; @endphp
        <tr>
            <td><strong>{{ $v['codigo'] }}</strong></td>
            <td>{{ $v['profissional'] }}</td>
            <td>{{ $v['unidade'] }}</td>
            <td>{{ $v['atividade'] }}</td>
            <td>{{ $v['data_agendamento'] ? \Carbon\Carbon::parse($v['data_agendamento'])->format('d/m/Y') : '—' }}</td>
            <td><span class="badge badge-{{ $cls }}">{{ $label }}</span></td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @elseif (isset($q) && $q !== '')
    <div class="empty">Nenhuma ordem de serviço encontrada para "{{ $q }}".</div>
    @else
    <div class="features">
        <div class="feature">
            <div class="feature-icon">📋</div>
            <h3>Acompanhamento em tempo real</h3>
            <p>Visualize o status de atendimentos das unidades de saúde.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">👥</div>
            <h3>Gestão de profissionais</h3>
            <p>Controle de facilitadores e gestores em campo.</p>
        </div>
        <div class="feature">
            <div class="feature-icon">📊</div>
            <h3>Relatórios e métricas</h3>
            <p>Dados sobre produtividade e tempo médio por unidade.</p>
        </div>
    </div>
    @endif
</div>

<footer>VitaCare OS · Sistema de Gestão de Ordens de Serviço · {{ date('Y') }}</footer>
</body>
</html>
