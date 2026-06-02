import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { LogIn, Shield } from 'lucide-react';

export default function Login() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const { login } = useAuth();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setError('');

    if (!email || !password) {
      setError('Preencha todos os campos');
      return;
    }

    const success = login(email, password);
    if (!success) {
      setError('Credenciais inválidas');
    }
  };

  const demoAccounts = [
    { role: 'Admin', email: 'admin@vitacare.com' },
    { role: 'Diretor', email: 'diretor@vitacare.com' },
    { role: 'Coordenador', email: 'coordenador@vitacare.com' },
    { role: 'Facilitador', email: 'facilitador1@vitacare.com' },
    { role: 'Enfermeiro', email: 'enfermeiro@vitacare.com' },
  ];

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
      <div className="bg-white rounded-lg shadow-xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row">
        {/* Painel esquerdo - Informações */}
        <div className="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 md:w-1/2 text-white">
          <div className="flex items-center gap-3 mb-8">
            <Shield className="w-10 h-10" />
            <div>
              <h1 className="text-2xl font-bold">VitaCare</h1>
              <p className="text-blue-100 text-sm">Gestão de Ordens de Serviço em Saúde</p>
            </div>
          </div>

          <div className="space-y-4">
            <div>
              <h3 className="font-semibold mb-2">Funcionalidades</h3>
              <ul className="space-y-2 text-sm text-blue-100">
                <li>✓ Gestão completa de Ordens de Serviço</li>
                <li>✓ Controle de atendimentos em campo</li>
                <li>✓ Dashboard gerencial em tempo real</li>
                <li>✓ Notificações automáticas</li>
                <li>✓ Relatórios e indicadores</li>
                <li>✓ Múltiplos perfis de acesso</li>
              </ul>
            </div>

            <div className="pt-4 border-t border-blue-400">
              <h3 className="font-semibold mb-2">Contas Demo</h3>
              <div className="space-y-1 text-xs text-blue-100">
                {demoAccounts.map((acc, idx) => (
                  <div key={idx}>
                    <span className="font-medium">{acc.role}:</span> {acc.email}
                  </div>
                ))}
                <div className="mt-2 text-yellow-200">Senha para todas: 123456</div>
              </div>
            </div>
          </div>
        </div>

        {/* Painel direito - Login */}
        <div className="p-8 md:w-1/2">
          <h2 className="text-2xl font-bold text-gray-800 mb-6">Entrar no Sistema</h2>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                E-mail
              </label>
              <input
                type="email"
                value={email}
                onChange={e => setEmail(e.target.value)}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                placeholder="seu@email.com"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Senha
              </label>
              <input
                type="password"
                value={password}
                onChange={e => setPassword(e.target.value)}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                placeholder="••••••"
              />
            </div>

            {error && (
              <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {error}
              </div>
            )}

            <button
              type="submit"
              className="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors flex items-center justify-center gap-2"
            >
              <LogIn className="w-5 h-5" />
              Entrar
            </button>
          </form>

          <div className="mt-6 text-center text-sm text-gray-500">
            <p>Sistema de demonstração</p>
            <p className="text-xs mt-1">Use uma das contas demo ao lado</p>
          </div>
        </div>
      </div>
    </div>
  );
}
