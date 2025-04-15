FROM nginx:alpine

# Копируем файлы проекта в директорию Nginx
COPY . /usr/share/nginx/html

COPY . /usr/share/nginx/html
EXPOSE 80