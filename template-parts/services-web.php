<?php
/**
 * template-parts/services-web.php
 * Web制作 – サービス詳細ページ
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

/* =========================
   0) データ（ACFがあれば優先）
   ======================= */
$lead = [
  'sub'   => 'Web Production',
  'title' => get_the_title() ?: 'Web制作',
  'text'  => (function_exists('get_field') && get_field('lead_text'))
              ? get_field('lead_text')
              : 'ビジネスゴールから逆算し、設計・デザイン・実装・運用までを一気通貫で支援します。スピードと品質の両立を重視し、更新しやすく成果につながるWebサイトをご提供します。',
  'image' => (function_exists('get_field') && get_field('hero_image')) ? get_field('hero_image') : '',
];

$plans = (function_exists('get_field') && get_field('plans')) ? get_field('plans') : [
  [
    'name'        => 'コーポレートサイト',
    'desc'        => '会社・事業紹介/採用導線強化に。5〜10ページ規模の基本セット。',
    'price_from'  => '¥350,000〜',
    'link_url'    => home_url('/contact/'),
  ],
  [
    'name'        => 'LP（ランディングページ）',
    'desc'        => '広告/キャンペーンに最適。1ページ完結でCV導線を最適化。',
    'price_from'  => '¥180,000〜',
    'link_url'    => home_url('/contact/'),
  ],
  [
    'name'        => 'ECサイト',
    'desc'        => 'Shop&Cart導入＋運用支援。初期構築から改善まで伴走。',
    'price_from'  => '¥480,000〜',
    'link_url'    => home_url('/contact/'),
  ],
];

$features = (function_exists('get_field') && get_field('features')) ? get_field('features') : [
  '要件ヒアリング／KPI設計',
  '情報設計（IA）・ワイヤーフレーム',
  'UIデザイン（Figma）',
  'レスポンシブ実装（HTML/CSS/JS）',
  'WordPress 導入・編集画面最適化',
  'パフォーマンス/SEOベース対策（Core Web Vitals 配慮）',
  '公開後の運用・改善',
];

$deliverables = (function_exists('get_field') && get_field('deliverables')) ? get_field('deliverables') : [
  ['label' => 'ドメイン/サーバ設定', 'value' => '初期設定対応可（代行オプション）'],
  ['label' => 'CMS',               'value' => 'WordPress（要件に応じて静的化も可）'],
  ['label' => '対応ブラウザ',       'value' => '最新の主要モダンブラウザ'],
  ['label' => 'セキュリティ',       'value' => 'reCAPTCHA / WAF など導入可'],
  ['label' => '納品形態',           'value' => 'Git/ZIP/本番反映 いずれも可'],
];

$flow = (function_exists('get_field') && get_field('flow')) ? get_field('flow') : [
  ['title' => 'ヒアリング/要件整理', 'text' => '現状課題・目的・KPI・体制・予算/期日を共有します。'],
  ['title' => '設計/ワイヤー',      'text' => 'サイト構造・導線・原稿構成を設計します。'],
  ['title' => 'デザイン',           'text' => 'トンマナ定義と画面デザイン（Figma）。'],
  ['title' => '実装/検証',          'text' => 'コーディング・CMS組込・動作/表示/速度の検証。'],
  ['title' => '公開/運用',          'text' => '公開後の保守・改善（分析/ABテスト等）。'],
];

$faqs = (function_exists('get_field') && get_field('faqs')) ? get_field('faqs') : [
  ['q' => '納期の目安は？', 'a' => '規模により異なりますが、LPで3〜4週間、5〜10P規模のコーポレートで6〜10週間が目安です。'],
  ['q' => '原稿や写真が無いのですが？', 'a' => '構成案のご提案・ライティング支援・写真撮影の手配が可能です（別途）。'],
  ['q' => '既存サイトのリニューアルも可能？', 'a' => '可能です。現状の資産・SEO評価を考慮して計画します。'],
];

/* =========================
   1) サブヒーロー
   ======================= */
get_template_part('components/subhero', null, [
  'sub'        => $lead['sub'],
  'title'      => $lead['title'],
  'variant'    => 'services',     // → .subhero--services
  'tag'        => 'h1',
  'image_url'  => $lead['image'], // 空なら自動でアイキャッチ
  'id'         => 'subhero-services-web',
]);

?>
<main class="page page--service-single" role="main">
  <!-- Overview -->
  <section class="section container service-overview" aria-labelledby="service-overview-heading">
    <header class="section__header">
      <p class="section__sub">Overview</p>
      <h2 id="service-overview-heading" class="section__title">サービス概要</h2>
    </header>
    <div class="service-overview__grid">
      <div class="service-overview__lead">
        <p class="section__text"><?php echo esc_html($lead['text']); ?></p>
        <ul class="service-features">
          <?php foreach ($features as $f): ?>
            <li class="service-feature"><?php echo esc_html($f); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <aside class="service-overview__meta">
        <table class="info-table">
          <tbody>
            <?php foreach ($deliverables as $row): ?>
              <tr class="info-table__row">
                <th class="info-table__label"><?php echo esc_html($row['label']); ?></th>
                <td class="info-table__data"><?php echo esc_html($row['value']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </aside>
    </div>
  </section>

  <!-- Plans -->
  <section class="section container service-plans" aria-labelledby="service-plans-heading">
    <header class="section__header">
      <p class="section__sub">Plans</p>
      <h2 id="service-plans-heading" class="section__title">提供プラン</h2>
    </header>
    <ul class="service-plans__cards" role="list">
      <?php foreach ($plans as $p): ?>
        <li class="service-plan">
          <div class="service-plan__body">
            <h3 class="service-plan__title"><?php echo esc_html($p['name']); ?></h3>
            <p class="service-plan__desc"><?php echo esc_html($p['desc']); ?></p>
            <p class="service-plan__price"><span>目安</span> <?php echo esc_html($p['price_from']); ?></p>
            <?php if (!empty($p['link_url'])): ?>
              <p class="service-plan__cta">
                <a href="<?php echo esc_url($p['link_url']); ?>" class="button button--primary button--icon"><span class="btn-text">相談する</span></a>
              </p>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>

  <!-- Flow -->
  <section class="section container service-flow" aria-labelledby="service-flow-heading">
    <header class="section__header">
      <p class="section__sub">Flow</p>
      <h2 id="service-flow-heading" class="section__title">制作の流れ</h2>
    </header>
    <ol class="service-flow__steps">
      <?php foreach ($flow as $step): ?>
        <li class="service-flow__step">
          <h3 class="service-flow__step-title"><?php echo esc_html($step['title']); ?></h3>
          <p class="service-flow__step-text"><?php echo esc_html($step['text']); ?></p>
        </li>
      <?php endforeach; ?>
    </ol>
  </section>

  <!-- FAQ -->
  <section class="section container service-faq" aria-labelledby="service-faq-heading">
    <header class="section__header">
      <p class="section__sub">FAQ</p>
      <h2 id="service-faq-heading" class="section__title">よくあるご質問</h2>
    </header>
    <div class="service-faq__list">
      <?php foreach ($faqs as $qa): ?>
        <details class="service-faq__item">
          <summary class="service-faq__q"><?php echo esc_html($qa['q']); ?></summary>
          <div class="service-faq__a"><p><?php echo esc_html($qa['a']); ?></p></div>
        </details>
      <?php endforeach; ?>
    </div>
  </section>

  <?php
    // 共通CTA・ページトップ
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
  ?>
</main>
