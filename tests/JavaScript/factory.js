export const languageFactory = props => {
  return {
    name: 'Test Language',
    algo_code: 'TL',
    group: {},
    ...props
  };
};

export const groupFactory = props => {
  return {
    name: 'Test Group',
    ...props
  };
};

export const morphemeFactory = props => {
  return {
    slot: slotFactory(),
    glosses: [glossFactory()],
    ...props
  };
};

export const slotFactory = props => {
  return {
    abv: 'SLOT',
    ...props
  }
};

export const glossFactory = props => {
  return {
    abv: 'GLOSS',
    ...props
  }
};
