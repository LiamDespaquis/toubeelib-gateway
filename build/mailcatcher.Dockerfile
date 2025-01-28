FROM ruby:3.3

RUN bundle config --global frozen 1

WORKDIR /usr/src/app

RUN gem install mailcatcher
RUN gem install sqlite3
EXPOSE 1025 1080
CMD ["mailcatcher", "-f", "--ip", "0.0.0.0"]

