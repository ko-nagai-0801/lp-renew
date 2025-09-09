<?php

/**
 * プライバシーポリシー 本文（条文本体）
 * template-parts/privacy-section-body.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0 (2025-09-09): 「第8条（Cookieその他の技術の利用）」を追加。以降の条番号を繰上げ。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;
?>

<section class="privacy section container">
  <div class="privacy__inner">

    <?php
    // セクション見出し（共通コンポーネント）
    get_template_part('components/section-header', null, [
      'id'          => 'privacy-heading',
      'sub'         => 'Privacy Policy',
      'title'       => 'プライバシーポリシー',
      'tag'         => 'h2',
      'variant'     => 'privacy',
      'extra_class' => 'privacy__header',
    ]);
    ?>

    <article class="privacy__content" aria-labelledby="privacy-heading">
      <p class="privacy__intro">
        株式会社ＬｉＮＥ ＰＡＲＫ（以下「当社」といいます。）は、当社が運営するウェブサイト（以下「本サービス」といいます。）において、ユーザーの個人情報を以下のプライバシーポリシー（以下「本ポリシー」といいます。）に基づいて取り扱います。
      </p>

      <!-- 第1条 -->
      <section class="privacy__section" id="pp-1">
        <h3 class="privacy__heading">第1条（個人情報の定義）</h3>
        <p class="privacy__text">
          本ポリシーにおける「個人情報」とは、生存する個人に関する情報であり、氏名、生年月日、住所、電話番号、メールアドレスなどの情報により個人を識別できる情報を指します。これには、外見、指紋、声紋などの生体情報も含まれます。
        </p>
      </section>

      <!-- 第2条 -->
      <section class="privacy__section" id="pp-2">
        <h3 class="privacy__heading">第2条（個人情報の収集方法）</h3>
        <p class="privacy__text">
          当社は、ユーザーが本サービスの利用登録を行う際や、ユーザーと提携先との間で取引が発生した際に、個人情報を収集することがあります。これには、直接ユーザーから提供される情報のほか、当社の提携先から提供される情報も含まれます。
        </p>
      </section>

      <!-- 第3条 -->
      <section class="privacy__section" id="pp-3">
        <h3 class="privacy__heading">第3条（個人情報の利用目的）</h3>
        <p class="privacy__text">当社が個人情報を収集・利用する目的は以下の通りです。</p>
        <ul class="privacy__list">
          <li class="privacy__list-item">サービスの提供・運営</li>
          <li class="privacy__list-item">ユーザーからの問い合わせへの対応</li>
          <li class="privacy__list-item">新機能やキャンペーンの案内</li>
          <li class="privacy__list-item">サービス利用料の請求</li>
          <li class="privacy__list-item">利用規約違反の調査と対応</li>
          <li class="privacy__list-item">その他、上記に付随する目的</li>
        </ul>
      </section>

      <!-- 第4条 -->
      <section class="privacy__section" id="pp-4">
        <h3 class="privacy__heading">第4条（利用目的の変更）</h3>
        <p class="privacy__text">
          当社は、合理的な範囲で利用目的が変更前と関連性を持つ場合に限り、個人情報の利用目的を変更することがあります。変更を行った場合、ユーザーには本ウェブサイト上での公表やメール等での通知を通じて、変更内容を報告します。
        </p>
      </section>

      <!-- 第5条 -->
      <section class="privacy__section" id="pp-5">
        <h3 class="privacy__heading">第5条（個人情報の第三者提供）</h3>
        <p class="privacy__text">
          当社は、法令に基づく場合やユーザーの同意がある場合を除き、個人情報を第三者に提供しません。例外として、緊急の事態や公衆の安全を守る必要がある場合には、事前の同意なく個人情報を提供することがあります。
        </p>
      </section>

      <!-- 第6条 -->
      <section class="privacy__section" id="pp-6">
        <h3 class="privacy__heading">第6条（個人情報の開示）</h3>
        <p class="privacy__text">
          ユーザーが自己の個人情報の開示を求めた場合、当社は遅滞なくこれを開示します。ただし、第三者の権利を害するおそれがある場合や当社の業務実施に支障がある場合は、開示を拒否することがあります。
        </p>
      </section>

      <!-- 第7条 -->
      <section class="privacy__section" id="pp-7">
        <h3 class="privacy__heading">第7条（個人情報の訂正および削除）</h3>
        <p class="privacy__text">
          ユーザーは、自身の個人情報に誤りがあると判断した場合、当社に訂正または削除を求めることができます。当社は、必要な調査を行った上で、迅速に対応します。
        </p>
      </section>

      <!-- ★ 追加：第8条 Cookieその他の技術の利用 -->
      <section class="privacy__section" id="pp-8">
        <h3 class="privacy__heading">第8条（Cookie（クッキー）その他の技術の利用）</h3>
        <p class="privacy__text">
          当社は、本サービスにおいて Cookie（クッキー）やローカルストレージ等の類似技術を利用します。これらは、（1）サイトの安全な動作と利便性の向上、（2）閲覧状況の把握によるコンテンツ改善、（3）不正利用の防止のために用いられます。
        </p>
        <p class="privacy__text">
          本サービスでは、Google LLC が提供するアクセス解析ツール「Google アナリティクス（GA4）」を利用しています。Google アナリティクスは Cookie を用いてトラフィックデータ（閲覧したページ、滞在時間、参照元等）を収集します。当社が取得するデータには、個人を直接特定する情報は含まれません。収集・利用・保管は Google の規約およびプライバシーポリシーに従い取り扱われます。詳細は以下をご覧ください。
        </p>
        <ul class="privacy__list">
          <li class="privacy__list-item"><a href="https://marketingplatform.google.com/about/analytics/terms/jp/" target="_blank" rel="noopener noreferrer">Google アナリティクス サービス利用規約</a></li>
          <li class="privacy__list-item"><a href="https://policies.google.com/technologies/partner-sites?hl=ja" target="_blank" rel="noopener noreferrer">Google によるデータの使用（パートナーサイト）</a></li>
          <li class="privacy__list-item"><a href="https://policies.google.com/?hl=ja" target="_blank" rel="noopener noreferrer">Google ポリシーと規約</a></li>
        </ul>

        <?php
        // ★ ここから“再選択”の文（ショートコードがあればボタン/リンク、無ければ直リンク）
        $reset_link = '<a href="' . esc_url(add_query_arg('consent', 'reset')) . '" ' .
          'class="link-underline" ' .
          'onclick="if(window.lpConsent){window.lpConsent.reset();return false;}">Cookie設定をやり直す</a>';
        ?>
        <p class="privacy__text">
          Cookie を利用したくない場合は、ブラウザ設定で無効化できます。無効化または同意しないを選択した場合、Cookie を用いない最小限の計測（cookieless）が行われることがあります。
          なお、本サービス上での同意の再選択は
          <?php if (function_exists('shortcode_exists') && shortcode_exists('lp_consent_reset')): ?>
            <?php echo do_shortcode('[lp_consent_reset text="Cookie設定をやり直す" class="link-underline"]'); ?>
          <?php else: ?>
            <?php echo $reset_link; ?>
          <?php endif; ?>
          からいつでも行えます。
        </p>
      </section>


      <!-- 以降の条番号を繰上げ：第9条 -->
      <section class="privacy__section" id="pp-9">
        <h3 class="privacy__heading">第9条（プライバシーポリシーの変更）</h3>
        <p class="privacy__text">
          本ポリシーの内容は、法令の変更またはその他の理由により変更されることがあります。変更後のポリシーは、本ウェブサイトに掲載された時点で効力を発生します。
        </p>
      </section>

      <!-- 第10条 -->
      <section class="privacy__section" id="pp-10">
        <h3 class="privacy__heading">第10条（お問い合わせ窓口）</h3>
        <address class="privacy__contact">
          <p class="privacy__text">本ポリシーに関するお問い合わせは、以下の窓口までお願いいたします。</p>
          <dl class="privacy__contact-list">
            <div class="privacy__contact-row">
              <dt class="privacy__contact-label">住所</dt>
              <dd class="privacy__contact-data">東京都足立区綾瀬 2-27-4 D1 AYASE 2F</dd>
            </div>
            <div class="privacy__contact-row">
              <dt class="privacy__contact-label">社名</dt>
              <dd class="privacy__contact-data">株式会社ＬｉＮＥ ＰＡＲＫ</dd>
            </div>
            <div class="privacy__contact-row">
              <dt class="privacy__contact-label">代表取締役</dt>
              <dd class="privacy__contact-data">大久保　和樹</dd>
            </div>
            <div class="privacy__contact-row">
              <dt class="privacy__contact-label">Eメールアドレス</dt>
              <dd class="privacy__contact-data">
                <a href="mailto:info@linepark.co.jp" class="privacy__contact-link">info@linepark.co.jp</a>
              </dd>
            </div>
          </dl>
        </address>
      </section>

      <p class="privacy__closing">
        以上、ユーザーの権利とプライバシーの保護を最優先に考えた当社の取り組みをご理解いただければ幸いです。
      </p>
    </article>

  </div>
</section>