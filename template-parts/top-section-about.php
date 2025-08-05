<section id="about" class="about section" aria-labelledby="about-heading">
  <div class="about__grid">
    <!-- Left : Content -->
    <div class="about__content">
      <p class="about__sub">About us</p>
      <h2 id="about-heading" class="about__title">ＬｉＮＥ&nbsp;ＰＡＲＫ&nbsp;について</h2>
      <p class="about__lead">誰もが当たり前のことを、当たり前にできる世界へ</p>
      <p class="about__text">
        できること、できないことには必ず環境要因があると私たちは考えます。

        「自分が正しい」ではなく、多様な人々が共存していることを理解し、 ひとり一人の気持ちを共有しながら共に進んでいく――。

        私たちはそんな世界の実現を理想とし、その歯車の一つとなることを目指しています。
      </p>
      <a href="<?php echo esc_url(home_url('/about/')); ?>" class="about__cta btn">View&nbsp;More</a>
    </div>

    <!-- Right : Visuals -->
    <div class="about__visual">
      <img src="<?php echo esc_url(get_theme_file_uri('assets/img/test-about-01.jpg')); ?>"
        alt="" class="about__img about__img--lg" loading="lazy">
      <img src="<?php echo esc_url(get_theme_file_uri('assets/img/test-about-02.webp')); ?>"
        alt="" class="about__img about__img--sm" loading="lazy">
    </div>
  </div><!-- /.about__grid -->
</section>

<style>
  /* ===== カラーパレット（お好みで） ===== */
  :root {
    --c-bg: #faf9f7;
    --c-accent: #007aff;
    --c-text: #333;
    --c-sub: #888;
  }

  /* ===== 全体レイアウト ===== */
  .about {
    background: var(--c-bg);
    padding-inline: clamp(1rem, 6vw, 4rem);
    padding-block: clamp(3rem, 10vw, 6rem);
  }

  .about__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: clamp(2rem, 6vw, 5rem);
    position: relative;
  }

  /* ===== Left – Content ===== */
  .about__content {
    align-self: center;
    position: relative;
    z-index: 2;
    /* ← 画像より上 */
    translate: -20px 0;
    /* ← 画像に少し被せる */
  }

  .about__sub {
    font-size: .9rem;
    letter-spacing: .1em;
    color: var(--c-sub);
    margin-bottom: .5rem;
    text-transform: uppercase;
  }

  .about__title {
    font-family: var(--font-en, "Poppins", sans-serif);
    font-weight: 800;
    font-size: clamp(2rem, 4vw, 2.8rem);
    line-height: 1.2;
    margin-bottom: 1rem;
  }

  .about__lead {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--c-accent);
  }

  .about__text {
    line-height: 1.8;
    margin-bottom: 2rem;
    color: var(--c-text);
  }

  .about__cta.btn {
    display: inline-block;
    padding: .9rem 2.4rem;
    font-weight: 700;
    border-radius: 40px;
    color: #fff;
    background: var(--c-accent);
    transition: background .25s;

    &:hover {
      background: darken(#007aff, 10%);
    }
  }

  /* ===== Right – Visuals (Broken Grid) ===== */
  .about__visual {
    position: relative;
    aspect-ratio: 4 / 5;
    /* レイアウト維持用 */
  }

  .about__img {
    border-radius: 12px;
    object-fit: cover;
    box-shadow: 0 12px 22px rgba(0, 0, 0, .12);
  }
.about__img--lg{
  /* 左側のチーム写真（turn0image0） */
  position:absolute; inset:0;
  width:100%; height:100%;
  object-fit:cover;
  border-radius:18px;
  box-shadow:0 12px 28px rgba(#000,.15);
}

.about__img--sm{
  /* 右上に重ねる海の空撮（turn0image1） */
  position:absolute;
  width:54%;
  right:-8%;
  top:-6%;
  border-radius:14px;
  object-fit:cover;
  box-shadow:0 10px 22px rgba(#000,.12);
  rotate:-4deg;
}

  /* ===== Tablet & Down ===== */
  @media (max-width: 992px) {
    .about__grid {
      grid-template-columns: 1fr;
    }

    .about__content {
      translate: 0;
      /* 重なり解除 */
      margin-bottom: 3rem;
      text-align: center;
    }

    .about__cta.btn {
      margin-inline: auto;
    }

    .about__visual {
      aspect-ratio: 3 / 4;
      max-width: 80%;
      margin-inline: auto;
    }
  }
</style>

