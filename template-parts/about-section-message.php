<?php if (!defined('ABSPATH')) exit;

$theme_uri = get_stylesheet_directory_uri(); ?>
<section class="section message">
  <div class="container">

    <?php
    get_template_part(
      'components/section-header',
      null,
      [
        'id' => '', // 見出しID（任意）
        'sub' => 'Message', // 小見出し
        'title' => '代表メッセージ', // メイン見出し
        'tag' => '', // h1〜h6（省略でh2）
        'extra_class' => 'message__header' // 追加クラス（任意）
      ]
    );
    ?>

    <div class="message__content">
      <p class="message__catch">「誰もが当たり前のことを、当たり前にできる世界へ」</p>

      <div class="message__text">
        <p>ＬｉＮＥ ＰＡＲＫのホームページをご覧いただき誠に有り難う御座います。<br>
          弊社では上記の理念を掲げております。<br>
          我々にとって「当たり前」とは～<br>
          ◆ご飯を食べられる<br>
          ◆十分に睡眠をとることができる<br>
          ◆互いの思いを共有する人がいる<br>
          ◆働きたい場所で働ける<br>
          ◆夢を自由に思い描くことができる<br>
          ～などを指しております。<br>
          あまりに「当たり前」で、普段意識さえせず生活している事柄ばかりです。</p>

        <p>"当たり前"ができない方が大勢おられます。<br>
          やる気が足りない、行動力が足りない、努力が足りない・・・だからできない、のでしょうか。<br>
          そもそも、やる気を奮い立たせてくれる環境、行動を後押ししてくれる環境、努力を見守ってくれる環境に恵まれず、何をどうすれば良いのか糸口さえ見失っている方々がおられます。</p>

        <p>我々は、そのような方々に寄り添い、共に考え、共に歩んで行ける関係を築いていくために開業を致しました。<br>
          「当たり前」に生きる上でも、障がいを抱えながら生きる上でも、我々が一番大切にしている"共存共栄"を模索し続けて参ります。<br>
          さて弊社では、開放的な事業所、環境を目指しております。<br>
          ご来訪いただいた皆さまには、仕事の様子を見ていただき、従業員(スタッフ・利用者)とのコミュニケーションを図れる環境を整えております。<br>
          どうぞお気軽にお越しください。</p>
      </div>

      <div class="message__signature">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/sign.svg'); ?>" alt="代表者サイン" class="message__sign">
        <div class="message__name">
          <p class="message__company">株式会社ＬｉＮＥ ＰＡＲＫ</p>
          <p class="message__ceo">代表取締役　大久保　和樹</p>
        </div>
      </div>
    </div>
  </div>
</section>