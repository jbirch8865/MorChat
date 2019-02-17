FROM mattrayner/lamp:latest-1604

RUN ["rm /app/*"]

COPY * /app/

CMD ["/run.sh"]