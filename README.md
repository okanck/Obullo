
### Obullo Php Framework

Small, fast & expandable.

* Small because we are just use 6 core components.
* Obullo core supports Http Middlewares, Psr7 HTTP Standarts, Container, Dependency Injection, Service Providers. 
* Fast because of our router contains just 300 lines of code.
* Expandable because of you can install extra packages (HMVC, Authentication, Validator ..) using composer.

### Philosophy

* The smaller parts that consist the whole framework should be compatible with each other.

[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/obullo/framework?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) [![Follow us on twitter !](https://img.shields.io/badge/twitter-follow me-green.svg?style=flat-square)](http://twitter.com/obullo)

### Status

----

There is no release yet, we are still working on it.

### Configuration of Vhost File

Put the latest version to your web root (<kbd>/var/www/project/</kbd>). Create your apache vhost file and set your project root as <kbd>public</kbd>.

```xml
<VirtualHost *:80>
        ServerName project.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/project/public
</VirtualHost>
```

#### License

<a href="http://obullo.com/license.txt" targe="_blank">http://obullo.com/license.txt</a>

