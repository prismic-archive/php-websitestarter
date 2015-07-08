<div class="social-share-widgets">
    <span class="social-share-button facebook" onclick="popUp=window.open('https://www.facebook.com/sharer/sharer.php?u=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="icon-facebook"></i>
    </span>
    <span class="social-share-button google" onclick="popUp=window.open('https://plus.google.com/share?url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="icon-google-plus"></i>
    </span>
    <span class="social-share-button twitter" onclick="popUp=window.open('https://twitter.com/intent/tweet?url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="icon-twitter"></i>
    </span>
    <span class="social-share-button linkedin" onclick="popUp=window.open('https://www.linkedin.com/shareArticle?mini=true&url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="icon-linkedin"></i>
    </span>

    <a class="social-share-button mail" href="mailto:?subject=<?= email_title() ?>&body=<?= email_description() ?>">
        <i class="icon-email"></i>
    </a>
</div>