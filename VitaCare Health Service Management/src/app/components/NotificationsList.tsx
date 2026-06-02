import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { Bell, CheckCheck, Trash2, Info, CheckCircle, AlertCircle, XCircle } from 'lucide-react';

export default function NotificationsList() {
  const { user } = useAuth();
  const { notifications, markNotificationAsRead } = useData();

  const userNotifications = notifications
    .filter(n => n.userId === user?.id)
    .sort((a, b) => new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime());

  const unreadCount = userNotifications.filter(n => !n.read).length;

  const typeIcons = {
    info: <Info className="w-5 h-5 text-blue-500" />,
    success: <CheckCircle className="w-5 h-5 text-green-500" />,
    warning: <AlertCircle className="w-5 h-5 text-yellow-500" />,
    error: <XCircle className="w-5 h-5 text-red-500" />,
  };

  const typeColors = {
    info: 'bg-blue-50 border-blue-200',
    success: 'bg-green-50 border-green-200',
    warning: 'bg-yellow-50 border-yellow-200',
    error: 'bg-red-50 border-red-200',
  };

  return (
    <div className="max-w-4xl mx-auto space-y-6">
      {/* Header */}
      <div className="bg-white rounded-lg shadow p-6">
        <div className="flex items-center justify-between">
          <div className="flex items-center gap-3">
            <Bell className="w-6 h-6 text-gray-700" />
            <div>
              <h2 className="text-xl font-semibold text-gray-900">Notificações</h2>
              <p className="text-sm text-gray-600">
                {unreadCount > 0 ? `${unreadCount} não lida(s)` : 'Todas as notificações lidas'}
              </p>
            </div>
          </div>

          {unreadCount > 0 && (
            <button
              onClick={() => {
                userNotifications.forEach(n => {
                  if (!n.read) markNotificationAsRead(n.id);
                });
              }}
              className="flex items-center gap-2 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
            >
              <CheckCheck className="w-4 h-4" />
              Marcar todas como lidas
            </button>
          )}
        </div>
      </div>

      {/* Notifications List */}
      <div className="space-y-3">
        {userNotifications.length === 0 && (
          <div className="bg-white rounded-lg shadow p-12 text-center">
            <Bell className="w-16 h-16 text-gray-300 mx-auto mb-4" />
            <p className="text-gray-600 font-medium">Nenhuma notificação</p>
            <p className="text-sm text-gray-500 mt-1">
              Você receberá notificações sobre suas ordens de serviço aqui
            </p>
          </div>
        )}

        {userNotifications.map(notification => (
          <div
            key={notification.id}
            className={`
              bg-white rounded-lg shadow border p-5 transition-all
              ${!notification.read ? 'border-l-4 border-l-blue-500' : 'border-gray-200'}
              ${!notification.read ? 'bg-blue-50/30' : ''}
            `}
          >
            <div className="flex gap-4">
              <div className="flex-shrink-0">{typeIcons[notification.type]}</div>

              <div className="flex-1 min-w-0">
                <div className="flex items-start justify-between gap-4 mb-2">
                  <h3 className="font-semibold text-gray-900">{notification.title}</h3>
                  <span className="text-xs text-gray-500 whitespace-nowrap">
                    {new Date(notification.createdAt).toLocaleString('pt-BR', {
                      day: '2-digit',
                      month: '2-digit',
                      hour: '2-digit',
                      minute: '2-digit',
                    })}
                  </span>
                </div>

                <p className="text-gray-700 text-sm">{notification.message}</p>

                {!notification.read && (
                  <button
                    onClick={() => markNotificationAsRead(notification.id)}
                    className="mt-3 text-sm text-blue-600 hover:text-blue-700 font-medium"
                  >
                    Marcar como lida
                  </button>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
