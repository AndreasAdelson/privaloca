FROM alpine:3.10

RUN apk add --update nginx
RUN rm -rf /var/cache/apk/* && rm -rf /tmp/*

COPY nginx.conf /etc/nginx/
COPY symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

RUN adduser -D -g '' -G www-data www-data

RUN chown -R www-data:www-data /var/tmp/nginx

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
