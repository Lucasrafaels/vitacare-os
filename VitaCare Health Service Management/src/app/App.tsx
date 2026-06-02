import { useState } from 'react';
import { AuthProvider, useAuth } from './context/AuthContext';
import { DataProvider } from './context/DataContext';
import { Toaster } from 'sonner';
import Login from './components/Login';
import Layout from './components/Layout';
import Dashboard from './components/Dashboard';
import ServiceOrdersList from './components/ServiceOrdersList';
import ServiceOrderForm from './components/ServiceOrderForm';
import ServiceOrderDetails from './components/ServiceOrderDetails';
import CalendarView from './components/CalendarView';
import NotificationsList from './components/NotificationsList';
import { ServiceOrder } from './types';

function AppContent() {
  const { isAuthenticated } = useAuth();
  const [currentView, setCurrentView] = useState('dashboard');
  const [showOrderForm, setShowOrderForm] = useState(false);
  const [showOrderDetails, setShowOrderDetails] = useState(false);
  const [selectedOrder, setSelectedOrder] = useState<ServiceOrder | undefined>();

  if (!isAuthenticated) {
    return <Login />;
  }

  const handleViewOrder = (order: ServiceOrder) => {
    setSelectedOrder(order);
    setShowOrderDetails(true);
  };

  const handleEditOrder = (order: ServiceOrder) => {
    setSelectedOrder(order);
    setShowOrderForm(true);
  };

  const handleCreateOrder = () => {
    setSelectedOrder(undefined);
    setShowOrderForm(true);
  };

  const handleCloseForm = () => {
    setShowOrderForm(false);
    setSelectedOrder(undefined);
  };

  const handleCloseDetails = () => {
    setShowOrderDetails(false);
    setSelectedOrder(undefined);
  };

  const handleSaveOrder = () => {
    setShowOrderForm(false);
    setSelectedOrder(undefined);
  };

  const renderView = () => {
    switch (currentView) {
      case 'dashboard':
        return <Dashboard />;
      case 'orders':
        return (
          <ServiceOrdersList
            onViewOrder={handleViewOrder}
            onEditOrder={handleEditOrder}
            onCreateOrder={handleCreateOrder}
          />
        );
      case 'calendar':
        return <CalendarView onViewOrder={handleViewOrder} />;
      case 'notifications':
        return <NotificationsList />;
      case 'users':
        return (
          <div className="bg-white rounded-lg shadow p-8 text-center">
            <h2 className="text-xl font-semibold text-gray-900 mb-2">
              Gestão de Usuários
            </h2>
            <p className="text-gray-600">
              Esta funcionalidade estará disponível em breve
            </p>
          </div>
        );
      case 'units':
        return (
          <div className="bg-white rounded-lg shadow p-8 text-center">
            <h2 className="text-xl font-semibold text-gray-900 mb-2">
              Gestão de Unidades de Saúde
            </h2>
            <p className="text-gray-600">
              Esta funcionalidade estará disponível em breve
            </p>
          </div>
        );
      case 'reports':
        return (
          <div className="bg-white rounded-lg shadow p-8 text-center">
            <h2 className="text-xl font-semibold text-gray-900 mb-2">
              Relatórios
            </h2>
            <p className="text-gray-600">
              Esta funcionalidade estará disponível em breve
            </p>
          </div>
        );
      default:
        return <Dashboard />;
    }
  };

  return (
    <>
      <Layout currentView={currentView} onViewChange={setCurrentView}>
        {renderView()}
      </Layout>

      {showOrderForm && (
        <ServiceOrderForm
          order={selectedOrder}
          onClose={handleCloseForm}
          onSave={handleSaveOrder}
        />
      )}

      {showOrderDetails && selectedOrder && (
        <ServiceOrderDetails order={selectedOrder} onClose={handleCloseDetails} />
      )}

      <Toaster position="top-right" richColors />
    </>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <DataProvider>
        <AppContent />
      </DataProvider>
    </AuthProvider>
  );
}
