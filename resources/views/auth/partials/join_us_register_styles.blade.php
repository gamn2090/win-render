  <style>
    .join-us-vendor-body {
      font-family: "Poppins", sans-serif;
      background: #F6F4FA;
    }

  /* Escala tipográfica alineada al mockup (new_hero_vendor_1) */
    .join-us-vendor-register {
      --ju-badge: 18px;
      --ju-headline: 26px;
      --ju-feature-title: 14px;
      --ju-feature-desc: 13px;
      --ju-community: 14px;
      --ju-step: 14px;
      --ju-form-title: 22px;
      --ju-form-sub: 13px;
      --ju-label: 12px;
      --ju-input: 14px;
      --ju-btn: 15px;
      --ju-footer: 13px;
      --ju-error: 13px;
      width: 100%;
      max-width: none;
      margin: 0;
      padding: 0;
    }

    /* Tablet */
    @media (min-width: 768px) and (max-width: 1023px) {
      .join-us-vendor-register {
        --ju-badge: 17px;
        --ju-headline: 24px;
        --ju-feature-title: 14px;
        --ju-feature-desc: 13px;
        --ju-community: 13px;
        --ju-step: 14px;
        --ju-form-title: 21px;
        --ju-form-sub: 13px;
        --ju-label: 12px;
        --ju-input: 14px;
        --ju-btn: 15px;
      }

      .join-us-vendor-grid {
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        min-height: auto;
      }

      .join-us-vendor-left {
        padding: 28px 24px;
      }

      .join-us-vendor-form-col {
        padding: 28px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
      }

      .join-us-vendor-left-inner {
        flex-direction: row;
        gap: 16px;
        max-width: 100%;
      }

      .join-us-vendor-hero-col {
        flex: 0 0 38%;
        max-width: 200px;
      }

      .join-us-vendor-hero-img {
        width: 100%;
        max-width: 200px;
        height: auto !important;
        margin-top: 0 !important;
        aspect-ratio: 300 / 532;
      }

      .join-us-vendor-left-copy {
        flex: 1;
        width: auto;
        max-width: none;
        min-width: 0;
      }

      .join-us-vendor-features-wrap {
        padding-left: 32px;
      }

      .join-us-vendor-form-inner {
        max-width: 100%;
      }

      .join-us-vendor-pricing-grid {
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 6px;
      }
    }

    /* Mobile */
    @media (max-width: 767px) {
      .join-us-vendor-register {
        --ju-badge: 15px;
        --ju-headline: 22px;
        --ju-feature-title: 13px;
        --ju-feature-desc: 12px;
        --ju-community: 12px;
        --ju-step: 13px;
        --ju-form-title: 20px;
        --ju-form-sub: 13px;
        --ju-label: 11px;
        --ju-input: 14px;
        --ju-btn: 15px;
        --ju-footer: 13px;
      }

      .join-us-vendor-grid {
        min-height: auto;
      }

      .join-us-vendor-left {
        padding: 24px 16px 20px;
      }

      .join-us-vendor-form-col {
        padding: 24px 16px 32px;
        overflow: visible;
      }

      .join-us-vendor-form-col:has(.join-us-vendor-form-inner--step2) {
        padding-top: 20px;
      }

      .join-us-vendor-left-inner {
        flex-direction: column;
        align-items: center;
        gap: 20px;
      }

      .join-us-vendor-hero-col {
        width: 100%;
        display: flex;
        justify-content: center;
        max-width: 280px;
        margin: 0 auto;
      }

      .join-us-vendor-hero-img {
        width: 100%;
        max-width: min(280px, 72vw);
        height: auto !important;
        margin-top: 0 !important;
        aspect-ratio: 300 / 532;
      }

      .join-us-vendor-left-copy {
        width: 100%;
        max-width: 100%;
      }

      .join-us-vendor-headline--step2 {
        font-size: var(--ju-headline);
      }

      .join-us-vendor-features-wrap {
        padding-left: 0;
        width: 100%;
      }

      .join-us-vendor-stepper-nav {
        gap: 16px 20px;
        flex-wrap: wrap;
        margin-bottom: 14px;
      }

      .join-us-vendor-form-inner {
        max-width: 100%;
      }

      .join-us-vendor-form-subtitle {
        margin-bottom: 16px;
      }

      .join-us-vendor-name-row,
      .join-us-vendor-password-row {
        grid-template-columns: 1fr;
        gap: 0;
      }

      .join-us-vendor-password-row .join-us-vendor-field {
        margin-bottom: 14px;
      }

      .join-us-vendor-pricing-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 8px;
      }

      .join-us-vendor-pricing-option {
        min-height: 38px;
        padding: 8px 6px;
      }

      .join-us-vendor-form-inner--step2 .join-us-vendor-policy-label {
        flex-wrap: wrap;
        font-size: 11px;
        line-height: 1.4;
      }

      .join-us-vendor-form-inner--step2 .join-us-vendor-policy-title,
      .join-us-vendor-form-inner--step2 .join-us-vendor-policy-label a {
        white-space: normal;
        font-size: 11px;
      }
    }

    /* Mobile pequeño */
    @media (max-width: 479px) {
      .join-us-vendor-register {
        --ju-headline: 20px;
        --ju-form-title: 18px;
        --ju-form-sub: 12px;
        --ju-badge: 14px;
      }

      .join-us-vendor-left,
      .join-us-vendor-form-col {
        padding-left: 14px;
        padding-right: 14px;
      }

      .join-us-vendor-hero-img {
        max-width: min(240px, 78vw);
      }

      .join-us-vendor-stepper-nav {
        gap: 12px 16px;
      }

      .join-us-vendor-step-label {
        font-size: 12px;
      }

      .join-us-vendor-pricing-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 6px;
      }

      .join-us-vendor-pricing-option span {
        font-size: 12px;
      }

      .join-us-vendor-badge {
        padding: 7px 20px;
      }
    }

    @media (max-width: 359px) {
      .join-us-vendor-pricing-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
      }
    }

    .join-us-vendor-register [data-hs-stepper-content-item] {
      width: 100%;
    }

    /* Proporción ~1014 / 908 en ancho completo (mockup new_hero_vendor_1) */
    .join-us-vendor-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 0;
      align-items: stretch;
      width: 100%;
      min-height: min(900px, calc(100vh - 72px));
      background: none;
    }

    @media (min-width: 1024px) {
      .join-us-vendor-grid {
        width: 100%;
        max-width: none;
        margin: 0;
        grid-template-columns: minmax(0, 52.758%) minmax(0, 47.242%);
      }
    }

    .join-us-vendor-left {
      padding: 32px 24px;
      box-sizing: border-box;
      background: linear-gradient(180deg, #F0EBF9 0%, #FEF0E6 100%);
      overflow: visible;
      min-width: 0;
    }

    @media (min-width: 1024px) {
      .join-us-vendor-left {
        padding: 48px 40px 48px 80px;
        display: flex;
        align-items: center;
      }
    }

    .join-us-vendor-left-inner {
      position: relative;
      display: flex;
      gap: 20px;
      align-items: flex-start;
      width: 100%;
      max-width: 823px;
      box-sizing: border-box;
    }

    .join-us-vendor-hero-col {
      flex: 0 0 auto;
    }

    .join-us-vendor-hero-img {
      width: 382px;
      height: 532px;
      flex: 0 0 auto;
      object-fit: cover;
      border-radius: 8px;
      display: block;
    }

    /* Pasos 1 y 2: misma imagen, tipografía y panel izquierdo */
    @media (min-width: 1024px) {
      .join-us-vendor-left-inner--account,
      .join-us-vendor-left-inner--business {
        align-items: stretch;
        max-width: 741px;
      }

      .join-us-vendor-left-inner--account .join-us-vendor-hero-col,
      .join-us-vendor-left-inner--business .join-us-vendor-hero-col {
        align-self: stretch;
        display: flex;
        flex-direction: column;
        flex: 0 0 300px;
        width: 300px;
      }

      /* Paso 1: 10px arriba de "Join" */
      .join-us-vendor-left-inner--account .join-us-vendor-hero-img {
        width: 300px;
        margin-top: 46px;
        height: calc(100% - 46px - 52px);
        object-fit: cover;
        object-position: center top;
        flex: 0 0 auto;
      }

      /* Paso 2: 10px arriba del badge; base al borde inferior de la caja comunidad */
      .join-us-vendor-left-inner--business .join-us-vendor-hero-img {
        width: 300px;
        margin-top: -10px;
        height: calc(100% + 10px);
        flex: 1 1 auto;
        min-height: 0;
        object-fit: cover;
        object-position: center top;
      }
    }

    .join-us-vendor-left-copy {
      flex: 1 1 421px;
      width: 421px;
      max-width: 421px;
      min-width: 0;
      box-sizing: border-box;
      overflow-wrap: break-word;
      word-wrap: break-word;
    }

    @media (max-width: 767px) {
      .join-us-vendor-features-gutter {
        display: none;
      }
    }

    .join-us-vendor-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: max-content;
      max-width: 100%;
      min-height: 40px;
      box-sizing: border-box;
      padding: 8px 32px;
      background: #F85705;
      color: #FCF6F3;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-badge);
      font-weight: 600;
      line-height: 1.2;
      transform: rotate(-3deg);
      transform-origin: right center;
      margin-bottom: 16px;
    }

    .join-us-vendor-badge--mockup-tilt {
      transform: rotate(-3deg);
      transform-origin: left center;
    }

    .join-us-vendor-headline {
      color: #231F20;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-headline);
      font-weight: 600;
      line-height: 1.25;
      letter-spacing: -0.01em;
      margin: 0 0 20px;
      max-width: 100%;
    }

    .join-us-vendor-headline-line1 {
      display: inline;
    }

    .join-us-vendor-headline--step2 {
      font-size: 25px;
      line-height: 1.28;
    }

    .join-us-vendor-features-wrap {
      padding-left: 65px;
      margin: 0 0 20px;
      width: 100%;
      box-sizing: border-box;
    }

    .join-us-vendor-features-gutter {
      min-width: 0;
      height: 0;
      overflow: visible;
      pointer-events: none;
    }

    .join-us-vendor-features-gutter::before {
      content: "Join hundreds of vendors";
      display: block;
      visibility: hidden;
      white-space: nowrap;
      height: 0;
      overflow: hidden;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-headline);
      font-weight: 600;
      letter-spacing: -0.01em;
      line-height: 0;
    }

    .join-us-vendor-features {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 14px;
      min-width: 0;
      text-align: left;
    }

    .join-us-vendor-features li {
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .join-us-vendor-feature-icon {
      width: 24px;
      height: 24px;
      object-fit: contain;
      flex-shrink: 0;
      margin-top: 1px;
    }

    .join-us-vendor-feature-title {
      margin: 0;
      color: #231F20;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-feature-title);
      font-weight: 600;
      line-height: 1.35;
    }

    .join-us-vendor-feature-desc {
      margin: 2px 0 0;
      color: #6B7280;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-feature-desc);
      font-weight: 400;
      line-height: 1.45;
    }

    .join-us-vendor-community {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 14px 16px;
      background: rgba(241, 211, 211, 0.4);
      border-radius: 24px;
    }

    .join-us-vendor-community-icons {
      width: 64px;
      height: auto;
      flex-shrink: 0;
    }

    .join-us-vendor-community p,
    .join-us-vendor-community-text {
      margin: 0;
      color: #6B7280;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-community);
      font-weight: 400;
      line-height: 1.45;
    }

    .join-us-vendor-form-col {
      background: #F6F4FA;
      padding: 32px 24px;
      box-sizing: border-box;
      overflow: hidden;
      min-width: 0;
      position: relative;
      z-index: 2;
    }

    @media (min-width: 1024px) {
      .join-us-vendor-form-col {
        padding: 48px 48px 48px 56px;
        min-height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
      }

      .join-us-vendor-form-col:has(.join-us-vendor-form-inner--step2) {
        justify-content: flex-start;
        padding-top: 28px;
      }
    }

    .join-us-vendor-form-inner--step2 {
      display: flex;
      flex-direction: column;
      row-gap: 10px;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-stepper-nav {
      margin-bottom: 5px;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-form-title {
      margin: 0;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-form-subtitle {
      margin: 0 0 4px;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-field {
      margin-bottom: 0;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-field--policies {
      margin-top: 0;
      margin-bottom: 0;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-row {
      align-items: center;
      margin-bottom: 8px;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-label {
      display: flex;
      flex-wrap: nowrap;
      align-items: baseline;
      gap: 5px;
      font-size: 12px;
      line-height: 1.35;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-title {
      display: inline;
      margin: 0;
      white-space: nowrap;
      font-size: 12px;
      font-weight: 600;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-label a {
      display: inline;
      white-space: nowrap;
      font-size: 12px;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-row input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin: 0;
      flex-shrink: 0;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      border: 3px solid #6432C8;
      border-radius: 4px;
      background: #fff;
      box-sizing: border-box;
    }

    .join-us-vendor-form-inner--step2 .join-us-vendor-policy-row input[type="checkbox"]:checked {
      background-color: #6432C8;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none'%3E%3Cpath d='M3 8.5L6.5 12L13 4' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: center;
      background-size: 11px 11px;
    }

    .join-us-vendor-form-inner--step2 #avg-price {
      color: #231F20;
    }

    .join-us-vendor-form-inner--step2 #avg-price:required:invalid {
      color: #B0ABBC;
    }

    .join-us-vendor-form-inner--step2 #avg-price option[value=""] {
      color: #B0ABBC;
    }

    .join-us-vendor-form-inner--step2 #avg-price option:not([value=""]) {
      color: #231F20;
    }

    .join-us-vendor-community-text strong {
      color: #6B7280;
      font-weight: 700;
    }

    .join-us-vendor-form-inner {
      width: 100%;
      max-width: 560px;
      margin: 0 auto;
      box-sizing: border-box;
    }

    .join-us-vendor-stepper-nav {
      display: flex;
      gap: 28px;
      list-style: none;
      margin: 0 0 20px;
      padding: 0;
    }

    .join-us-vendor-stepper-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .join-us-vendor-step-icon {
      width: 24px;
      height: 24px;
      object-fit: contain;
    }

    .join-us-vendor-step-label {
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-step);
      font-weight: 600;
      line-height: 1.2;
    }

    .join-us-vendor-step-label.is-active {
      color: #6432C8;
    }

    .join-us-vendor-step-label.is-inactive {
      color: #D5C6E7;
    }

    .join-us-vendor-form-title {
      margin: 0 0 6px;
      color: #231F20;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-form-title);
      font-weight: 600;
      line-height: 1.3;
      letter-spacing: -0.01em;
    }

    .join-us-vendor-form-subtitle {
      margin: 0 0 20px;
      color: #6B7280 !important;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-form-sub);
      font-weight: 400;
      line-height: 1.45;
    }

    .join-us-vendor-form-subtitle--step1 {
      max-width: 100%;
    }

    .join-us-vendor-label {
      display: block;
      margin-bottom: 5px;
      color: #B0ABBC;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-label);
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      line-height: 1.3;
    }

    .join-us-vendor-label .req {
      color: #F85705;
    }

    .join-us-vendor-input,
    .join-us-vendor-select,
    .join-us-vendor-textarea {
      width: 100%;
      background: #fff;
      border: 1px solid #E5E7EB;
      border-radius: 4px;
      color: #231F20;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-input);
      font-weight: 500;
      padding: 9px 12px;
      outline: none;
      box-sizing: border-box;
      line-height: 1.4;
    }

    .join-us-vendor-input::placeholder,
    .join-us-vendor-textarea::placeholder {
      color: #B0ABBC;
      font-size: var(--ju-input);
      font-weight: 500;
    }

    .join-us-vendor-field {
      margin-bottom: 14px;
    }

    .join-us-vendor-field-control {
      position: relative;
      width: 100%;
    }

    .join-us-vendor-field-control--select::after {
      content: "";
      pointer-events: none;
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 6px solid #B0ABBC;
    }

    .join-us-vendor-field-control--select .join-us-vendor-input,
    .join-us-vendor-field-control--select .join-us-vendor-select {
      padding-right: 32px;
    }

    .join-us-vendor-select {
      appearance: none;
      -webkit-appearance: none;
      cursor: pointer;
    }

    .join-us-vendor-label--inline {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 8px;
    }

    .join-us-vendor-help-icon {
      width: 16px;
      height: 16px;
      object-fit: contain;
      flex-shrink: 0;
      cursor: help;
      display: inline-block;
      vertical-align: middle;
    }

    .join-us-vendor-pricing-grid {
      display: grid;
      grid-template-columns: repeat(7, minmax(0, 1fr));
      gap: 8px;
      width: 100%;
    }

    .join-us-vendor-pricing-option {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 40px;
      margin: 0;
      padding: 8px 4px;
      background: #fff;
      border: 1px solid #E5E7EB;
      border-radius: 4px;
      cursor: pointer;
      box-sizing: border-box;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .join-us-vendor-pricing-option span {
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-input);
      font-weight: 500;
      color: #231F20;
      line-height: 1.2;
      pointer-events: none;
    }

    .join-us-vendor-pricing-option input {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
      margin: 0;
    }

    .join-us-vendor-pricing-option:has(input:checked) {
      border-color: #6432C8;
      box-shadow: 0 0 0 1px #6432C8;
    }

    .join-us-vendor-field--policies {
      margin-top: 4px;
      margin-bottom: 16px;
    }

    .join-us-vendor-policy-row {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-bottom: 12px;
    }

    .join-us-vendor-policy-row:last-child {
      margin-bottom: 0;
    }

    .join-us-vendor-policy-row input[type="checkbox"] {
      width: 16px;
      height: 16px;
      margin: 2px 0 0;
      flex-shrink: 0;
      accent-color: #6432C8;
      cursor: pointer;
    }

    .join-us-vendor-policy-label {
      display: block;
      margin: 0;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-input);
      font-weight: 400;
      line-height: 1.45;
      color: #231F20;
      cursor: pointer;
    }

    .join-us-vendor-policy-title {
      display: block;
      font-weight: 600;
      color: #231F20;
      margin-bottom: 2px;
    }

    .join-us-vendor-policy-label a {
      color: #6432C8;
      font-weight: 400;
      text-decoration: underline;
      font-size: var(--ju-input);
    }

    .join-us-vendor-policy-label a:hover {
      color: #7244D6;
    }

    .join-us-vendor-name-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .join-us-vendor-password-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .join-us-vendor-label--with-eye {
      display: inline-flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 4px;
      cursor: default;
    }

    .join-us-vendor-label--with-eye .join-us-vendor-eye-btn {
      position: static;
      transform: none;
      flex-shrink: 0;
      border: 0;
      background: transparent;
      padding: 0;
      margin: 0;
      cursor: pointer;
      line-height: 0;
    }

    .join-us-vendor-label--with-eye .join-us-vendor-eye-btn img {
      width: 1em;
      height: 1em;
      object-fit: contain;
      display: block;
      vertical-align: middle;
    }

    .join-us-vendor-btn-primary {
      width: 100%;
      border: 0;
      border-radius: 80px;
      background: #6432C8;
      color: #FCF6F3;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-btn);
      font-weight: 500;
      padding: 12px 24px;
      cursor: pointer;
      margin-top: 6px;
      line-height: 1.3;
    }

    .join-us-vendor-btn-primary:hover {
      background: #7244D6;
    }

    .join-us-vendor-signin-link {
      margin-top: 14px;
      text-align: center;
      color: #6B7280;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-footer);
      font-weight: 400;
      line-height: 1.4;
    }

    .join-us-vendor-signin-link a {
      color: #6432C8;
      font-size: var(--ju-footer);
      font-weight: 700;
      text-decoration: none;
    }

    .join-us-vendor-errors {
      list-style: none;
      margin: 0 0 12px;
      padding: 0;
      background: none;
      border: none;
    }

    .join-us-vendor-errors:empty {
      display: none;
      margin: 0;
    }

    .join-us-vendor-errors li {
      padding: 2px 0;
      color: #F85705;
      font-family: "Poppins", sans-serif;
      font-size: var(--ju-error);
      font-weight: 400;
      line-height: 1.4;
    }

    /* Couple registration — form grid (form_couple.png) */
    .join-us-couple-form-inner .join-us-vendor-form-subtitle {
      margin-bottom: 16px;
    }

    .join-us-couple-form-row {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      gap: 12px 16px;
      margin-bottom: 12px;
    }

    .join-us-couple-form-row--full {
      grid-template-columns: 1fr;
    }

    .join-us-couple-form-col {
      min-width: 0;
    }

    .join-us-couple-form-col .join-us-vendor-field-control {
      width: 100%;
    }

    .join-us-couple-label {
      display: block;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-size: 11px;
      font-weight: 600;
      color: #9CA3AF;
    }

    .join-us-couple-label .req {
      color: #F85705;
    }

    .join-us-couple-password-row {
      margin-bottom: 4px;
    }

    @media (max-width: 767px) {
      .join-us-couple-form-row,
      .join-us-couple-password-row {
        grid-template-columns: 1fr;
        gap: 12px;
      }

      .join-us-couple-form-row .join-us-vendor-name-row {
        grid-template-columns: 1fr;
      }
    }

    /* Join as a Couple — step 2 form (paso_2_joinusacouple.png) */
    .join-us-vendor-form-col--couple-step2 {
      padding-top: 28px;
      padding-bottom: 40px;
    }

    .join-us-couple-form-inner--step2 {
      width: 100%;
      max-width: 520px;
      margin: 0 auto;
    }

    .join-us-couple-form-inner--step2 .join-us-vendor-form-title {
      text-align: left;
      font-size: 19px;
      font-weight: 700;
      line-height: 1.25;
      color: #231F20;
    }

    .join-us-couple-form-inner--step2 .join-us-vendor-form-subtitle {
      text-align: left;
      max-width: none;
      margin: 6px 0 14px;
      font-size: 14px;
      color: #6B7280;
    }

    .join-us-couple-select-all-row {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 8px 16px;
      margin-bottom: 12px;
    }

    .join-us-couple-select-all {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .join-us-couple-select-all-label {
      font-family: "Poppins", sans-serif;
      font-size: 14px;
      font-weight: 500;
      color: #231F20;
      cursor: pointer;
    }

    .join-us-couple-vendor-hint {
      margin: 0;
      font-family: "Poppins", sans-serif;
      font-size: 12px;
      font-weight: 500;
      color: #2563EB;
      line-height: 1.35;
    }

    /* icons_paso2_couple.png — chips ancho según texto, flex wrap */
    .join-us-couple-vendor-types {
      display: flex;
      flex-wrap: wrap;
      align-items: flex-start;
      gap: 6px;
      margin-bottom: 18px;
      width: 100%;
      max-width: 520px;
    }

    .join-us-couple-vendor-card {
      position: relative;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      width: max-content;
      max-width: 100%;
      height: 56px;
      padding: 0 16px;
      background: #fff;
      border-radius: 8px;
      border: 1px solid #E5E7EB;
      cursor: pointer;
      box-sizing: border-box;
      flex: 0 0 auto;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .join-us-couple-vendor-card:has(.join-us-couple-vendor-card-input:checked) {
      border-color: #6432C8;
      box-shadow: 0 0 0 1px #6432C8;
    }

    .join-us-couple-vendor-card-input {
      position: absolute;
      opacity: 0;
      width: 0;
      height: 0;
      pointer-events: none;
    }

    .join-us-couple-vendor-card-icon {
      display: block;
      width: 17px;
      height: auto;
      object-fit: contain;
      object-position: center;
      flex-shrink: 0;
    }

    .join-us-couple-vendor-card-label {
      font-family: "Poppins", sans-serif;
      font-size: 11px;
      font-weight: 500;
      line-height: 1.2;
      color: #231F20;
      white-space: nowrap;
    }

    .join-us-couple-vendor-checkbox {
      width: 16px;
      height: 16px;
      accent-color: #6432C8;
      flex-shrink: 0;
      cursor: pointer;
    }

    /* Select All + ToS — mismo checkbox morado (paso 2 couple) */
    .join-us-couple-form-inner--step2 .join-us-couple-vendor-checkbox,
    .join-us-couple-form-inner--step2 .join-us-vendor-field--policies input[type="checkbox"] {
      width: 18px;
      height: 18px;
      margin: 0;
      flex-shrink: 0;
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      border: 3px solid #6432C8;
      border-radius: 4px;
      background: #fff;
      box-sizing: border-box;
    }

    .join-us-couple-form-inner--step2 .join-us-couple-vendor-checkbox:checked,
    .join-us-couple-form-inner--step2 .join-us-vendor-field--policies input[type="checkbox"]:checked {
      background-color: #6432C8;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='none'%3E%3Cpath d='M3 8.5L6.5 12L13 4' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: center;
      background-size: 11px 11px;
    }

    .join-us-couple-contact-toggle {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 16px;
      padding: 4px 0;
      color: #231F20;
      font-family: "Poppins", sans-serif;
      font-size: 13px;
      font-weight: 500;
    }

    .join-us-couple-contact-text {
      flex: 1 1 200px;
      line-height: 1.35;
    }

    .join-us-couple-contact-switch {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .join-us-couple-contact-switch-label {
      font-size: 13px;
      font-weight: 600;
      color: #231F20;
      cursor: pointer;
    }

    .join-us-couple-contact-switch-input {
      width: 52px;
      height: 28px;
      accent-color: #6432C8;
      cursor: pointer;
    }

    .join-us-couple-form-inner--step2 .join-us-couple-contact-switch-input {
      appearance: none;
      -webkit-appearance: none;
      position: relative;
      width: 38px;
      height: 16px;
      margin: 0;
      border: none;
      border-radius: 40px;
      background: #EAE7F0;
      cursor: pointer;
      flex-shrink: 0;
      transition: background 0.2s ease;
    }

    .join-us-couple-form-inner--step2 .join-us-couple-contact-switch-input::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: #6432C8;
      transition: transform 0.2s ease;
    }

    .join-us-couple-form-inner--step2 .join-us-couple-contact-switch-input:checked::after {
      transform: translateX(22px);
    }

    .join-us-couple-form-inner--step2 .join-us-vendor-btn-primary {
      width: 100%;
      margin-top: 4px;
    }

    @media (max-width: 1023px) {
      .join-us-couple-form-inner--step2,
      .join-us-couple-vendor-types {
        max-width: 100%;
      }
    }

    @media (max-width: 639px) {
      .join-us-couple-vendor-types {
        gap: 5px;
      }

      .join-us-couple-vendor-card {
        height: 52px;
        padding: 0 16px;
        gap: 5px;
      }

      .join-us-couple-vendor-card-label {
        font-size: 10px;
      }

      .join-us-couple-vendor-card-icon {
        width: 17px;
        height: auto;
      }
    }

    @media (max-width: 399px) {
      .join-us-couple-vendor-card {
        height: 48px;
        max-width: 100%;
      }

      .join-us-couple-vendor-card-label {
        font-size: 10px;
        white-space: normal;
      }

      .join-us-couple-vendor-card-icon {
        width: 17px;
        height: auto;
      }
    }

    /* Join as a Couple — hero image +20px taller, shifted up 15px vs vendor */
    .join-us-vendor-left--couple .join-us-vendor-hero-img,
    .join-us-vendor-left--couple-step2 .join-us-vendor-hero-img {
      height: 552px;
      margin-top: -15px;
    }

    @media (min-width: 768px) and (max-width: 1023px) {
      .join-us-vendor-left--couple .join-us-vendor-hero-img,
      .join-us-vendor-left--couple-step2 .join-us-vendor-hero-img {
        aspect-ratio: 300 / 552;
        margin-top: -15px !important;
      }
    }

    @media (max-width: 767px) {
      .join-us-vendor-left--couple .join-us-vendor-hero-img,
      .join-us-vendor-left--couple-step2 .join-us-vendor-hero-img {
        aspect-ratio: 300 / 552;
        margin-top: -15px !important;
      }
    }

    @media (min-width: 1024px) {
      .join-us-vendor-left--couple .join-us-vendor-left-inner--business .join-us-vendor-hero-img,
      .join-us-vendor-left--couple-step2 .join-us-vendor-left-inner--business .join-us-vendor-hero-img {
        height: calc(100% + 30px);
        margin-top: -25px;
      }
    }
  </style>
