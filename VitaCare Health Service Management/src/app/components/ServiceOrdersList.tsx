import { useState, useMemo } from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { ServiceOrder, OSStatus } from '../types';
import {
  Plus,
  Search,
  Filter,
  Eye,
  Edit,
  CheckCircle,
  XCircle,
  Play,
  Calendar,
} from 'lucide-react';

interface ServiceOrdersListProps {
  onViewOrder: (order: ServiceOrder) => void;
  onEditOrder: (order: ServiceOrder) => void;
  onCreateOrder: () => void;
}

export default function ServiceOrdersList({
  onViewOrder,
  onEditOrder,
  onCreateOrder,
}: ServiceOrdersListProps) {
  const { user } = useAuth();
  const { serviceOrders, updateServiceOrder } = useData();
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState<OSStatus | 'all'>('all');

  const filteredOrders = useMemo(() => {
    let orders = serviceOrders;

    // Filtrar por usuário se for facilitador
    if (user?.role === 'facilitador') {
      orders = orders.filter(os => os.assignedTo === user.id);
    }

    // Filtro de busca
    if (searchTerm) {
      orders = orders.filter(
        os =>
          os.number.toLowerCase().includes(searchTerm.toLowerCase()) ||
          os.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
          os.unit?.name.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Filtro de status
    if (statusFilter !== 'all') {
      orders = orders.filter(os => os.status === statusFilter);
    }

    return orders.sort(
      (a, b) => new Date(b.scheduledDate).getTime() - new Date(a.scheduledDate).getTime()
    );
  }, [serviceOrders, user, searchTerm, statusFilter]);

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

  const handleQuickAction = (orderId: string, status: OSStatus) => {
    const updates: Partial<ServiceOrder> = { status };

    if (status === 'em_andamento') {
      updates.startedAt = new Date();
    } else if (status === 'concluida') {
      updates.completedAt = new Date();
    }

    updateServiceOrder(orderId, updates);
  };

  const canCreate = ['admin', 'coordenador', 'diretor'].includes(user?.role || '');

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row justify-between gap-4">
        <div className="flex-1 max-w-md">
          <div className="relative">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
            <input
              type="text"
              placeholder="Buscar por número, descrição ou unidade..."
              value={searchTerm}
              onChange={e => setSearchTerm(e.target.value)}
              className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
            />
          </div>
        </div>

        <div className="flex gap-3">
          <div className="relative">
            <Filter className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
            <select
              value={statusFilter}
              onChange={e => setStatusFilter(e.target.value as OSStatus | 'all')}
              className="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none appearance-none bg-white"
            >
              <option value="all">Todos os Status</option>
              <option value="pendente">Pendente</option>
              <option value="em_andamento">Em Andamento</option>
              <option value="concluida">Concluída</option>
              <option value="nao_executada">Não Executada</option>
              <option value="cancelada">Cancelada</option>
            </select>
          </div>

          {canCreate && (
            <button
              onClick={onCreateOrder}
              className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors"
            >
              <Plus className="w-5 h-5" />
              Nova OS
            </button>
          )}
        </div>
      </div>

      {/* Orders Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
        {filteredOrders.map(order => {
          const isOverdue =
            order.status !== 'concluida' && new Date(order.scheduledDate) < new Date();

          return (
            <div
              key={order.id}
              className="bg-white rounded-lg shadow border border-gray-200 hover:shadow-lg transition-shadow"
            >
              <div className="p-4 border-b border-gray-100">
                <div className="flex items-start justify-between mb-2">
                  <div>
                    <h3 className="font-semibold text-gray-900">{order.number}</h3>
                    <p className="text-sm text-gray-600">{order.unit?.name}</p>
                  </div>
                  <span
                    className={`px-2 py-1 text-xs font-medium rounded-full border ${
                      statusColors[order.status]
                    }`}
                  >
                    {statusLabels[order.status]}
                  </span>
                </div>

                <p className="text-sm text-gray-700 line-clamp-2 mb-3">{order.description}</p>

                <div className="flex items-center justify-between text-xs">
                  <div className="flex items-center gap-1 text-gray-500">
                    <Calendar className="w-4 h-4" />
                    <span>{new Date(order.scheduledDate).toLocaleDateString('pt-BR')}</span>
                    {isOverdue && (
                      <span className="ml-1 text-red-600 font-medium">(Atrasada)</span>
                    )}
                  </div>
                  <span className={`font-medium ${priorityColors[order.priority]}`}>
                    {priorityLabels[order.priority]}
                  </span>
                </div>
              </div>

              <div className="p-3 bg-gray-50 flex gap-2">
                <button
                  onClick={() => onViewOrder(order)}
                  className="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded border border-blue-200 transition-colors"
                >
                  <Eye className="w-4 h-4" />
                  Ver
                </button>

                {user?.role === 'facilitador' && order.status === 'pendente' && (
                  <button
                    onClick={() => handleQuickAction(order.id, 'em_andamento')}
                    className="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm text-green-700 hover:bg-green-50 rounded border border-green-200 transition-colors"
                  >
                    <Play className="w-4 h-4" />
                    Iniciar
                  </button>
                )}

                {user?.role === 'facilitador' && order.status === 'em_andamento' && (
                  <button
                    onClick={() => onEditOrder(order)}
                    className="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 rounded border border-blue-200 transition-colors"
                  >
                    <CheckCircle className="w-4 h-4" />
                    Finalizar
                  </button>
                )}

                {canCreate && (
                  <button
                    onClick={() => onEditOrder(order)}
                    className="flex items-center justify-center gap-1 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded border border-gray-200 transition-colors"
                  >
                    <Edit className="w-4 h-4" />
                  </button>
                )}
              </div>
            </div>
          );
        })}
      </div>

      {filteredOrders.length === 0 && (
        <div className="text-center py-12">
          <div className="text-gray-400 mb-2">
            <ClipboardList className="w-16 h-16 mx-auto" />
          </div>
          <p className="text-gray-600 font-medium">Nenhuma ordem de serviço encontrada</p>
          <p className="text-sm text-gray-500 mt-1">
            {searchTerm || statusFilter !== 'all'
              ? 'Tente ajustar os filtros'
              : 'Crie uma nova OS para começar'}
          </p>
        </div>
      )}
    </div>
  );
}
