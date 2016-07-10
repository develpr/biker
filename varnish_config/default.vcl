# This is an example VCL file for Varnish.
#
# It does not do anything by default, delegating control to the
# builtin VCL. The builtin VCL is called when there is no explicit
# return statement.
#
# See the VCL chapters in the Users Guide at https://www.varnish-cache.org/docs/
# and https://www.varnish-cache.org/trac/wiki/VCLExamples for more examples.

# Marker to tell the VCL compiler that this VCL has been adapted to the
# new 4.0 format.
vcl 4.0;

import xkey;

# Default backend definition. Set this to point to your content server.
backend default {
    .host = "127.0.0.1";
    .port = "80";
}

# list of IP addresses that can be used to purge the cache
acl purgers {
    "127.0.0.1";
    "192.168.0.0"/24;
    "192.168.144.129";
    "192.168.144.1";
}

sub vcl_recv {
    if (req.http.xkey-purge) {
        if (xkey.purge(req.http.Xkey-purge) != 0) {
            return (synth(200, "Purged"));
        } else {
            return (synth(404, "Key not found"));
        }
    }

    if(req.url ~ "^/two.php"){
        return(pass);
    }

    # Happens before we check if we have this in cache already.
    #
    # Typically you clean up the request here, removing cookies you don't need,
    # rewriting the request, etc.
    if ((req.url ~ "^/one.php")) {

        if (req.method == "PURGE") {
            if (client.ip !~ purgers) {
                return (synth(405, "Method not allowed"));
            }
            if(req.url ~ "^/"){
                return (purge);
            }
            return (synth(200, "Purged"));
        }

        if(req.http.X-Purger && ! req.http.X-ID-Refresh) {
            return ( synth(200, "Purged") );
        }

        if (req.restarts == 0) {
            unset req.http.X-Purger;
        }

        //Unset cookies
        if (req.http.Cookie) {
            set req.http.Cookie = ";" + req.http.Cookie;
            set req.http.Cookie = regsuball(req.http.Cookie, "; +", ";");
            set req.http.Cookie = regsuball(req.http.Cookie, ";(XDEBUG_SESSION|PHPSESSID)=", "; \1=");
            set req.http.Cookie = regsuball(req.http.Cookie, ";[^ ][^;]*", "");
            set req.http.Cookie = regsuball(req.http.Cookie, "^[; ]+|[; ]+$", "");

            if (req.http.Cookie == "") {
                unset req.http.Cookie;
            }
	        unset req.http.Cookie;
        }
    }
}

/**
 *  Purge is called after return(purge) - we'll use this to re-cache this value from the backend
 */
sub vcl_purge {
    set req.method = "GET";
    set req.http.X-Purger = "Purged";
    return (restart);
}

//sub vcl_hash {
//    hash_data(req.url);
//}

sub vcl_backend_response {
    # Happens after we have read the response headers from the backend.
    #
    # Here you clean the response headers, removing silly Set-Cookie headers
    # and other mistakes your backend does.

    if (bereq.url ~ "^/one.php") {

        set beresp.do_esi = true;

        set beresp.ttl = 2m;
        set beresp.http.Cache-Control = "public";
        unset beresp.http.Cache-Control;
        unset beresp.http.Set-Cookie;
	}
}

sub vcl_deliver {
    # Happens when we have all the pieces we need, and are about to send the
    # response to the client.
    #
    # You can do accounting or modifying the final object here.
	if (obj.hits > 0) {
            set resp.http.X-Cache = "HIT";
    } else {
            set resp.http.X-Cache = "MISS";
    }

//
//    if (resp.http.X-Magento-Debug) {
//        if (resp.http.x-varnish ~ " ") {
//            set resp.http.X-Magento-Cache-Debug = "HIT";
//        } else {
//            set resp.http.X-Magento-Cache-Debug = "MISS";
//        }
//    } else {
//        unset resp.http.Age;
//    }
}
