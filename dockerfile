FROM mattrayner/lamp:latest-1604

CMD ["rm -r /app","mkdir /app"]

COPY * /app/

CMD ["/run.sh"]