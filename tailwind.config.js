/** @type {import('tailwindcss').Config} */
// Espelha design-system/tokens.json e tokens.css. Se um token mudar, atualize os três.
module.exports = {
  content: ['./src/**/*.php', './public/**/*.{php,html,js}'],
  theme: {
    // Substitui (não estende) a paleta padrão: só cores aprovadas e auditadas (ver docs/fase-3-design-system/wdlc-3.1-auditoria-wcag.md).
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      turquesa: '#5AA7A1',
      petroleo: '#1E5A56',
      'petroleo-profundo': '#123B39',
      tinta: '#0E1E1D',
      papel: '#F5F2EC',
      nevoa: '#E4EEEC',
      'texto-secundario': '#4A5554',
      'texto-claro': '#B9CFCC',
      latao: '#C9A970',
      'latao-escuro': '#7A5F33',
      linha: '#CFD8D6',
      'borda-campo': '#6E7C7A',
      branco: '#FFFFFF',
    },
    fontFamily: {
      display: ['Cinzel', 'Trajan Pro', 'Georgia', 'serif'],
      titulo: ['Plus Jakarta Sans', 'Segoe UI', 'system-ui', 'sans-serif'],
      sans: ['Source Sans 3', 'Segoe UI', 'system-ui', 'sans-serif'],
    },
    // [tamanho, { lineHeight, fontWeight, letterSpacing }]
    fontSize: {
      eyebrow: ['13px', { lineHeight: '16px', fontWeight: '500', letterSpacing: '0.18em' }],
      display: ['44px', { lineHeight: '52px', fontWeight: '400', letterSpacing: '0.04em' }],
      h1: ['46px', { lineHeight: '54px', fontWeight: '600', letterSpacing: '-0.01em' }],
      h2: ['34px', { lineHeight: '42px', fontWeight: '600', letterSpacing: '-0.01em' }],
      h3: ['22px', { lineHeight: '30px', fontWeight: '600' }],
      lead: ['20px', { lineHeight: '30px', fontWeight: '400' }],
      body: ['17px', { lineHeight: '27px', fontWeight: '400' }],
      botao: ['16px', { lineHeight: '20px', fontWeight: '600' }],
      nota: ['14px', { lineHeight: '21px', fontWeight: '400' }],
    },
    spacing: {
      0: '0px',
      px: '1px',
      1: '8px',
      2: '16px',
      3: '24px',
      4: '32px',
      6: '48px',
      10: '80px',
      16: '128px',
    },
    borderRadius: {
      none: '0px',
      sm: '2px',
      full: '9999px',
    },
    borderWidth: {
      DEFAULT: '1px',
      0: '0px',
      hairline: '1px',
    },
    extend: {},
  },
  plugins: [],
};
