<?php

return array(

        /*
        |--------------------------------------------------------------------------
        | Default Remote Connection Name
        |--------------------------------------------------------------------------
        |
        | Here you may specify the default connection that will be used for SSH
        | operations. This name should correspond to a connection name below
        | in the server list. Each connection will be manually accessible.
        |
        */

        'default' => 'production',

        /*
        |--------------------------------------------------------------------------
        | Remote Server Connections
        |--------------------------------------------------------------------------
        |
        | These are the servers that will be accessible via the SSH task runner
        | facilities of Laravel. This feature radically simplifies executing
        | tasks on your servers, such as deploying out these applications.
        |
        */

        'connections' => array(

                'production' => array(
                        'host'      => '192.241.120.109',
                        'username'  => 'root',
                        'password'  => '',
                        'key'       => '-----BEGIN RSA PRIVATE KEY-----
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
-----END RSA PRIVATE KEY-----
',
                        'keyphrase' => '',
                        'root'      => '/var/www/html/',
                ),

        ),

        /*
        |--------------------------------------------------------------------------
        | Remote Server Groups
        |--------------------------------------------------------------------------
        |
        | Here you may list connections under a single group name, which allows
        | you to easily access all of the servers at once using a short name
        | that is extremely easy to remember, such as "web" or "database".
        |
        */

        'groups' => array(

                'web' => array('production')

        ),

);