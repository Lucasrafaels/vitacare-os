import React, { createContext, useContext, useState, useEffect } from 'react';
import { ServiceOrder, HealthUnit, User, Notification } from '../types';
import {
  mockServiceOrders,
  mockHealthUnits,
  mockUsers,
  getServiceOrdersWithRelations,
} from '../data/mockData';

interface DataContextType {
  serviceOrders: ServiceOrder[];
  healthUnits: HealthUnit[];
  users: User[];
  notifications: Notification[];
  updateServiceOrder: (id: string, updates: Partial<ServiceOrder>) => void;
  addServiceOrder: (os: Omit<ServiceOrder, 'id' | 'number' | 'createdAt' | 'updatedAt'>) => void;
  addNotification: (notification: Omit<Notification, 'id' | 'createdAt'>) => void;
  markNotificationAsRead: (id: string) => void;
}

const DataContext = createContext<DataContextType | undefined>(undefined);

export function DataProvider({ children }: { children: React.ReactNode }) {
  const [serviceOrders, setServiceOrders] = useState<ServiceOrder[]>([]);
  const [healthUnits] = useState<HealthUnit[]>(mockHealthUnits);
  const [users] = useState<User[]>(mockUsers);
  const [notifications, setNotifications] = useState<Notification[]>([]);

  useEffect(() => {
    // Carregar dados do localStorage ou usar dados mockados
    const savedOrders = localStorage.getItem('vitacare_orders');
    if (savedOrders) {
      setServiceOrders(JSON.parse(savedOrders));
    } else {
      const ordersWithRelations = getServiceOrdersWithRelations();
      setServiceOrders(ordersWithRelations);
      localStorage.setItem('vitacare_orders', JSON.stringify(ordersWithRelations));
    }

    const savedNotifications = localStorage.getItem('vitacare_notifications');
    if (savedNotifications) {
      setNotifications(JSON.parse(savedNotifications));
    }
  }, []);

  const updateServiceOrder = (id: string, updates: Partial<ServiceOrder>) => {
    setServiceOrders(prev => {
      const updated = prev.map(os =>
        os.id === id
          ? {
              ...os,
              ...updates,
              updatedAt: new Date(),
            }
          : os
      );
      localStorage.setItem('vitacare_orders', JSON.stringify(updated));
      return updated;
    });
  };

  const addServiceOrder = (osData: Omit<ServiceOrder, 'id' | 'number' | 'createdAt' | 'updatedAt'>) => {
    const newOS: ServiceOrder = {
      ...osData,
      id: String(Date.now()),
      number: `OS-2026-${String(serviceOrders.length + 1).padStart(3, '0')}`,
      createdAt: new Date(),
      updatedAt: new Date(),
    };

    setServiceOrders(prev => {
      const updated = [...prev, newOS];
      localStorage.setItem('vitacare_orders', JSON.stringify(updated));
      return updated;
    });
  };

  const addNotification = (notifData: Omit<Notification, 'id' | 'createdAt'>) => {
    const newNotif: Notification = {
      ...notifData,
      id: String(Date.now()),
      createdAt: new Date(),
    };

    setNotifications(prev => {
      const updated = [newNotif, ...prev];
      localStorage.setItem('vitacare_notifications', JSON.stringify(updated));
      return updated;
    });
  };

  const markNotificationAsRead = (id: string) => {
    setNotifications(prev => {
      const updated = prev.map(n => (n.id === id ? { ...n, read: true } : n));
      localStorage.setItem('vitacare_notifications', JSON.stringify(updated));
      return updated;
    });
  };

  return (
    <DataContext.Provider
      value={{
        serviceOrders,
        healthUnits,
        users,
        notifications,
        updateServiceOrder,
        addServiceOrder,
        addNotification,
        markNotificationAsRead,
      }}
    >
      {children}
    </DataContext.Provider>
  );
}

export function useData() {
  const context = useContext(DataContext);
  if (!context) {
    throw new Error('useData must be used within DataProvider');
  }
  return context;
}
