# RoboTask

iMi's fork of Robo.li

iRobo = robo.li + robo-pack

See https://github.com/iMi-digital/robo-pack for more information about the addtional commands.

## Branches

| Branch | Support Level | Symfony Versions | PHP Versions |
| ------ | ------------- | ---------------- | ------------ |
| [3.x](https://github.com/consolidation/robo/tree/3.x) | Unstable        | 4 & 5 | 7.1 - 7.4 |
| [2.x](https://github.com/consolidation/robo/tree/2.x) | Stable          | 4 & 5 | 7.1 - 7.4 |
| [1.x](https://github.com/consolidation/robo/tree/1.x) | Not recommended | 2 - 4 | 5.5 - 7.4 |

The pre-build [robo.phar](http://robo.li/robo.phar) is built with Symfony 5, and requires PHP 7.2+.  Robo also works with Symfony 4 and PHP 7.1.3+ if packaged as a library in another application. For Symfony 2 or 3 support, or PHP versions prior to 7.1, please use the Robo 1.x branch.

All three branches of Robo are currently supported, although the 2.x and 1.x branches receive minimum support. All versions are roughly compatible; the breaking changes introduced at each major version are fairly minor, and typically only affect classes that are not used by most clients.

Note that the 3.x branch is still unstable; minor breaking changes, especially with respect to collections and the ConsoleIO class might still be made. To avoid tracking changes closely, typehint the $io parameter as SymfonyStyle rather than ConsoleIO. This technique also works on the 2.x branch.

## Installing

### Phar

[Download irobo](http://irobo.imi.de/irobo.phar)

```
wget http://irobo.imi.de/irobo.phar
```

To install globally put `irobo.phar` in `/usr/bin`.

```
chmod +x irobo.phar && sudo mv irobo.phar /usr/bin/irobo
```

OSX 10.11+
```
chmod +x irobo.phar && sudo mv irobo.phar /usr/local/bin/irobo
```

Now you can use it just like `irobo`.

### Composer

* Run `composer require consolidation/robo:~1`
* Use `vendor/bin/robo` to execute Robo tasks.

## Usage

All tasks are defined as **public methods** in `RoboFile.php`. It can be created by running `robo init`.
All protected methods in traits that start with `task` prefix are tasks and can be configured and executed in your tasks.

## Examples

The best way to learn Robo by example is to take a look into [its own RoboFile](https://github.com/consolidation/Robo/blob/2.x/RoboFile.php)
 or [RoboFile of Codeception project](https://github.com/Codeception/Codeception/blob/2.4/RoboFile.php). There are also some basic example commands in `examples/RoboFile.php`.

Here are some snippets from them:

---

Run acceptance test with local server and selenium server started.


``` php
<?php
class RoboFile extends \Robo\Tasks
{

    function testAcceptance($seleniumPath = '~/selenium-server-standalone-2.39.0.jar')
    {
       // launches PHP server on port 8000 for web dir
       // server will be executed in background and stopped in the end
       $this->taskServer(8000)
            ->background()
            ->dir('web')
            ->run();

       // running Selenium server in background
       $this->taskExec('java -jar ' . $seleniumPath)
            ->background()
            ->run();

       // loading Symfony Command and running with passed argument
       $this->taskSymfonyCommand(new \Codeception\Command\Run('run'))
            ->arg('suite','acceptance')
            ->run();
    }
}
```
wget http://irobo.imi.de/irobo.phar
```

To install globally put `irobo.phar` in `/usr/bin`.

```
chmod +x irobo.phar && sudo mv irobo.phar /usr/bin/irobo
```

Now you can use it just like `irobo`.

### Using Phive

Download using [phar.io](https://phar.io) / Phive:

`sudo phive install -g iMi-digital/irobo`

### Release Process

1. Bump version in \Robo\Robo::VERSION
2. Commit and `git push`; `git push --tags`
3. `./robo phar:build`
4. check `./robo.phar`
5. `./robo irobo:phar-sign`
6. `./robo irobo:phar-publish`
