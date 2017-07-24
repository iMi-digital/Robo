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

### Release Process

1. Bump version in \Robo\Robo::VERSION
2. Commit an push Tag