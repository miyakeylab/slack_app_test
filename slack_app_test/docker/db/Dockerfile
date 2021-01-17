FROM mysql:5.7

RUN usermod -u 1000 mysql
RUN chmod 777 /var/lib/mysql
RUN chown -R mysql:mysql /var/lib/mysql && chown -R mysql:mysql /var/log/mysql

COPY my.cnf /etc/mysql/conf.d/
RUN ln -sf /usr/share/zoneinfo/UTC /etc/localtime

COPY init.d /docker-entrypoint-initdb.d

CMD ["mysqld"]

