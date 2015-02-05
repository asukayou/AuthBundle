# AuthBundle

**composer.json**
```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/asukayou/AuthBundle"
        }
    ],
    "require": {
        "asukayou/auth-bundle": "dev-master"
    },
```

```
composer update asukayou/auth-bundle
```

**app/AppKernel.php**
```
            new Ay\AuthBundle\AyAuthBundle(),
```

**app/config/security.yml**
```
parameters:
    encoder_algorithm: sha512
    encoder_encode_as_base64: false
    encoder_iterations: 1
    
security:
    encoders:
        Ay\AuthBundle\Entity\User:
            algorithm: %encoder_algorithm%
            encode_as_base64: %encoder_encode_as_base64%
            iterations: %encoder_iterations%

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER

    providers:
        user_db:
            entity: { class: Ay\AuthBundle\Entity\User, property: username }

    firewalls:
        secured_area:
            pattern:    ^/
            anonymous: ~
            form_login:
                login_path:  /login
                check_path:  /login_check
                default_target_path: /
                always_use_default_target_path: false
            logout: ~
```

**app/config/routing.yml**
```
ay_auth:
    resource: "@AyAuthBundle/Controller/"
    type:     annotation
    prefix:   /

login_check:
    pattern: /login_check

logout:
    pattern: /logout
```

**app/config/parameters.yml**
```
system_name: 'システム名'
system_mail_address: '送信元メールアドレス'
```

```
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load --fixtures=vendor/asukayou/auth-bundle/DataFixtures/ORM
```

