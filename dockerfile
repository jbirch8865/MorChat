FROM mattrayner/lamp:latest-1604

# Your custom commands
COPY * /app

CMD ["/run.sh"]