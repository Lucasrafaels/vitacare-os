# Melhorias aplicadas nesta revisão (vitacare_final)

## v4 — Ajustes finais (RF12, RF22, RF23 + Landing institucional)

- **RF12 — Botão "Concluir" condicional:** nova rota `POST /os/{id}/ficha`
  (método `salvarFicha`) salva a ficha de execução sem alterar o status.
  O botão "Concluir atendimento" só é renderizado em `os/show.blade.php`
  quando `tipo_intervencao`, `resolucao` e `contato_local` estão
  preenchidos. Gestor não vê mais o botão "Concluir" (apenas o
  facilitador responsável); rota também bloqueia a tentativa via backend.
- **RF23 — Coluna `senha`:** decisão registrada no `README.md` (Opção B:
  mantém `senha` + `getAuthPassword()`).
- **Landing page institucional:** seção "Como funciona" (3 passos
  Gestor → Facilitador → Gestor), 4 depoimentos baseados nas entrevistas
  (Carlos, Renata, Cleide, Adriano), CTA "Acessar Sistema" reforçado no
  topo (sticky) e ao final, navegação interna por âncoras, layout
  responsivo (`@media max-width: 760px`).

---

Base: `vitacare_completo`. Esta versão lapida o código mantendo 100% dos
requisitos da planilha de avaliação e corrige bugs reais.

## 1. Geração de código da OS (`OrdemServico::gerarCodigo`)
- **Antes:** `orderByDesc('id')` + `explode` — falhava se o último id não
  fosse o de maior sequencial e estava sujeito a *race condition* em
  inserções concorrentes (dois usuários criando OS ao mesmo tempo podiam
  gerar o mesmo código `OS-2026-0001`).
- **Agora:** transação com `lockForUpdate()` calculando o maior
  sequencial real (`MAX`) — atômico e correto sob concorrência.

## 2. Relatório de tempo médio por unidade
- **Antes:** `Carbon::parse($concluido)->diffInMinutes($iniciado)` retorna
  valor **negativo** no Carbon 3 (Laravel 11), distorcendo a média.
- **Agora:** envolvido em `abs(...)` — sempre positivo e correto.

## 3. Validação reforçada no cadastro de OS
- `profissional_id`: exige perfil = `facilitador` **e** status = `ativo`
  (evita atribuir OS a gestores ou contas desativadas).
- `unidade_id`: exige status = `ativa`.
- `atividade_id`: exige status = `ativa`.
- `data_agendamento` e `hora_agendamento`: agora **obrigatórios** com
  formato validado (`HH:MM`).
- Mensagens de erro em português.

## 4. Tela de Agenda (item 10 da planilha — "Deveria")
- Já presente: `/agenda`, agrupando OS dos próximos 7 dias por dia.
- Facilitador vê apenas a própria agenda; gestor vê todas.

## 5. Demais comportamentos preservados
- Ciclo de vida (não iniciado → iniciado → concluído/não executada) com
  timestamps automáticos.
- Motivo obrigatório em "não executada".
- Ficha obrigatória (tipo de intervenção, resolução, contato, obs) para
  concluir — botão Concluir só funciona via formulário preenchido.
- PDF do atendimento (DomPDF).
- Dashboard gestor com cards por profissional do dia.
- Filtros (data, unidade, profissional, status) na lista de OS.
- Relatórios por profissional e tempo médio por unidade.
- CRUD completo de profissionais e unidades.
- Pesquisa pública de visitas na landing page.

## 6. Correções no PDF da OS (v2)
- Nome do arquivo era `OS-OS-2026-0004.pdf` (prefixo duplicado). Agora: `OS-2026-0004.pdf`.
- Botões "Imprimir / Salvar PDF" e "Voltar" apareciam dentro do PDF gerado pelo DomPDF (`@media print` é ignorado pelo DomPDF). Agora são renderizados apenas quando a view é exibida no navegador (`@if (empty($pdfMode))`).
- Removidos emojis (🖨️ ←) que viravam `??` por falta de fonte Unicode no DomPDF.

## v3 — CRUD de Atividades

- Novo `AtividadeController` com index/show/nova/criar/editar/excluir.
- Rotas `/atividades*` adicionadas dentro do grupo `auth` + `gestor`
  (protegidas pelo middleware `EhGestor`).
- Views `atividades/index`, `atividades/form`, `atividades/show` no padrão
  visual das demais telas de gestão.
- Link "Atividades" adicionado na sidebar dentro da seção Gestão (visível
  apenas para gestores).
- Exclusão é lógica (status = inativa) para preservar histórico de OS.

---

## v5 — Reforma visual e verificação final de requisitos (28/06/2026)

### Auditoria dos requisitos da planilha
Conferência item a item (linhas 1 a 19 da grade de avaliação):
- **Stack** (Laravel/PHP, MySQL, MVC): atende.
- **RF1 Landing page com pesquisa**: atende (`PortalController@index/pesquisar`).
- **RF2 Login**: atende.
- **RF3 Perfis Gestor/Facilitador**: atende (`perfil` + `EhGestor` middleware).
- **RF4 Facilitador vê só as próprias OS**: atende (filtro em `OrdemServicoController@index`).
- **RF5 Criar OS com profissional, unidade, atividade, data e observações**: atende.
- **RF6 Ciclo Não iniciado → Iniciado → Concluído/Não executado**: atende.
- **RF7 Registro de data/hora ao iniciar e concluir**: atende (`iniciado_em`, `concluido_em`).
- **RF8 Motivo obrigatório em "Não executada"**: atende.
- **RF9 Duplicar OS**: atende.
- **RF10 Agendamento de OS** (rota `/agenda`): atende.
- **RF11 Ficha do facilitador** (tipo intervenção, resolução, contato local, observações): atende.
- **RF12 Botão "Concluir" só depois de salvar a ficha**: atende (v4: rota `POST /os/{os}/ficha`).
- **RF13 Gerar PDF**: atende (DomPDF).
- **RF14 Dashboard Gestor com cards por profissional**: atende.
- **RF15 Filtros por data, unidade, profissional e status**: atende.
- **RF16 Relatório por profissional no período**: atende.
- **RF17 Relatório de tempo médio por unidade**: atende.
- **RF18 CRUD Profissional**: atende.
- **RF19 CRUD Unidade**: atende.
- **RF20 CRUD Atividade** (extra): atende (v3).

Todos os critérios obrigatórios, "deveria" e "poderia" estão entregues.

### Reforma visual
- **Tipografia**: pareamento Plus Jakarta Sans (display) + Inter (corpo) com letter-spacing negativo para um ar mais editorial.
- **Paleta**: slate mais profundo (#0f172a), gradiente da marca teal→cyan (`--grad-brand`), gradiente da sidebar (`--grad-sidebar`).
- **Sidebar**: fundo em gradiente escuro, separador iluminado sob o logo, item ativo com pill em gradiente e barra lateral cyan, avatar do usuário com gradiente da marca.
- **Topbar**: glassmorphism (blur + saturate), título em Plus Jakarta.
- **Cards**: bordas mais suaves (radius 14px), header com gradiente sutil, hover-lift nos stat cards e prof cards.
- **Stat cards**: barra colorida vertical conforme o tipo, números 30px em Plus Jakarta 800, labels em uppercase.
- **Botões**: primary com gradiente + sombra colorida + inner highlight; estado :active com translateY(1px).
- **Inputs**: borda mais marcada, foco com ring teal 12% opacidade, hover detectável.
- **Tabelas**: header mais respirado, linhas com hover azulado leve.
- **Código da OS** (`.os-codigo`): pill verde-água com fonte JetBrains Mono, parecendo um identificador real do sistema.
- **Login**: layout split-screen com painel da marca em gradiente escuro + auroras, mensagem editorial à esquerda e card de login refinado à direita; em telas <880px o painel some e o card ocupa a tela.

Nenhuma mudança de regra de negócio nesta versão — apenas design system, sidebar, topbar, cards, formulários e tela de login.
