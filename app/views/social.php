<div class="social-share-widgets">
    <span class="social-share-button facebook" onclick="popUp=window.open('https://www.facebook.com/sharer/sharer.php?u=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="fa fa-facebook"></i>
    </span>
    <span class="social-share-button google" onclick="popUp=window.open('https://plus.google.com/share?url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="fa fa-google-plus"></i>
    </span>
    <span class="social-share-button twitter" onclick="popUp=window.open('https://twitter.com/intent/tweet?url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="fa fa-twitter"></i>
    </span>
    <span class="social-share-button linkedin" onclick="popUp=window.open('https://www.linkedin.com/shareArticle?mini=true&url=<?= page_url() ?>', 'popupwindow', 'scrollbars=yes,width=800,height=400');popUp.focus();return false">
        <i class="fa fa-linkedin"></i>
    </span>
    <a class="social-share-button mail" href="mailto:?subject=<?= email_title() ?>&body=<?= email_description() ?>">
        <i class="fa fa-envelope-o"></i>
    </a>
</div>