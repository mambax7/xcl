<?php

$db =& Database::getInstance() ;

// beggining of Output
xoops_cp_header();
include __DIR__ . '/mymenu.php' ;

// open ui-card-full for ADVISORY
echo "<div class='ui-card-full'>\n" ;

// calculate the relative path between XOOPS_ROOT_PATH and XOOPS_TRUST_PATH
$root_paths = explode('/', XOOPS_ROOT_PATH) ;
$trust_paths = explode('/', XOOPS_TRUST_PATH) ;
foreach ($root_paths as $i => $rpath) {
    if ($rpath != $trust_paths[ $i ]) {
        break ;
    }
}
$relative_path = str_repeat('../', count($root_paths) - $i) . implode('/', array_slice($trust_paths, $i)) ;

    // UI toggle view options
    echo '<script>function toggle(className, obj) {$(className).toggle(750,"easeOutQuint", obj.checked )}</script>';

    echo  '<h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 32 32"><path d="M16 30l-6.176-3.293A10.982 10.982 0 0 1 4 17V4a2.002 2.002 0 0 1 2-2h20a2.002 2.002 0 0 1 2 2v13a10.982 10.982 0 0 1-5.824 9.707zM6 4v13a8.985 8.985 0 0 0 4.766 7.942L16 27.733l5.234-2.79A8.985 8.985 0 0 0 26 17V4z" fill="#626262"/><path d="M16 25.277V6h8v10.805a7 7 0 0 1-3.7 6.173z" fill="currentColor"/>
    </svg> Protector Security Advisor</h2>';

    echo '<div class="tips"<p>Protector Security Advisor can make a set of security checks and detect potential security risks.</p>
    <p>Moreover, you can get recommendations as well as remediation methods for the detected security risks.</p></div>';

    // Check the type of server
    // Perform access control Apache | NginX

    if (stristr($_SERVER["SERVER_SOFTWARE"], 'nginx')) {


        // header("X-Accel-Redirect: ../data/server_nginx.html");
        echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M12 0L1.605 6v12L12 24l10.395-6V6L12 0zm6 16.59c0 .705-.646 1.29-1.529 1.29c-.631 0-1.351-.255-1.801-.81l-6-7.141v6.66c0 .721-.57 1.29-1.274 1.29H7.32c-.721 0-1.29-.6-1.29-1.29V7.41c0-.705.63-1.29 1.5-1.29c.646 0 1.38.255 1.83.81l5.97 7.141V7.41c0-.721.6-1.29 1.29-1.29h.075c.72 0 1.29.6 1.29 1.29v9.18H18z" fill="currentColor"/>
        </svg> NginX</h2>'
        .'<p>You should keep in mind, that NginX does not manage php processes like Apache and you might need to configure either php-fpm, or php-cgi.</p>';
        // toggle var dump
        echo "<h4><input class='switch' type='checkbox' name='server-nginx' onclick=\"toggle('.nginx', this)\" value='0'>
        <label for='server-nginx'> Server software var dump</label></h4>";
        // NginX var dump
        echo '<div class="nginx" style="display:none">
        <pre class="tips" style="display:block; max-width:100%; margin:auto; height:400px;white-space: pre-wrap; overflow-y: auto">';
        echo var_export($_SERVER);
        echo '</pre></div>';

        }
        else if (stristr($_SERVER["SERVER_SOFTWARE"], 'apache')) {

        // header("X-Sendfile: ../data/server_apache.html");
            if(function_exists(apache_get_version)) {
                echo '<h3>Apache</h3>'
                .'<p>'. apache_get_version() .'</p>';
            }

        }

        else if(isset($_SERVER['SERVER_SOFTWARE'])){

        echo "<p>ELSE IF <input class='switch' type='checkbox'
        name='server-software-info' onclick=\"toggle('.server-software', this)\" value='0'>
        <label for='server-software-info'> Server Software</label></p>
        <div class='server-software' style='display:none'><pre>"
        .$_SERVER['SERVER_SOFTWARE'].
        "</pre></div>\n" ;

    }

    // server environment information
    echo '<p>The entries in this table are created by the web server. There is no guarantee that every web server will provide any of these.
    servers may omit some, or provide others not listed here. These variables are accounted for in the » CGI/1.1 specification, so you should be able to expect those.</p>';

    echo "<h4><input class='switch' type='checkbox' name='server-software-info' onclick=\"toggle('.server-software', this)\" value='0'>
    <label for='server-software-info'> Server and execution environment information</label></h4>";

    echo '<div class="server-software" style="display:none">
    <div class="tips">
    The Apache functions are only available when running PHP as an Apache module.
    Furthermore, some web servers configuration might not return the value for <code>$_SERVER["SERVER_SOFTWARE"]</code></div>'
    .'<table>
    <tr><td width="25%">Server Software</td><td><strong>'. $_SERVER['SERVER_SOFTWARE'] .'</strong> <code title="php sapi name">'. php_sapi_name() .'</code>
    <code title="GATEWAY_INTERFACE">'. $_SERVER['GATEWAY_INTERFACE'] .'</code>
    <code title="SERVER_PROTOCOL">'. $_SERVER['SERVER_PROTOCOL'] .'</code>
    <code>HTTPS/'. $_SERVER['HTTPS'] .'</code></td></tr>
    <tr><td>Server Address : <b>'. $_SERVER['SERVER_ADDR'] .'</b></td><td>Server Name : <b>'. $_SERVER['SERVER_NAME'] .'</b></td></tr>
    <tr><td>HTTP_ACCEPT</td><td>'. $_SERVER['HTTP_ACCEPT'] .' <code>'. $_SERVER['HTTP_ACCEPT_LANGUAGE'] .'</code> <code title="HTTP_ACCEPT_ENCODING">'. $_SERVER['HTTP_ACCEPT_ENCODING'] .'</code></td></tr>
    <tr><td>DOCUMENT_ROOT</td><td>'. $_SERVER['DOCUMENT_ROOT'] .'</td></tr>
    <tr><td>SCRIPT_FILENAME</td><td>'. $_SERVER['SCRIPT_FILENAME'] .'</td></tr>
    <tr><td>PHP SELF</td><td>'. $_SERVER['PHP_SELF'] .'</td></tr>
    <tr><td>REQUEST_URI</td><td>'. $_SERVER['REQUEST_URI'] .'</td></tr>
    </table></div>';

    // TODO : Modal loading echo phpinfo();
    // echo '<h4>PHP Modules</h4>';
    // echo '<div style="border:4px solid #ccc; display:block; width:400px; height:height: calc(100vh - 400px); overflow-y: auto">'. phpinfo(INFO_MODULES).'</div>';

    // the path of XOOPS_TRUST_PATH accessible check
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M22 10H12v7.382c0 1.409.632 2.734 1.705 3.618H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h7.414l2 2H21a1 1 0 0 1 1 1v4zm-8 2h8v5.382c0 .897-.446 1.734-1.187 2.23L18 21.499l-2.813-1.885A2.685 2.685 0 0 1 14 17.383V12z" fill="currentColor"/>
    </svg> TRUST PATH</h2>';

    echo "<div class='error'>
        <p>Public check [ image ]  <img class='message-warning' src='" . XOOPS_URL . '/' .htmlspecialchars($relative_path). "/modules/protector/public_check.png' width='40' height='25' style='border:1px solid black;margin-left:2em;'></p>
        </div>
        <p>'" . _AM_ADV_TRUSTPATHPUBLIC . "'</p>
        <div class='info'>
        <p>Public check [ link ] : <a href='" . XOOPS_URL . '/'.htmlspecialchars($relative_path)."/modules/protector/public_check.php' target='_blank'>"._AM_ADV_TRUSTPATHPUBLICLINK."</a></p>
        </div>";

    echo '<p>Since Nginx does not have an equivalent to the .htaccess file (i.e. no directory level configuration files), you need to update the main configuration and reload nginx for any changes to take effect.'
        .'<p>By default, the configuration file is named <code>nginx.conf</code> and placed in the directory :</p>
        <ul>
        <li><code>/usr/local/nginx/conf</code>
        <li><code>/etc/nginx</code>
        <li><code>/usr/local/etc/nginx</code>
        </ul>';

    // Check register_globals
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM6 6h5v5H6V6zm4.5 13a2.5 2.5 0 0 1 0-5a2.5 2.5 0 0 1 0 5zm3-6l3-5l3 5h-6z" fill="currentcolor"/>
    </svg> register_globals</h2>';

    $safe = ! ini_get('register_globals') ;
    if ($safe) {
        echo '<div class="success">[ off ] &nbsp; <span style="color:green;font-weight:bold;">ok</span></div>'
            .'<p>Register_globals has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 5.4.0 so it should be turned off.</p>';
    } else {
        echo '<div class="error">[ on ]  &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>'
            .'<p>' . _AM_ADV_REGISTERGLOBALS . '</p>
            <p><code>' . XOOPS_ROOT_PATH . '/.htaccess</code></p>
            <p><code>php_flag &nbsp; register_globals &nbsp; off</code></p>
            <p>or edit edit PHP.ini</p>
            <pre style="width:50%"><code>
            # Do not register globals for input data
            register_globals = Off
            </code></pre>';
    }

    // Check allow_url_fopen
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M13 19h1a1 1 0 0 1 1 1h7v2h-7a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1H2v-2h7a1 1 0 0 1 1-1h1v-1.66C8.07 16.13 6 13 6 9.67v-4L12 3l6 2.67v4c0 3.33-2.07 6.46-5 7.67V19M12 5L8 6.69V10h4V5m0 5v6c1.91-.47 4-2.94 4-5v-1h-4z" fill="currentColor"/>
    </svg> Allow url fopen</h2>';

    $safe = ! ini_get('allow_url_fopen') ;
    if ($safe) {
        echo '<div class="success">[ off ] &nbsp; <span style="color:green;font-weight:bold;">ok</span></div>';
    } else {
        echo '<div class="error">[ on ] &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>'
            .'<p>' . _AM_ADV_ALLOWURLFOPEN . '</p>'
            .'<p>best practice for many is to turn off <code>allow_url_include</code></p>';
    }
    echo '<h5>Description</h5>
        <p>The PHP configuration directive allow_url_fopen is enabled. When enabled, this directive allows data retrieval from remote locations
        (web site or FTP server). A large number of code injection vulnerabilities reported in PHP-based web applications are caused by the combination
        of enabling allow_url_fopen and bad input filtering.</p>
        <p><b>allow_url_fopen is enabled by default!</b></p>
        <h5>Remediation</h5>
        <p>You can disable allow_url_fopen from either php.ini (for PHP versions newer than 4.3.4) or .htaccess (for PHP versions up to 4.3.4).</p>
        <h5>php.ini</h5>
        <p><code>allow_url_fopen = "off"</code></p>
        <h5>.htaccess</h5>
        <p><code>php_flag allow_url_fopen off</code></p>';


    // Check session.use_trans_sid
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12c5.16-1.26 9-6.45 9-12V5l-9-4m0 4a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3m5.13 12A9.69 9.69 0 0 1 12 20.92A9.69 9.69 0 0 1 6.87 17c-.34-.5-.63-1-.87-1.53c0-1.65 2.71-3 6-3s6 1.32 6 3c-.24.53-.53 1.03-.87 1.53z" fill="currentColor"/></svg>
    session.use_trans_sid</h2>';

    $safe = ! ini_get('session.use_trans_sid') ;
    if ($safe) {
        echo '<div class="success">[ off ] &nbsp; <span style="color:green;font-weight:bold;">ok</span></div>';
    } else {
        echo '<div class="error">[ on ] &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>';
        echo '<p>' . _AM_ADV_USETRANSSID . '</p>';
    }
    echo '<h5>Description</h5>
         <p>When use_trans_sid is enabled, PHP will pass the session ID via the URL. This makes the application more vulnerable to session hijacking attacks.
         Session hijacking is basically a form of identity theft wherein a hacker impersonates a legitimate user by stealing his session ID.
         When the session token is transmitted in a cookie, and the request is made on a secure channel (that is, it uses SSL), the token is secure.</p>'

        .'<h5>Remediation</h5>
        <p>You can disable <b>session.use_trans_sid</b> from <code>php.ini</code> or <code>.htaccess</code> </p>'
        .'<h5>php.ini</h5>
        <p><code>session.use_trans_sid = "off"</code></p>'
        .'<h5>.htaccess</h5>
        <p><code>php_flag session.use_trans_sid off</code></p>';


    // Database PREFIX
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M3 1h16a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1m0 8h16a1 1 0 0 1 1 1v.67l-2.5-1.11l-6.5 2.88V15H3a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1m0 8h8c.06 2.25 1 4.4 2.46 6H3a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1M8 5h1V3H8v2m0 8h1v-2H8v2m0 8h1v-2H8v2M4 3v2h2V3H4m0 8v2h2v-2H4m0 8v2h2v-2H4m13.5-7l4.5 2v3c0 2.78-1.92 5.37-4.5 6c-2.58-.63-4.5-3.22-4.5-6v-3l4.5-2m0 1.94L15 15.06v2.66c0 1.54 1.07 2.98 2.5 3.34v-7.12z" fill="currentColor"/>
    </svg> Database Prefix</h2>';
    $safe = 'xoops' != strtolower(XOOPS_DB_PREFIX);
    if ($safe) {
        echo '<div class="success">[ ' . XOOPS_DB_PREFIX . ' ] &nbsp; <span style="color:green;font-weight:bold;">ok</span></div>';
    } else {
        echo '<div class="error">[ ' . XOOPS_DB_PREFIX . ' ] &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>';
        echo '<p>' . _AM_ADV_DBPREFIX . '</p>';
    }
    echo '<p><a href="index.php?page=prefix_manager">' . _AM_ADV_LINK_TO_PREFIXMAN . '</a></p>';


    // Check mainfile.php
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" width="1em" height="1em" viewBox="0 0 1024 1024"><path d="M644.7 669.2a7.92 7.92 0 0 0-6.5-3.3H594c-6.5 0-10.3 7.4-6.5 12.7l73.8 102.1c3.2 4.4 9.7 4.4 12.9 0l114.2-158c3.8-5.3 0-12.7-6.5-12.7h-44.3c-2.6 0-5 1.2-6.5 3.3l-63.5 87.8l-22.9-31.9zM688 306v-48c0-4.4-3.6-8-8-8H296c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h384c4.4 0 8-3.6 8-8zm-392 88c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H296zm184 458H208V148h560v296c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V108c0-17.7-14.3-32-32-32H168c-17.7 0-32 14.3-32 32v784c0 17.7 14.3 32 32 32h312c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm402.6-320.8l-192-66.7c-.9-.3-1.7-.4-2.6-.4s-1.8.1-2.6.4l-192 66.7a7.96 7.96 0 0 0-5.4 7.5v251.1c0 2.5 1.1 4.8 3.1 6.3l192 150.2c1.4 1.1 3.2 1.7 4.9 1.7s3.5-.6 4.9-1.7l192-150.2c1.9-1.5 3.1-3.8 3.1-6.3V538.7c0-3.4-2.2-6.4-5.4-7.5zM826 763.7L688 871.6L550 763.7V577l138-48l138 48v186.7z" fill="currentColor"/>
    </svg> mainfile.php</h2>';
    if (! defined('PROTECTOR_PRECHECK_INCLUDED')) {
        echo '<div class="error">missing precheck &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>';
        echo '<p>' . _AM_ADV_MAINUNPATCHED . '</p>';
    } elseif (! defined('PROTECTOR_POSTCHECK_INCLUDED')) {
        echo '<div class="error">missing postcheck &nbsp; <span style="color:red;font-weight:bold;">' . _AM_ADV_NOTSECURE . '</span></div>';
        echo '<p>' . _AM_ADV_MAINUNPATCHED . '</p>';
    } else {
        echo '<div class="success"><span style="color:green;font-weight:bold;">ok</span> patched</div>' ;
    }


    // Check databasefactory.php
    echo '<hr><h2><svg xmlns="http://www.w3.org/2000/svg" focusable="false" width="1em" height="1em" viewBox="0 0 24 24"><path d="M3 1h16a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1m0 8h16a1 1 0 0 1 1 1v.67l-2.5-1.11l-6.5 2.88V15H3a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1m0 8h8c.06 2.25 1 4.4 2.46 6H3a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1M8 5h1V3H8v2m0 8h1v-2H8v2m0 8h1v-2H8v2M4 3v2h2V3H4m0 8v2h2v-2H4m0 8v2h2v-2H4m13.5-7l4.5 2v3c0 2.78-1.92 5.37-4.5 6c-2.58-.63-4.5-3.22-4.5-6v-3l4.5-2m0 1.94L15 15.06v2.66c0 1.54 1.07 2.98 2.5 3.34v-7.12z" fill="currentColor"/>
    </svg> databasefactory.php</h2>';
    $db =& Database::getInstance() ;
    if ('protectormysqldatabase' != strtolower(get_class($db))) {
        echo '<div class="error"><span style="color:red;font-weight:bold;">' . _AM_ADV_DBFACTORYUNPATCHED . '</span></div>';
    } else {
        echo '<div class="success"><span style="color:green;font-weight:bold;">ok</span> ' . _AM_ADV_DBFACTORYPATCHED . '</div>';
    }
    echo '<h5>Description</h5>
    <p>SQL injection (SQLi) refers to an injection attack wherein an attacker can execute malicious SQL statements that control a web app
    database server.</p>
    <h5>Remediation</h5>
    <p>Use parameterized queries when dealing with SQL queries that contains user input.
    Parameterized queries allows the database to understand which parts of the SQL query should be considered as user input, therefore solving SQL injection.</p>';

    // PROTECTION CHECK TEST

    echo '<hr><h2>' . _AM_ADV_SUBTITLECHECK . '</h2>';
    // Check contaminations
    $uri_contami = XOOPS_URL . '/index.php?xoopsConfig%5Bnocommon%5D=1';
    echo '<div class="tips"><p>' . _AM_ADV_CHECKCONTAMI . ':</p>';
    echo "<p><a href='$uri_contami' target='_blank'>'$uri_contami'</a></p></div>";

    // Check isolated comments
    $uri_isocom = XOOPS_URL . '/index.php?cid=' . urlencode(',password /*') ;
    echo '<div class="tips">' . _AM_ADV_CHECKISOCOM . ":</p>\n" ;
    echo "<p><a href='$uri_isocom' target='_blank'>$uri_isocom</a></div>" ;

// close div 'ui-card-full'
echo "</div>\n" ;

xoops_cp_footer();

?>
