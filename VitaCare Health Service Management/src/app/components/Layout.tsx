import { ReactNode, useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import {
  LayoutDashboard,
  ClipboardList,
  Calendar,
  Users,
  Building2,
  BarChart3,
  Bell,
  LogOut,
  Menu,
  X,
  Shield,
} from 'lucide-react';

interface MenuItem {
  icon: ReactNode;
  label: string;
  id: string;
  roles?: string[];
}

interface LayoutProps {
  children: ReactNode;
  currentView: string;
  onViewChange: (view: string) => void;
}

export default function Layout({ children, currentView, onViewChange }: LayoutProps) {
  const { user, logout } = useAuth();
  const { notifications } = useData();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const unreadCount = notifications.filter(n => !n.read && n.userId === user?.id).length;

  const menuItems: MenuItem[] = [
    { icon: <LayoutDashboard className="w-5 h-5" />, label: 'Dashboard', id: 'dashboard' },
    { icon: <ClipboardList className="w-5 h-5" />, label: 'Ordens de Serviço', id: 'orders' },
    { icon: <Calendar className="w-5 h-5" />, label: 'Calendário', id: 'calendar' },
    {
      icon: <Users className="w-5 h-5" />,
      label: 'Usuários',
      id: 'users',
      roles: ['admin', 'coordenador', 'diretor'],
    },
    {
      icon: <Building2 className="w-5 h-5" />,
      label: 'Unidades',
      id: 'units',
      roles: ['admin', 'coordenador', 'diretor'],
    },
    {
      icon: <BarChart3 className="w-5 h-5" />,
      label: 'Relatórios',
      id: 'reports',
      roles: ['admin', 'coordenador', 'diretor'],
    },
  ];

  const filteredMenuItems = menuItems.filter(
    item => !item.roles || item.roles.includes(user?.role || '')
  );

  const roleLabels = {
    admin: 'Administrador',
    diretor: 'Diretor',
    coordenador: 'Coordenador',
    facilitador: 'Facilitador',
    enfermeiro: 'Enfermeiro',
  };

  return (
    <div className="flex h-screen bg-gray-50">
      {/* Sidebar */}
      <aside
        className={`
          fixed md:static inset-y-0 left-0 z-50
          w-64 bg-gradient-to-b from-blue-700 to-indigo-800 text-white
          transform transition-transform duration-300 ease-in-out
          ${sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}
        `}
      >
        <div className="flex flex-col h-full">
          {/* Logo */}
          <div className="p-6 border-b border-blue-600">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-3">
                <Shield className="w-8 h-8" />
                <div>
                  <h1 className="font-bold text-lg">VitaCare</h1>
                  <p className="text-xs text-blue-200">Gestão de OS</p>
                </div>
              </div>
              <button
                onClick={() => setSidebarOpen(false)}
                className="md:hidden text-white hover:bg-blue-600 p-1 rounded"
              >
                <X className="w-5 h-5" />
              </button>
            </div>
          </div>

          {/* User Info */}
          <div className="p-4 border-b border-blue-600 bg-blue-800/30">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center font-bold">
                {user?.name.charAt(0)}
              </div>
              <div className="flex-1 min-w-0">
                <p className="font-medium truncate text-sm">{user?.name}</p>
                <p className="text-xs text-blue-200">{roleLabels[user?.role || 'facilitador']}</p>
              </div>
            </div>
          </div>

          {/* Menu */}
          <nav className="flex-1 overflow-y-auto p-4">
            <ul className="space-y-2">
              {filteredMenuItems.map(item => (
                <li key={item.id}>
                  <button
                    onClick={() => {
                      onViewChange(item.id);
                      setSidebarOpen(false);
                    }}
                    className={`
                      w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      ${
                        currentView === item.id
                          ? 'bg-white text-blue-700 font-medium'
                          : 'text-blue-100 hover:bg-blue-600'
                      }
                    `}
                  >
                    {item.icon}
                    <span>{item.label}</span>
                  </button>
                </li>
              ))}
            </ul>
          </nav>

          {/* Logout */}
          <div className="p-4 border-t border-blue-600">
            <button
              onClick={logout}
              className="w-full flex items-center gap-3 px-4 py-3 text-blue-100 hover:bg-blue-600 rounded-lg transition-colors"
            >
              <LogOut className="w-5 h-5" />
              <span>Sair</span>
            </button>
          </div>
        </div>
      </aside>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Header */}
        <header className="bg-white border-b border-gray-200 px-6 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
              <button
                onClick={() => setSidebarOpen(true)}
                className="md:hidden text-gray-600 hover:text-gray-900"
              >
                <Menu className="w-6 h-6" />
              </button>
              <h2 className="text-xl font-semibold text-gray-800">
                {filteredMenuItems.find(item => item.id === currentView)?.label || 'Dashboard'}
              </h2>
            </div>

            <div className="flex items-center gap-4">
              <button
                onClick={() => onViewChange('notifications')}
                className="relative text-gray-600 hover:text-gray-900 p-2 hover:bg-gray-100 rounded-lg"
              >
                <Bell className="w-6 h-6" />
                {unreadCount > 0 && (
                  <span className="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                    {unreadCount}
                  </span>
                )}
              </button>
            </div>
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 overflow-y-auto p-6">{children}</main>
      </div>

      {/* Overlay for mobile */}
      {sidebarOpen && (
        <div
          onClick={() => setSidebarOpen(false)}
          className="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden"
        />
      )}
    </div>
  );
}
