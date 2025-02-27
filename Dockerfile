# Используем официальный образ Nginx
FROM nginx:alpine

# Копируем файлы проекта в директорию Nginx
COPY . /usr/share/nginx/html

# Копируем файл конфигурации Nginx
COPY . /usr/share/nginx/html

# Указываем, что Nginx будет слушать на 80 порту
EXPOSE 80