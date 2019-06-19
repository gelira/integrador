FROM debian

RUN useradd -ms /bin/bash app
RUN echo "root:abc@123" | chpasswd

VOLUME ["/home/app/projeto"]
RUN chown -R app:app /home/app/projeto

RUN apt-get update
RUN apt-get install -y ca-certificates apt-transport-https nano wget gnupg gnupg1 gnupg2 git zip unzip

RUN wget -q https://packages.sury.org/php/apt.gpg -O- | apt-key add -
RUN echo "deb https://packages.sury.org/php/ stretch main" > /etc/apt/sources.list.d/php.list
RUN apt-get update

RUN apt-get install -y apache2 libapache2-mod-php7.3 php7.3 php7.3-bcmath php7.3-mbstring php7.3-pgsql php7.3-mysql php7.3-xml php7.3-zip

USER app
WORKDIR /home/app

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar composer
