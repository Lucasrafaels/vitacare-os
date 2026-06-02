import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { ServiceOrder, OSStatus, OSPriority, ServiceType } from '../types';
import { X, Save } from 'lucide-react';
import { toast } from 'sonner';

interface ServiceOrderFormProps {
  order?: ServiceOrder;
  onClose: () => void;
  onSave: () => void;
}

export default function ServiceOrderForm({ order, onClose, onSave }: ServiceOrderFormProps) {
  const { user } = useAuth();
  const { healthUnits, users, addServiceOrder, updateServiceOrder } = useData();

  const [formData, setFormData] = useState({
    unitId: order?.unitId || '',
    serviceType: order?.serviceType || ('manutencao_equipamento' as ServiceType),
    description: order?.description || '',
    priority: order?.priority || ('media' as OSPriority),
    status: order?.status || ('pendente' as OSStatus),
    assignedTo: order?.assignedTo || '',
    scheduledDate: order?.scheduledDate
      ? new Date(order.scheduledDate).toISOString().split('T')[0]
      : '',
    observations: order?.observations || '',
    notExecutedReason: order?.notExecutedReason || '',
  });

  const facilitators = users.filter(u => u.role === 'facilitador' && u.active);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    if (!formData.unitId || !formData.description || !formData.scheduledDate) {
      toast.error('Preencha todos os campos obrigatórios');
      return;
    }

    if (order) {
      // Editar OS existente
      updateServiceOrder(order.id, {
        ...formData,
        scheduledDate: new Date(formData.scheduledDate),
      });
      toast.success('OS atualizada com sucesso!');
    } else {
      // Criar nova OS
      addServiceOrder({
        ...formData,
        scheduledDate: new Date(formData.scheduledDate),
        createdBy: user!.id,
        status: 'pendente',
      });
      toast.success('OS criada com sucesso!');
    }

    onSave();
  };

  const serviceTypeLabels: Record<ServiceType, string> = {
    manutencao_equipamento: 'Manutenção de Equipamento',
    instalacao: 'Instalação',
    treinamento: 'Treinamento',
    suporte_tecnico: 'Suporte Técnico',
    vistoria: 'Vistoria',
    outros: 'Outros',
  };

  const priorityLabels: Record<OSPriority, string> = {
    baixa: 'Baixa',
    media: 'Média',
    alta: 'Alta',
    urgente: 'Urgente',
  };

  const statusLabels: Record<OSStatus, string> = {
    pendente: 'Pendente',
    em_andamento: 'Em Andamento',
    concluida: 'Concluída',
    cancelada: 'Cancelada',
    nao_executada: 'Não Executada',
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div className="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div className="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
          <h2 className="text-xl font-semibold text-gray-900">
            {order ? 'Editar Ordem de Serviço' : 'Nova Ordem de Serviço'}
          </h2>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 transition-colors"
          >
            <X className="w-6 h-6" />
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-6 space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Unidade de Saúde *
              </label>
              <select
                value={formData.unitId}
                onChange={e => setFormData({ ...formData, unitId: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                required
              >
                <option value="">Selecione uma unidade</option>
                {healthUnits.map(unit => (
                  <option key={unit.id} value={unit.id}>
                    {unit.name} ({unit.type})
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Tipo de Serviço *
              </label>
              <select
                value={formData.serviceType}
                onChange={e => setFormData({ ...formData, serviceType: e.target.value as ServiceType })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                required
              >
                {Object.entries(serviceTypeLabels).map(([value, label]) => (
                  <option key={value} value={value}>
                    {label}
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Prioridade *
              </label>
              <select
                value={formData.priority}
                onChange={e => setFormData({ ...formData, priority: e.target.value as OSPriority })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                required
              >
                {Object.entries(priorityLabels).map(([value, label]) => (
                  <option key={value} value={value}>
                    {label}
                  </option>
                ))}
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Data Prevista *
              </label>
              <input
                type="date"
                value={formData.scheduledDate}
                onChange={e => setFormData({ ...formData, scheduledDate: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                required
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Atribuir para
              </label>
              <select
                value={formData.assignedTo}
                onChange={e => setFormData({ ...formData, assignedTo: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
              >
                <option value="">Não atribuído</option>
                {facilitators.map(facilitator => (
                  <option key={facilitator.id} value={facilitator.id}>
                    {facilitator.name}
                  </option>
                ))}
              </select>
            </div>

            {order && (
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Status
                </label>
                <select
                  value={formData.status}
                  onChange={e => setFormData({ ...formData, status: e.target.value as OSStatus })}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                >
                  {Object.entries(statusLabels).map(([value, label]) => (
                    <option key={value} value={value}>
                      {label}
                    </option>
                  ))}
                </select>
              </div>
            )}

            <div className="md:col-span-2">
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Descrição *
              </label>
              <textarea
                value={formData.description}
                onChange={e => setFormData({ ...formData, description: e.target.value })}
                rows={3}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"
                placeholder="Descreva o serviço a ser realizado..."
                required
              />
            </div>

            {order && (
              <div className="md:col-span-2">
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Observações
                </label>
                <textarea
                  value={formData.observations}
                  onChange={e => setFormData({ ...formData, observations: e.target.value })}
                  rows={2}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"
                  placeholder="Adicione observações sobre o atendimento..."
                />
              </div>
            )}

            {formData.status === 'nao_executada' && (
              <div className="md:col-span-2">
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Motivo da Não Execução *
                </label>
                <textarea
                  value={formData.notExecutedReason}
                  onChange={e =>
                    setFormData({ ...formData, notExecutedReason: e.target.value })
                  }
                  rows={2}
                  className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none resize-none"
                  placeholder="Explique por que a OS não foi executada..."
                  required
                />
              </div>
            )}
          </div>

          <div className="flex gap-3 pt-4 border-t border-gray-200">
            <button
              type="button"
              onClick={onClose}
              className="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Cancelar
            </button>
            <button
              type="submit"
              className="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center justify-center gap-2 transition-colors"
            >
              <Save className="w-5 h-5" />
              {order ? 'Salvar' : 'Criar OS'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
