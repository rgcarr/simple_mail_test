<?php

declare(strict_types=1);

namespace Drupal\simple_mail_test\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Simple mail test settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'simple_mail_test_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['simple_mail_test.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['recipient'] = [
      '#type' => 'email',
      '#title' => $this->t('Recipient email'),
      '#description' => $this->t("Send a test email to this address from the default mail system, and save this email address as the default."),
      '#default_value' => $this->config('simple_mail_test.settings')->get('recipient'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if ($form_state->getValue('example') === 'wrong') {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('The value is not correct.'),
    //     );
    //   }
    // @endcode
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('simple_mail_test.settings')
      ->set('recipient', $form_state->getValue('recipient'))
      ->save();

    // Send email
    $mailManager = \Drupal::service('plugin.manager.mail');
    $params['context']['subject'] = $this->t("Email Test Message");
    $params['context']['message'] = $this->t("If you can read this your site can send emails through the default mail system.");
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $recipient = $form_state->getValue('recipient');

    $result = $mailManager->mail("system", "mail", $recipient, $langcode, $params);
    if ($result['result'] !== TRUE) {
      $message = t('Error sending email notification to @email.', ['@email' => $recipient]);
      \Drupal::logger('simple_mail_test')->error($message);
      return;
    } else {
      $message = t('Email notification sent to @email', ['@email' => $recipient]);
      \Drupal::logger('simple_mail_test')->notice($message);
      \Drupal::messenger()->addMessage($message);
    }

    parent::submitForm($form, $form_state);

  }

}
