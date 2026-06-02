import { ServiceOrder } from '../types';
import { X, Calendar, User, Building2, Clock, CheckCircle2 } from 'lucide-react';

interface ServiceOrderDetailsProps {
  order: ServiceOrder;
  onClose: () => void;
}

export default function ServiceOrderDetails({ order, onClose }: ServiceOrderDetailsProps) {
  const statusColors: Record<string, string> = {
    pendente: 'bg-yellow-100 text-yellow-800 border-yellow-200',
    em_andamento: 'bg-blue-100 text-blue-800 border-blue-200',
    concluida: 'bg-green-100 text-green-800 border-green-200',
    cancelada: 'bg-gray-100 text-gray-800 border-gray-200',
    nao_executada: 'bg-red-100 text-red-800 border-red-200',
  };

  const statusLabels: Record<string, string> = {
    pendente: 'Pendente',
    em_andamento: 'Em Andamento',
    concluida: 'Concluída',
    cancelada: 'Cancelada',
    nao_executada: 'Não Executada',
  };

  const priorityColors: Record<string, string> = {
    baixa: 'text-gray-600',
    media: 'text-blue-600',
    alta: 'text-orange-600',
    urgente: 'text-red-600',
  };

  const priorityLabels: Record<string, string> = {
    baixa: 'Baixa',
    media: 'Média',
    alta: 'Alta',
    urgente: 'Urgente',
  };

  const serviceTypeLabels: Record<string, string> = {
    manutencao_equipamento: 'Manutenção de Equipamento',
    instalacao: 'Instalação',
    treinamento: 'Treinamento',
    suporte_tecnico: 'Suporte Técnico',
    vistoria: 'Vistoria',
    outros: 'Outros',
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
          <div>
            <h2 className="text-xl font-semibold text-gray-900">{order.number}</h2>
            <p className="text-sm text-gray-600">{order.unit?.name}</p>
          </div>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <X className="w-6 h-6" />
          </button>
        </div>

        <div className="p-6 space-y-6">
          {/* Status e Prioridade */}
          <div className="flex gap-3">
            <span
              className={`px-3 py-1 text-sm font-medium rounded-full border ${
                statusColors[order.status]
              }`}
            >
              {statusLabels[order.status]}
            </span>
            <span className={`px-3 py-1 text-sm font-medium ${priorityColors[order.priority]}`}>
              Prioridade: {priorityLabels[order.priority]}
            </span>
          </div>

          {/* Informações Principais */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <div className="flex items-start gap-3">
                <Building2 className="w-5 h-5 text-gray-400 mt-0.5" />
                <div>
                  <p className="text-sm text-gray-600">Unidade</p>
                  <p className="font-medium text-gray-900">{order.unit?.name}</p>
                  <p className="text-sm text-gray-600">{order.unit?.address}</p>
                  <p className="text-sm text-gray-600">
                    {order.unit?.city} - {order.unit?.phone}
                  </p>
                </div>
              </div>
            </div>

            <div>
              <div className="flex items-start gap-3">
                <User className="w-5 h-5 text-gray-400 mt-0.5" />
                <div>
                  <p className="text-sm text-gray-600">Atribuído para</p>
                  <p className="font-medium text-gray-900">
                    {order.assignedUser?.name || 'Não atribuído'}
                  </p>
                  {order.assignedUser && (
                    <>
                      <p className="text-sm text-gray-600">{order.assignedUser.email}</p>
                      <p className="text-sm text-gray-600">{order.assignedUser.phone}</p>
                    </>
                  )}
                </div>
              </div>
            </div>
          </div>

          {/* Tipo de Serviço */}
          <div>
            <p className="text-sm text-gray-600 mb-1">Tipo de Serviço</p>
            <p className="font-medium text-gray-900">{serviceTypeLabels[order.serviceType]}</p>
          </div>

          {/* Descrição */}
          <div>
            <p className="text-sm text-gray-600 mb-2">Descrição</p>
            <p className="text-gray-900 bg-gray-50 p-4 rounded-lg">{order.description}</p>
          </div>

          {/* Datas */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <div className="flex items-center gap-2 mb-1">
                <Calendar className="w-4 h-4 text-gray-400" />
                <p className="text-sm text-gray-600">Data Prevista</p>
              </div>
              <p className="font-medium text-gray-900">
                {new Date(order.scheduledDate).toLocaleDateString('pt-BR')}
              </p>
            </div>

            {order.startedAt && (
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <Clock className="w-4 h-4 text-gray-400" />
                  <p className="text-sm text-gray-600">Iniciado em</p>
                </div>
                <p className="font-medium text-gray-900">
                  {new Date(order.startedAt).toLocaleString('pt-BR')}
                </p>
              </div>
            )}

            {order.completedAt && (
              <div>
                <div className="flex items-center gap-2 mb-1">
                  <CheckCircle2 className="w-4 h-4 text-gray-400" />
                  <p className="text-sm text-gray-600">Concluído em</p>
                </div>
                <p className="font-medium text-gray-900">
                  {new Date(order.completedAt).toLocaleString('pt-BR')}
                </p>
              </div>
            )}
          </div>

          {/* Observações */}
          {order.observations && (
            <div>
              <p className="text-sm text-gray-600 mb-2">Observações</p>
              <p className="text-gray-900 bg-gray-50 p-4 rounded-lg">{order.observations}</p>
            </div>
          )}

          {/* Motivo não execução */}
          {order.notExecutedReason && (
            <div>
              <p className="text-sm text-gray-600 mb-2">Motivo da Não Execução</p>
              <p className="text-gray-900 bg-red-50 border border-red-200 p-4 rounded-lg">
                {order.notExecutedReason}
              </p>
            </div>
          )}

          {/* Informações de criação */}
          <div className="pt-4 border-t border-gray-200 text-sm text-gray-600">
            <p>
              Criado por {order.createdByUser?.name} em{' '}
              {new Date(order.createdAt).toLocaleString('pt-BR')}
            </p>
            {order.updatedAt && (
              <p className="mt-1">
                Última atualização: {new Date(order.updatedAt).toLocaleString('pt-BR')}
              </p>
            )}
          </div>
        </div>

        <div className="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4">
          <button
            onClick={onClose}
            className="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium transition-colors"
          >
            Fechar
          </button>
        </div>
      </div>
    </div>
  );
}
