
## Kurulum ve Konfigürasyon

Composer PHP için geliştirilmiş bağımlılık kontrölü olan paket indirme aracıdır. Proje ihtiyaçlarınıza göre projenize dahil etmek istediğiniz birbirine bağımlı olabilen kütüphaneleri <kbd>composer.json</kbd> adlı bir dosya altında tanımlayarak onları proje kök dizininde <kbd>/vendor</kbd> klasörü içerisine bir paket olarak kurmanıza yada projeden kaldırmanıza olanak sağlar.

Composer projesi hakkında daha detaylı bilgi için <a href="http://getcomposer.org" target="_blank">http://getcomposer.org</a> adresini ziyaret edebilirsiniz.

<ul>
    <li>
        <a href="#setup">Kurulum</a>
        <ul>
            <li><a href="#linux-setup">Linux / Unix / OSX İşletim Sistemleri İçin Kurulum</a></li>
            <li><a href="#windows-setup">Windows İşletim Sistemleri İçin Kurulum</a></li>
        </ul>
    </li>

    <li>
        <a href="#dump-autoload">Autoload Önbelleğini Tazeleyin</a>
    </li>

    <li>
        <a href="#apache">Apache</a>
        <ul>
            <li><a href="#htaccess">.htaccess</a></li>
            <li><a href="#vhost">Vhost Konfigürasyonu</a></li>
        </ul>
    </li>
</ul>

<a name='setup'></a>

### Kurulum

<a name='linux-setup'></a>

#### Linux / Unix / OSX İşletim Sistemleri İçin Kurulum

Proje kök dizininde aşağıdaki <kbd>composer.json</kbd> dosyasını oluşturun. Çerçeve çalışabilmek için bu dosya içerisinde tanımlı paketlere ihtiyaç duyar.

```php
{
    "autoload": {
        "psr-4": {
            "": "app/classes"
        },
        "files": [
            "vendor/ircmaxell/password-compat/lib/password.php"
        ]
    },
    "require": {
        "obullo/obullo": "dev-master"
    }
}
```

* Psr-4 içerisine eklenen <kbd>app/classes</kbd> yükleyicisisi uygulamanızdaki bu dizine ait kütüphanelerin yüklenebilmesi için gereklidir.

Konsolonuza gidin ve proje kök dizininde aşağıdaki komutu çalıştırarak composer.phar dosyasını indirin.

```php
curl -sS https://getcomposer.org/installer | php
```

Eğer curl paketi bilgisayarınızda yüklü değilse bu işlemi yapmadan önce curl paketini yükleyin. Son olarak composer paketlerini php komutu ile çalıştırabilirsiniz.

```php
php composer install
```

Eğer php önekini kullanmadan composer komutlarını direkt çalıştırmak istiyorsanız indirdiğiniz <kbd>composer.phar</kbd> dosyasını <kbd>/usr/local/bin/composer</kbd> dizini altına kopyalamanız gerekiyor.


```php
mv composer.phar /usr/local/bin/composer
```

Şimdi composer komutlarını aşağıdaki gibi çalıştırabilirsiniz.

```php
composer update
```

Eğer henüz composer paketleri kurulmadıysa aşağıdaki gibi yükleme işlemini başlatmalısınız.

```php
composer install
```

<a name='windows-setup'></a>

#### Windows İşletim Sistemleri İçin Kurulum

Composer.json konfigürasyonu Linux / Unix / OSX İşletim Sistemlerinde olduğu gibidir, composer paketinin Windows kurulumu için ise <a href="https://getcomposer.org/doc/00-intro.md#installation-windows">bu linkten</a> faydalanabilirsiniz.


#### Xampp Altında Kurulum

Uygulamayı örnek olarak "obullo" adıyla  "C:\xampp\htdocs\ klasörüne kopyalayın.

Bu dosyayı açın C:\xampp\apache\conf\extra\httpd-vhosts.conf

<VirtualHost *:80>
   ServerAdmin webmaster@obullo.com
   DocumentRoot "C:/xampp/htdocs/obullo/public"
   ServerName obullo
<Directory "C:/xampp/htdocs/obullo">
AllowOverride All
Require all Granted
</Directory>
    ##ErrorLog "logs/dummy-host2.example.com-error.log"
    ##CustomLog "logs/dummy-host2.example.com-access.log" common
</VirtualHost>

Windows host dosyanızı açın C:\Windows\System32\Drives\etc\host

127.0.0.1 obullo

yazıp kaydedin.

Xampp uygulamasını yeniden başlatın.

Composer kurulumunu gerçekleştirin.

Tarayıcınıza http://obullo/ yazdığınızda uygulama çalışır.

<a name='dump-autoload'></a>

### Autoload Önbelleğini Tazeleyin

Son olarak autoload dosyalarını composer ın yeniden oluşturmasını istememiz gerekiyor.

```php
composer dump-autoload
```

#### deb_app.php dosyası

Zend tarafından sağlanan gelişmiş bir katman çözümüdür.Konfigürasyon için index.php dosyanızı açın ve <kbd>$app</kbd> değişkenini <kbd>Obullo\Http\Zend\Stratigility\MiddlewarePipe</kbd> olarak aşağıdaki gibi güncelleyin.

```php

$server->listen();
```

Katman hakkında detaylı bilgiye <a href="https://github.com/zendframework/zend-stratigility" target="_blank">zend middleware</a> bağlantısından ulaşabilirsiniz.

<a name="apache"></a>

### Apache

<a name="htaccess"></a>

#### .htaccess

Htaccess url rewrite özelliklerinin çalışabilmesi için apache <kbd>mod_rewrite</kbd> modülünün kurulu olduğundan emin olun.

<a name="vhost"></a>

#### Vhost Konfigürasyonu

Eğer projenizi <kbd>/var/www/project/</kbd> klasörü içerisinde tutuyorsanız, apache vhost dosyanızdaki <kbd>DocumentRoot</kbd> konfigürasyonu aşağıdaki gibi <kbd>public</kbd> klasörünü hedef göstermelidir.

```xml
<VirtualHost *:80>
        ServerName project.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/project/public
</VirtualHost>
```

Apache VirtualHost kurulumu hakkında daha detaylı bilgi için <a href="">https://github.com/obullo/warmup/tree/master/Apache2</a> bağlantısını ziyaret edebilirsiniz.