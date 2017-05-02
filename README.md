# RoboTask

iMi's fork of Robo.li

## Installing

### Phar

[Download irobo>](http://irobo.imi.de/irobo.phar)

```
wget http://irobo.imi.de/irobo.phar
```

To install globally put `irobo.phar` in `/usr/bin`.

```
chmod +x irobo.phar && sudo mv irobo.phar /usr/bin/irobo
```

Now you can use it just like `irobo`.


# Development

    ./robo irobo:phar-publish

needs https://github.com/aktau/github-release/releases/download/v0.7.2/linux-amd64-github-release.tar.bz2 to publish on GitHub