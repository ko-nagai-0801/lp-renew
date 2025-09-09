<?php

/**
 * Contact フォーム（表示）— Step1: 確認へ進む
 * template-parts/contact-section-form.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

/** 設定 */
$CONTACT_PAGE_URL = get_permalink();
$PRIVACY_URL      = home_url('/privacy/'); // プライバシーポリシーへのリンク

/** 結果受け取り（Transient） */
$ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$salt    = defined('NONCE_SALT') ? NONCE_SALT : 'lp_contact_salt';
$ok_key  = 'lp_contact_ok_'  . md5($ip . $salt);
$err_key = 'lp_contact_err_' . md5($ip . $salt);
$old_key = 'lp_contact_old_' . md5($ip . $salt);

$sent   = (bool) get_transient($ok_key);
$errors = get_transient($err_key) ?: [];
$old    = get_transient($old_key) ?: [
  'type'          => '',
  'name'          => '',
  'name_kana'     => '',
  'company'       => '',
  'email'         => '',
  'email_confirm' => '',
  'phone'         => '',
  'message'       => '',
  'agree'         => '',
];

if ($sent)   delete_transient($ok_key);
if ($errors) delete_transient($err_key);
if ($old)    delete_transient($old_key);

/** ラジオ選択肢（4つに固定） */
$types = ['お仕事のご相談', 'ご利用/ご見学のご相談', '協賛/ご支援など', 'その他'];
?>

<section class="contact section" id="contact-form">
  <div class="container">

    <?php
    get_template_part('components/section-header', null, [
      'id'      => 'contact-heading',
      'sub'     => 'Contact',
      'title'   => 'お問い合わせ',
      'tag'     => 'h2',
      'variant' => 'contact',
      'extra_class' => 'contact__header',
    ]);
    ?>

    <?php if ($sent) : ?>
      <div class="contact__notice contact__notice--success" role="status">
        <p>お問い合わせを送信しました。確認の自動返信メールをお送りしています。担当より追ってご連絡いたします。</p>
        <p><a class="contact__btn contact__btn--primary" href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a></p>
      </div>
    <?php else : ?>
      <?php if ($errors) : ?>
        <div class="contact__notice contact__notice--error" role="alert">
          <p>入力内容に誤りがあります。各項目をご確認ください。</p>
        </div>
      <?php endif; ?>

      <!-- Step1: 確認へ。送信先は admin-post.php?action=lp_contact_confirm -->
      <form class="contact__form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
        <input type="hidden" name="action" value="lp_contact_confirm">
        <input type="hidden" name="_redirect" value="<?php echo esc_url($CONTACT_PAGE_URL); ?>">
        <?php wp_nonce_field('lp_contact_step1', 'contact_nonce'); ?>

        <!-- ハニーポット（実項目 company と衝突しない名称） -->
        <div class="contact__hp" aria-hidden="true">
          <label>会社HP</label>
          <input type="text" name="hp_company" tabindex="-1" autocomplete="off">
        </div>

        <div class="contact__grid">
          <!-- ラジオ 4択 -->
          <fieldset class="contact__field contact__field--full" aria-describedby="<?php echo isset($errors['type']) ? 'err-type' : ''; ?>">
            <legend class="contact__legend">お問い合わせ種別 <span class="contact__req">必須</span></legend>

            <div class="contact__radios">
              <?php foreach ($types as $i => $t) :
                $id = 'cf-type-' . md5($t);
              ?>
                <label class="contact__radio" for="<?php echo esc_attr($id); ?>">
                  <input
                    id="<?php echo esc_attr($id); ?>"
                    type="radio"
                    name="type"
                    value="<?php echo esc_attr($t); ?>"
                    <?php
                    // required はグループに1つ付ければ十分（全部に付けなくてOK）
                    if ($i === 0) echo 'required';
                    checked($old['type'], $t);
                    ?>>
                  <span><?php echo esc_html($t); ?></span>
                </label>
              <?php endforeach; ?>
            </div>

            <?php if (isset($errors['type'])) : ?>
              <p id="err-type" class="contact__error" role="alert"><?php echo esc_html($errors['type']); ?></p>
            <?php endif; ?>
          </fieldset>


          <!-- お名前 -->
          <div class="contact__field">
            <label for="cf-name" class="contact__label">
              お名前 <span class="contact__req">必須</span>
            </label>
            <input id="cf-name" name="name" type="text" class="contact__input"
              placeholder="例）山田 太郎" value="<?php echo esc_attr($old['name']); ?>"
              required maxlength="64" autocomplete="name"
              aria-invalid="<?php echo isset($errors['name']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['name']) ? 'err-name' : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
              <p id="err-name" class="contact__error"><?php echo esc_html($errors['name']); ?></p>
            <?php endif; ?>
          </div>

          <!-- ふりがな -->
          <div class="contact__field">
            <label for="cf-kana" class="contact__label">
              ふりがな<span class="contact__req">必須</span>
            </label>
            <input id="cf-kana" name="name_kana" type="text" class="contact__input"
              placeholder="例）やまだ たろう" value="<?php echo esc_attr($old['name_kana']); ?>"
              required maxlength="64" autocomplete="additional-name"
              aria-invalid="<?php echo isset($errors['name_kana']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['name_kana']) ? 'err-kana' : ''; ?>">
            <?php if (isset($errors['name_kana'])) : ?>
              <p id="err-kana" class="contact__error"><?php echo esc_html($errors['name_kana']); ?></p>
            <?php endif; ?>
          </div>

          <!-- 会社名（任意） -->
          <div class="contact__field contact__field--full">
            <label for="cf-company" class="contact__label">会社名 <span class="contact__opt">任意</span></label>
            <input id="cf-company" name="company" type="text" class="contact__input"
              placeholder="会社名を入力してください" value="<?php echo esc_attr($old['company']); ?>"
              maxlength="200" autocomplete="organization" aria-invalid="false">
          </div>

          <!-- メール -->
          <div class="contact__field">
            <label for="cf-email" class="contact__label">メールアドレス <span class="contact__req">必須</span></label>
            <input id="cf-email" name="email" type="email" class="contact__input"
              placeholder="メールアドレスを入力してください" value="<?php echo esc_attr($old['email']); ?>"
              required maxlength="254" autocomplete="email"
              aria-invalid="<?php echo isset($errors['email']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['email']) ? 'err-email' : ''; ?>">
            <?php if (isset($errors['email'])) : ?>
              <p id="err-email" class="contact__error"><?php echo esc_html($errors['email']); ?></p>
            <?php endif; ?>
          </div>

          <!-- メール（確認） -->
          <div class="contact__field">
            <label for="cf-email2" class="contact__label">メールアドレス（確認用） <span class="contact__req">必須</span></label>
            <input id="cf-email2" name="email_confirm" type="email" class="contact__input"
              placeholder="上記と同じメールアドレスを入力してください" value="<?php echo esc_attr($old['email_confirm']); ?>"
              required maxlength="254" autocomplete="email"
              aria-invalid="<?php echo isset($errors['email_confirm']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['email_confirm']) ? 'err-email2' : ''; ?>">
            <?php if (isset($errors['email_confirm'])) : ?>
              <p id="err-email2" class="contact__error"><?php echo esc_html($errors['email_confirm']); ?></p>
            <?php endif; ?>
          </div>

          <!-- 電話番号（任意） -->
          <div class="contact__field contact__field--full">
            <label for="cf-phone" class="contact__label">電話番号 <span class="contact__opt">任意</span></label>
            <input id="cf-phone" name="phone" type="tel" class="contact__input"
              placeholder="電話番号を入力してください" value="<?php echo esc_attr($old['phone']); ?>"
              maxlength="20" inputmode="tel" autocomplete="tel-national"
              aria-invalid="<?php echo isset($errors['phone']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['phone']) ? 'err-phone' : ''; ?>">
            <?php if (isset($errors['phone'])) : ?>
              <p id="err-phone" class="contact__error"><?php echo esc_html($errors['phone']); ?></p>
            <?php endif; ?>
          </div>

          <!-- 本文 -->
          <div class="contact__field contact__field--full">
            <label for="cf-message" class="contact__label">お問合せ内容 <span class="contact__req">必須</span></label>
            <textarea
              id="cf-message" name="message" class="contact__textarea" rows="8" required maxlength="4000"
              placeholder="お問い合わせ内容を入力してください"
              aria-invalid="<?php echo isset($errors['message']) ? 'true' : 'false'; ?>"
              aria-describedby="<?php echo isset($errors['message']) ? 'err-message' : ''; ?>"><?php echo esc_textarea($old['message']); ?></textarea>
            <?php if (isset($errors['message'])) : ?>
              <p id="err-message" class="contact__error"><?php echo esc_html($errors['message']); ?></p>
            <?php endif; ?>
            <p class="contact__note contact__note--small">(1000文字以内)</p>
          </div>

          <!-- 同意 -->
          <div class="contact__field contact__field--full">
            <div class="contact__agree">
              <label class="contact__checkbox">
                <input type="checkbox" name="agree" value="1" <?php checked($old['agree'], '1'); ?>>
                <span>
                  <a href="<?php echo esc_url($PRIVACY_URL); ?>" target="_blank" rel="noopener">プライバシーポリシー</a>
                  に同意します
                </span>
              </label>
              <?php if (isset($errors['agree'])) : ?>
                <p class="contact__error"><?php echo esc_html($errors['agree']); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <?php if (isset($errors['send'])) : ?>
          <p class="contact__error contact__error--global"><?php echo esc_html($errors['send']); ?></p>
        <?php endif; ?>

        <div class="contact__actions">
          <button class="contact__btn contact__btn--primary" type="submit">確認ページへ</button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</section>