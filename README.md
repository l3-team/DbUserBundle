Database Provider for CAS

Allow authorize many ROLES (Symfony ROLES) from UID (returned by CasBundle) for application Symfony2, Symfony3 and Symfony4
* UID is the id user returned by jasig cas sso server and by the l3-team/CasBundle (repository github) or l3/cas-bundle (repository packagist)
* ROLES are Symfony ROLES prefixed by ROLE_, example ROLE_ADMIN, ROLE_USER, etc...

Installation of the Bundle
---
* Simple add this line in the require in your composer.json :
```
"l3/db-user-bundle": "~1.0"
```
Launch the command **composer update** to install the package.

* For Symfony 2 and 3 : add the Bundle in the AppKernel.php file.
```
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new L3\Bundle\DbUserBundle\L3DbUserBundle(),
        );

        // ...
    }

    // ...
}
```

* For Symfony 4 :
Verify if the line are present in config/bundles.php file (if not present, just add the line) :
```
# config/bundles.php
...
L3\Bundle\DbUserBundle\L3DbUserBundle::class => ['all' => true],
...
```

* Next, configure the database connection in parameters.yml (fills the variables named prefixed by database*) :
```
# app/config/parameters.yml
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: null
    database_name: symfony
    database_user: root
    database_password: null
```

* And create the 3 tables (x_user, x_role and x_user_role) with this command(s) :
- For Symfony 2 :
```
php app/console doctrine:schema:update --force
```
- For Symfony 3 :
```
php bin/console doctrine:schema:update --force
```
- For Symfony 4 :
```
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

Configuration of the bundle
---

* For Symfony 2 and 3 : in the firewall of your application, use the Bundle :
```
# app/config/security.yml
security:
    providers:
        database:
            entity:
                class: L3DbUserBundle:User
                property: uid
```

* For Symfony 4 : in the firewall of your application, use the Bundle :
```
# config/packages/security.yaml
security:
    providers:
        database:
            entity:
                class: L3DbUserBundle:User
                property: uid
```

Anonymous mode
---
if your application use the anonymous mode of the CasBundle (special username **__NO_USER__**), then configure the security file like this :
```
security:
    providers:
        chain_provider:
            chain:
                providers: [in_memory, database]
        in_memory:
            memory:
                users:
                    __NO_USER__:
                        password:
                        roles: ROLE_ANON
        database:
            entity:
                class: L3DbUserBundle:User
                property: uid
```
In Symfony4, if you use chain_provider, you should set provider name on all entry (ie l3_firewall and main) firewall (where security is active : **security: true**) in config/packages/security.yaml like this :
```
# config/packages/security.yaml
security:
    providers:
        chain_provider:
            chain:
                providers: [in_memory, your_userbundle]
        in_memory:
            memory:
                users:
                    __NO_USER__:
                        password:
                        roles: ROLE_ANON
        your_userbundle:
            id: your_userbundle

    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false

        l3_firewall:
            pattern: ^/
            security: true
            cas: true # Activation du CAS
            provider: chain_provider

        main:
            pattern: ^/
            security: true
            cas: true # Activation du CAS
            anonymous: true
            provider: chain_provider
```

How to use
---
3 tables x_user, x_role and x_user_role have been created with the doctrine command schema update.
* table x_user contains the UID used with CasBundle
* table x_role contains all the ROLES Symfony
* table x_user_role contains the map of all ROLES of all UID
