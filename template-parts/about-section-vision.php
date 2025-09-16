<?php
/**
 * About ビジョンセクション
 * template-parts/about-section-vision.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<section class="section vision">
  <div class="container">

    <?php
    get_template_part(
      'components/section-header',
      null,
      [
        'id' => '', // 見出しID（任意）
        'sub' => 'Vision', // 小見出し
        'title' => 'ビジョン', // メイン見出し
        'tag' => '', // h1〜h6（省略でh2）
        'extra_class' => 'vision__header' // 追加クラス（任意）
      ]
    );
    ?>
    <div class="vision__content">
      <div class="vision__bg"></div>

      <ul class="vision__list">
        <li class="vision__item">
          <h3 class="vision__heading">
            <span class="vision__initial">M</span>ind
            <span class="vision__subtitle">(心に寄り添い続ける)</span>
          </h3>
          <p class="vision__text">誰にでも、心という土台があり全てが成り立っている。1人1人の人格や特性、環境を尊重し、心で寄り添い合う。</p>
        </li>

        <li class="vision__item">
          <h3 class="vision__heading">
            <span class="vision__initial">O</span>rdinary
            <span class="vision__subtitle">(当たり前のことを当たり前に)</span>
          </h3>
          <p class="vision__text">相手に対する様々な想い、環境の理解に努める。人に対する偏見や拘りを持たずに接する心を持つことで世界は拓ける。</p>
        </li>

        <li class="vision__item">
          <h3 class="vision__heading">
            <span class="vision__initial">U</span>nderstanding
            <span class="vision__subtitle">(福祉への理解を深める)</span>
          </h3>
          <p class="vision__text">福祉の理解をいただくためのアプローチ環境を構築、様々な繋がりを育む。対企業だけでなく、障害をお持ちの皆さまもwinとなる環境を築く。</p>
        </li>

        <li class="vision__item">
          <h3 class="vision__heading">
            <span class="vision__initial">T</span>hanks
            <span class="vision__subtitle">(感謝の心を忘れない)</span>
          </h3>
          <p class="vision__text">良い事、悪い事、全てのモノゴトには意味があり、全ては学び、糧となる。<br>感謝できない自分がいれば、それすらも学びとし、感謝できるよう努める。</p>
        </li>

        <li class="vision__item">
          <h3 class="vision__heading">
            <span class="vision__initial">H</span>onesty
            <span class="vision__subtitle">(誠実に、正直に生きる)</span>
          </h3>
          <p class="vision__text">誠実に、正直に、真っすぐに生きる人には誰も敵わない。たとえ批判をされてもその者を批判せず、目標へ突き進む。</p>
        </li>
      </ul>
    </div>
  </div>

  <!-- Vision Flow Animation -->
  <!-- <div class="vision__flow">
    <div class="vision__flow-track">
      <div class="vision__flow-list">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
      </div>
      <div class="vision__flow-list" aria-hidden="true">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
        <img src="<?php echo esc_url($theme_uri . '/assets/img/phiso-flow.svg'); ?>" alt="" class="vision__flow-item">
      </div>
    </div>
  </div> -->
</section>