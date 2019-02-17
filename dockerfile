FROM jbirch8865/lamp

COPY / /app/
RUN ls -la /*

CMD ["/run.sh"]