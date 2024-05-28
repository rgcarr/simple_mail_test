## INTRODUCTION

The Simple Mail Test module allows you to send a single email to a configurable
email address using a Drupal site's default mailsystem.

The primary use case for this module is to check where default emails are sent. 
Other (more complex) mail modules offer this functionality, and this test can 
also be run from a simple Drush script.

## REQUIREMENTS

Ensure your mail system, local environment or server is configured to send mail.

## INSTALLATION

Install as you would normally install a contributed Drupal module.
See: https://www.drupal.org/node/895232 for further information.

## CONFIGURATION
Alter the default testing address at
`/admin/config/system/settings/simple-mail-test`

Submitting the form sets the default email address, and triggers an email to be
sent.

## DRUSH EQUIVALENT
Don't want to install *another* Drupal module? Run this simple Drush script 
instead:

```
drush php-eval '$mailManager Drupal::service("plugin.manager.mail");
 $params["context"]["subject"] = "Test mail subject.";
 $params["context"]["message"] = "Test mail message.";
 $to = "email@example.com"; 
 $langcode = "en"; 
 $result = $mailManager->mail("system", "mail", $to, $langcode, $params);
 if ($result["result"] !== true) {
 echo t("There was a problem sending your mail.");
 }
 else {
 echo t("Your message has been sent.");
 }'
```
Obviously, set `email@example.com` to an email address of your chosing. You
may also wish to change the language code `$langcode`.
## MAINTAINER

Current maintainer for Drupal 10/11 version:

- Robert Carr - https://www.drupal.org/u/robcarr

