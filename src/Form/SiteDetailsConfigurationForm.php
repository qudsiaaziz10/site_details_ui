<?php

namespace Drupal\site_details_ui\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a system configuration form for adding and editing site details.
 */
class SiteDetailsConfigurationForm extends ConfigFormBase {


  /**
  * Site details configuration.
  *
  * @var string
  */
  const CONFIG_NAME = 'site_details_ui.site_details_configuration';

  /**
  * Logos.
  *
  * @var array
  */
  const LOGOS = [
    ["form_element" => "header_logo", "logo_url_config" => "header_logo_url"],
    ["form_element" => "mobile_logo", "logo_url_config" => "mobile_logo_url"],
    ["form_element" => "footer_logo", "logo_url_config" => "footer_logo_url"],
  ];

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::CONFIG_NAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_details_ui_site_details_configuration_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::CONFIG_NAME);
    $this->buildCompanyDetailsForm($form, $config);
    $this->buildAddressForm($form, $config);
    $this->buildSocialMediaUrlsForm($form, $config);
    $this->buildLogoForm($form, $config);
    $this->buildColorForm($form, $config);
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('site_details_ui.site_details_configuration');

    $config
      ->set('company_abbreviation', check_markup($form_state->getValue('company_abbreviation'), 'plain_text'))
      ->set('company_name', check_markup($form_state->getValue('company_name'), 'plain_text'))

      ->set('address', check_markup($form_state->getValue('address'), 'plain_text'))

      ->set('facebook', check_markup($form_state->getValue('facebook'), 'plain_text'))
      ->set('twitter', check_markup($form_state->getValue('twitter'), 'plain_text'))
      ->set('instagram', check_markup($form_state->getValue('instagram'), 'plain_text'))
      ->set('pinterest', check_markup($form_state->getValue('pinterest'), 'plain_text'))
      ->set('youtube', check_markup($form_state->getValue('youtube'), 'plain_text'))
      ->set('linkedin', check_markup($form_state->getValue('linkedin'), 'plain_text'))

      ->set('header_logo_fid', $form_state->getValue('header_logo'))
      ->set('footer_logo_fid', $form_state->getValue('footer_logo'))
      ->set('mobile_logo_fid', $form_state->getValue('mobile_logo'))

      ->set('primary-dark', $form_state->getValue('primary_dark'))
      ->set('primary-light', $form_state->getValue('primary_light'))
      ->set('secondary-dark', $form_state->getValue('secondary_dark'))
      ->set('secondary-light', $form_state->getValue('secondary_light'))
      ->set('tertiary-dark', $form_state->getValue('tertiary_dark'))
      ->set('tertiary-light', $form_state->getValue('tertiary-light'))
      ->save();

    $this->saveLogoFiles($form_state);
  }

  /**
   * Provides a form for editing site's social media URLs.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $config
   *   The configuration object for social media URLs.
   */
  private function buildSocialMediaUrlsForm(&$form, $config) {
    // Social Media URLs.
    $form['social_media'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('Social Media URLs'),
      '#description' => 'Please enter the URL for each social media platform.',
    ];

    $form['social_media']['facebook'] = [
      '#type' => 'url',
      '#title' => $this->t('Facebook'),
      '#default_value' => $config->get('facebook'),
    ];

    $form['social_media']['twitter'] = [
      '#type' => 'url',
      '#title' => $this->t('Twitter'),
      '#default_value' => $config->get('twitter'),
    ];

    $form['social_media']['instagram'] = [
      '#type' => 'url',
      '#title' => $this->t('Instagram'),
      '#default_value' => $config->get('instagram'),
    ];

    $form['social_media']['pinterest'] = [
      '#type' => 'url',
      '#title' => $this->t('Pinterest'),
      '#default_value' => $config->get('pinterest'),
    ];

    $form['social_media']['youtube'] = [
      '#type' => 'url',
      '#title' => $this->t('YouTube'),
      '#default_value' => $config->get('youtube'),
    ];

    $form['social_media']['linkedin'] = [
      '#type' => 'url',
      '#title' => $this->t('LinkedIn'),
      '#default_value' => $config->get('linkedin'),
    ];
  }

  /**
   * Builds the address form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $config
   *   The configuration object for social media URLs.
   */
  private function buildAddressForm(&$form, $config) {
    // Footer Address.
    $form['address'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('Address'),
    ];

    $form['address']['address'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Address'),
      '#default_value' => $config->get('address'),
      '#description' => $this->t('No HTML tags allowed.'),
    ];
  }

  /**
   * Builds the address form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $config
   *   The configuration object for social media URLs.
   */
  private function buildCompanyDetailsForm(&$form, $config) {
    // Company Details.
    $form['company_details'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('Company Details'),
    ];

    // Company Name.
    $form['company_details']['company_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company Name'),
      '#default_value' => $config->get('company_name'),
      '#required' => TRUE,
      '#description' => $this->t('No HTML tags allowed.'),
    ];

    // Company Abbreviation.
    $form['company_details']['company_abbreviation'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Company Abbreviation'),
      '#default_value' => $config->get('company_abbreviation'),
      '#required' => TRUE,
      '#description' => $this->t('No HTML tags allowed.'),
    ];
  }

  /**
   * Provides the logo form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $config
   *   The configuration object for social media URLs.
   */
  private function buildLogoForm(&$form, $config) {
    // Logo.
    $form['logos'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('Logos'),
    ];

    // Header Logo.
    $form['logos']['header_logo'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Header (desktop) logo'),
      '#default_value' => $config->get('header_logo_fid'),
      '#upload_validators' => [
        'file_validate_extensions' => ['svg'],
      ],
      '#description' => 'This image should be 100px wide and 50-100px tall.',
    ];

    // Mobile Logo.
    $form['logos']['mobile_logo'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Header (mobile) logo'),
      '#default_value' => $config->get('mobile_logo_fid'),
      '#upload_validators' => [
        'file_validate_extensions' => ['svg'],
      ],
      '#description' => 'This image should be 50px wide by 15-50px tall.',
    ];

    // Footer Logo.
    $form['logos']['footer_logo'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Footer logo'),
      '#default_value' => $config->get('footer_logo_fid'),
      '#upload_validators' => [
        'file_validate_extensions' => ['svg'],
      ],
      '#description' => 'This image should be 150px wide by 50-150px tall.',
    ];
  }

  /**
   * Provides the color form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Config\Config|\Drupal\Core\Config\ImmutableConfig $config
   *   The configuration object for social media URLs.
   */
  private function buildColorForm(&$form, $config) {

    // Color.
    $form['color'] = [
      '#type' => 'details',
      '#title' => $this
        ->t('Color'),
      '#description' => $this->t('Use the Google Chrome web browser to see and/or add color HEX values.'),
    ];

    // Primary Dark.
    $form['color']['primary_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary Dark'),
      '#default_value' => $config->get('primary-dark'),
      '#description' => $this->t('This color is used for ... , and should pass contrast guidelines with white.'),
    ];

    // Primary Light.
    $form['color']['primary_light'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary Light'),
      '#default_value' => $config->get('primary-light'),
      '#description' => $this->t('TThis color is used for ... , and should pass contrast guidelines with black.'),
    ];

    // Secondary Dark.
    $form['color']['secondary_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary Dark'),
      '#default_value' => $config->get('secondary-dark'),
      '#description' => $this->t('This color is used for ... , and should pass contrast guidelines with white.'),
    ];

    // Secondary Light.
    $form['color']['secondary_light'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary Light'),
      '#default_value' => $config->get('secondary-light'),
      '#description' => $this->t('This color is used for ... , and should pass contrast guidelines with black.'),
    ];

    // Tertiary Dark.
    $form['color']['tertiary_dark'] = [
      '#type' => 'color',
      '#title' => $this->t('Tertiary Dark'),
      '#default_value' => $config->get('tertiary-dark'),
      '#description' => $this->t('This color is used for ... , and should pass contrast guidelines with white.'),
    ];

    // Tertiary Dark.
    $form['color']['tertiary-light'] = [
      '#type' => 'color',
      '#title' => $this->t('Tertiary Light'),
      '#default_value' => $config->get('tertiary-light'),
      '#description' => $this->t('This color is used for ... , and should pass contrast guidelines with black.'),
    ];
  }

  /**
   * Saves the logo image files.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function saveLogoFiles(FormStateInterface $form_state) {
    foreach (self::LOGOS as $logo) {
      $logo_config = $form_state->getValue($logo['form_element']);
      if (!empty($logo_config)) {
        $logo_file = File::load($logo_config[0]);
        if (!empty($logo_file)) {
          $logo_file->setPermanent();
          $logo_file->save();
          $logo_uri = $logo_file->getFileUri();
          $logo_url = \Drupal::service('file_url_generator')->generate($logo_uri)->toString();
          $this->config('site_details_ui.site_details_configuration')->set($logo['logo_url_config'], $logo_url)->save();
        }
      }
      else {
        $this->config('site_details_ui.site_details_configuration')->set($logo['logo_url_config'], NULL)->save();
      }
    }
  }

}
