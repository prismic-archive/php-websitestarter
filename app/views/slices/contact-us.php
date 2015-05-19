
<?php

  $items = $slice->getValue()->getArray();

  $contact = $items[0];

?>

<div class="row-centered-separate contact-us">

  <div class="col-2">

    <form name="contact-form" action="#">

      <div class="form-group">

        <label for="sender"><?= $contact->getText('email-label'); ?></label>

        <input type="email" spellcheck="false" name="sender" />

      </div>

      <div class="form-group">

        <label for="subject"><?= $contact->getText('subject-label'); ?></label>

        <input type="text" spellcheck="false" name="subject" />

      </div>

      <div class="form-group">

        <label for="message"><?= $contact->getText('message-label'); ?></label>

        <textarea name="message"></textarea>

      </div>

      <input type="hidden" name="token" value="<?=  mailgun_domain_sha1() ?>" />

      <input type="hidden" name="pubkey" value="<?= mailgun_pubkey() ?>" />

      <button class="send button" disabled="disabled">Send</button>

      <span data-success="<?= $contact->getText('success-label'); ?>" data-error="<?= $contact->getText('error-label'); ?>" class="feedback"></span>

    </form>

  </div>

  <div class="col-2">

    <div class="map" data-address="<?= $contact->getText('address') ?>"></div>

    <p class="address"><?= $contact->getText('address') ?></p>

  </div>

</div>
