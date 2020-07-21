export const slotFactory = props => ({
  abv: 'SLOT',
  ...props
});

export const glossFactory = props => ({
  abv: 'GLOSS',
  ...props
});

export const groupFactory = props => ({
  name: 'Test Group',
  ...props
});

export const languageFactory = props => ({
  name: 'Test Language',
  algo_code: 'TL',
  group: groupFactory(),
  ...props
});

export const morphemeFactory = props => ({
  slot: slotFactory(),
  gloss: 'TGL',
  glosses: [glossFactory({ abv: 'TGL' })],
  ...props
});

export const verbFormFactory = props => ({
  ...props
});
