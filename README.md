# VitaCare OS — Sistema de Gestão de Ordens de Serviço

POC desenvolvida na Oficina ADS · UniVassouras · 2026  
Stack: Laravel 11 · MySQL 8 · PHP 8.3 · Docker

---

## Como rodar

```bash
# 1. Destruir containers e volumes antigos (se houver)
docker compose down -v
docker rm -f vitacare_app vitacare_db vitacare_nginx vitacare_phpmyadmin 2>/dev/null; true

# 2. Build (instala Laravel + DomPDF + copia o código)
docker compose build --no-cache

# 3. Subir
docker compose up -d

# 4. Acessar
# Sistema:    http://localhost:8000
# phpMyAdmin: http://localhost:8080
```

---

## Credenciais de acesso (demo)

| Perfil      | E-mail                   | Senha         |
|-------------|--------------------------|---------------|
| Gestor      | gestor@vitacare.dev      | vitacare123   |
| Facilitador | carlos@vitacare.dev      | vitacare123   |
| Facilitador | ana@vitacare.dev         | vitacare123   |
| Facilitador | marcos@vitacare.dev      | vitacare123   |

---

## Requisitos implementados

### Stack
- Laravel / PHP ✓
- Banco de dados MySQL ✓
- Arquitetura MVC ✓

### POC — Gerenciamento de OS
| # | Requisito | Prioridade | Status |
|---|-----------|-----------|--------|
| 1 | Landing page com pesquisa de visitas | Obrigatório | ✓ |
| 2 | Tela de login para profissionais | Obrigatório | ✓ |
| 3 | Perfis: Gestor e Facilitador | Obrigatório | ✓ |
| 4 | Facilitador vê só as próprias OS | Obrigatório | ✓ |
| 5 | Gestor cria OS com profissional, unidade, atividade, data e observações | Obrigatório | ✓ |
| 6 | Ciclo de vida: não iniciado → iniciado → concluída / não executada | Obrigatório | ✓ |
| 7 | Registro de data e hora ao iniciar e concluir | Obrigatório | ✓ |
| 8 | "Não executada" exige motivo obrigatório | Obrigatório | ✓ |
| 9 | Duplicar OS para reaproveitamento | Deveria | ✓ |
| 10 | Agendamento de OS — visão de agenda futura por dia | Deveria | ✓ |
| 11 | Formulário do facilitador (tipo, resolução, contato, obs) | Obrigatório | ✓ |
| 12 | Botão "Concluir" só aparece após ficha preenchida | Obrigatório | ✓ |
| 13 | Gerar PDF do atendimento para download (DomPDF) | Deveria | ✓ |
| 14 | Dashboard Gestor com cards por profissional | Obrigatório | ✓ |
| 15 | Filtro por data, unidade, profissional e status | Obrigatório | ✓ |
| 16 | Relatório por profissional no período com filtro de data | Deveria | ✓ |
| 17 | Relatório de tempo médio por unidade | Poderia | ✓ |
| 18 | CRUD de profissionais (nome, e-mail, cargo, status) | Obrigatório | ✓ |
| 19 | CRUD de unidades (nome, cidade, endereço, status) | Obrigatório | ✓ |
