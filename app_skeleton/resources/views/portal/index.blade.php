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
        header{background:linear-gradient(135deg,#0f766e,#1e293b);padding:18px 40px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:50;box-shadow:0 2px 8px rgba(15,118,110,.18)}
        .logo{color:#fff;font-size:20px;font-weight:700}
        .logo em{color:#5eead4;font-style:normal}
        .header-nav{display:flex;align-items:center;gap:22px}
        .header-nav a.nav-link{color:rgba(255,255,255,.85);font-size:13.5px;font-weight:500;text-decoration:none}
        .header-nav a.nav-link:hover{color:#5eead4}
        .header-link{background:#5eead4;color:#0f3a36;padding:10px 20px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;transition:transform .15s,box-shadow .15s;box-shadow:0 4px 14px rgba(94,234,212,.35)}
        .header-link:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(94,234,212,.5)}
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
        /* Como funciona */
        .section{padding:60px 40px;background:#fff}
        .section.alt{background:#f1f5f9}
        .section-wrap{max-width:1040px;margin:0 auto}
        .section h2{font-size:26px;font-weight:700;color:#0f172a;text-align:center;margin-bottom:8px;letter-spacing:-.3px}
        .section .lead{text-align:center;color:#64748b;font-size:14.5px;margin-bottom:40px;max-width:560px;margin-left:auto;margin-right:auto}
        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:22px}
        .step{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:26px;position:relative;box-shadow:0 4px 14px rgba(15,23,42,.04)}
        .step-num{position:absolute;top:-14px;left:22px;width:32px;height:32px;border-radius:50%;background:#0d9488;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;box-shadow:0 4px 10px rgba(13,148,136,.35)}
        .step h3{font-size:15px;font-weight:700;color:#0f172a;margin:8px 0 8px}
        .step p{font-size:13.5px;color:#475569;line-height:1.55}
        .step .who{display:inline-block;margin-top:12px;font-size:11.5px;font-weight:600;color:#0f766e;background:#ccfbf1;padding:3px 10px;border-radius:20px;text-transform:uppercase;letter-spacing:.04em}
        /* Depoimentos */
        .testimonials{display:grid;grid-template-columns:repeat(2,1fr);gap:22px}
        .quote{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;box-shadow:0 4px 14px rgba(15,23,42,.04);display:flex;flex-direction:column}
        .quote .mark{color:#5eead4;font-size:42px;font-family:Georgia,serif;line-height:1;margin-bottom:-6px}
        .quote p{font-size:14px;color:#334155;line-height:1.6;font-style:italic;flex:1}
        .quote .author{display:flex;align-items:center;gap:12px;margin-top:18px;padding-top:16px;border-top:1px solid #f1f5f9}
        .avatar{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#0d9488,#0f766e);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;flex-shrink:0}
        .author-info strong{display:block;font-size:13.5px;color:#0f172a;font-weight:600}
        .author-info span{display:block;font-size:12px;color:#64748b;margin-top:1px}
        .cta-final{background:linear-gradient(135deg,#0f766e,#1e293b);padding:50px 40px;text-align:center;color:#fff}
        .cta-final h2{color:#fff;font-size:24px;margin-bottom:10px}
        .cta-final p{opacity:.85;margin-bottom:22px;font-size:14.5px}
        .cta-btn{display:inline-block;background:#5eead4;color:#0f3a36;padding:13px 28px;border-radius:8px;font-size:15px;font-weight:700;text-decoration:none;box-shadow:0 6px 18px rgba(94,234,212,.4);transition:transform .15s}
        .cta-btn:hover{transform:translateY(-2px)}
        @media (max-width:760px){
            header{padding:14px 18px}
            .header-nav{gap:10px}
            .header-nav a.nav-link{display:none}
            .hero{padding:44px 20px}
            .hero h1{font-size:26px}
            .section{padding:40px 18px}
            .steps,.testimonials,.features{grid-template-columns:1fr}
        }
        footer{text-align:center;padding:24px;color:#94a3b8;font-size:12px;border-top:1px solid #e2e8f0;background:#fff;margin-top:40px}
    </style>
</head>
<body>
<header>
    <div class="logo">Vita<em>Care</em> OS</div>
    <nav class="header-nav">
        <a href="#como-funciona" class="nav-link">Como funciona</a>
        <a href="#depoimentos" class="nav-link">Depoimentos</a>
        <a href="/login" class="header-link">Acessar Sistema →</a>
    </nav>
</header>

<div class="hero">
    <h1>Gestão de <em>Ordens de Serviço</em> da Atenção Primária</h1>
    <p>Plataforma que conecta gestores e facilitadores das unidades de saúde para planejar, executar e auditar visitas, ações educativas e atendimentos &mdash; com rastreabilidade ponta a ponta.</p>

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

@if (!isset($visitas) || !count($visitas))
{{-- Seção "Como funciona" --}}
<section class="section" id="como-funciona">
    <div class="section-wrap">
        <h2>Como funciona</h2>
        <p class="lead">Três passos simples que organizam todo o ciclo de uma visita técnica nas UBSs.</p>
        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <h3>Gestor cria a OS</h3>
                <p>Define profissional responsável, unidade, atividade, data e observações da visita. Tudo em um único formulário, com validações que evitam retrabalho.</p>
                <span class="who">Gestor</span>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <h3>Facilitador executa</h3>
                <p>No próprio celular ou desktop, inicia o atendimento, preenche a ficha de execução (tipo, resolução, contato) e finaliza &mdash; ou registra o motivo da não execução.</p>
                <span class="who">Facilitador</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <h3>Gestor acompanha</h3>
                <p>Dashboard, filtros e relatórios mostram o que foi feito, por quem e quanto tempo levou. PDF da OS pronto para auditoria a qualquer momento.</p>
                <span class="who">Gestor</span>
            </div>
        </div>
    </div>
</section>

{{-- Depoimentos baseados nas entrevistas --}}
<section class="section alt" id="depoimentos">
    <div class="section-wrap">
        <h2>O que dizem quem usa</h2>
        <p class="lead">Depoimentos inspirados nas entrevistas com gestores e facilitadores da rede de atenção primária.</p>
        <div class="testimonials">
            <div class="quote">
                <div class="mark">&ldquo;</div>
                <p>Antes eu perdia metade do dia ligando para saber se a equipe tinha conseguido entrar na casa do paciente. Agora abro o sistema, vejo o status de cada visita e já sei onde precisamos reforçar.</p>
                <div class="author">
                    <div class="avatar">CA</div>
                    <div class="author-info">
                        <strong>Carlos Andrade</strong>
                        <span>Coordenador da Atenção Primária</span>
                    </div>
                </div>
            </div>
            <div class="quote">
                <div class="mark">&ldquo;</div>
                <p>O relatório de tempo médio por unidade mudou nossa conversa com a secretaria. Saímos do "acho que está demorando" para um dado concreto que ajuda a redistribuir as equipes.</p>
                <div class="author">
                    <div class="avatar">RM</div>
                    <div class="author-info">
                        <strong>Renata Moura</strong>
                        <span>Gestora de Unidade de Saúde</span>
                    </div>
                </div>
            </div>
            <div class="quote">
                <div class="mark">&ldquo;</div>
                <p>O que eu mais gosto é a ficha de execução. Preencho na hora, com o paciente ainda na minha frente, e quando volto pra UBS já está tudo registrado &mdash; sem papel, sem caderno, sem retrabalho.</p>
                <div class="author">
                    <div class="avatar">CR</div>
                    <div class="author-info">
                        <strong>Cleide Ramos</strong>
                        <span>Agente Comunitária de Saúde</span>
                    </div>
                </div>
            </div>
            <div class="quote">
                <div class="mark">&ldquo;</div>
                <p>Quando uma visita não dá pra ser feita, o sistema obriga a registrar o motivo. Isso é ouro pra auditoria e me protege também: fica claro que o problema não foi a equipe.</p>
                <div class="author">
                    <div class="avatar">AS</div>
                    <div class="author-info">
                        <strong>Adriano Souza</strong>
                        <span>Facilitador / Enfermeiro</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-final">
    <h2>Pronto para organizar as visitas da sua equipe?</h2>
    <p>Acesse a plataforma com o seu perfil de gestor ou facilitador.</p>
    <a href="/login" class="cta-btn">Acessar Sistema →</a>
</section>
@endif

<footer>VitaCare OS · Sistema de Gestão de Ordens de Serviço · {{ date('Y') }}</footer>
</body>
</html>
