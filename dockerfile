FROM mattrayner/lamp:latest-1604

RUN ["rm -r /app"]

COPY * /app/

CMD ["mkdir /app","/run.sh"]