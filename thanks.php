<?php
/**
 * theme root / thanks.php - 完了画面（5秒後にbackへ戻す）
 */
if (!defined('ABSPATH')) exit;

/* ★ キャッシュ無効化 */
nocache_headers();

/* ★ 検索除外 */
add_action('wp_head', function () {
  echo "<meta name=\"robots\" content=\"noindex,nofollow\" />\n";
}, 0);

get_header();


$back = isset($_GET['back']) ? esc_url_raw(wp_unslash($_GET['back'])) : home_url('/contact/');
$back_host = wp_parse_url($back, PHP_URL_HOST);
$site_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
if ($back_host && $site_host && $back_host !== $site_host) {
  $back = home_url('/contact/');
}

/* 戻り先で成功バナーを出さないため、Transient を掃除 */
$ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$salt = defined('NONCE_SALT') ? NONCE_SALT : 'lp_contact_salt';
delete_transient('lp_contact_ok_'  . md5($ip . $salt));
delete_transient('lp_contact_err_' . md5($ip . $salt));
delete_transient('lp_contact_old_' . md5($ip . $salt));
?>
<main class="thanks container">
  <div class="thanks__card">
    <div class="thanks__icon" aria-hidden="true">
      <!-- シンプルなチェックアイコン -->
      <svg viewBox="0 0 24 24" fill="none">
        <path d="M20 6L9 17l-5-5" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <h1 class="thanks__title">お問い合わせありがとうございました</h1>
    <p class="thanks__text">送信が完了しました。確認の自動返信メールをお送りしています。担当より追ってご連絡いたします。</p>
    <p class="thanks__note">5秒後にお問い合わせページへ自動的に戻ります。</p>

    <div class="thanks__actions">
      <a class="contact__btn contact__btn--primary" href="<?php echo esc_url($back); ?>">今すぐ戻る</a>
    </div>
  </div>
</main>
<script> setTimeout(function(){ location.href = <?php echo json_encode($back); ?>; }, 5000); </script>
<?php get_footer(); ?>