# GravitonAuditTrackingBundle

## Inner Auditing tool bundle
This tool is meant to run as a hidden service in order to know what each user request or modifies.
It will not limit nor interfere with the user request but only store the changes and data received
if so is configure`.
* `x-header-audit-thread` → id-string-uuid
* Api to list thread: `/auditing/?eq(thread,string:id-string-uuid)`

### version
* `v0.0.1`: 2016/09/22 First version with basic auditing enabled by default, collection changes.

#### Configuration
* Need Graviton ^v0.76.0, so ModelEvent is fired on Document Updates.
* Setup configuration in `AuditTracking/Resources/config/parameters.yml`.

```yml
parameters:
    graviton_audit_tracking:
        # General on/off switch
        log_enabled: true
        # Localhost and not Real User on/off switch
        log_test_calls: false`
        # Store request log also on 400 error
        log_on_failure: false
        # Request methods to be saved, array PUT,POST,DELETE,PATCH...
        requests: []
        # Store full request header request data.
        request_headers: false
        # Store full request content body. if true full lenght, can be limited with a integer
        request_content: false
        # Store reponse basic information. if true full lenght, can be limited with a integer
        response: false
        # Store full response header request data.
        response_headers: false
        # Store response body content
        response_content: false
        # Store data base events, array of events, insert, update, delete
        database: ['insert','update','delete']
        # Store all exception
        exceptions: false
        # Exclude header status exceptions code, 400=bad request, form validation
        exceptions_exclude: [400]
        # Exlucde listed URLS, array
        exclude_urls: ["/auditing"]
```

### Testing in Graviton
* composer require graviton/graviton-service-bundle-audit-tracking
* Inside graviton load the bundle: GravitonBundleBundle:getBundles - add the load of this new bundle
* Enable in config the log_test_calls: true  ( also, so you use the bundle in dev mode )

### Enabling in a Wrapper
* Enable in resources/configuration.sh the new bundle: `\\Graviton\\AuditTrackingBundle\\GravitonAuditTrackingBundle`
* composer require graviton/graviton-service-bundle-audit-tracking
* sh dev-cleanstart.sh
