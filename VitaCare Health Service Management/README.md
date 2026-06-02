# 🏥 VitaCare - Gestão de Ordens de Serviço em Saúde

Sistema completo de gerenciamento de ordens de serviço para unidades de saúde (UBS, CAPS, Hospitais).

## ✨ Funcionalidades Principais

### 🔐 Sistema de Autenticação
- Login com diferentes perfis de usuário
- Controle de acesso baseado em permissões

### 📊 Dashboard Inteligente
- Estatísticas em tempo real
- Gráficos de status e distribuição
- Visualização das OS recentes
- Indicadores customizados por perfil

### 📋 Gestão de Ordens de Serviço
- Criação e edição de OS
- Atribuição para facilitadores
- Acompanhamento de status
- Histórico completo de alterações
- Justificativa de não execução

### 📅 Calendário de Visitas
- Visualização mensal de agendamentos
- Código de cores por status
- Lista de próximas OS
- Navegação intuitiva

### 🔔 Notificações
- Alertas de novas OS
- Notificações de OS atrasadas
- Sistema de leitura/não lidas

### 👥 Perfis de Usuário

1. **Facilitador/Técnico**
   - Visualiza apenas suas OS atribuídas
   - Inicia e finaliza atendimentos
   - Registra observações e fotos
   - Justifica não execuções

2. **Coordenador**
   - Cria e distribui OS
   - Atribui facilitadores
   - Visualiza painel completo
   - Gerencia usuários e unidades

3. **Enfermeiro da UBS**
   - Visualiza OS da sua unidade
   - Acompanha visitas agendadas
   - Acessa comprovantes

4. **Diretor**
   - Dashboard gerencial completo
   - Relatórios e indicadores
   - Visão global do sistema

5. **Administrador**
   - Acesso total ao sistema
   - Gerenciamento de usuários
   - Configurações globais

## 🚀 Como Usar

### Fazer Login

Use uma das contas de demonstração:

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Admin | admin@vitacare.com | 123456 |
| Diretor | diretor@vitacare.com | 123456 |
| Coordenador | coordenador@vitacare.com | 123456 |
| Facilitador | facilitador1@vitacare.com | 123456 |
| Enfermeiro | enfermeiro@vitacare.com | 123456 |

### Navegar pelo Sistema

**Menu Principal:**
- **Dashboard** - Visão geral e estatísticas
- **Ordens de Serviço** - Lista e gestão de OS
- **Calendário** - Agenda de visitas
- **Usuários** - Gerenciamento de usuários (Admin/Coordenador/Diretor)
- **Unidades** - Gestão de unidades de saúde (Admin/Coordenador/Diretor)
- **Relatórios** - Indicadores e relatórios (Admin/Coordenador/Diretor)

### Criar uma Ordem de Serviço

1. Clique em **Ordens de Serviço** no menu
2. Clique no botão **Nova OS**
3. Preencha os campos:
   - Unidade de Saúde
   - Tipo de Serviço
   - Descrição
   - Prioridade
   - Data Prevista
   - Facilitador responsável
4. Clique em **Criar OS**

### Gerenciar uma OS (Facilitador)

1. Acesse **Ordens de Serviço**
2. Veja suas OS atribuídas
3. Clique em **Iniciar** para começar o atendimento
4. Clique em **Finalizar** para completar
5. Adicione observações e informações do atendimento

### Visualizar Estatísticas

- **Dashboard** mostra:
  - Total de OS
  - OS Pendentes, Em Andamento, Concluídas
  - OS Atrasadas
  - Gráficos de distribuição
  - Ordens de serviço recentes

### Usar o Calendário

1. Acesse **Calendário** no menu
2. Visualize as OS agendadas por mês
3. Clique em uma OS no calendário para ver detalhes
4. Use os botões para navegar entre meses
5. Veja a lista de próximas OS abaixo do calendário

## 🎨 Status das OS

- 🟡 **Pendente** - OS criada, aguardando execução
- 🔵 **Em Andamento** - Facilitador iniciou o atendimento
- 🟢 **Concluída** - Atendimento finalizado com sucesso
- 🔴 **Não Executada** - Não foi possível realizar (com justificativa)
- ⚫ **Cancelada** - OS cancelada

## 📝 Tipos de Serviço

- Manutenção de Equipamento
- Instalação
- Treinamento
- Suporte Técnico
- Vistoria
- Outros

## ⚠️ Importante

Este é um sistema de **demonstração** com dados mockados:

- Todos os dados são armazenados localmente no navegador (localStorage)
- Ao recarregar a página, os dados são mantidos
- Para resetar, limpe o localStorage do navegador
- **Não use para dados reais de produção** sem implementar backend real

## 🔧 Tecnologias Utilizadas

- **React 18** - Framework frontend
- **TypeScript** - Tipagem estática
- **Tailwind CSS** - Estilização
- **Recharts** - Gráficos e visualizações
- **Lucide React** - Ícones
- **Sonner** - Notificações toast
- **date-fns** - Manipulação de datas

## 📱 Responsividade

O sistema é totalmente responsivo e funciona em:
- 💻 Desktop
- 📱 Tablets
- 📱 Smartphones

---

**Desenvolvido para demonstração dos requisitos do sistema VitaCare**
