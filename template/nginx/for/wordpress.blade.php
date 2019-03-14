server {

	server_name {{$server_name or 'example.com'}} {{$other_names}};
	root {{$root}}/{{$server_name or 'example.com'}}/{{$subfolder}};
	index index.php;

	gzip on;
	gzip_disable "msie6";
	gzip_types text/plain text/css application/json application/x-javascript text/xml application/xml application/xml+rss text/javascript application/javascript;

	location ~ /\. {
		deny all;
	}

	location ~* /(?:uploads|files)/.*\.php$ {
		deny all;
	}

	location ~* ^.+\.(ogg|ogv|svg|svgz|eot|otf|woff|mp4|ttf|rss|atom|jpg|jpeg|gif|png|ico|zip|tgz|gz|rar|bz2|doc|xls|exe|ppt|tar|mid|midi|wav|bmp|rtf)$ {
		access_log off;
		log_not_found off;
		expires max;
	}

	location / {
		try_files $uri $uri/ /index.php?$args;
	}

	location ~ \.php$ {
		fastcgi_pass {{$fastcgi_pass}};
		fastcgi_index index.php;
		include fastcgi_params;
	}

	client_max_body_size 32m;

}
