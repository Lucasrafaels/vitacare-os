# Melhorias aplicadas nesta revisão (vitacare_final)

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
