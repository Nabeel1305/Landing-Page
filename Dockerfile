# PakaPay marketing site — static Nginx image
#
# The site is plain HTML/CSS/JS with no build step, so this is a single-stage
# image: drop the files into Nginx's web root and serve them.

FROM nginx:1.27-alpine

# Strip the default Nginx welcome page and default vhost config
RUN rm -rf /usr/share/nginx/html/* /etc/nginx/conf.d/default.conf

# Custom server block: maps "/" to the homepage and wires up the real 404 page
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Site pages
COPY 404.html        /usr/share/nginx/html/404.html
COPY about.html       /usr/share/nginx/html/about.html
COPY business.html    /usr/share/nginx/html/business.html
COPY compliance.html  /usr/share/nginx/html/compliance.html
COPY contact.html     /usr/share/nginx/html/contact.html
COPY help.html        /usr/share/nginx/html/help.html
COPY pricing.html     /usr/share/nginx/html/pricing.html
COPY privacy.html     /usr/share/nginx/html/privacy.html
COPY terms.html       /usr/share/nginx/html/terms.html

# SEO assets
COPY robots.txt       /usr/share/nginx/html/robots.txt
COPY sitemap.xml      /usr/share/nginx/html/sitemap.xml
COPY og-image.png     /usr/share/nginx/html/og-image.png

# The homepage file is named pakapay-website.html in this project. Every page
# on the site links to it as https://pakapay.ng/ (domain root), so it needs to
# become index.html for those links to actually resolve once deployed.
COPY pakapay-website.html /usr/share/nginx/html/index.html

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s \
    CMD wget -qO- http://localhost/ > /dev/null || exit 1

CMD ["nginx", "-g", "daemon off;"]
