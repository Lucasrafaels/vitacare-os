import { useState, useMemo } from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { ServiceOrder } from '../types';
import { ChevronLeft, ChevronRight, Calendar as CalendarIcon } from 'lucide-react';

interface CalendarViewProps {
  onViewOrder: (order: ServiceOrder) => void;
}

export default function CalendarView({ onViewOrder }: CalendarViewProps) {
  const { user } = useAuth();
  const { serviceOrders } = useData();
  const [currentDate, setCurrentDate] = useState(new Date());

  const daysInMonth = useMemo(() => {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();

    const days: (number | null)[] = [];

    for (let i = 0; i < startingDayOfWeek; i++) {
      days.push(null);
    }

    for (let i = 1; i <= daysInMonth; i++) {
      days.push(i);
    }

    return days;
  }, [currentDate]);

  const ordersInMonth = useMemo(() => {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    let orders = serviceOrders;

    if (user?.role === 'facilitador') {
      orders = orders.filter(os => os.assignedTo === user.id);
    }

    return orders.filter(os => {
      const osDate = new Date(os.scheduledDate);
      return osDate.getFullYear() === year && osDate.getMonth() === month;
    });
  }, [serviceOrders, currentDate, user]);

  const getOrdersForDay = (day: number) => {
    return ordersInMonth.filter(os => {
      const osDate = new Date(os.scheduledDate);
      return osDate.getDate() === day;
    });
  };

  const previousMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() - 1));
  };

  const nextMonth = () => {
    setCurrentDate(new Date(currentDate.getFullYear(), currentDate.getMonth() + 1));
  };

  const today = new Date();
  const isToday = (day: number) => {
    return (
      day === today.getDate() &&
      currentDate.getMonth() === today.getMonth() &&
      currentDate.getFullYear() === today.getFullYear()
    );
  };

  const monthName = currentDate.toLocaleDateString('pt-BR', {
    month: 'long',
    year: 'numeric',
  });

  const statusColors: Record<string, string> = {
    pendente: 'bg-yellow-500',
    em_andamento: 'bg-blue-500',
    concluida: 'bg-green-500',
    cancelada: 'bg-gray-500',
    nao_executada: 'bg-red-500',
  };

  const weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="bg-white rounded-lg shadow p-6">
        <div className="flex items-center justify-between mb-6">
          <h2 className="text-2xl font-bold text-gray-900 capitalize">{monthName}</h2>
          <div className="flex gap-2">
            <button
              onClick={previousMonth}
              className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <ChevronLeft className="w-5 h-5" />
            </button>
            <button
              onClick={() => setCurrentDate(new Date())}
              className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
            >
              Hoje
            </button>
            <button
              onClick={nextMonth}
              className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
            >
              <ChevronRight className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* Calendar Grid */}
        <div className="grid grid-cols-7 gap-2">
          {weekDays.map(day => (
            <div
              key={day}
              className="text-center font-semibold text-gray-600 text-sm py-2"
            >
              {day}
            </div>
          ))}

          {daysInMonth.map((day, index) => {
            if (day === null) {
              return <div key={`empty-${index}`} className="aspect-square" />;
            }

            const dayOrders = getOrdersForDay(day);
            const isCurrentDay = isToday(day);

            return (
              <div
                key={day}
                className={`
                  aspect-square border rounded-lg p-2 hover:bg-gray-50 transition-colors
                  ${isCurrentDay ? 'border-blue-500 bg-blue-50' : 'border-gray-200'}
                `}
              >
                <div className="flex flex-col h-full">
                  <div
                    className={`
                      text-sm font-medium mb-1
                      ${isCurrentDay ? 'text-blue-600' : 'text-gray-900'}
                    `}
                  >
                    {day}
                  </div>
                  <div className="flex-1 flex flex-col gap-1 overflow-hidden">
                    {dayOrders.slice(0, 3).map(order => (
                      <button
                        key={order.id}
                        onClick={() => onViewOrder(order)}
                        className={`
                          ${statusColors[order.status]} text-white
                          text-xs px-1 py-0.5 rounded truncate text-left
                          hover:opacity-80 transition-opacity
                        `}
                        title={order.description}
                      >
                        {order.number}
                      </button>
                    ))}
                    {dayOrders.length > 3 && (
                      <div className="text-xs text-gray-500 text-center">
                        +{dayOrders.length - 3}
                      </div>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Legend */}
      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="font-semibold text-gray-900 mb-4">Legenda</h3>
        <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
          <div className="flex items-center gap-2">
            <div className="w-4 h-4 bg-yellow-500 rounded"></div>
            <span className="text-sm text-gray-700">Pendente</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-4 h-4 bg-blue-500 rounded"></div>
            <span className="text-sm text-gray-700">Em Andamento</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-4 h-4 bg-green-500 rounded"></div>
            <span className="text-sm text-gray-700">Concluída</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-4 h-4 bg-gray-500 rounded"></div>
            <span className="text-sm text-gray-700">Cancelada</span>
          </div>
          <div className="flex items-center gap-2">
            <div className="w-4 h-4 bg-red-500 rounded"></div>
            <span className="text-sm text-gray-700">Não Executada</span>
          </div>
        </div>
      </div>

      {/* Upcoming Orders */}
      <div className="bg-white rounded-lg shadow">
        <div className="px-6 py-4 border-b border-gray-200">
          <h3 className="font-semibold text-gray-900">Próximas Ordens de Serviço</h3>
        </div>
        <div className="p-6 space-y-3">
          {ordersInMonth
            .filter(os => new Date(os.scheduledDate) >= new Date())
            .sort((a, b) => new Date(a.scheduledDate).getTime() - new Date(b.scheduledDate).getTime())
            .slice(0, 5)
            .map(order => (
              <button
                key={order.id}
                onClick={() => onViewOrder(order)}
                className="w-full text-left p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <div className="flex items-start justify-between gap-4">
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-2 mb-1">
                      <CalendarIcon className="w-4 h-4 text-gray-400" />
                      <span className="text-sm text-gray-600">
                        {new Date(order.scheduledDate).toLocaleDateString('pt-BR')}
                      </span>
                    </div>
                    <p className="font-medium text-gray-900">{order.number}</p>
                    <p className="text-sm text-gray-600 truncate">{order.description}</p>
                    <p className="text-sm text-gray-500 mt-1">{order.unit?.name}</p>
                  </div>
                  <div className={`${statusColors[order.status]} w-3 h-3 rounded-full flex-shrink-0 mt-1`}></div>
                </div>
              </button>
            ))}

          {ordersInMonth.filter(os => new Date(os.scheduledDate) >= new Date()).length === 0 && (
            <p className="text-center text-gray-500 py-8">
              Nenhuma ordem de serviço agendada para este mês
            </p>
          )}
        </div>
      </div>
    </div>
  );
}
