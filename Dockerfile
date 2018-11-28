FROM php:7.2-fpm

RUN apt-get update \
  && apt-get install --yes --no-install-recommends libpq-dev \
  		git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libmcrypt-dev \
        wget \
	libav-tools \
	software-properties-common \
	libcurl4-openssl-dev \
        && docker-php-ext-install iconv \
        && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
        && docker-php-ext-install gd \
        && docker-php-ext-install curl pgsql mysqli pdo_pgsql pdo_mysql

#RUN sed -i "s/jessie main/jessie main contrib non-free/" /etc/apt/sources.list
#RUN echo "deb http://http.debian.net/debian jessie-backports main contrib non-free" >> /etc/apt/sources.list
#RUN apt-get update && apt-get install -y \
#    ffmpeg
#
#RUN apt-get install -y locales && \
#    locale-gen C.UTF-8 && \
#    /usr/sbin/update-locale LANG=C.UTF-8
#
#ENV LC_ALL C.UTF-8
#
#CMD ["/bin/bash"]

RUN pecl install redis-3.1.2 \
    && pecl install xdebug \
    && docker-php-ext-enable redis xdebug \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable mcrypt

RUN apt-get install -y autoconf pkg-config libssl-dev
RUN pecl install mongodb
RUN docker-php-ext-install bcmath
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

RUN echo "deb http://www.deb-multimedia.org jessie main non-free" >>/etc/apt/sources.list
RUN echo "deb-src http://www.deb-multimedia.org jessie main non-free" >>/etc/apt/sources.list
RUN apt-get update && apt-get install -y --force-yes deb-multimedia-keyring

RUN apt-get update && apt-get install -y \
        build-essential \
        libmp3lame-dev \
        libvorbis-dev \
        libtheora-dev \
        libspeex-dev \
        yasm \
        pkg-config \
        libfaac-dev \
        libmagickwand-dev \
        python-cffi \
        libx264-dev \
        libfreetype6 \
        libfreetype6-dev \
        libfribidi-dev \
        libfontconfig1-dev

RUN mkdir /software \
    && cd /software \
    && wget http://ffmpeg.org/releases/ffmpeg-2.7.2.tar.bz2 \
    && mkdir src \
    && cd src \
    && tar xvjf ../ffmpeg-2.7.2.tar.bz2 \
    && cd ffmpeg-2.7.2 \
    && ./configure \
        --enable-gpl \
        --enable-postproc \
        --enable-swscale \
        --enable-avfilter \
        --enable-libmp3lame \
        --enable-libvorbis \
        --enable-libtheora \
        --enable-libx264 \
        --enable-libspeex \
        --enable-shared \
        --enable-pthreads \
        --enable-libfaac \
        --enable-nonfree \
        --enable-libfreetype \
        --enable-libfontconfig \
        --enable-libfribidi \
    && make \
    && make install \
    && rm -rf /software/ffmpeg-2.7.2 \
    && ldconfig

RUN apt-get install -y python-pip

# Hack for debian-slim to make the jdk install work below.
RUN mkdir -p /usr/share/man/man1

# repo needed for jdk install below.
RUN echo 'deb http://deb.debian.org/debian stretch-backports main' > /etc/apt/sources.list.d/backports.list

# Update image & install application dependant packages.
RUN apt-get update && apt-get install -y \
nano \
libxext6 \
libfreetype6-dev \
libjpeg62-turbo-dev \
libpng-dev \
libmcrypt-dev \
libxslt-dev \
libpcre3-dev \
libxrender1 \
libfontconfig \
uuid-dev \
ghostscript \
curl \
wget \
ca-certificates-java

RUN apt-get -t stretch-backports install -y default-jdk-headless

RUN apt-get install ant


RUN export CFLAGS="$PHP_CFLAGS" CPPFLAGS="$PHP_CPPFLAGS" LDFLAGS="$PHP_LDFLAGS" \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install imagick-3.4.3 \
    && docker-php-ext-enable imagick
    
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer
    
#CMD bash -c "mkdir -p /code/tmp"

#CMD bash -c "cd /code && composer install"
