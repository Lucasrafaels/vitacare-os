#!/bin/sh
set -e

# O volume nomeado 'vitacare_app_code' é montado em /var/www/html para que o
# container nginx também tenha acesso ao diretório public/. Na primeira
# inicialização esse volume está vazio, então copiamos o conteúdo gerado
# no build (preservado em /opt/app-build) para dentro dele.
if [ ! -f /var/www/html/artisan ]; then
    echo "Volume de aplicação vazio. Copiando código da imagem..."
    cp -a /opt/app-build/. /var/www/html/
fi

cd /var/www/html

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "VitaCare OS — aguardando o banco de dados em $DB_HOST:$DB_PORT..."

until php -r "
    try {
        new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
"; do
    echo "Banco ainda não está pronto, tentando novamente em 2s..."
    sleep 2
done

echo "Banco disponível. Rodando migrations..."
php artisan migrate --force

# Roda os seeders apenas se a tabela de profissionais estiver vazia,
# para não duplicar dados em reinicializações do container.
PROF_COUNT=$(php artisan tinker --execute="echo App\Models\Profissional::count();" 2>/dev/null | tail -n 1)

if [ "$PROF_COUNT" = "0" ]; then
    echo "Banco vazio. Rodando seeders com dados de demonstração..."
    php artisan db:seed --force
else
    echo "Dados já existem, seeders não serão executados novamente."
fi

php artisan config:clear

exec "$@"
