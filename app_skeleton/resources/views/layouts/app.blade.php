<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VitaCare OS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal:   #0d9488;
            --teal-d: #0f766e;
            --teal-l: #ccfbf1;
            --slate:  #1e293b;
            --slate-m:#475569;
            --slate-l:#94a3b8;
            --bg:     #f8fafc;
            --white:  #ffffff;
            --border: #e2e8f0;
            --red:    #ef4444;
            --red-l:  #fee2e2;
            --amber:  #f59e0b;
            --amber-l:#fef3c7;
            --green:  #22c55e;
            --green-l:#dcfce7;
            --blue:   #3b82f6;
            --blue-l: #dbeafe;
            --gray:   #64748b;
            --gray-l: #f1f5f9;
            --radius: 8px;
            --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--slate); font-size: 14px; line-height: 1.5; }
        a { color: var(--teal); text-decoration: none; }
        a:hover { color: var(--teal-d); }

        .app-layout { display: flex; min-height: 100vh; }
        .sidebar {
            width: 240px; flex-shrink: 0; background: var(--slate);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; height: 100vh; z-index: 100;
        }
        .sidebar-logo { padding: 20px 20px 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar-logo span { font-size: 18px; font-weight: 700; color: #fff; letter-spacing: -.3px; }
        .sidebar-logo span em { color: var(--teal); font-style: normal; }
        .sidebar-logo small { display: block; color: var(--slate-l); font-size: 11px; margin-top: 2px; }
        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .sidebar-section { padding: 8px 16px 4px; font-size: 10px; font-weight: 600; color: var(--slate-l); letter-spacing: .08em; text-transform: uppercase; }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 16px; color: #cbd5e1; font-size: 13.5px; font-weight: 500;
            transition: background .15s, color .15s;
        }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(255,255,255,.08); color: #fff; }
        .sidebar-link.active { border-left: 3px solid var(--teal); }
        .sidebar-link svg { width: 16px; height: 16px; flex-shrink: 0; }
        .sidebar-user { padding: 14px 16px; border-top: 1px solid rgba(255,255,255,.1); display: flex; align-items: center; gap: 10px; }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%; background: var(--teal);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0;
        }
        .user-info small { display: block; font-size: 11px; color: var(--slate-l); }
        .user-info span { font-size: 13px; color: #fff; font-weight: 500; }
        .user-logout { margin-left: auto; color: var(--slate-l); font-size: 11px; background: none; border: none; cursor: pointer; font-family: inherit; }
        .user-logout:hover { color: #fff; }

        .main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            height: 56px; background: var(--white); border-bottom: 1px solid var(--border);
            display: flex; align-items: center; padding: 0 24px; gap: 16px; position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-weight: 600; font-size: 15px; color: var(--slate); }
        .topbar-actions { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .page-body { padding: 24px; flex: 1; }

        .btn {
            display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px;
            border-radius: var(--radius); font-size: 13.5px; font-weight: 500; cursor: pointer;
            border: none; transition: background .15s, box-shadow .15s; white-space: nowrap;
        }
        .btn svg { width: 14px; height: 14px; }
        .btn-primary { background: var(--teal); color: #fff; }
        .btn-primary:hover { background: var(--teal-d); color: #fff; }
        .btn-outline { background: var(--white); color: var(--slate); border: 1px solid var(--border); }
        .btn-outline:hover { background: var(--gray-l); }
        .btn-danger { background: var(--red); color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-sm { padding: 5px 10px; font-size: 12.5px; }
        .btn-ghost { background: transparent; color: var(--slate-m); border: 1px solid transparent; }
        .btn-ghost:hover { background: var(--gray-l); }

        .card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow); }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-weight: 600; font-size: 14px; color: var(--slate); }
        .card-body { padding: 20px; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px 20px; box-shadow: var(--shadow); }
        .stat-value { font-size: 26px; font-weight: 700; color: var(--slate); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--slate-m); margin-top: 4px; }
        .stat-card.teal .stat-value { color: var(--teal); }
        .stat-card.green .stat-value { color: #16a34a; }
        .stat-card.amber .stat-value { color: #d97706; }
        .stat-card.red .stat-value { color: var(--red); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th { padding: 10px 14px; text-align: left; font-size: 11.5px; font-weight: 600; color: var(--slate-m); text-transform: uppercase; letter-spacing: .05em; background: var(--gray-l); border-bottom: 1px solid var(--border); }
        tbody td { padding: 11px 14px; border-bottom: 1px solid var(--border); font-size: 13.5px; color: var(--slate); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 11.5px; font-weight: 600; white-space: nowrap; }
        .badge-gray    { background: var(--gray-l); color: var(--gray); }
        .badge-amber   { background: var(--amber-l); color: #92400e; }
        .badge-green   { background: var(--green-l); color: #15803d; }
        .badge-red     { background: var(--red-l); color: #b91c1c; }
        .badge-blue    { background: var(--blue-l); color: #1d4ed8; }
        .badge-teal    { background: var(--teal-l); color: var(--teal-d); }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 12.5px; font-weight: 600; color: var(--slate-m); }
        label .req { color: var(--red); margin-left: 2px; }
        input, select, textarea {
            padding: 8px 11px; border: 1px solid var(--border); border-radius: var(--radius);
            font-size: 13.5px; color: var(--slate); background: var(--white); font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,148,136,.1); }
        textarea { resize: vertical; min-height: 80px; }

        .alert { padding: 10px 14px; border-radius: var(--radius); font-size: 13.5px; margin-bottom: 16px; display: flex; align-items: flex-start; gap: 8px; }
        .alert-sucesso { background: var(--green-l); color: #15803d; border: 1px solid #bbf7d0; }
        .alert-erro    { background: var(--red-l);   color: #b91c1c; border: 1px solid #fecaca; }
        .alert-info    { background: var(--blue-l);  color: #1d4ed8; border: 1px solid #bfdbfe; }

        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; }
        .gap-3 { gap: 12px; }
        .mt-4 { margin-top: 16px; }
        .mb-4 { margin-bottom: 16px; }
        .text-sm { font-size: 12.5px; }
        .text-muted { color: var(--slate-m); }
        .font-semibold { font-weight: 600; }
        .empty-state { text-align: center; padding: 48px 24px; color: var(--slate-m); }
        .empty-state svg { width: 40px; height: 40px; margin: 0 auto 12px; opacity: .4; }
        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        .action-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 20px; }

        .os-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; }
        .os-card + .os-card { margin-top: 12px; }
        .os-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .os-codigo { font-weight: 700; font-size: 13px; color: var(--teal-d); font-family: monospace; }

        .prof-cards { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
        .prof-card { background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; box-shadow: var(--shadow); }
        .prof-card-header { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
        .prof-card-avatar { width: 36px; height: 36px; border-radius: 50%; background: var(--teal-l); display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--teal-d); font-size: 14px; }
        .prof-mini-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; }
        .prof-mini-stat { background: var(--gray-l); border-radius: 6px; padding: 6px 4px; text-align: center; }
        .prof-mini-stat .n { font-size: 18px; font-weight: 700; display: block; }
        .prof-mini-stat .l { font-size: 10px; color: var(--slate-m); }

        .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; background: var(--white); border: 1px solid var(--border); border-radius: var(--radius); padding: 14px 16px; margin-bottom: 20px; }
        .filter-bar .form-group { flex: 1; min-width: 140px; }
        .filter-bar label { font-size: 11.5px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .form-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="app-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <span>Vita<em>Care</em> OS</span>
            <small>Sistema de Ordens de Serviço</small>
        </div>
        <nav class="sidebar-nav">
            <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="/os" class="sidebar-link {{ request()->is('os') || request()->is('os/*') && !request()->is('agenda') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Ordens de Serviço
            </a>
            <a href="/agenda" class="sidebar-link {{ request()->is('agenda') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Agenda
            </a>
            @if (auth()->user()->ehGestor())
            <div class="sidebar-section">Gestão</div>
            <a href="/profissionais" class="sidebar-link {{ request()->is('profissionais*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                Profissionais
            </a>
            <a href="/unidades" class="sidebar-link {{ request()->is('unidades*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Unidades de Saúde
            </a>
            <a href="/atividades" class="sidebar-link {{ request()->is('atividades*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
                Atividades
            </a>
            <div class="sidebar-section">Relatórios</div>
            <a href="/relatorios/os-profissional" class="sidebar-link {{ request()->is('relatorios*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Relatórios
            </a>
            @endif
        </nav>
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nome, 0, 1)) }}</div>
            <div class="user-info">
                <span>{{ explode(' ', auth()->user()->nome)[0] }}</span>
                <small>{{ auth()->user()->ehGestor() ? 'Gestor' : 'Facilitador' }}</small>
            </div>
            <form method="POST" action="/logout" style="margin-left:auto">
                @csrf
                <button type="submit" class="user-logout" title="Sair">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        @if (session('flash'))
        <div style="padding: 0 24px; padding-top: 16px;">
            <div class="alert alert-{{ session('flash')['tipo'] }}">
                {{ session('flash')['msg'] }}
            </div>
        </div>
        @endif

        @yield('content')
    </div>
</div>
</body>
</html>
