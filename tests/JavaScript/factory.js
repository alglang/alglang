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
  get formatted_shape() {
    return `<i>${this.shape}</i>`;
  },
  ...props
});

export const verbFormFactory = props => ({
  get formatted_shape() {
    return `<i>${this.shape}</i>`;
  },
  ...props
});

export const verbClassFactory = props => ({
  ...props
});

export const verbOrderFactory = props => ({
  ...props
});

export const verbModeFactory = props => ({
  ...props
});

export const featureFactory = props => ({
  ...props
});

export const nominalFormFactory = props => ({
  get formatted_shape() {
    return `<i>${this.shape}</i>`;
  },
  ...props
});

export const nominalParadigmTypeFactory = props => ({
  ...props
});

export const sourceFactory = props => ({
  id: Math.floor(Math.random() * 10000),
  ...props
});

export const exampleFactory = props => ({
  id: Math.floor(Math.random() * 10000),
  form: verbFormFactory(),
  ...props
});
