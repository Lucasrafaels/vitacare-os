import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { useMemo } from 'react';
import {
  ClipboardList,
  Clock,
  CheckCircle2,
  AlertCircle,
  Users,
  TrendingUp,
} from 'lucide-react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

export default function Dashboard() {
  const { user } = useAuth();
  const { serviceOrders, users } = useData();

  const stats = useMemo(() => {
    const now = new Date();
    const userOrders =
      user?.role === 'facilitador'
        ? serviceOrders.filter(os => os.assignedTo === user.id)
        : serviceOrders;

    const pending = userOrders.filter(os => os.status === 'pendente').length;
    const inProgress = userOrders.filter(os => os.status === 'em_andamento').length;
    const completed = userOrders.filter(os => os.status === 'concluida').length;
    const overdue = userOrders.filter(
      os => os.status !== 'concluida' && new Date(os.scheduledDate) < now
    ).length;

    return {
      total: userOrders.length,
      pending,
      inProgress,
      completed,
      overdue,
    };
  }, [serviceOrders, user]);

  const chartData = useMemo(() => {
    const statusCount = {
      Pendente: stats.pending,
      'Em Andamento': stats.inProgress,
      Concluída: stats.completed,
      Atrasada: stats.overdue,
    };

    return Object.entries(statusCount).map(([name, value]) => ({ name, value }));
  }, [stats]);

  const pieData = useMemo(() => {
    return [
      { name: 'Concluídas', value: stats.completed, color: '#10b981' },
      { name: 'Em Andamento', value: stats.inProgress, color: '#3b82f6' },
      { name: 'Pendentes', value: stats.pending, color: '#f59e0b' },
      { name: 'Atrasadas', value: stats.overdue, color: '#ef4444' },
    ];
  }, [stats]);

  const recentOrders = useMemo(() => {
    const userOrders =
      user?.role === 'facilitador'
        ? serviceOrders.filter(os => os.assignedTo === user.id)
        : serviceOrders;

    return userOrders
      .sort((a, b) => new Date(b.updatedAt).getTime() - new Date(a.updatedAt).getTime())
      .slice(0, 5);
  }, [serviceOrders, user]);

  const statCards = [
    {
      title: 'Total de OS',
      value: stats.total,
      icon: <ClipboardList className="w-6 h-6" />,
      color: 'bg-blue-500',
    },
    {
      title: 'Pendentes',
      value: stats.pending,
      icon: <Clock className="w-6 h-6" />,
      color: 'bg-yellow-500',
    },
    {
      title: 'Em Andamento',
      value: stats.inProgress,
      icon: <TrendingUp className="w-6 h-6" />,
      color: 'bg-blue-500',
    },
    {
      title: 'Concluídas',
      value: stats.completed,
      icon: <CheckCircle2 className="w-6 h-6" />,
      color: 'bg-green-500',
    },
    {
      title: 'Atrasadas',
      value: stats.overdue,
      icon: <AlertCircle className="w-6 h-6" />,
      color: 'bg-red-500',
    },
  ];

  if (user?.role === 'admin' || user?.role === 'coordenador' || user?.role === 'diretor') {
    statCards.push({
      title: 'Facilitadores Ativos',
      value: users.filter(u => u.role === 'facilitador' && u.active).length,
      icon: <Users className="w-6 h-6" />,
      color: 'bg-purple-500',
    });
  }

  const statusColors: Record<string, string> = {
    pendente: 'bg-yellow-100 text-yellow-800',
    em_andamento: 'bg-blue-100 text-blue-800',
    concluida: 'bg-green-100 text-green-800',
    cancelada: 'bg-gray-100 text-gray-800',
    nao_executada: 'bg-red-100 text-red-800',
  };

  const statusLabels: Record<string, string> = {
    pendente: 'Pendente',
    em_andamento: 'Em Andamento',
    concluida: 'Concluída',
    cancelada: 'Cancelada',
    nao_executada: 'Não Executada',
  };

  return (
    <div className="space-y-6">
      {/* Welcome */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900">
          Olá, {user?.name.split(' ')[0]}! 👋
        </h1>
        <p className="text-gray-600 mt-1">
          Aqui está um resumo das suas atividades
        </p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        {statCards.map((stat, index) => (
          <div key={index} className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm text-gray-600">{stat.title}</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">{stat.value}</p>
              </div>
              <div className={`${stat.color} text-white p-3 rounded-lg`}>{stat.icon}</div>
            </div>
          </div>
        ))}
      </div>

      {/* Charts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Bar Chart */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="font-semibold text-gray-900 mb-4">Status das OS</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={chartData}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="name" />
              <YAxis />
              <Tooltip />
              <Bar dataKey="value" fill="#3b82f6" radius={[8, 8, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>

        {/* Pie Chart */}
        <div className="bg-white rounded-lg shadow p-6">
          <h3 className="font-semibold text-gray-900 mb-4">Distribuição</h3>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={pieData}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                outerRadius={100}
                fill="#8884d8"
                dataKey="value"
              >
                {pieData.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Recent Orders */}
      <div className="bg-white rounded-lg shadow">
        <div className="px-6 py-4 border-b border-gray-200">
          <h3 className="font-semibold text-gray-900">Ordens de Serviço Recentes</h3>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Número
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Unidade
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Descrição
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                  Data
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {recentOrders.map(order => (
                <tr key={order.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {order.number}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {order.unit?.name}
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                    {order.description}
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span
                      className={`px-2 py-1 text-xs font-medium rounded-full ${
                        statusColors[order.status]
                      }`}
                    >
                      {statusLabels[order.status]}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    {new Date(order.scheduledDate).toLocaleDateString('pt-BR')}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
