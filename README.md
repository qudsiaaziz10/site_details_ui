# A Custom Drupal Module Code Sample
### Note
This is a simplified and reduced version of a module I had built for a real use case a while back. It makes it easier for developers to add new Drupal multi sites, and minimizes code changes. It also enables certain users, with the right permissions, to change some of the site details that were normally hard-coded otherwise.

### Example of reading the configuration
```
$config = \Drupal::config('site_details_ui.site_details_configuration');
$company_name = $config->get('company_name');
```
