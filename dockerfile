FROM mattrayner/lamp:latest-1604
VOLUME . /app/
COPY * /app/

CMD ["/run.sh"]