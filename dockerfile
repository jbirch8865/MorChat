FROM mattrayner/lamp:latest-1604

RUN ["rm -r /app","mkdir /app"]

COPY * /app/

CMD ["/run.sh"]