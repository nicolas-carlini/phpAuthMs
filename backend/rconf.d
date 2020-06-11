server {
  server_name localhost;
  listen 80;

  root /public_html;
  index index.php;
  access_log /var/log/nginx/back-access.log;
  error_log /var/log/nginx/back-error.log;
  port_in_redirect on;
  rewrite ^/static/(.*)$ /dist/static/$1 permanent;


  location / {
    try_files $uri $uri/ @lime;
  }

  # pass the PHP scripts to FastCGI server listening on the php-fpm socket
  location @lime {
    # no `.php` in our fancy uri, useless
    fastcgi_split_path_info ^(.+\.php)(/.+)$;

    fastcgi_intercept_errors on;

    # useless as well
    try_files $uri /index.php =404;

    fastcgi_pass 0.0.0.0:9000;
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

    # Yeah, you should use index.php and it will route for you
    fastcgi_param SCRIPT_FILENAME $document_root/index.php;

    fastcgi_param HTTPS off;
  }
}