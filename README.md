# Установка
cp .env.dist .env
Создать базу данных mysql, прописать ее данные в .env
Там же прописать данные для facebook

composer install

# Просмотр
php bin/console serve
