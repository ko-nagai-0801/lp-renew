<?php
/**
 * theme root / confirm.php - お問い合わせ確認画面
 * ルール: /confirm/?t={ticket}
 */
if (!defined('ABSPATH')) exit;

/* ★ キャッシュ無効化（履歴・中間キャッシュ対策） */
nocache_headers();

/* ★ 検索除外（headに noindex を挿入） */
add_action('wp_head', function () {
  echo "<meta name=\"robots\" content=\"noindex,nofollow\" />\n";
}, 0);

get_header();

/* 以降は従来どおり ------------------------------------------- */
$ticket  = isset($_GET['t']) ? sanitize_text_field(wp_unslash($_GET['t'])) : '';
$pending = $ticket ? get_transient('lp_contact_pending_' . $ticket) : false;

if (!$pending || empty($pending['data'])) : ?>
  <main class="container py-5">
    <h1>確認データの有効期限切れ</h1>
    <p>お手数ですが、最初からやり直してください。</p>
    <p><a class="contact__btn contact__btn--primary" href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせフォームへ戻る</a></p>
  </main>
<?php
  get_footer();
  exit;
endif;

$d    = $pending['data'];
$back = $pending['redirect'] ?: home_url('/contact/');

/* reCAPTCHA site key（未設定なら空） */
$site_key = apply_filters('lp_recaptcha_site_key', defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : '');
?>
<main class="contact container py-4">
  <header class="contact__header">
    <p class="section__sub">Confirm</p>
    <h1 class="section__title">入力内容のご確認</h1>
    <p class="contact__note">以下の内容でお間違いなければ「送信する」を押してください。</p>
  </header>

  <section class="confirm">
    <dl class="confirm__list">
      <div class="confirm__row"><dt>お問い合わせ種別</dt><dd><?php echo esc_html($d['type']); ?></dd></div>
      <div class="confirm__row"><dt>お名前</dt><dd><?php echo esc_html($d['name']); ?></dd></div>
      <div class="confirm__row"><dt>ふりがな</dt><dd><?php echo esc_html($d['name_kana']); ?></dd></div>
      <div class="confirm__row"><dt>会社名</dt><dd><?php echo $d['company'] !== '' ? esc_html($d['company']) : '（未入力）'; ?></dd></div>
      <div class="confirm__row"><dt>メール</dt><dd><?php echo esc_html($d['email']); ?></dd></div>
      <div class="confirm__row"><dt>電話番号</dt><dd><?php echo $d['phone'] !== '' ? esc_html($d['phone']) : '（未入力）'; ?></dd></div>
      <div class="confirm__row"><dt>お問い合わせ内容</dt><dd><pre class="confirm__pre"><?php echo esc_html($d['message']); ?></pre></dd></div>
    </dl>

    <div class="contact__actions" style="display:flex; gap:12px; flex-wrap:wrap;">
      <!-- 修正する -->
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="lp_contact_edit">
        <input type="hidden" name="ticket" value="<?php echo esc_attr($ticket); ?>">
        <?php wp_nonce_field('lp_contact_send', 'contact_nonce'); // 使い回しOK ?>
        <button type="submit" class="contact__btn">修正する</button>
      </form>

      <!-- 送信する -->
      <form id="confirm-send-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="lp_contact_send">
        <input type="hidden" name="ticket" value="<?php echo esc_attr($ticket); ?>">
        <input type="hidden" name="_back"  value="<?php echo esc_url($back); ?>">
        <input type="hidden" name="g-recaptcha-response" value="">
        <?php wp_nonce_field('lp_contact_send', 'contact_nonce'); ?>
        <button type="submit" class="contact__btn contact__btn--primary">送信する</button>
      </form>
    </div>
  </section>
</main>

<?php if ($site_key) : ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr($site_key); ?>"></script>
  <script>
  (function(){
    var form = document.getElementById('confirm-send-form');
    if(!form) return;
    form.addEventListener('submit', function(e){
      e.preventDefault();
      if(!window.grecaptcha) { form.submit(); return; }
      grecaptcha.ready(function(){
        grecaptcha.execute('<?php echo esc_js($site_key); ?>', {action: 'contact_send'}).then(function(token){
          var input = form.querySelector('input[name="g-recaptcha-response"]');
          if(input) input.value = token;
          form.submit();
        });
      });
    });
  })();
  </script>
<?php endif; ?>

<?php get_footer(); ?>
