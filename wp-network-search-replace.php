<?php

/**
 * Run Search and Replace on WordPress Network sites.
 */
if (!empty($_ENV['PANTHEON_ENVIRONMENT'] && $_ENV['PANTHEON_ENVIRONMENT'] !== 'live') && !empty($_POST['wf_type'] && $_POST['wf_type'] == 'clone_database')) {

  // Get environment contexts
  $target = $_POST['to_environment'];
  $source = $_POST['from_environment'];

  // Get domains
  $old_domain = get_domain($source);
  $new_domain = get_domain($target);
  
  // Run Search Replace on WPMS sites
  $cmd = "wp search-replace '{$old_domain}' '{$new_domain}' --precise --recurse-objects --all-tables --verbose --network --skip-columns=guid";
  passthru($cmd);
}

/**
 * Fetch customer domains.
 *
 * @param [string] $env
 * @return void
 */
function get_domain($env) {
  $req = pantheon_curl("https://api.live.getpantheon.com/sites/self/environments/{$env}/hostnames", NULL, 8443);
  $domains = json_decode($req['body'], true);
  
  // Check if custom domains are available.
  if (count($domains) > 1) {
    // Check if there is a primary domain available. If so, use that.
    return get_primary_domain($domains, $env);
  } else {
    return get_env_domain($env); 
  }
}

/**
 * Return current environment platform domain
 *
 * @return string
 */
function get_env_domain($env = null) {
  $sub = (!empty($env)) ? $env : $_ENV['PANTHEON_ENVIRONMENT'];
  return "{$sub}-{$_ENV['PANTHEON_SITE_NAME']}.pantheonsite.io";
}

/**
 * Get primary domain (if available)
 *
 * @param [array] $domains
 * @param [string] $env
 * @return void
 */
function get_primary_domain($domains, $env = null) {
    // Loop through domains
    foreach($domains as $domain) {
        // Only look at custom domains
        if (!empty($domain['type']) && $domain['type'] == 'custom') {
            // If domain is marked as primary, use that.
            if (!empty($domain['primary'])) {
                return $domain['key'];
            }
        }
    }
    return get_env_domain($env);
}
