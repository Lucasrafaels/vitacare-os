// Tipos do sistema VitaCare

export type UserRole = 'facilitador' | 'coordenador' | 'enfermeiro' | 'diretor' | 'admin';

export type OSStatus = 'pendente' | 'em_andamento' | 'concluida' | 'cancelada' | 'nao_executada';

export type OSPriority = 'baixa' | 'media' | 'alta' | 'urgente';

export type ServiceType =
  | 'manutencao_equipamento'
  | 'instalacao'
  | 'treinamento'
  | 'suporte_tecnico'
  | 'vistoria'
  | 'outros';

export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
  phone?: string;
  avatar?: string;
  active: boolean;
  createdAt: Date;
}

export interface HealthUnit {
  id: string;
  name: string;
  type: 'UBS' | 'CAPS' | 'Hospital' | 'Clinica';
  address: string;
  city: string;
  phone: string;
  responsible?: string;
  active: boolean;
}

export interface ServiceOrder {
  id: string;
  number: string;
  unitId: string;
  unit?: HealthUnit;
  serviceType: ServiceType;
  description: string;
  status: OSStatus;
  priority: OSPriority;
  assignedTo?: string;
  assignedUser?: User;
  createdBy: string;
  createdByUser?: User;
  scheduledDate: Date;
  startedAt?: Date;
  completedAt?: Date;
  observations?: string;
  photos?: string[];
  notExecutedReason?: string;
  createdAt: Date;
  updatedAt: Date;
}

export interface OSHistoryEntry {
  id: string;
  osId: string;
  userId: string;
  user?: User;
  action: string;
  previousValue?: string;
  newValue?: string;
  timestamp: Date;
}

export interface Notification {
  id: string;
  userId: string;
  title: string;
  message: string;
  type: 'info' | 'success' | 'warning' | 'error';
  read: boolean;
  osId?: string;
  createdAt: Date;
}

export interface DashboardStats {
  totalOS: number;
  pendingOS: number;
  inProgressOS: number;
  completedOS: number;
  overdueOS: number;
  activeUsers: number;
  avgCompletionTime: number;
}
